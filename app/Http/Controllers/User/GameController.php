<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Voucher;

class GameController extends Controller
{
    // Hiển thị game (frontend)
    public function index()
    {
        return view('game.index');
    }

    // Xử lý thưởng sau khi chơi thắng
    public function reward(Request $request)
    {
        $user = Auth::user();

        // Ví dụ: thưởng 50 điểm
        $user->addPoints(50);

        // Tặng kèm voucher (optional)
        $voucher = Voucher::create([
            'code' => 'GAME' . strtoupper(uniqid()),
            'discount_type' => 'percent',
            'discount_value' => 10,
            'usage_limit' => 1,
            'expires_at' => now()->addDays(7),
            'active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Chúc mừng! Bạn nhận được 50 điểm + voucher 10%',
            'voucher' => $voucher->code,
            'points' => $user->points,
            'level' => $user->level,
        ]);
    }
}
