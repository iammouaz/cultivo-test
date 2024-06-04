<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteThemeSetting;
use Illuminate\Http\Request;

class SiteThemeSettingController extends Controller
{
    public function index()
    {
        $pageTitle = 'Site Theme Setting';
        // get all site settings from database and key by name with just value
        $siteSettings = SiteThemeSetting::all()->keyBy('name')->map(function ($item) {
            return $item->value;
        });

        return view('admin.settings.theme_settings', compact('pageTitle', 'siteSettings'));
    }
    public function Update(Request $request)
    {

        $validationData = $request->validate(
            [
                'head_family' => 'nullable',
                'head_style' => 'string|nullable',
                'head_letter_spacing' => 'string|nullable',
                'head_text_transform' => 'string|nullable',

                'paragraph_family' => 'nullable',
                'paragraph_style' => 'string|nullable',
                'paragraph_letter_spacing' => 'string|nullable',
                'paragraph_text_transform' => 'string|nullable',

                'card_family' => 'nullable',
                'card_style' => 'string|nullable',
                'card_letter_spacing' => 'string|nullable',
                'card_text_transform' => 'string|nullable',

                'button_text_style' => 'string|nullable',
                'button_family' => 'nullable',
                'button_style' => 'string|nullable',
                'button_letter_spacing' => 'string|nullable',
                'button_text_transform' => 'string|nullable',
                'button_shape' => 'string|nullable',
                'is_custom_corners' => 'in:true,false|nullable',
                'custom_corners' => 'string|nullable',
                'outlined_button_family' => 'nullable',
                'outlined_button_style' => 'string|nullable',
                'outlined_button_letter_spacing' => 'string|nullable',
                'outlined_button_text_transform' => 'string|nullable',
                'outlined_button_shape' => 'string|nullable',
                'outlined_is_custom_corners' => 'in:true,false|nullable',
                'outlined_custom_corners' => 'string|nullable',
                'text_button_family' => 'nullable',
                'text_button_style' => 'string|nullable',
                'text_button_letter_spacing' => 'string|nullable',
                'text_button_text_transform' => 'string|nullable',
                'text_button_shape' => 'string|nullable',
                'text_is_custom_corners' => 'in:true,false|nullable',
                'text_custom_corners' => 'string|nullable',

            ]
        );
        $validationColorData = $request->validate(
            [
                // color style
                // navbar
                'nav_background_color' => 'nullable',
                'nav_links_color' => 'nullable',
                'nav_icons_color' => 'nullable',
                'nav_hover_color' => 'nullable',
                // footer
                'footer_background_color' => 'nullable',
                'footer_links_color' => 'nullable',
                'footer_icons_color' => 'nullable',
                'footer_hover_color' => 'nullable',

                // sitewide
                'page_background_color' => 'nullable',

                //text color
                'text_h1_color' => 'nullable',
                'text_h2_color' => 'nullable',
                'text_h3_color' => 'nullable',
                'text_h4_color' => 'nullable',
                'text_h5_color' => 'nullable',
                'text_subtitle_color' => 'nullable',
                'text_subtitle_1_color' => 'nullable',
                'text_subtitle_2_color' => 'nullable',
                'text_body_1_color' => 'nullable',
                'text_body_2_color' => 'nullable',
                'text_caption_color' => 'nullable',
                'text_overline_color' => 'nullable',
                'text_highlight_color' => 'nullable',

                // link color
                'links_color' => 'nullable',
                'links_on_dark_background_color' => 'nullable',

                // button color
                'button_background_color' => 'nullable',
                'button_text_color' => 'nullable',
                'button_hover_background_color' => 'nullable',

                // secondary button color
                'secondary_button_background_color' => 'nullable',
                'secondary_button_text_color' => 'nullable',
                'secondary_button_hover_background_color' => 'nullable',

                // secondary text color
                'secondary_text_color' => 'nullable',
                'secondary_hover_text_color' => 'nullable',

                // icon color
                'icon_color' => 'nullable',
                'icon_hover_active_color' => 'nullable',
                'icon_hover_background_color' => 'nullable',

                // chips color
                'chip_background_color' => 'nullable',
                'chip_text_and_icon_color' => 'nullable',

                // checkbox color
                'checkbox_and_radio_active_color' => 'nullable',

                'tabs_hover_color' => 'nullable',
                'tabs_active_color' => 'nullable',
                'budget_progress_bar_color' => 'nullable',
            ]
        );
        foreach ($validationData as $key => $value) {
            $siteThemeSetting = SiteThemeSetting::where('name', $key)->first();
            if ($siteThemeSetting) {
                if ($request->hasFile($key)) {
                    $file = $request->file($key);
                    $filename = $file->getClientOriginalName();
                    $filePath = $this->storeFontFile($file, $filename);
                    $value = $filePath;
                    $siteThemeSetting->update([
                        'value' => $value ?? ''
                    ]);
                } else {
                    $siteThemeSetting->update([
                        'value' => $value ?? ''
                    ]);
                }
            } else {
                if ($request->hasFile($key)) {
                    $file = $request->file($key);
                    $filename = $key . '.' . $file->getClientOriginalExtension();
                    $filePath = $this->storeFontFile($file, $filename);
                    $value = $filePath;
                    SiteThemeSetting::create([
                        'name' => $key,
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
        $notify[] = ['success', __('Site theme setting has been updated')];
        return back()->withNotify($notify);
    }

    /**
     * @param $file
     * @param string $filename
     * @return array|string|string[]
     */
    public function storeFontFile($file, string $filename)
    {
//        if(config('filesystems.default') == 's3'){
//            $filePath = $file->storeAs('assets/fonts', $filename, 's3');
//            $filePath = str_replace('assets/', '', $filePath);
//        }
//        else {
            $filePath = $file->storeAs('public/fonts', $filename);
            $filePath = str_replace('public/', '', $filePath);
//        }
        return $filePath;
    }
}
