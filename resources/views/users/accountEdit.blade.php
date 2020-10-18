@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header aqua-gradient text-white">アカウント情報の変更</div>

                <div class="card-body">
                  @if($errors->any())
                    <div class="alert alert-danger">
                      @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </div>
                  @endif

                  <form method="POST" action="{{ route('users.emailUpdate', ['name' => $auth_user->name]) }}">
                      @method('PATCH')
                      @csrf

                      <div class="form-group row">
                          <label for="email" class="col-md-4 col-form-label text-md-right">メールアドレスの変更</label>

                          <div class="col-md-6">
                              <input id="email" type="email" class="form-control" name="email" value="{{ $auth_user->email ?? old('email') }}">
                          </div>
                      </div>

                      <div class="form-group row mb-0">
                          <div class="col-md-6 offset-md-4">
                              <button type="submit" class="btn btn-primary">
                                  変更する
                              </button>
                          </div>
                      </div>
                  </form>

                  <form method="POST" action="{{ route('users.passwordUpdate', ['name' => $auth_user->name]) }}">
                      @method('PATCH')
                      @csrf

                      <div class="form-group row">
                          <label for="password" class="col-md-4 col-form-label text-md-right">パスワードの変更</label>

                          <div class="col-md-6">
                              <input id="password" type="password" class="form-control" name="password" placeholder="新しいパスワードを入力してください">
                          </div>
                      </div>

                      <div class="form-group row">
                          <label for="password-confirm" class="col-md-4 col-form-label text-md-right">パスワードの変更（確認用）</label>

                          <div class="col-md-6">
                              <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="もう一度新しいパスワードを入力してください">
                          </div>
                      </div>

                      <div class="form-group row mb-0">
                          <div class="col-md-6 offset-md-4">
                              <button type="submit" class="btn btn-primary">
                                  変更する
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
