<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SiteThemeSetting;

class SiteSettingController extends Controller
{
    public function index()
    {
        $pageTitle = 'Site Setting';
        // get all site settings from database and key by name with just value
        $siteSettings = SiteThemeSetting::all()->keyBy('name')->map(function ($item) {
            return $item->value;
        });

        return view('admin.settings.site_settings', compact('pageTitle', 'siteSettings'));
    }
    public function Update(Request $request)
    {

        $validationData = $request->validate(
            [
                'favicon' => 'nullable|image|mimes:png,jpg,jpeg,gif,svg|max:2048',
                'site_title' => 'required',
                'site_description' => 'string|nullable',
                'home_page_logo' => 'image|mimes:png,jpg,jpeg,gif,svg|max:2048',
                'home_page_url' => 'string|nullable',
                'navbar_links' => 'nullable',
                'social_image' => 'image|mimes:png,jpg,jpeg,gif,svg|max:2048',
                'login_image' => 'image|mimes:png,jpg,jpeg,gif,svg|max:2048',
                'facebook_url' => 'string|nullable',
                'instagram_url' => 'string|nullable',
                'twitter_url' => 'string|nullable',
                'linkedin_url' => 'string|nullable',
                'youtube_url' => 'string|nullable',
                'vimeo_url' => 'string|nullable',
                'email' => 'string|nullable',
                'is_template_footer' => 'boolean',
                'footer_facebook_link' => 'string|nullable',
                'footer_instagram_link' => 'string|nullable',
                'footer_twitter_link' => 'string|nullable',
                'footer_linkedin_link' => 'string|nullable',
                'footer_youtube_link' => 'string|nullable',
                'footer_vimeo_link' => 'string|nullable',
                'footer_email' => 'string|nullable',
                'footer_logo' => 'image|mimes:png,jpg,jpeg,gif,svg|max:2048',
                'is_footer_image' => 'boolean',
                'footer_image' => 'image|mimes:png,jpg,jpeg,gif,svg|max:2048',
                'footer_image_link' => 'string|nullable',
                'past_auction_label' => 'string|nullable',
                'new_auction_label' => 'string|nullable',
                'new_auction_id' => 'string|nullable',
                'past_auction_ids' => 'string|nullable',


            ]
        );

        $validationColorData = $request->validate(
            [
                // color style
                'login_form_background_color' => 'nullable',

            ]
        );

        foreach ($validationData as $key => $value) {

            if ($key == 'favicon' || $key == 'home_page_logo' || $key == 'social_image' || $key == 'login_image' || $key == 'footer_logo' || $key == 'footer_image') {
                if ($key == 'favicon') {
                    $size = "32x32";
                    $this->saveImage($key, $request, $size);
                } elseif ($key == 'home_page_logo') {
                    $size = '300x300';
                    $sm_size = '150x150';
                    $md_size = '180x180';
                    $this->saveImage($key, $request, $size, true,false,true, $sm_size, $md_size);
                } elseif ($key == 'social_image') {
                    $size = "1200x630";
                    $this->saveImage($key, $request, $size);
                } elseif ($key == 'login_image') {
                    $size = '3840x3840';
                    $sm_size = '720x510';
                    $md_size = '1536x1090';
                    $this->saveImage($key, $request, $size, true,true,true, $sm_size, $md_size);
                } elseif ($key == 'footer_logo') {
                    $size = '300x300';
                    $sm_size = '150x150';
                    $md_size = '180x180';
                    $this->saveImage($key, $request, $size, true,false,true, $sm_size, $md_size);
                } elseif ($key == 'footer_image') {
                    $size = '3840x766';
                    $sm_size = '720x144';
                    $md_size = '1536x306';
                    $this->saveImage($key, $request, $size,true,true,true, $sm_size, $md_size);
                }
            }
            // Nabar links
            elseif ($key == 'navbar_links') {
                $siteThemeSetting = SiteThemeSetting::where('name', $key)->first();
                if ($siteThemeSetting) {
                    $siteThemeSetting->update([
                        'value' => json_encode($request->navbar_links)
                    ]);
                } else {
                    SiteThemeSetting::create([
                        'name' => $key,
                        'value' => json_encode($request->navbar_links)
                    ]);
                }
            } else {
                $siteThemeSetting = SiteThemeSetting::where('name', $key)->first();
                if ($siteThemeSetting) {
                    $siteThemeSetting->update([
                        'value' => $value ?? ''
                    ]);
                } else {
                    SiteThemeSetting::create([
                        'name' => $key,
                        'value' => $value ?? ''
                    ]);
                }
            }
        }

        foreach ($validationColorData as $key => $value) {
            $siteThemeSetting = SiteThemeSetting::where('name', $key)->first();
            // cast to boolean
            $value['is_with_glass_effect'] = isset($value['is_with_glass_effect']) && $value['is_with_glass_effect'] == 'true' ? true : false;
            $value['is_no_color'] = $value['is_no_color'] == 'true' ? true : false;

            if ($siteThemeSetting) {

                $siteThemeSetting->update([
                    'value' => json_encode($value ?? [])
                ]);
            } else {
                SiteThemeSetting::create([
                    'name' => $key,
                    'value' => json_encode($value ?? [])
                ]);
            }
        }
        clear_all_cache();
        $notify[] = ['success', __('Site setting has been updated')];
        return back()->withNotify($notify);
    }

    /**
     * @param $key
     * @param Request $request
     * @param string $size
     * @param string $sm_size
     * @param string $md_size
     * @return mixed
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function saveImage($key, Request $request,  $size,$preserveWidth=true,$preserveHeight=true,$isResponsive=false,  $sm_size=null,  $md_size=null)
    {
        $siteThemeSetting = SiteThemeSetting::where('name', $key)->first();
        if ($request->hasFile($key)) {
            $image = uploadImageToS3($request->$key, imagePath()['settings']['path'], $size, $siteThemeSetting->value ?? null, null, $isResponsive, $sm_size, $md_size,$preserveHeight,$preserveWidth);
        } else {
            $image = '';
        }
        if ($siteThemeSetting) {
            $siteThemeSetting->update([
                'value' => $image
            ]);
        } else {
            SiteThemeSetting::create([
                'name' => $key,
                'value' => $image
            ]);
        }
        return $siteThemeSetting;
    }


}
