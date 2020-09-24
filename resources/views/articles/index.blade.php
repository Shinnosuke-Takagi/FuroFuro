@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="card-deck">
      @foreach($articles as $article)
        <div class="card">
          <a href="{{ route('articles.show', ['article' => $article]) }}">
            <img class="card-img-top" src="{{ config('filesystems.disks.s3.url'). $article->main_filename }}" alt="">
          </a>
          <div class="card-body">
            <h5 class="card-title">{{ $article->title }}</h5>
            <div class="card-body pt-0 pb-2 pl-3">
              <div class="card-text">
                <article-like
                  :initial-is-liked-by='@json($article->isLikedBy(Auth::user()))'
                  :initial-count-likes='@json($article->count_likes)'
                  :authorized='@json(Auth::check())'
                  endpoint="{{ route('articles.like', ['article' => $article]) }}"
                ></article-like>
              </div>
            </div>
            @if($article->user_id === Auth::id())
              <a href="{{ route('articles.edit', ['article' => $article]) }}" class="btn btn-primary">edit</a>
              <form method="POST" action="{{ route('articles.destroy', ['article' => $article]) }}">
                @method('DELETE')
                @csrf
                <button type="submit" class="btn btn-danger">delete</button>
              </form>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection
