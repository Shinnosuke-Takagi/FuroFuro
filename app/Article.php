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

    protected $keyType = 'string';

    const ID_LENGTH = 12;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (! Arr::get($this->attributes, 'id')) {
            $this->setId();
        }
    }

    private function setId()
    {
        $this->attributes['id'] = $this->getRandomId();
    }

    private function getRandomId()
    {
        $characters = array_merge(
            range(0, 9), range('a', 'z'),
            range('A', 'Z'), ['-', '_']
        );

        $length = count($characters);

        $id = "";

        for ($i = 0; $i < self::ID_LENGTH; $i++) {
            $id .= $characters[random_int(0, $length - 1)];
        }

        return $id;
    }
}
