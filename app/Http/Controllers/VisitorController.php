<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class VisitorController extends Controller
{
    public function getTodayCount(): JsonResponse
    {
        $count = DB::table('visitors')
            ->whereDate('created_at', Carbon::today())
            ->count();

        return response()->json(['count' => $count]);
    }
}