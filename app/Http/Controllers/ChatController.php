<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\OpenAIService;
use App\Services\EmbeddingService;
use App\Models\Product;
use App\Models\Faq;

class ChatController extends Controller
{
    // Hàm chuẩn hóa text (bỏ dấu câu, lowercase)
    private function normalize($text)
    {
        $text = mb_strtolower(trim($text), 'UTF-8');
        $text = preg_replace('/[?.!,]/u', '', $text); // bỏ ?, . , !
        return $text;
    }

    // Load view chat customer
    public function index()
    {
        return view('chat.index');
    }

    // Fetch messages between customer and admin
    public function fetchMessages()
    {
        $userId = Auth::id();
        $adminId = User::where('role','admin')->value('id');

        if (!$adminId) {
            return response()->json(['error'=>'Không tìm thấy admin'], 400);
        }

        $messages = Message::with('sender')
            ->where(function($q) use ($userId, $adminId){
                $q->where('sender_id',$userId)
                  ->where('receiver_id',$adminId);
            })
            ->orWhere(function($q) use ($userId, $adminId){
                $q->where('sender_id',$adminId)
                  ->where('receiver_id',$userId);
            })
            ->orderBy('created_at','asc')
            ->get();

        return response()->json($messages);
    }

    // Send message customer → admin
    public function send(Request $request)
{
    $request->validate([
        'message' => 'required|string|max:1000',
    ]);

    // ✅ Xác định admin nhận tin
    $receiverId = $request->input('receiver_id') ?: User::where('role', 'admin')->value('id');
    $admin = User::where('id', $receiverId)->where('role', 'admin')->first();
    if (!$admin) {
        return response()->json(['error' => 'Receiver không hợp lệ'], 403);
    }

    // ✅ Lưu tin nhắn user gửi
    $msg = Message::create([
        'sender_id'   => Auth::id(),
        'receiver_id' => $admin->id,
        'message'     => $request->message,
    ]);

    // ✅ Chuẩn hóa tin nhắn user
    $userMessage = $this->normalize($request->message);

    // ✅ Tìm câu hỏi gần giống trong FAQ
    $bestMatch   = null;
    $bestPercent = 0;

    foreach (Faq::all() as $f) {
        $q = $this->normalize($f->question);
        similar_text($userMessage, $q, $percent);

        if ($percent > $bestPercent) {
            $bestPercent = $percent;
            $bestMatch   = $f;
        }
    }

    // ✅ Nếu độ giống > 60% thì coi như match
    if ($bestMatch && $bestPercent >= 36) {
        Message::create([
            'sender_id'   => 15, // Bot ID
            'receiver_id' => Auth::id(),
            'message'     => $bestMatch->answer,
        ]);
    }

    return response()->json($msg);
}


    // Mark all messages read
    public function markAsRead()
    {
        Message::where('receiver_id', Auth::id())
               ->where('is_read', false)
               ->update(['is_read'=>true]);

        return response()->json(['status'=>'ok']);
    }

    // Chat with AI (FAQ)
    public function chatFaq(Request $request)
    {
        $request->validate(['message'=>'required|string']);
        $userMessage = $this->normalize($request->message);

        // Tìm câu hỏi trong FAQ
        $faq = Faq::all()->first(function($f) use ($userMessage) {
            return $this->normalize($f->question) === $userMessage;
        });

        $replyText = $faq ? $faq->answer : "Xin lỗi, tôi chưa có câu trả lời cho câu hỏi này. Vui lòng liên hệ admin.";

        // Lưu message FAQ vào DB
        $faqMessage = Message::create([
            'sender_id'=>0, // FAQ bot
            'receiver_id'=>Auth::id(),
            'message'=>$replyText
        ]);

        return response()->json(['message'=>$faqMessage]);
    }
}
