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
            <div class="indigo-text">
              <i class="far fa-comment-dots mr-1 p-1"></i>
                {{ $article->count_comments_replies }}
            </div>
          </div>
          <div class="d-flex flex-row">
            @if($article->user_id === Auth::id())
              <a href="{{ route('articles.edit', ['article' => $article]) }}" class="btn btn-primary btn-sm">修正</a>
              <form method="POST" action="{{ route('articles.destroy', ['article' => $article]) }}">
                @method('DELETE')
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">削除</button>
              </form>
            @endif
          </div>
          <div class="d-flex flex-row">
            @foreach($article->tags as $tag)
            @if($loop->first)
            <div class="card-body pt-3 pb-4 pl-3">
              <div class="card-text line-height">
                @endif
                <a href="{{ route('tags.show', ['name' => $tag->name]) }}" class="border p-1 mr-1 mt-1 text-muted">
                  {{ $tag->hashtag }}
                </a>
                @if($loop->last)
              </div>
            </div>
            @endif
            @endforeach
          </div>
          <div class="d-flex flex-row">
            <div class="ml-auto">
              <a href="{{ route('users.show', ['name' => $article->user->name]) }}" class="text-dark">
                by:
                @if(isset($article->user->avatar))
                  <img src="{{ config('filesystems.disks.s3.url'). $article->user->avatar }}" class="rounded-circle z-depth-0"
                    alt="avatar image" height="35">
                @endif
                {{ $article->user->name }}
              </a>
            </div>
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

          <div class="card-body pb-0">
            <h6 class="indigo-text"><i class="fas fa-comments mr-1"></i>みんなのコメント</h6>
            @if($article->user_id !== Auth::id())
              <a href="{{ route('comment.create', ['article' => $article]) }}" class="btn btn-primary btn-sm ml-auto">Add Comment</a>
            @endif
          </div>
            @foreach($comments as $comment)
              <div class="card card-body mt-4 pb-0">
                <div class="d-flex flex-row">
                  <a href="{{ route('users.show', ['name' => $comment->user->name]) }}" class="text-dark">
                    @if(isset($comment->user->avatar))
                    <img src="{{ config('filesystems.disks.s3.url'). $comment->user->avatar }}" class="rounded-circle z-depth-0"
                    alt="avatar image" height="35">
                    @endif
                    {{ $comment->user->name }}
                  </a>
                </div>
                <p class="card-text mt-3">{{ $comment->content }}</p>
                <div class="d-flex justify-content-between mt-4">
                  @if($comment->user_id !== Auth::id())
                    <a href="{{ route('reply.create', ['comment' => $comment]) }}" class="indigo-text pr-4">
                      <i class="fas fa-reply"></i>
                      返信する
                    </a>
                  @endif
                  <p class="indigo-text">
                    <i class="fas fa-reply-all pr-1"></i>
                    {{ $comment->replies->count() }}
                  </p>
                  <p class="ml-auto">
                    {{ $comment->created_at }}
                  </p>
                </div>
              </div>
              @foreach($comment->replies as $reply)
                <div class="card card-body ml-5 m-1">
                  <div class="d-flex flex-row">
                    <a href="{{ route('users.show', ['name' => $reply->user->name]) }}" class="text-dark">
                      @if(isset($reply->user->avatar))
                      <img src="{{ config('filesystems.disks.s3.url'). $reply->user->avatar }}" class="rounded-circle z-depth-0"
                      alt="avatar image" height="35">
                      @endif
                      {{ $reply->user->name }}
                    </a>
                  </div>
                  <p class="card-text mt-3">{{ $reply->content }}</p>
                  <div class="d-flex justify-content-end">
                    {{ $reply->created_at }}
                  </div>
                </div>
              @endforeach
            @endforeach
            <div class="card-body">
              {{ $comments->links() }}
            </div>
      </div>
    </div>
  </div>
@endsection
