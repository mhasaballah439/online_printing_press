<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'DESC')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users'
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->active = $request->active == 'on' ? 1 : 0;
        $user->password = bcrypt($request->password);
        $user->save();
        if ($request->save == 1)
            return redirect()->route('admin.users.edit', $user->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.users.index')->with('success', __('msg.created_success'));
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.users.edit', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            if ($request->name)
                $user->name = $request->name;
            if ($request->phone)
                $user->phone = $request->phone;
            if ($request->email)
                $user->email = $request->email;
            if ($request->password)
                $user->password = bcrypt($request->password);
            $user->active = $request->active == 'on' ? 1 : 0;
            $user->save();
        }
        if ($request->save == 1)
            return redirect()->route('admin.users.edit', $user->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.users.index')->with('success', __('msg.created_success'));
    }

    public function delete(Request $request)
    {
        $user = User::find($request->id);
        if ($user){
            $user->delete();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }
    public function updateStatus(Request $request)
    {
        $user = User::find($request->id);
        if ($user){
            $user->active = $request->active == 'true' ? 1 : 0;
            $user->save();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }
}
