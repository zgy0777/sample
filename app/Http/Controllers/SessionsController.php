<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    //
    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $credentials = $this->validate($request,[
            'email' => 'required|email|max:100',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials,$request->has('remember'))){
            //登陆成功逻辑
            session()->flash('success','欢迎回来');
            return redirect()->route('users.show',[Auth::user()]);
        }else{
            //失败后操作
            session()->flash('danger','您的邮箱和密码不匹配');
            return redirect()->back()->exceptInput('password');
        }

        return;

    }

    public function destroy()
    {
        Auth::logout();
        session()->flash('success','您已成功登出');
        return redirect('login');
    }


}
