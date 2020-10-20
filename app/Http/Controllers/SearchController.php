<?php

namespace App\Http\Controllers;

use App\User;
use App\Article;
use App\Tag;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function result(Request $request)
    {
        $tags = Tag::where('name', 'like', "%{$request->keyword}%")->get();
        $users = User::where('name', 'like', "%{$request->keyword}%")->get();
        $articles = Article::where('title', 'like', "%{$request->keyword}%")->get();

        return view('search.index', [
          'tags' => $tags,
          'users' => $users,
          'articles' => $articles,
        ]);
    }
}
