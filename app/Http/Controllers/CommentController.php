<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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
            if (App::isLocale('en')) {
                return redirect()->back()->with('success', 'Commented successfully.');
            } else {
                return redirect()->back()->with('نجاح', 'تم نشر تعليقك');
            }
        } else {
            if (App::isLocale('en')) {
                return redirect()->back()->with('error', 'Failed to comment.');
            } else {
                return redirect()->back()->with('خطأ', 'حدث خطأ اثناء نشر تعليقك, رجاء اعادة المحاولة');
            }
        }
    }
    public function deleteComment(Request $request): RedirectResponse
    {
        $comment = Comment::where('id', $request->comment_id);
        if ($comment) {
            $comment->delete();
            if (App::isLocale('en')) {
                return redirect()->back()->with('success', 'Comment deleted successfully.');
            } else {
                return redirect()->back()->with('نجاح', 'تم حذف تعليقك');
            }
        } else {
            if (App::isLocale('en')) {
                return redirect()->back()->with('error', 'Failed to delete comment.');
            } else {
                return redirect()->back()->with('خطأ', 'حدث خطأ اثناء حذف تعليقك, رجاء اعادة المحاولة');
            }
        }
    }
}
