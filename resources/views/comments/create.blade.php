@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header aqua-gradient text-white">コメントの追加</div>

                <div class="card-body">
                    @if($errors->any())
                      <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                      </div>
                    @endif
                    <form method="POST" action="{{ route('comment.store', ['article' => $article]) }}">
                        @csrf

                        <div class="form-group row">
                            <label for="body" class="col-md-4 col-form-label text-md-right">コメント</label>

                            <div class="col-md-6">
                                <textarea id="content" class="form-control" name="content" required></textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    コメントする
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
