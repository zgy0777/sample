<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{

    public function __construct()
    {
        //1.未登陆用户只能访问登陆页
        //只允许未登陆的用户访问登陆页（同时限制了已登陆用户无法访问这个页面
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }

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
            //判断用户是否已激活,这里逻辑有点问题，未激活用户应该也能登陆
            if(Auth::user()->activated){
                //登陆成功逻辑
                session()->flash('success','欢迎回来');
                //intended返回用户尝试访问的页面
                return redirect()->intended(route('users.show',[Auth::user()]));
            }else{
                Auth::logout();
                session()->flash('waring','你的账号未激活，请检查邮箱中的注册邮件进行激活');
                return redirect('/');
            }

        }else{
            //失败后操作
            session()->flash('danger','您的邮箱和密码不匹配');
            return redirect()->back()->exceptInput('password');
        }



    }

    public function destroy()
    {
        Auth::logout();
        session()->flash('success','您已成功登出');
        return redirect('login');
    }


}
