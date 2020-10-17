<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['destroy']);
        $this->middleware('verified')->except(['index', 'show']);
    }

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
        $request->validate([
          'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        $user = User::where('name', $name)->first();

        $user->email = $request->email;
        $user->email_verified_at = null;
        $user->save();
        $user->sendEmailVerificationNotification();

        return redirect()->to('/verifyEmail');
    }

    public function passwordUpdate(Request $request, string $name)
    {
        $request->validate([
          'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::where('name', $name)->first();

        $user->password = Hash::make($request->password);
        $user->save();

        $user->sendChangePasswordNotification($user->name);

        return redirect()->route('users.show', ['name' => $user->name]);
    }
}
