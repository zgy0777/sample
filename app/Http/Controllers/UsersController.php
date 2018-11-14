<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Mail;//引入mail接口

class UsersController extends Controller
{
    //控制器中间件
    public function __construct()
    {

        //1.未登陆用户仅能访问首页/注册页
        $this->middleware('auth', [
            //黑名单，除了此处定义的方法外，其他方法均需要登陆才可以认证
            //临时添加confirmEmail，未激活的用户可以访问激活页面
            'except' => ['show', 'create', 'store','index','confirmEmail']
        ]);

        //只允许未登陆用户访问注册页（同时限制了已登陆用户无法访问注册页
        $this->middleware('guest', [
            'only' => ['create']
        ]);


    }

    //创建用户视图
    public function create()
    {
        return view('users.create');
    }

    //show显示用户个人信息，并且读取用户所发布的微博
    public function show(User $user)
    {
        $statuses = $user->statuses()
            ->orderBy('created_at','desc')
            ->paginate('30');

        return view('users.show', compact('user','statuses'));
    }

    //创建行为
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|max:50|unique:users',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        //暂时修改，
        //Auth::login($user);

        //测试邮件发送，登陆时判断是否已激活，未激活则需要前去激活先
        $this->sendEmailConfirmationTo($user);

        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收');
        return redirect()->route('users.show', [$user]);
    }

    //更新视图
    public function edit(User $user)
    {
        //1。判断用户是否翻墙，当前用户仅能编辑自身
        try {

            $this->authorize('update', $user);

        } catch (AuthorizationException $e) {

            return abort(403, '无权访问');
        }

        return view('users.edit', compact('user'));
    }

    //更新行为
    public function update(User $user, Request $request)
    {
        $this->validate($request, [
            'name' => "required|max:50|unique:users,name,$user->id",
            'password' => 'nullable|confirmed|min:6',
        ]);

        try {
            $this->authorize('update', $user);

        } catch (AuthorizationException $e) {

            return abort(403, '无权访问');
        }

        //可只更新制定字段，判断是否接收到pwd,有则加密，无则只更新name
        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }


        $user->update($data);
        session()->flash('success', '个人资料更新成功');
        return redirect()->route('users.show', [$user]);

    }

    //主页
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index',compact('users'));
    }

    //定义删除方法
    public function destroy(User $user)
    {
        $this->authorize('destroy',$user);
        $user->delete();
        session()->flash('success','成功删除用户');
        return back();
    }

    //发送邮件方法
    public function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $to = $user->email;
        $subject = '感谢注册应用，请确定你的邮箱';

        Mail::send($view,$data,function ($message) use($to,$subject){
            $message->to($to)->subject($subject);
        });
    }

    //激活功能
    public function confirmEmail($token)
    {
        $user = User::where('activation_token',$token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success','恭喜你，激活成功');
        return redirect()->route('users.show',[$user]);

    }

    //


}
