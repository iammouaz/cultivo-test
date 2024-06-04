<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\CheckoutMail;
use App\Models\Extension;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LoginControllerDrawer extends Controller//todo check the need of this class
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
    */

    const LOGIN_ACE_MEMBER = "ace_member";


    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $username;
    /**
     * @var string
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->copan_findUsername();
        $this->redirectTo = url()->previous();

    }

    public function showLoginForm($ace_member = null)
    {
        session(['perv_url' => url()->previous()]);

        if (!is_null($ace_member) && $ace_member == "ace_member") {
            $pageTitle = "Sign In Ace Memeber";
            return view(activeTemplate() . 'user.auth.acelogin', compact('pageTitle'));
        }
        $pageTitle = "Sign In";
        return view(activeTemplate() . 'user.auth.login', compact('pageTitle'));
    }

    public function login(Request $request)
    {

        // Mail::to($request->username)->send(new CheckoutMail());
        $this->validateLogin($request);


        if (isset($request->captcha)) {
            if (!captchaVerify($request->captcha, $request->captcha_secret)) {
                $notify[] = ['error', "Invalid captcha"];
                return back()->withNotify($notify)->withInput();
            }
        }


        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $user_temp = User::where('email', $request->input('email'))
                ->orWhere('username', $request->input('email'))->first();
            if(session()->has('cart')&&$user_temp){
                $user_temp->cart = json_encode(session()->get('cart'));
                $user_temp->save();
            }
            elseif($user_temp && $user_temp->cart) {
                session()->put('cart', json_decode($user_temp->cart));
            }
            return $this->sendLoginResponse($request);
        }


        //TODO: Check login from wordpess after check in local db. if the user found in
        // wp and not found in this system create user here
        // By Nizar

        //$login_type = $request->has('login_type') ? $request->input('login_type') : null;
        $login_type = LoginControllerDrawer::LOGIN_ACE_MEMBER;
        $user_temp = User::where('email', $request->input('email'))
            ->orWhere('username', $request->input('email'))->first();


        $is_email = filter_var($request->username, FILTER_VALIDATE_EMAIL);
        $data = WordPressSiteAuthHelper::check($request->input('email'), $request->input('password'), $is_email);

        if (!$user_temp && $data) {
            $user_temp = User::where('email', $data['email'])
                ->orWhere('username', $data['username'])->first();
        }

        if ($user_temp && $data) {

            $myRequest = new \Illuminate\Http\Request();
            $myRequest->setMethod('POST');
            if($is_email){
                $myRequest->request->add(['username' => $data['username']]);
                $this->username  = 'username';
            }
            else{
                $myRequest->request->add(['email' => $data['email']]);
                $this->username  = 'email';
            }


            $myRequest->request->add(['password' => $request->input('password')]);
            $myRequest->request->add(['remember' => $request->input('remember')]);

            if ($this->attemptLogin($myRequest)) {
                return $this->sendLoginResponse($request);
            }

            //change password if user found in wordpress
            $new_password = password_hash($request->input('password'), PASSWORD_DEFAULT);
            $user_temp->password = $new_password;
            $user_temp->save();

            if ($this->attemptLogin($myRequest)) {
                return $this->sendLoginResponse($request);
            }

        }



        if (!$user_temp && $login_type == LoginControllerDrawer::LOGIN_ACE_MEMBER) {

            if ($data) {
                $reg = new RegisterController();

                $ndata['firstname'] = $data['first_name'];
                $ndata['lastname'] = $data['last_name'];
                $ndata['company_name'] = isset($data['company_name']) ? $data['company_name'] : null;
                $ndata['company_website'] = isset($data['company_website']) ? $data['company_website'] : null;

                $ndata['email'] = $data['email'];

                $ndata['password'] = $request->input('password');
                $ndata['username'] = $data['username'];
                $ndata['country_code'] = null;
                $ndata['mobile_code'] = null;
                $ndata['mobile'] = null;
                $ndata['ace_member'] = true;
                $ndata['billing'] = $data['woocommerce_data']['billing'];
                $ndata['shipping'] = $data['woocommerce_data']['shipping'];

                $reg->create($ndata);

                if ($this->attemptLogin($request)) {
                    return $this->sendLoginResponse($request);
                }
            }
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }
        //from git
        // if (filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
        //     $user = User::where('email', $request->username)->first();
        // } else {
        //     $user = User::where('username', $request->username)->first();
        // }
        // if ($user) {
        //     if ($user->create_password == 0 && $user->ace_member == 1) {
        //         session()->put('user_id', $user->id);
        //         return redirect()->route('user.create.password');
        //     }
        // }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);


        return $this->sendFailedLoginResponse($request);
    }

    public function copan_login(Request $request)
    {

        // Mail::to($request->username)->send(new CheckoutMail());
        $this->copan_validateLogin($request);


        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $user_temp = User::where('email', $request->input('email'))->first();
            if(session()->has('cart')&&$user_temp){
                $user_temp->cart = json_encode(session()->get('cart'));
                $user_temp->save();
            }
            elseif($user_temp && $user_temp->cart) {
                session()->put('cart', json_decode($user_temp->cart));
            }
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);


        return $this->sendFailedLoginResponse($request);
    }

    public function copan_findUsername()
    {
        $login = request()->input('email')??request()->input('username');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        // request()->merge([$fieldType => $login]);
        return $fieldType;
    }
    public function findUsername()
    {
        $login = request()->input('username');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $login]);
        return $fieldType;
    }

    public function username()
    {
        return $this->username;
    }

    protected function validateLogin(Request $request)
    {
        $customRecaptcha = Extension::where('act', 'custom-captcha')->where('status', 1)->first();
        $validation_rule = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];

        if ($customRecaptcha) {
            $validation_rule['captcha'] = 'required';
        }

        $request->validate($validation_rule);

    }

    protected function copan_validateLogin(Request $request)
    {
        $validation_rule = [
            'email' => 'required|email',
            'password' => 'required|string',
        ];

        $request->validate($validation_rule);

    }

    public function logout()
    {
        $this->guard()->logout();

        request()->session()->invalidate();

        $notify[] = ['success', 'You have been logged out.'];
        return redirect()->route('user.login')->withNotify($notify);
    }


    public function authenticated(Request $request, $user)
    {
        if ($user->status == 0) {
            $this->guard()->logout();
            $notify[] = ['error', 'Your account has been deactivated.'];
            return redirect()->route('user.login')->withNotify($notify);
        }


        $user = auth()->user();
        $user->tv = $user->ts == 1 ? 0 : 1;
        $user->save();
        $ip = $_SERVER["REMOTE_ADDR"];
        $exist = UserLogin::where('user_ip', $ip)->first();
        $userLogin = new UserLogin();
        if ($exist) {
            $userLogin->longitude = $exist->longitude;
            $userLogin->latitude = $exist->latitude;
            $userLogin->city = $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country = $exist->country;
        } else {
            $info = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude = @implode(',', $info['long']);
            $userLogin->latitude = @implode(',', $info['lat']);
            $userLogin->city = @implode(',', $info['city']);
            $userLogin->country_code = @implode(',', $info['code']);
            $userLogin->country = @implode(',', $info['country']);
        }

        $userAgent = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip = $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os = @$userAgent['os_platform'];
        $userLogin->save();

        $perv_url = session('perv_url');
        if ($perv_url) {
            return redirect($perv_url);
        }
        return redirect()->route('home');
    }


}
