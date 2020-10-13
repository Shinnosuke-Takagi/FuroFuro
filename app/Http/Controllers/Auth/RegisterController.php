<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use InterventionImage;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'avatar' => ['file', 'mimes:jpg,jpeg,png,gif'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        if(! empty($data['avatar'])) {
          $avatar_file = $data['avatar'];
          $avatar_name = $data['avatar']->getClientOriginalName();
        } else {
          $avatar_name = null;
        }

        $user = new User();

        $user->fill([
          'name' => $data['name'],
          'email' => $data['email'],
          'password' => Hash::make($data['password']),
          'avatar' => $avatar_name,
        ]);

        if(! empty($data['avatar'])) {
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
          $user->save();
        }

        return $user;
    }
}
