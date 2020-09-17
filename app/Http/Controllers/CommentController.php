<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Article;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function create(Article $article)
    {
        return view('comments.create', ['article' => $article]);
    }

    public function store(CommentRequest $request, Article $article, Comment $comment)
    {
        $comment->article_id = $article->id;
        $comment->user_id = $request->user()->id;
        $comment->content = $request->content;

        $comment->save();

        return redirect()->route('articles.show', ['article' => $article]);
    }
}
