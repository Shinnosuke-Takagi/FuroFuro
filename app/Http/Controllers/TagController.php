<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;

class TagController extends Controller
{
    public function show(string $name)
    {
        $tag = Tag::where('name', $name)->first();

        $articles = $tag->articles->sortByDesc('created_at');

        return view('tags.show', [
          'tag' => $tag,
          'articles' => $articles,
        ]);
    }
}
