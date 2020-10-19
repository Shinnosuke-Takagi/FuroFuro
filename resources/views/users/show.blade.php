@extends('layouts.app')

@section('content')
  <div class="container">

    <div class="card">

      <!-- Avatar -->
      @if(isset($user->avatar))
        <div class="avatar mx-auto white mt-3">
          <img src="{{ config('filesystems.disks.s3.url'). $user->avatar }}" class="rounded-circle"
            alt="avatar image">
        </div>
      @endif

      <!-- Content -->
      <div class="card-body d-flex flex-column">
        <!-- Name -->
        <h4 class="card-title text-center">{{ $user->name }}</h4>
        @if(Auth::id() === $user->id)
          <div class="text-center">
            <a href="{{ route('users.profileEdit', ['name' => $user->name]) }}" type="button" class="btn btn-default w-30">プロフィールの編集</a>
            <a href="{{ route('users.accountEdit', ['name' => $user->name]) }}" type="button" class="btn btn-success w-30">アカウント情報の変更</a>
          </div>
        @endif
        <hr>

        <ul class="nav nav-tabs nav-justified mb-3">
          <li class="nav-item">
            <a class="nav-link text-muted {{ $activeArticle ? 'active' : '' }}" href="{{ route('users.show', ['name' => $user->name]) }}">
              <i class="fas fa-user-edit pr-2"></i>投稿</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-muted {{ $activeLike ? 'active' : '' }}" href="{{ route('users.likes', ['name' => $user->name]) }}">
              <i class="fas fa-heart pr-2"></i>いいね</a>
          </li>
        </ul>

        <!-- User Posts -->
        <div class="card-deck">
          @foreach($articles as $article)
            <div class="card">
              <a href="{{ route('articles.show', ['article' => $article]) }}">
                <img class="card-img-top" src="{{ config('filesystems.disks.s3.url'). $article->main_filename }}" alt="">
              </a>
              <div class="card-body">
                <div class="d-flex flex-row">
                  <h5 class="card-title mb-0"><i class="indigo-text fas fa-hot-tub mr-1"></i>{{ $article->title }}</h5>
                  <article-like
                  class="ml-auto mr-4"
                  :initial-is-liked-by='@json($article->isLikedBy(Auth::user()))'
                  :initial-count-likes='@json($article->count_likes)'
                  :authorized='@json(Auth::check())'
                  endpoint="{{ route('articles.like', ['article' => $article]) }}"
                  ></article-like>
                  <div class="indigo-text">
                    <i class="far fa-comment-dots mr-1 p-1"></i>
                      {{ $article->count_comments }}
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
            </div>
          @endforeach
        </div>

      </div>

    </div>

  </div>
@endsection
