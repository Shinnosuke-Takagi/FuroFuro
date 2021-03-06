<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $with = [
      'user', 'replies.user'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function article()
    {
        return $this->belongsTo('App\Article');
    }

    public function replies()
    {
        return $this->hasMany('App\Reply');
    }
}
