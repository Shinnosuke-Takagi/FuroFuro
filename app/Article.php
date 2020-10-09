<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Article extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function photos()
    {
        return $this->hasMany('App\Photo');
    }

    public function likes()
    {
        return $this->belongsToMany('App\User', 'likes')->withTimeStamps();
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    public function isLikedBy(?User $user)
    {
        return $user
          ? (bool)$this->likes->where('id', $user->id)->count()
          : false;
    }

    public function getCountLikesAttribute()
    {
        return $this->likes->count();
    }

}
