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
            <div class="d-flex flex-row">
              <h5 class="card-title">{{ $article->title }}</h5>
              <article-like
              class="ml-auto mr-4"
              :initial-is-liked-by='@json($article->isLikedBy(Auth::user()))'
              :initial-count-likes='@json($article->count_likes)'
              :authorized='@json(Auth::check())'
              endpoint="{{ route('articles.like', ['article' => $article]) }}"
              ></article-like>
            </div>
            @if($article->user_id === Auth::id())
            <div class="d-flex flex-row">
              <a href="{{ route('articles.edit', ['article' => $article]) }}" class="btn btn-primary btn-sm">edit</a>
              <form method="POST" action="{{ route('articles.destroy', ['article' => $article]) }}">
                @method('DELETE')
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">delete</button>
              </form>
            </div>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection
