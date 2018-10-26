<?php

namespace App\Models;

class Reply extends Model
{
    protected $fillable = ['content'];

    // 关联话题模型 一对一
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    // 关联用户模型 一对一
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
