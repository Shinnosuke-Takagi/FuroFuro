<?php

namespace App\Http\Controllers;

use App\Article;
use App\Photo;
use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::all()->sortByDesc('created_at');

        return view('articles.index', ['articles' => $articles]);
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(ArticleRequest $request)
    {
      $article = new Article();

      $article->title = $request->title;
      $article->body = $request->body;
      $article->map_query = $request->map_query;
      $article->main_filename = $request->file('main_filename')->getClientOriginalName();
      $article->user_id = $request->user()->id;

      Storage::cloud()->putFileAs('', $request->file('main_filename'), $article->main_filename, 'public');

      if(! empty($request->file('files'))) {
        $files = $request->file('files');
        foreach($files as $file) {
          $photos[] = [
            'filename' => $file->getClientOriginalName(),
            'article_id' => $article->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
          ];

          Storage::cloud()->putFileAs('', $file, $file->getClientOriginalName(), 'public');

          $s3_filenames[] = $file->getClientOriginalName();
        }
      }

      DB::beginTransaction();

      try {

          $article->save();
          if(! empty($request->file('files'))) {
              DB::table('photos')->insert($photos);
          }

          DB::commit();

      } catch(\Exception $exception) {
        DB::rollback();

        Storage::cloud()->delete($article->main_filename);
        if(! empty($request->file('files'))) {
          Storage::cloud()->delete($s3_filenames);
        }
        throw $exception;
      }

      return redirect()->route('articles.index');
    }

    public function show(Article $article)
    {
        $photos = $article->photos;
        $comments = $article->comments()->paginate(5);

        return view('articles.show', [
          'article' => $article,
          'photos' => $photos,
          'comments' => $comments,
        ]);
    }

    public function edit(Article $article)
    {
        $photos = $article->photos;

        return view('articles.edit', [
          'article' => $article,
          'photos' => $photos,
        ]);
    }

    public function update(ArticleRequest $request, Article $article)
    {
      Storage::cloud()->delete($article->main_filename);
      $photos = $article->photos;
      foreach($photos as $photo) {
        Storage::cloud()->delete($photo->filename);
        $photo->delete();
      }

      $article->title = $request->title;
      $article->body = $request->body;
      $article->map_query = $request->map_query;
      $article->main_filename = $request->file('main_filename')->getClientOriginalName();
      $article->user_id = $request->user()->id;

      Storage::cloud()->putFileAs('', $request->file('main_filename'), $article->main_filename, 'public');

      if(! empty($request->file('files'))) {
        $files = $request->file('files');
        foreach($files as $file) {
          $new_photos[] = [
            'filename' => $file->getClientOriginalName(),
            'article_id' => $article->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
          ];

          Storage::cloud()->putFileAs('', $file, $file->getClientOriginalName(), 'public');

          $s3_filenames[] = $file->getClientOriginalName();
        }
      }

      DB::beginTransaction();

      try {

          $article->save();
          if(! empty($request->file('files'))) {
              DB::table('photos')->insert($new_photos);
          }

          DB::commit();

      } catch(\Exception $exception) {
        DB::rollback();

        Storage::cloud()->delete($article->main_filename);
        if(! empty($request->file('files'))) {
          Storage::cloud()->delete($s3_filenames);
        }
        throw $exception;
      }

      return redirect()->route('articles.show', ['article' => $article]);
    }

    public function destroy(Article $article)
    {
        Storage::cloud()->delete($article->main_filename);

        $photos = $article->photos;
        foreach($photos as $photo) {
          Storage::cloud()->delete($photo->filename);
          $photo->delete();
        }

        $article->delete();

        return redirect()->route('articles.index');
    }

    public function like(Request $request, Article $article)
    {
        $article->likes()->detach($request->user()->id);
        $article->likes()->attach($request->user()->id);

        return [
          'id' => $article->id,
          'countLikes' => $article->count_likes,
        ];
    }

    public function unlike(Request $request, Article $article)
    {
        $article->likes()->detach($request->user()->id);

        return [
          'id' => $article->id,
          'countLikes' => $article->count_likes,
        ];
    }
}
