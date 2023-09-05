<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function addComment(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'description' => ['required', 'min:5', 'max:5000'],
        ]);

        $comment = Comment::create([
            'comment' => $credentials['description'],
            'user_id' => $request->user()->id,
            'task_id' => $request->task_id
        ]);

        if ($comment) {
            return redirect()->back()->with('success', 'Commented successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to comment.');
        }
    }
}
