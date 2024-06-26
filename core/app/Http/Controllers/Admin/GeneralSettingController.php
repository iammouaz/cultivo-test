<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Image;

class GeneralSettingController extends Controller
{
    public function index()
    {
        $general = GeneralSetting::first();
        $pageTitle = __('General Setting');
        $timezones = json_decode(file_get_contents(resource_path('views/admin/partials/timezone.json')));
        return view('admin.setting.general_setting', compact('pageTitle', 'general','timezones'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'base_color' => 'nullable', 'regex:/^[a-f0-9]{6}$/i',
            'timezone' => 'required'
        ]);


        $general = GeneralSetting::first();
        $general->ev = $request->ev ? 1 : 0;
        $general->en = $request->en ? 1 : 0;
        $general->sv = $request->sv ? 1 : 0;
        $general->sn = $request->sn ? 1 : 0;
        $general->force_ssl = $request->force_ssl ? 1 : 0;
        $general->secure_password = $request->secure_password ? 1 : 0;
        $general->registration = $request->registration ? 1 : 0;
        $general->agree = $request->agree ? 1 : 0;
        $general->sitename = $request->sitename;
        $general->cur_text = $request->cur_text;
        $general->cur_sym = $request->cur_sym;
        $general->base_color = $request->base_color;
        $general->stop_live_in_not_auth_users = $request->stop_live_in_not_auth_users ? 1 : 0;
        $general->stop_cart = $request->stop_cart ? 1 : 0;

        $general->save();

//        $timezoneFile = config_path('timezone.php');
/*        $content = '<?php $timezone = '.$request->timezone.' ?>';*/
//        file_put_contents($timezoneFile, $content);
        clear_general_settings_cache();
        $notify[] = ['success', __('General setting has been updated.')];
        return back()->withNotify($notify);
    }


    public function logoIcon()
    {
        $pageTitle = __('Logo & Favicon');
        return view('admin.setting.logo_icon', compact('pageTitle'));
    }

    public function logoIconUpdate(Request $request)
    {
        $request->validate([
            'logo' => ['image',new FileTypeValidate(['jpg','jpeg','png'])],
            'favicon' => ['image',new FileTypeValidate(['png'])],
        ]);
        if ($request->hasFile('logo')) {
            try {
                $path = imagePath()['logoIcon']['path'];
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                Image::make($request->logo)->save($path . '/logo.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', __('Logo could not be uploaded.')];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('favicon')) {
            try {
                $path = imagePath()['logoIcon']['path'];
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                $size = explode('x', imagePath()['favicon']['size']);
                Image::make($request->favicon)->resize($size[0], $size[1])->save($path . '/favicon.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', __('Favicon could not be uploaded.')];
                return back()->withNotify($notify);
            }
        }
        $notify[] = ['success', __('Logo & favicon has been updated.')];
        return back()->withNotify($notify);
    }

    public function customCss(){
        $pageTitle = 'Custom CSS';
        $file = activeTemplate(true).'css/custom.css';
        $file_content = @file_get_contents($file);
        return view('admin.setting.custom_css',compact('pageTitle','file_content'));
    }


    public function customCssSubmit(Request $request){
        $file = activeTemplate(true).'css/custom.css';
        if (!file_exists($file)) {
            fopen($file, "w");
        }
        file_put_contents($file,$request->css);
        $notify[] = ['success',__('CSS updated successfully')];
        return back()->withNotify($notify);
    }

    public function optimize(){
        Artisan::call('route:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        //Artisan::call('config:cache');
        Artisan::call('optimize:clear');

        $notify[] = ['success',__('Cache cleared successfully')];
        return back()->withNotify($notify);

    }


    public function cookie(){
        $pageTitle = 'GDPR Cookie';
        $cookie = Frontend::where('data_keys','cookie.data')->firstOrFail();
        return view('admin.setting.cookie',compact('pageTitle','cookie'));
    }

    public function cookieSubmit(Request $request){
        $request->validate([
            'link'=>'required',
            'description'=>'required',
        ]);
        $cookie = Frontend::where('data_keys','cookie.data')->firstOrFail();
        $cookie->data_values = [
            'link' => $request->link,
            'description' => $request->description,
            'status' => $request->status ? 1 : 0,
        ];
        $cookie->save();
        $notify[] = ['success',__('Cookie policy updated successfully')];
        return back()->withNotify($notify);
    }

    public function merchantProfile(){
        $pageTitle = __('Merchant Profile for Admin');

        return view('admin.setting.merchant_profile',compact('pageTitle'));
    }

    public function merchantProfileSubmit(Request $request){
        $request->validate([
            'image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png'])],
            'cover_image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png'])]
        ]);

        $general = GeneralSetting::first();

        $merchantProfile = [];

        $merchantProfile['name'] = $request->merchant_name;
        $merchantProfile['mobile'] = $request->merchant_mobile;
        $merchantProfile['address'] = $request->merchant_address;

        if ($request->hasFile('image')) {
            try {
                $old = @$general->merchant_profile->image;
                $merchantProfile['image'] = uploadImage($request->image, imagePath()['profile']['admin']['path'], imagePath()['profile']['admin']['size'], $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', __('Image could not be uploaded.')];
                return back()->withNotify($notify);
            }
        }else{
            $merchantProfile['image'] = @$general->merchant_profile->image;
        }

        if ($request->hasFile('cover_image')) {
            try {
                $old = @$general->merchant_profile->cover_image;
                $merchantProfile['cover_image'] = uploadImage($request->cover_image, imagePath()['profile']['admin_cover']['path'], imagePath()['profile']['admin_cover']['size'], $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', __('Image could not be uploaded.')];
                return back()->withNotify($notify);
            }
        }else{
            $merchantProfile['cover_image'] = @$general->merchant_profile->cover_image;
        }

        $general->merchant_profile = $merchantProfile;
        $general->save();


        $notify[] = ['success', __('Merchant profile has been updated.')];
        return back()->withNotify($notify);
    }
}
