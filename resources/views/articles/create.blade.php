@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header aqua-gradient text-white">記事の投稿</div>

                <div class="card-body">
                    @if($errors->any())
                      <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                      </div>
                    @endif
                    <form method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <label for="title" class="col-md-4 col-form-label text-md-right">店名</label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control" name="title" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="main_filename" class="col-md-4 col-form-label text-md-right">メインとなる写真（トップページの一覧画面に表示される写真です）</label>

                            <div class="col-md-6">
                                <input id="main_filename" type="file" class="form-control" name="main_filename" accept=".jpg, .jpeg, .png">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="files" class="col-md-4 col-form-label text-md-right">その他の写真（投稿記事の詳細画面に表示される写真です）</label>

                            <div class="col-md-6">
                                <input id="files" type="file" class="form-control" name="files[]" multiple="" accept=".jpg, .jpeg, .png">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="map_query" class="col-md-4 col-form-label text-md-right">お店の場所</label>

                            <div class="col-md-6">
                                <input id="map_query" type="text" class="form-control" name="map_query" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="body" class="col-md-4 col-form-label text-md-right">説明など</label>

                            <div class="col-md-6">
                                <textarea id="body" class="form-control" name="body" required></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tags" class="col-md-4 col-form-label text-md-right">タグ</label>

                            <div class="col-md-6">
                                <article-tags-input
                                 :autocomplete-items='@json($allTagNames ?? [])'
                                >
                                </article-tags-input>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    投稿する
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
