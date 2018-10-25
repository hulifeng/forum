<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $fillable = ['name', 'description'];

    protected $casts = ['post_count' => 'boolean'];
}
