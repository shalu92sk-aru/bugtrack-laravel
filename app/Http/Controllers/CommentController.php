<?php
namespace App\Http\Controllers;
use App\Models\Bug;
use Illuminate\Http\Request;
class CommentController extends Controller
{
    public function store(Request $request,Bug $bug) {
        abort_unless(auth()->user()->isAdmin() || $bug->assigned_to===auth()->id() || $bug->reported_by===auth()->id(),403);
        $data=$request->validate(['comment'=>'required|string|max:3000']);
        $bug->comments()->create(['user_id'=>auth()->id(),'comment'=>$data['comment']]);
        $bug->activities()->create(['user_id'=>auth()->id(),'action'=>'Added a comment']);
        return back()->with('success','Comment added.');
    }
}
