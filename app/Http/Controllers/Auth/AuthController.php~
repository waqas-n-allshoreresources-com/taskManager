<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use Laracurl;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    \View::share('loggedin_user', $this->getLoggedInUser());
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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {

    }

    public function postLogin(Request $request)
    {

        $this->validate($request, [
                'email' => 'required|email|max:255',
                'password' => 'required|min:6',
        ]);

        $credentials = [
            'email'  => \Request::input('email'),
            'password'   => \Request::input('password'),
        '_token' => \Request::input('_token')
        ];

        $url = Laracurl::buildUrl('http://users.local/user/login', $credentials);
        $response = Laracurl::post($url);
        $response = json_decode($response);
        if($response->success){
            if(!$this->isAllowed($response->response->permissions)){
                $messageBag = new \Illuminate\Support\MessageBag;
                $messageBag->add('auth-validation', "Sorry! you are not authorized to access this app.");
                return redirect('/auth/login')->withErrors($messageBag->all());
            }
            else
            {
        $user = $response->response;
                $this->login($user);
                return redirect('/tasks');
            }
        } else {
            $messageBag = new \Illuminate\Support\MessageBag;
            $messageBag->add('auth-validation', $response->response->error);

            return \Redirect::back()->withInput()->withErrors($messageBag->all());
        }
    }

    public function postRegister(Request $request)
    {
        $this->validate($request, [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $credentials = [
            'name'  => \Request::input('name'),
            'email'  => \Request::input('email'),
            'password' => \Request::input('password'),
            'app_key' => 'tasks.app',
            '_token' => \Request::input('_token')
        ];

        $url = Laracurl::buildUrl('http://users.local/user/create', $credentials);
        $response = Laracurl::post($url);
        $response = json_decode($response);

        if($response->success) {
            return redirect('/auth/login');
        } else {
        if(isset($response->response->error))
            $error = $response->response->error;
        else
            $error = $response->response;

        $messageBag = new \Illuminate\Support\MessageBag;
        $messageBag->add('auth-validation', $error);

        return \Redirect::back()->withInput()->withErrors($messageBag->all());
        }
    }

    public function getLogout(Request $request)
    {
        $request->session()->forget('user');
        return redirect('/');
    }

    public function login($user)
    {
        \Request::session()->put('user', $user);    
    }

    public function getLoggedInUser()
    {
        $user = \Request::session()->get('user');
        return $user;
    }

    public function isAllowed($responses){
    $flag = false;
        $desired = "manage.tasks";
        foreach($responses as $response){
        if($desired == $response->slug) {
            $flag = true;
            break;
        }
        }
        return $flag;
    }


}
