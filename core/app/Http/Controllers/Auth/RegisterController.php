<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\GeneralSetting;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('regStatus')->except('registrationNotAllowed');

        $this->activeTemplate = activeTemplate();
    }

    public function showRegistrationForm()
    {
        $pageTitle = "Sign Up";
        $info = json_decode(json_encode(getIpInfo()), true);
        $mobile_code = @implode(',', $info['code']);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view($this->activeTemplate . 'user.auth.register', compact('pageTitle','mobile_code','countries'));
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $general = GeneralSetting::first();
        $password_validation = Password::min(6);
        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        }
        $agree = 'nullable';
        if ($general->agree) {
            $agree = 'required';
        }
        $countryData = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
//        $mobileCodes = implode(',',array_column($countryData, 'dial_code'));
        $countries = implode(',',array_column($countryData, 'country'));
        $validate = Validator::make($data, [
            'firstname' => 'sometimes|required|string|max:50',
            'lastname' => 'sometimes|required|string|max:50',
            'email' => 'required|string|email|max:90|unique:users',
            'company_name' => 'required',
            'company_website' => 'required',
//            'mobile' => 'required|string|max:50|unique:users',
            'password' => ['required','confirmed',$password_validation],
            'username' => 'required|alpha_num|unique:users|min:6|max:50',
            'captcha' => 'sometimes|required',
//            'mobile_code' => 'required|in:'.$mobileCodes,
            'country_code' => 'required|in:'.$countryCodes,
            'country' => 'required|in:'.$countries,
            'agree' => $agree,
            'billing_phone' => 'required|string|max:50',
            'i_contacted_upcoming_auctions' => 'nullable|boolean',
        ]);
        return $validate;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
//        $exist = User::where('mobile',$request->mobile_code.$request->mobile)->first();
//        if ($exist) {
//            $notify[] = ['error', 'The mobile number already exists'];
//            return back()->withNotify($notify)->withInput();
//        }

        if (isset($request->captcha)) {
            if (!captchaVerify($request->captcha, $request->captcha_secret)) {
                $notify[] = ['error', "Invalid captcha"];
                return back()->withNotify($notify)->withInput();
            }
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    //TODO: add function to creat account from wp



    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    public function create(array $data)
    {

        $general = GeneralSetting::first();

        //User Create
        $user = new User();
        $user->firstname = isset($data['firstname']) ? $data['firstname'] : null;
        $user->lastname = isset($data['lastname']) ? $data['lastname'] : null;
        $user->company_name = isset($data['company_name']) ? $data['company_name'] : null;
        $user->company_website = isset($data['company_website']) ? $data['company_website'] : null;
        $user->email = strtolower(trim($data['email']));
        $user->password = Hash::make($data['password']);
        $user->username = trim($data['username']);
        $user->country_code = $data['country_code'];
        $user->mobile = !(isset($data['billing_phone'])) ?  isset($data['billing']['billing_phone'])? $data['billing']['billing_phone'] : null: $data['billing_phone'];
        $user->address = [
            'address' => '',
            'state' => '',
            'zip' => '',
            'country' => isset($data['country']) ? $data['country'] : null,
            'city' => ''
        ];
        $user->opt_in=isset($data['opt_in']) ? $data['opt_in'] : null;
        $user->i_contacted_upcoming_auctions=isset($data['i_contacted_upcoming_auctions']) ? $data['i_contacted_upcoming_auctions'] : false;
        $user->opt_in_upcoming_events_date=isset($data['i_contacted_upcoming_auctions']) ? now() : null;
        $user->status = 1;
        $user->ev = $general->ev ? 0 : 1;
        $user->sv = $general->sv ? 0 : 1;
        $user->ts = 0;
        $user->tv = 1;
        $user->ace_member = ($data['ace_member']??false) || ($data['login_type']??"normal") == 'ace';//todo add more login type logic here
        $user->is_agree = $user->ace_member ? 0 : 1;
        $user->agree_date = $user->ace_member ? null : now() ;



        if(isset($data['billing'])){
            $user->billing_first_name = isset($data['billing']['billing_first_name'])? $data['billing']['billing_first_name'] : null;
            $user->billing_last_name = isset($data['billing']['billing_last_name'])? $data['billing']['billing_last_name'] : null;
            $user->billing_address_1 = isset($data['billing']['billing_address_1'])? $data['billing']['billing_address_1'] : null;
            $user->billing_address_2 = isset($data['billing']['billing_address_2'])? $data['billing']['billing_address_2'] : null;
            $user->billing_city = isset($data['billing']['billing_city'])? $data['billing']['billing_city'] : null;
            $user->billing_state = isset($data['billing']['billing_state'])? $data['billing']['billing_state'] : null;
            $user->billing_zip = isset($data['billing']['billing_zip'])? $data['billing']['billing_zip'] : null;
            $user->billing_country = isset($data['billing']['billing_country'])? $data['billing']['billing_country'] : null;
            $user->billing_phone = isset($data['billing']['billing_phone'])? $data['billing']['billing_phone'] : null;

        }

        if(isset($data['billing_phone'])){
            $user->billing_phone = $data['billing_phone'];
        }
        if(isset($data['shipping'])){
            $user->shipping_first_name = isset($data['shipping']['shipping_first_name'])? $data['shipping']['shipping_first_name'] : null;
            $user->shipping_last_name = isset($data['shipping']['shipping_last_name'])? $data['shipping']['shipping_last_name'] : null;
            $user->shipping_address_1 = isset($data['shipping']['shipping_address_1'])? $data['shipping']['shipping_address_1'] : null;
            $user->shipping_address_2 = isset($data['shipping']['shipping_address_2'])? $data['shipping']['shipping_address_2'] : null;
            $user->shipping_city = isset($data['shipping']['shipping_city'])? $data['shipping']['shipping_city'] : null;
            $user->shipping_state = isset($data['shipping']['shipping_state'])? $data['shipping']['shipping_state'] : null;
            $user->shipping_zip = isset($data['shipping']['shipping_zip'])? $data['shipping']['shipping_zip'] : null;
            $user->shipping_country = isset($data['shipping']['shipping_country'])? $data['shipping']['shipping_country'] : null;
            $user->shipping_phone = isset($data['shipping']['shipping_phone'])? $data['shipping']['shipping_phone'] : null;
        }
        if(config('app.COMMERCE_MODE')==1){
            if (session()->has('cart')) {
                $user->cart = json_encode(session()->get('cart'));
            }
        }
        $user->save();


        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'New member registered';
        $adminNotification->click_url = urlPath('admin.users.detail',$user->id);
        $adminNotification->save();


        //Login Log Create
        $ip = $_SERVER["REMOTE_ADDR"];
        $exist = UserLogin::where('user_ip',$ip)->first();
        $userLogin = new UserLogin();

        //Check exist or not
        if ($exist) {
            $userLogin->longitude =  $exist->longitude;
            $userLogin->latitude =  $exist->latitude;
            $userLogin->city =  $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country =  $exist->country;
        }else{
            $info = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude =  @implode(',',$info['long']);
            $userLogin->latitude =  @implode(',',$info['lat']);
            $userLogin->city =  @implode(',',$info['city']);
            $userLogin->country_code = @implode(',',$info['code']);
            $userLogin->country =  @implode(',', $info['country']);
        }

        $userAgent = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip =  $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os = @$userAgent['os_platform'];
        $userLogin->save();


        return $user;
    }

    public function checkUser(Request $request){
        $exist['data'] = null;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data'] = User::where('email',$request->email)->first();
            $exist['type'] = 'email';
        }
        if ($request->mobile) {
            $exist['data'] = User::where('mobile',$request->mobile)->first();
            $exist['type'] = 'mobile';
        }
        if ($request->username) {
            $exist['data'] = User::where('username',$request->username)->first();
            $exist['type'] = 'username';
        }
        return response($exist);
    }

    public function registered()
    {
        return redirect()->route('user.home');
    }

    protected function copan_validator(array $data)
    {
        $general = GeneralSetting::first();
        $password_validation = Password::min(6);
        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        }
        $agree = 'nullable';
        if ($general->agree) {
            $agree = 'required';
        }

        $validate = Validator::make($data, [
            'firstname' => 'sometimes|required|string|max:50',
            'lastname' => 'sometimes|required|string|max:50',
            'email' => 'required|string|email|max:90|unique:users',
            'company_name' => 'required',
            'company_website' => 'required',
            'mobile' => 'required|string|max:50|unique:users',
            'password' => ['required','confirmed'],
            'agree' => $agree,
        ]);
        return $validate;
    }

    public function copan_register(Request $request)
    {
        $this->copan_validator($request->all())->validate();

        $user=$this->copan_create($request->all());

        $this->guard()->login($user);

        return redirect()->back()->with('success', 'Registration Successful');
    }

    public function copan_create(array $data)
    {

        // $general = GeneralSetting::first();

        //User Create
        $user = new User();
        $user->firstname = isset($data['firstname']) ? $data['firstname'] : null;
        $user->lastname = isset($data['lastname']) ? $data['lastname'] : null;
        $user->company_name = isset($data['company_name']) ? $data['company_name'] : null;
        $user->company_website = isset($data['company_website']) ? $data['company_website'] : null;
        $user->email = strtolower(trim($data['email']));
        $user->password = Hash::make($data['password']);
        $user->username = trim($data['email']);
        $user->mobile = trim($data['mobile']);

        $user->i_contacted_upcoming_auctions=false;

        $user->status = 1;
        $user->ev = 1;
        $user->sv = 1;
        $user->ts = 0;
        $user->tv = 1;
        $user->is_agree = 1;

        if(config('app.COMMERCE_MODE')==1) {
            if (session()->has('cart')) {
                $user->cart = json_encode(session()->get('cart'));
            }
        }

        $user->save();


        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'New member registered';
        $adminNotification->click_url = urlPath('admin.users.detail',$user->id);
        $adminNotification->save();


        //Login Log Create
        $ip = $_SERVER["REMOTE_ADDR"];
        $exist = UserLogin::where('user_ip',$ip)->first();
        $userLogin = new UserLogin();

        //Check exist or not
        if ($exist) {
            $userLogin->longitude =  $exist->longitude;
            $userLogin->latitude =  $exist->latitude;
            $userLogin->city =  $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country =  $exist->country;
        }else{
            $info = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude =  @implode(',',$info['long']);
            $userLogin->latitude =  @implode(',',$info['lat']);
            $userLogin->city =  @implode(',',$info['city']);
            $userLogin->country_code = @implode(',',$info['code']);
            $userLogin->country =  @implode(',', $info['country']);
        }

        $userAgent = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip =  $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os = @$userAgent['os_platform'];
        $userLogin->save();


        return $user;
    }

}
