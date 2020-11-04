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
              @include('layouts.parts')
            </div>
          @endforeach
        </div>

      </div>

    </div>

  </div>
@endsection
