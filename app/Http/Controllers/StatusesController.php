<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Status;
use App\Models\User;

class StatusesController extends Controller
{
    //设置控制器验证器，此控制器方法仅允许已登陆用户访问
    public function __construct()
    {
        $this->middleware('auth');
    }

    //创建微博
    public function store(Request $request)
    {
        $this->validate($request,[
           'content' => 'required|max:140',
        ]);

        Auth::user()->statuses()->create([
            'content' => $request['content'],
        ]);

        return redirect()->back();

    }

    //删除微博
    public function destroy(Status $status)
    {
        $this->authorize('destroy', $status);
        $status->delete();
        session()->flash('success', '删除成功！');
        return redirect()->back();
    }


}
