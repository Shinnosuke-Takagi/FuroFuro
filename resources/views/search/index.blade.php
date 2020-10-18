@extends('layouts.app')

@section('content')
  <div class="container">
      <div class="list-group">
        @foreach($tags as $tag)
          <a href="{{ route('tags.show', ['name' => $tag->name]) }}" class="list-group-item list-group-item-action aqua-gradient mb-1">
            <div class="text-white d-flex flex-row justify-content-center">
              <p class="mr-3">{{ $tag->hashtag }}</p>
              <p>投稿{{ $tag->count_articles }}件</p>
            </div>
          </a>
        @endforeach
        @foreach($users as $user)
          <a href="{{ route('users.show', ['name' => $user->name]) }}" class="list-group-item list-group-item-action aqua-gradient mb-1">
            <div class="text-center text-white">
              <img src="{{ config('filesystems.disks.s3.url'). $user->avatar }}" class="rounded-circle z-depth-0"
              alt="avatar image" height="35">
              {{ $user->name }}
            </div>
          </a>
        @endforeach
    </div>
    @if($tags->isEmpty() && $users->isEmpty())
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card text-center">
                  <div class="card-header aqua-gradient text-white">検索結果はありませんでした...</div>

                  <div class="card-body ">
                      <p>もう一度検索してみてください！</p>
                      <form method="GET" action="{{ route('search') }}" class="form-inline d-flex justify-content-center">
                        @csrf
                        <div class="md-form m-0">
                          <input class="form-control" type="text" name="keyword" placeholder="Search" aria-label="Search">
                        </div>
                        <button type="submit" class="btn btn-outline-info btn-rounded btn-sm ml-1">Search</button>
                      </form>
                  </div>
              </div>
          </div>
      </div>
    @endif
  </div>
@endsection
