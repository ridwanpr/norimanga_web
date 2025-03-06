<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\UserIssue;
use Illuminate\Http\Request;

class UserIssueController extends Controller
{
    public function index(Request $request)
    {
        $query = UserIssue::orderBy('created_at', 'desc');

        if ($request->status === 'solved') {
            $query->where('is_solved', true);
        } elseif ($request->status === 'unsolved') {
            $query->where('is_solved', false);
        }

        $userIssue = $query->paginate(15);
        return view('backend.issue.index', compact('userIssue'));
    }

    public function solve(Request $request)
    {
        $issue = UserIssue::findOrFail($request->id);
        $issue->update(['is_solved' => true]);

        return response()->json(['success' => true]);
    }

}
