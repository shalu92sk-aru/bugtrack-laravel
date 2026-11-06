<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index() { $users=User::latest()->paginate(12); return view('users.index',compact('users')); }
    public function create() { return view('users.create'); }
    public function store(Request $request) {
        $data=$request->validate(['name'=>'required|string|max:120','email'=>'required|email|unique:users,email','password'=>'required|string|min:8|confirmed','role'=>['required',Rule::in(['admin','developer'])]]);
        User::create($data+['is_active'=>true]); return redirect()->route('users.index')->with('success','User created.');
    }
    public function edit(User $user) { return view('users.edit',compact('user')); }
    public function update(Request $request,User $user) {
        $data=$request->validate(['name'=>'required|string|max:120','email'=>['required','email',Rule::unique('users','email')->ignore($user->id)],'role'=>['required',Rule::in(['admin','developer'])],'is_active'=>'required|boolean','password'=>'nullable|string|min:8|confirmed']);
        if(empty($data['password'])) unset($data['password']);
        $user->update($data); return redirect()->route('users.index')->with('success','User updated.');
    }
    public function destroy(User $user) {
        abort_if($user->id===auth()->id(),422,'You cannot delete your own account.');
        $user->delete(); return redirect()->route('users.index')->with('success','User deleted.');
    }
}
