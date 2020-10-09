<?php

namespace App\Http\Controllers;

use App\Article;
use App\Photo;
use App\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use InterventionImage;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Article::class, 'article');
    }
    public function index()
    {
        $articles = Article::all()->sortByDesc('created_at');

        return view('articles.index', ['articles' => $articles]);
    }

    public function create()
    {
        $allTagNames = Tag::all()->map(function ($tag) {
          return ['text' => $tag->name];
        });

        return view('articles.create', [
          'allTagNames' => $allTagNames,
        ]);
    }

    public function store(ArticleRequest $request)
    {
      $article = new Article();
      $main_file = $request->file('main_filename');

      $article->title = $request->title;
      $article->body = $request->body;
      $article->map_query = $request->map_query;
      $article->main_filename = $main_file->getClientOriginalName();
      $article->user_id = $request->user()->id;

      InterventionImage::make($main_file)
            ->heighten(300)->save();

      Storage::cloud()->putFileAs('', $main_file, $article->main_filename, 'public');

      DB::beginTransaction();

      try {

          $article->save();
          if(! empty($request->file('files'))) {
            $files = $request->file('files');
            foreach($files as $file) {
              $photos[] = [
                'filename' => $file->getClientOriginalName(),
                'article_id' => $article->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
              ];

              InterventionImage::make($file)
                    ->heighten(300)->save();

              Storage::cloud()->putFileAs('', $file, $file->getClientOriginalName(), 'public');

              $s3_filenames[] = $file->getClientOriginalName();

            }

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

      $request->tags->each(function ($tagName) use ($article) {
        $tag = Tag::firstOrCreate(['name' => $tagName]);
        $article->tags()->attach($tag);
      });

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

        $tagNames = $article->tags->map(function ($tag) {
          return ['text' => $tag->name];
        });

        $allTagNames = Tag::all()->map(function ($tag) {
          return ['text' => $tag->name];
        });

        return view('articles.edit', [
          'article' => $article,
          'photos' => $photos,
          'tagNames' => $tagNames,
          'allTagNames' => $allTagNames,
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

      $main_file = $request->file('main_filename');

      $article->title = $request->title;
      $article->body = $request->body;
      $article->map_query = $request->map_query;
      $article->main_filename = $main_file->getClientOriginalName();
      $article->user_id = $request->user()->id;

      InterventionImage::make($main_file)
            ->heighten(300)->save();

      Storage::cloud()->putFileAs('', $main_file, $article->main_filename, 'public');

      DB::beginTransaction();

      try {

          $article->save();

          if(! empty($request->file('files'))) {
            $files = $request->file('files');
            foreach($files as $file) {
              $new_photos[] = [
                'filename' => $file->getClientOriginalName(),
                'article_id' => $article->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
              ];

              InterventionImage::make($file)
                    ->heighten(300)->save();

              Storage::cloud()->putFileAs('', $file, $file->getClientOriginalName(), 'public');

              $s3_filenames[] = $file->getClientOriginalName();
            }

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

      $article->tags()->detach();
      $request->tags->each(function ($tagName) use ($article) {
        $tag = Tag::firstOrCreate(['name' => $tagName]);
        $article->tags()->attach($tag);
      });

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
