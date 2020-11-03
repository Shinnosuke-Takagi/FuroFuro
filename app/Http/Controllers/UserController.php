<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use InterventionImage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['destroy']);
        $this->middleware('verified')->except(['show', 'likes']);
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
        $user = User::where('name', $name)->first();

        $request->validate([
          'name' => ['required', 'string', 'max:255', 'unique:users'],
          'avatar' => ['file', 'mimes:jpg,jpeg,png,gif'],
        ]);

        if(! empty($request->file('avatar'))) {
            Storage::cloud()->delete($user->avatar);

            $avatar_file = $request->file('avatar');

            $user->name = $request->name;
            $user->avatar = $request->file('avatar')->getClientOriginalName();

            InterventionImage::make($avatar_file)->fit(300, 300, function($constraint) {
              $constraint->upsize();
            })->save();

            Storage::cloud()->putFileAs('', $avatar_file, $user->avatar, 'public');

            DB::beginTransaction();

            try {
              $user->save();
              DB::commit();
            } catch(\Exception $exception) {
              DB::rollback();

              Storage::cloud()->delete($user->avatar);
              throw $exception;
            }
        } else {
          $user->name = $request->name;
          $user->save();
        }

        return redirect()->route('users.show', ['name' => $user->name]);
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
