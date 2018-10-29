<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;

class User extends Authenticatable
{
    use Notifiable {
        notify as protected laravelNotify;
    }

    protected $fillable = [
        'name', 'email', 'password', 'avator', 'introduction'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // 关联话题
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    // 是否授权
    public function isAuthorOf($model)
    {
        return $this->id === $model->user_id;
    }

    // 关联回复
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    // 异步通知
    public function notify($instance)
    {
        // 如果要通知的人是当前用户，就不必通知了
        if ($this->id == Auth::id()) {
            return;
        }

        $this->increment('notification_count');
        $this->laravelNotify($instance);
    }

    public function makeAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }
}
