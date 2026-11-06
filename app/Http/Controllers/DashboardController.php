<?php

namespace App\Http\Controllers;

use App\Models\Bug;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        /** @var User $user */
        $user = auth()->user();

        $query = Bug::query();
        if (!$user->isAdmin()) {
            $query->where(fn ($q) => $q->where('assigned_to', $user->id)->orWhere('reported_by', $user->id));
        }

        $stats = [
            'total' => (clone $query)->count(),
            'open' => (clone $query)->where('status', 'open')->count(),
            'progress' => (clone $query)->where('status', 'in_progress')->count(),
            'resolved' => (clone $query)->where('status', 'resolved')->count(),
            'critical' => (clone $query)->where('severity', 'critical')->whereNotIn('status', ['resolved', 'closed'])->count(),
        ];

        $bugs = (clone $query)->with(['project', 'assignee'])->latest()->take(8)->get();

        return view('dashboard', compact('stats', 'bugs'));
    }
}
