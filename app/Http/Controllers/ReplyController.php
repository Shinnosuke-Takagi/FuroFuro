<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Reply;
use Illuminate\Http\Request;
use App\Http\Requests\ReplyRequest;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['verified']);
    }

    public function create(Comment $comment)
    {
        return view('replies.create', ['comment' => $comment]);
    }

    public function store(ReplyRequest $request, Comment $comment, Reply $reply)
    {
        $reply->comment_id = $comment->id;
        $reply->user_id = $request->user()->id;
        $reply->content = $request->content;

        $reply->save();

        $article = $comment->article;

        return redirect()->route('articles.show', ['article' => $article]);
    }
}
