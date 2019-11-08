<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['file_name', 'file_size', 'download_link'];

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}
