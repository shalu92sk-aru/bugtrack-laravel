<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::with('creator')->withCount('bugs')->latest()->paginate(10);
        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        /** @var User $user */
        $user = auth()->user();
        abort_unless($user->isAdmin(), 403);

        return view('projects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();
        abort_unless($user->isAdmin(), 403);

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'key' => 'required|string|max:12|alpha_dash|unique:projects,key',
            'description' => 'nullable|string',
        ]);
        $data['created_by'] = $user->id;
        Project::create($data);

        return redirect()->route('projects.index')->with('success', 'Project created.');
    }

    public function show(Project $project): View
    {
        /** @var User $user */
        $user = auth()->user();

        $bugsQuery = $project->bugs()->with(['assignee', 'reporter'])->latest();
        if (!$user->isAdmin()) {
            $bugsQuery->where(fn ($x) => $x->where('assigned_to', $user->id)->orWhere('reported_by', $user->id));
        }
        $bugs = $bugsQuery->paginate(10);

        return view('projects.show', compact('project', 'bugs'));
    }

    public function edit(Project $project): View
    {
        /** @var User $user */
        $user = auth()->user();
        abort_unless($user->isAdmin(), 403);

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();
        abort_unless($user->isAdmin(), 403);

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'key' => ['required', 'string', 'max:12', 'alpha_dash', Rule::unique('projects', 'key')->ignore($project)],
            'description' => 'nullable|string',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $project->update($data);

        return redirect()->route('projects.index')->with('success', 'Project updated.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();
        abort_unless($user->isAdmin(), 403);

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted.');
    }
}
