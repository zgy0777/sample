<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','is_admin'
    ];

    //模型入库前事件
    public static function boot(){

        parent::boot();

        static::creating(function ($user){
           $user->activation_token = str_random(30);
        });

    }
    //发送邮件
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
    //引入通用头像
    public function gravatar($size = '140')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    //一对多模型关联，取出用户所发布的微博
    public function statuses()
    {
        return $this->hasMany(\App\Models\Status::class,'user_id','id');
    }

    //取出用户所有发布的微博，并以倒序显示
    public function feed()
    {
        return $this->statuses()->orderBy('created_at','desc');
    }


}
