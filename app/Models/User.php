<?php

namespace App\Models;

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
        return $this->id = $model->user_id;
    }

    // 关联回复
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}
