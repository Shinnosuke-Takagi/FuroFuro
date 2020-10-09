@extends('layouts.app')

@section('content')
  <div class="container">

    <div class="row no-gutters">

      <div class="card col-md-6">
        <div id="carouselExampleControls" class="carousel slide elegant-color-dark" data-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img class="d-block mx-auto" src="{{config('filesystems.disks.s3.url'). $article->main_filename}}" alt="Card image cap">
            </div>
            @foreach($photos as $photo)
              <div class="carousel-item">
                <img src="{{ config('filesystems.disks.s3.url'). $photo->filename }}" alt="" class="d-block mx-auto">
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
            <h5 class="card-title"><i class="fas fa-hot-tub mr-1 indigo-text"></i>{{ $article->title }}</h5>
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
            @endif
          </div>
          <div class="d-flex flex-row">
            @foreach($article->tags as $tag)
            @if($loop->first)
            <div class="card-body pt-3 pb-4 pl-3">
              <div class="card-text line-height">
                @endif
                <a href="" class="border p-1 mr-1 mt-1 text-muted">
                  {{ $tag->hashtag }}
                </a>
                @if($loop->last)
              </div>
            </div>
            @endif
            @endforeach
          </div>
        </div>
        <div class="card-body">
          <h6 class="indigo-text"><i class="fas fa-align-left mr-1"></i>お店について</h6>
          <p class="card-text">{{ $article->body }}</p>
        </div>
      </div>

      <div class="card col-md-6">
        <div class="card-body">
          <h6 class="indigo-text"><i class="fas fa-map-marked-alt mr-1"></i>お店の場所</h6>
          <div id="map-container-google-1" class="z-depth-1-half map-container" style="overflow: hidden; position: relative; padding-bottom: 50%;" height="100px">
            <iframe src="https://maps.google.co.jp/maps?output=embed&q={{ $article->map_query }}" frameborder="0" style="border:0; position: absolute;" allowfullscreen width="100%" height="100%"></iframe>
          </div>
        </div>

          <div class="card-body">
            <h6 class="indigo-text"><i class="fas fa-comments mr-1"></i>みんなのコメント</h6>
            @if($article->user_id !== Auth::id())
              <a href="{{ route('comment.create', ['article' => $article]) }}" class="btn btn-primary btn-sm ml-auto">Add Comment</a>
            @endif
          </div>
            @foreach($comments as $comment)
            <div class="card-body">
              <h5 class="card-title">{{ $comment->user->name }}</h5>
              <p class="card-text">{{ $comment->content }}</p>
            </div>
            @endforeach
            <div class="card-body">
              {{ $comments->links() }}
            </div>
      </div>
    </div>
  </div>
@endsection
