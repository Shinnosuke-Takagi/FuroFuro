@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header aqua-gradient text-white">プロフィールの変更</div>

                <div class="card-body">
                  @if($errors->any())
                    <div class="alert alert-danger">
                      @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </div>
                  @endif

                  <form method="POST" action="{{ route('users.profileUpdate', ['name' => $auth_user->name]) }}" enctype="multipart/form-data">
                      @method('PATCH')
                      @csrf

                      <div class="form-group row">
                          <label for="name" class="col-md-4 col-form-label text-md-right">お名前</label>

                          <div class="col-md-6">
                              <input id="name" type="text" class="form-control" name="name" value="{{ $auth_user->name ?? old('name') }}">
                          </div>
                      </div>

                      <div class="form-group row">
                          <label for="avatar" class="col-md-4 col-form-label text-md-right">現在の画像</label>

                          <div class="avatar white m-3 p-0 col-md-4">
                            @if(isset($auth_user->avatar))
                              <img src="{{ config('filesystems.disks.s3.url'). $auth_user->avatar }}" class="rounded float-left"
                                alt="avatar image" height="100px">
                            @else
                              <div class="alert alert-light" role="alert">
                                画像は設定されていません
                              </div>
                            @endif
                            <input id="avatar" type="file" class="form-control" name="avatar">
                          </div>

                      </div>

                      <div class="form-group row mb-0">
                          <div class="col-md-6 offset-md-4">
                              <button type="submit" class="btn btn-primary">
                                  更新する
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
