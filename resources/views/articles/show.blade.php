@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="card">

      <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img class="d-block w-100" src="{{config('filesystems.disks.s3.url'). $article->main_filename}}" alt="Card image cap">
          </div>
          @foreach($photos as $photo)
            <div class="carousel-item">
              <img src="{{ config('filesystems.disks.s3.url'). $photo->filename }}" alt="" class="d-block w-100">
            </div>
          @endforeach
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>

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
        <div class="d-flex flex-row">
          @if($article->user_id === Auth::id())
          <a href="{{ route('articles.edit', ['article' => $article]) }}" class="btn btn-primary btn-sm">edit</a>
          <form method="POST" action="{{ route('articles.destroy', ['article' => $article]) }}">
            @method('DELETE')
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">delete</button>
          </form>
          @else
          <a href="{{ route('comment.create', ['article' => $article]) }}" class="btn btn-primary btn-sm">Add Comment</a>
          @endif
        </div>
        <p class="card-text">{{ $article->body }}</p>
      </div>

      <div class="card-footer">
        <iframe src="https://maps.google.co.jp/maps?output=embed&q={{ $article->map_query }}"></iframe>
      </div>
      @foreach($article->comments as $comment)
        <p class="card-text">{{ $comment->content }}</p>
      @endforeach
    </div>
  </div>
@endsection
