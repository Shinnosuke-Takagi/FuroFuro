@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-header aqua-gradient text-white">メールアドレスが未認証の状態です。</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            メールをお送りしました！
                        </div>
                    @endif

                    お送りしたメールからメールアドレスの認証を行なってください。
                    <p>メールが届かない場合は下のボタンから再送信をお願いします。</p>
                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-default">再送信</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
