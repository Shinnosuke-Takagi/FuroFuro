@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="card aqua-gradient mb-3">
      <div class="card-header text-white text-center">{{ $tag->hashtag }}</div>
    </div>
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
@endsection
