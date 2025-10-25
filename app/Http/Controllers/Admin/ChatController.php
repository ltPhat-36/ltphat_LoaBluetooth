<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Load view chat admin
    public function index()
    {
        return view('admin.chat.index');
    }

    // Fetch messages between admin and selected customer
    public function fetch(Request $request)
    {
        $customerId = $request->customer_id;
        $adminId = Auth::id();

        $customer = User::where('id',$customerId)->where('role','customer')->first();
        if(!$customer){
            return response()->json(['error'=>'Customer không hợp lệ'],403);
        }

        $messages = Message::with('sender')
            ->where(function($q) use ($adminId, $customerId){
                $q->where('sender_id',$adminId)
                  ->where('receiver_id',$customerId);
            })
            ->orWhere(function($q) use ($adminId, $customerId){
                $q->where('sender_id',$customerId)
                  ->where('receiver_id',$adminId);
            })
            ->orderBy('created_at','asc')
            ->get();

        return response()->json($messages);
    }

    // Send message admin → customer
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id'=>'required|exists:users,id',
            'message'=>'required|string|max:1000',
        ]);

        $receiver = User::where('id',$request->receiver_id)
                        ->where('role','customer')
                        ->first();

        if(!$receiver){
            return response()->json(['error'=>'Receiver không hợp lệ'],403);
        }

        $msg = Message::create([
            'sender_id'=>Auth::id(),
            'receiver_id'=>$receiver->id,
            'message'=>$request->message
        ]);

        return response()->json($msg);
    }
    public function destroy($id)
    {
        $msg = Message::find($id);
        if(!$msg){
            return response()->json(['error'=>'Tin nhắn không tồn tại'],404);
        }

        $msg->delete();
        return response()->json(['success'=>true]);
    }

}
