<?php

namespace App\Http\Controllers;

use App\Models\Bug;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BugController extends Controller
{
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = auth()->user();

        $q = Bug::with(['project', 'assignee', 'reporter']);
        if (!$user->isAdmin()) {
            $q->where(fn ($x) => $x->where('assigned_to', $user->id)->orWhere('reported_by', $user->id));
        }

        foreach (['status', 'severity', 'priority', 'project_id', 'assigned_to'] as $field) {
            if ($request->filled($field)) {
                $q->where($field, $request->input($field));
            }
        }

        if ($request->filled('search')) {
            $q->where(fn ($x) => $x->where('bug_key', 'like', '%' . $request->search . '%')->orWhere('title', 'like', '%' . $request->search . '%'));
        }

        $bugs = $q->latest()->paginate(12)->withQueryString();
        $projects = Project::where('is_active', true)->orderBy('name')->get();
        $developers = User::where('role', 'developer')->where('is_active', true)->orderBy('name')->get();

        return view('bugs.index', compact('bugs', 'projects', 'developers'));
    }

    public function create(): View
    {
        $projects = Project::where('is_active', true)->orderBy('name')->get();
        $developers = User::where('role', 'developer')->where('is_active', true)->orderBy('name')->get();
        abort_if($projects->isEmpty(), 422, 'Create a project before reporting a bug.');

        return view('bugs.create', compact('projects', 'developers'));
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:180',
            'description' => 'required|string',
            'steps_to_reproduce' => 'nullable|string',
            'expected_result' => 'nullable|string',
            'actual_result' => 'nullable|string',
            'environment' => 'nullable|string|max:180',
            'severity' => ['required', Rule::in(['critical', 'high', 'medium', 'low'])],
            'priority' => ['required', Rule::in(['urgent', 'high', 'normal', 'low'])],
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if (!empty($data['assigned_to']) && !User::where('id', $data['assigned_to'])->where('role', 'developer')->where('is_active', true)->exists()) {
            return back()->withErrors(['assigned_to' => 'Select an active developer.'])->withInput();
        }

        $data['assigned_to'] = $data['assigned_to'] ?? null;
        $data['reported_by'] = $user->id;
        $data['status'] = 'open';

        $prefix = Project::findOrFail($data['project_id'])->key;
        $data['bug_key'] = $prefix . '-' . Str::upper(Str::random(6));

        $bug = Bug::create($data);
        $bug->activities()->create(['user_id' => $user->id, 'action' => 'Bug created']);

        return redirect()->route('bugs.show', $bug)->with('success', 'Bug report created.');
    }

    public function show(Bug $bug): View
    {
        $this->authorizeBug($bug);
        $bug->load(['project', 'reporter', 'assignee', 'comments.user', 'activities.user']);

        return view('bugs.show', compact('bug'));
    }

    public function edit(Bug $bug): View
    {
        $this->authorizeBug($bug);
        $projects = Project::where('is_active', true)->get();
        $developers = User::where('role', 'developer')->where('is_active', true)->get();

        return view('bugs.edit', compact('bug', 'projects', 'developers'));
    }

    public function update(Request $request, Bug $bug): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $this->authorizeBug($bug);

        $data = $request->validate([
            'title' => 'required|string|max:180',
            'description' => 'required|string',
            'steps_to_reproduce' => 'nullable|string',
            'expected_result' => 'nullable|string',
            'actual_result' => 'nullable|string',
            'environment' => 'nullable|string|max:180',
            'severity' => ['required', Rule::in(['critical', 'high', 'medium', 'low'])],
            'priority' => ['required', Rule::in(['urgent', 'high', 'normal', 'low'])],
            'status' => ['required', Rule::in(['open', 'in_progress', 'resolved', 'closed'])],
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if (!empty($data['assigned_to']) && !User::where('id', $data['assigned_to'])->where('role', 'developer')->where('is_active', true)->exists()) {
            return back()->withErrors(['assigned_to' => 'Select an active developer.'])->withInput();
        }

        $data['assigned_to'] = $data['assigned_to'] ?? null;

        foreach ($data as $field => $value) {
            if ((string)$bug->$field !== (string)$value) {
                $bug->activities()->create([
                    'user_id' => $user->id,
                    'action' => 'Updated ' . $field,
                    'field' => $field,
                    'old_value' => (string)$bug->$field,
                    'new_value' => (string)$value,
                ]);
            }
        }

        $data['resolved_at'] = in_array($data['status'], ['resolved', 'closed']) ? ($bug->resolved_at ?? now()) : null;
        $bug->update($data);

        return redirect()->route('bugs.show', $bug)->with('success', 'Bug updated.');
    }

    public function destroy(Bug $bug): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();
        abort_unless($user->isAdmin(), 403);

        $bug->delete();

        return redirect()->route('bugs.index')->with('success', 'Bug deleted.');
    }

    private function authorizeBug(Bug $bug): void
    {
        /** @var User $user */
        $user = auth()->user();
        abort_unless($user->isAdmin() || $bug->assigned_to === $user->id || $bug->reported_by === $user->id, 403);
    }
}
