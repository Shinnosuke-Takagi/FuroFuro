<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
      'name',
    ];

    public function getHashtagAttribute()
    {
        return '# '.$this->name;
    }

    public function articles()
    {
        return $this->belongsToMany('App\Article')->withTimestamps();
    }

    public function getCountArticlesAttribute()
    {
        return $this->articles->count();
    }
}
