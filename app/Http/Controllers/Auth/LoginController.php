<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use App\Http\Requests\LoginRequest;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     https://codeanddeploy.com/blog/laravel/laravel-8-authentication-login-and-registration-with-username-or-email#2oRdAiokNGwApUyVlE6pj2HA6
    https://www.itsolutionstuff.com/post/laravel-6-auth-login-with-username-or-email-tutorialexample.html
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    /* versio aleix */
    
    public function username()
    {
    return 'username';
    }

    public function authenticate(Request $request){
        // Retrive Input
        $credentials = $request->only('username');

        if (Auth::attempt($credentials)) {
            // if success login

            return redirect()->route('home');

            //return redirect()->intended('/details');
        } else {
        // if failed login
        return redirect()->route('login')->with('error','Email-Address And Password Are Wrong.');
        }
    }

    /* no funking */
    /* public function login(LoginRequest $request)
    {   
        $input = $request->all();
  
        $this->validate($request, [
            'username' => 'required',
        ]);
  
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if(auth()->attempt(array($fieldType => $input['username'], 'password' => $input['password'])))
        {
            return redirect()->route('home');
        }else{
            return redirect()->route('login')
                ->with('error','Email-Address And Password Are Wrong.');
        }
          
    } */


}
