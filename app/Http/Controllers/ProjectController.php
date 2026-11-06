<?php
namespace App\Http\Controllers;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function index() {
        $projects=Project::with('creator')->withCount('bugs')->latest()->paginate(10);
        return view('projects.index',compact('projects'));
    }
    public function create() { abort_unless(auth()->user()->isAdmin(),403); return view('projects.create'); }
    public function store(Request $request) {
        abort_unless(auth()->user()->isAdmin(),403);
        $data=$request->validate(['name'=>'required|string|max:120','key'=>'required|string|max:12|alpha_dash|unique:projects,key','description'=>'nullable|string']);
        $data['created_by']=auth()->id(); Project::create($data);
        return redirect()->route('projects.index')->with('success','Project created.');
    }
    public function show(Project $project) {
        $bugs=$project->bugs()->with(['assignee','reporter'])->latest()->paginate(10);
        return view('projects.show',compact('project','bugs'));
    }
    public function edit(Project $project) { abort_unless(auth()->user()->isAdmin(),403); return view('projects.edit',compact('project')); }
    public function update(Request $request, Project $project) {
        abort_unless(auth()->user()->isAdmin(),403);
        $data=$request->validate(['name'=>'required|string|max:120','key'=>['required','string','max:12','alpha_dash',Rule::unique('projects','key')->ignore($project->id)],'description'=>'nullable|string','is_active'=>'sometimes|boolean']);
        $project->update($data); return redirect()->route('projects.index')->with('success','Project updated.');
    }
    public function destroy(Project $project) {
        abort_unless(auth()->user()->isAdmin(),403);
        $project->delete(); return redirect()->route('projects.index')->with('success','Project deleted.');
    }
}
