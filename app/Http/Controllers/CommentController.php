<?php

namespace App\Http\Controllers;

use App\Models\Bug;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Bug $bug): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        abort_unless($user->isAdmin() || $bug->assigned_to === $user->id || $bug->reported_by === $user->id, 403);

        $data = $request->validate([
            'comment' => 'required|string|max:3000',
        ]);

        $bug->comments()->create([
            'user_id' => $user->id,
            'comment' => $data['comment'],
        ]);

        $bug->activities()->create([
            'user_id' => $user->id,
            'action' => 'Added a comment',
        ]);

        return back()->with('success', 'Comment added.');
    }
}
