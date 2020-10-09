<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TAg;

class TagController extends Controller
{
    public function show(string $name)
    {
        $tag = Tag::where('name', $name)->first();

        return view('tags.show', ['tag' => $tag]);
    }
}
