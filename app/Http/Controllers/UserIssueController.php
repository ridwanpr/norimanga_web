<?php

namespace App\Http\Controllers;

use App\Models\UserIssue;
use Illuminate\Http\Request;

class UserIssueController extends Controller
{
    public function store(Request $request)
    {
        UserIssue::create([
            'url' => $request->url,
            'desc' => $request->desc,
            'created_at' => now(),
        ]);

        return response()->json(['message' => 'Laporan berhasil dikirim. Kami akan segera memperbaiki chapter ini. Terima kasih!']);
    }
}
