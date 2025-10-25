<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class CustomerLoyaltyController extends Controller
{
    public function updatePoints(User $user, int $points)
    {
        $user->addPoints($points);
    }
}

