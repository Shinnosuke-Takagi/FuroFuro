<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(string $name)
    {
        $user = User::where('name', $name)->first();

        $articles = $user->articles->sortByDesc('created_at');

        return view('users.show', [
          'user' => $user,
          'articles' => $articles,
          'activeArticle' => true,
          'activeLike' => false,
        ]);
    }

    public function likes(string $name)
    {
        $user = User::where('name', $name)->first();

        $articles = $user->likes->sortByDesc('created_at');

        return view('users.show', [
          'user' => $user,
          'articles' => $articles,
          'activeArticle' => false,
          'activeLike' => true,
        ]);
    }

    public function profileEdit(string $name)
    {
        $auth_user = User::where('name', $name)->first();

        return view('users.profileEdit', ['auth_user' => $auth_user]);
    }

    public function profileUpdate(Request $request, string $name)
    {
        dd($request->name);
    }

    public function accountEdit(string $name)
    {
        $auth_user = User::where('name', $name)->first();

        return view('users.accountEdit', ['auth_user' => $auth_user]);
    }

    public function emailUpdate(Request $request, string $name)
    {
        dd($request);
    }

    public function passwordUpdate(Request $request, string $name)
    {
        dd($request);
    }
}
