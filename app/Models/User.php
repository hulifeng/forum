<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

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

    // 重新设置密码
    public function setPasswordAttribute($value)
    {
        // 如果值的长度等于 60，即认为是已经做过加密的情况
        if (strlen($value) != 60) {

            // 不等于 60，做密码加密处理
            $value = bcrypt($value);
        }

        $this->attributes['password'] = $value;
    }

    // 上传头像
    public function setAvatorAttribute($path)
    {
        // 如果不是 `http` 子串开头，那就是从后台上传的，需要补全 URL
        if (!starts_with($path, 'http')) {

            // 拼接完整的 URL
            $path = config('app.url') . "/uploads/images/avators/$path";
        }

        $this->attributes['avator'] = $path;
    }
}
