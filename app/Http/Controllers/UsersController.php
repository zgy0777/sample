<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{

    //创建用户视图
    public function create()
    {
        return view('users.create');
    }

    //show显示用户个人信息
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    //创建行为
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);

        session()->flash('success', '欢迎，您将在这里开启一段新旅程');
        return redirect()->route('users.show', [$user]);
    }



}
