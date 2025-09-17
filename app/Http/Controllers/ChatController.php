<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Load view chat
    public function index()
    {
        return view('chat.index');
    }

    // Load tin nhắn (AJAX gọi)
    public function fetchMessages()
    {
        $userId = Auth::id();

        $messages = Message::where(function($q) use ($userId) {
                $q->where('sender_id', $userId)
                  ->orWhere('receiver_id', $userId);
            })
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    // Gửi tin nhắn
    // Gửi tin nhắn
    public function send(Request $request)
{
    $request->validate([
        'message' => 'required|string',
    ]);

    // Nếu client không truyền receiver_id → tự tìm admin
    $receiverId = $request->input('receiver_id');

    if (!$receiverId) {
        $receiverId = \App\Models\User::where('role', 'admin')->value('id');
    }

    if (!$receiverId) {
        return response()->json(['error' => 'Không tìm thấy admin'], 400);
    }

    $msg = Message::create([
        'sender_id'   => Auth::id(),
        'receiver_id' => $receiverId,
        'message'     => $request->message,
    ]);

    return response()->json($msg);
}

    


    // Đánh dấu đã đọc
    public function markAsRead(Request $request)
    {
        Message::where('receiver_id', Auth::id())
               ->where('is_read', false)
               ->update(['is_read' => true]);

        return response()->json(['status' => 'ok']);
    }
}
