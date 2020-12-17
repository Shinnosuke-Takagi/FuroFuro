# 概要

銭湯好きな人がみんなで共有できるアプリになります。

# 機能一覧

## 記事

- 記事のCRUD機能（MySQL, AWS S3）
- 記事に対するいいね機能
- 記事に対するコメント機能
- コメントに対するリプライ機能
- タグ付け機能
- Google Map地図機能

## ユーザー、認証

- ユーザー登録機能（メール認証機能付き, AWS SES）
- パスワード変更（メール認証付き, AWS SES）
- プロフィール画像設定機能（AWS S3）
- ユーザーページ機能
- プロフィール、アカウント変更機能（メール認証つき, AWS SES）
- ゲストログイン機能

## その他

- laravel-debugbarを使ったN+1問題の解消
- intervention/imageを使った画像サイズの調整
- league/flysystem-aws-s3-v3を使ったAWS S3への画像のアップロード
- phpunit CircleCI を使ったCI/CDパイプラインの構築

## 環境構築

- サーバー（AWS EC2）
- データベース（AWS RDS）
- CI/CDパイプライン（CircleCI）
- ソースコード管理（Github）
- 仮想環境（Docker）
