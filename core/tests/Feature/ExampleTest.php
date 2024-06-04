<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateEvent()
    {
        $response = $this->get('/product-details/435/ahmad-testproduct-1');

        $response->assertStatus(200);
    }
    // /admin/event/edit/111
    public function testEditEvent(){

        $response = $this->get('/admin/event/edit/111');

        $response->assertStatus(200);
    }
     public function testGetPolicyId()
    {
        dd(get_policy_id());
    }
    //route for create csv file and download it
    public function testConvertJsonToCsvAndDownLoad()
    {

        $user = Admin::query()->where('id', 1)->first();
      $response=  $this->actingAs($user)
            ->get('admin/download-lang-csv/en');
        $response->assertRedirect('/admin');

    }
    //route for upload csv file and convert to json
    public function testUploadCsvFileAndConvertToJson()
    {
        $user = Admin::query()->where('id', 1)->first();
        $this->assertNotNull($user);
        // creae csv file
        $csvFile = UploadedFile::fake()->createWithContent('new_ar.csv', 'Key,Value
             Account:,"new test:"
             "Account not found","Account not found"
             Browser:,Browser test:');

        $response = $this->actingAs($user)
            ->post(route('admin.upload.lang.csv'), [
                'csv_file' => $csvFile,
                'lang_name' => 'sp'
            ]);
//        assert redirect
        $response->assertRedirect('/admin');

            }

    public function testUpdateDisplayLanguage()
    {
        $user = User::query()->where('id', 1)->first();
        $this->assertNotNull($user);
        $response = $this->actingAs($user)
            ->post(route('update.display.language'), [
                'language_id' => 2,
            ]);
        $response->assertRedirect('/');
    }
    // get available languages
    public function testGetAvailableLanguages()
    {
       $languages= get_available_languages();
         $this->assertNotNull($languages);
    }
    public function testHelperFunctions()
    {
        $arr = [
            'getSiteTitle' => getSiteTitle(),
            'getSiteDescription' => getSiteDescription(),
            'getHomePageLogo' => getHomePageLogo(),
            'getHomePageUrl' => getHomePageUrl(),
            'getNavbarLinks' => getNavbarLinks(),
            'getFavicon' => getFavicon(),
            'getSocialImage' => getSocialImage(),
            'getLoginImage' => getLoginImage(),
            'getFacebookLink' => getFacebookLink(),
            'getInstagramLink' => getInstagramLink(),
            'getLinkedinLink' => getLinkedinLink(),
            'getYoutubeLink' => getYoutubeLink(),
            'getVimeoLink' => getVimeoLink(),
            'getEmail' => getEmail(),
            'getIsTemplateFooter' => getIsTemplateFooter(),
            'getFooterFacebookLink' => getFooterFacebookLink(),
            'getFooterInstagramLink' => getFooterInstagramLink(),
            'getFooterLinkedinLink' => getFooterLinkedinLink(),
            'getFooterYoutubeLink' => getFooterYoutubeLink(),
            'getFooterVimeoLink' => getFooterVimeoLink(),
            'getFooterEmail' => getFooterEmail(),
            'getFooterLogo' => getFooterLogo(),
            'getIsFooterImage' => getIsFooterImage(),
            'getFooterImage' => getFooterImage(),
            'getFooterImageLink' => getFooterImageLink(),
            'getNavbarBackground' => getNavbarBackground(),
            'getNavbarNavLinks' => getNavbarNavLinks(),
            'getNavbarNavIcons' => getNavbarNavIcons(),
            'getNavbarHover' => getNavbarHover(),
            'getFooterBackground' => getFooterBackground(),
            'getFooterLinks' => getFooterLinks(),
            'getFooterIcons' => getFooterIcons(),
            'getFooterHover' => getFooterHover(),
            'getSitewidePageBackground' => getSitewidePageBackground(),
            'getSitewideTextHeading1' => getSitewideTextHeading1(),
            'getSitewideTextHeading2' => getSitewideTextHeading2(),
            'getSitewideTextHeading3' => getSitewideTextHeading3(),
            'getSitewideTextHeading4' => getSitewideTextHeading4(),
            'getSitewideTextHeading5' => getSitewideTextHeading5(),
            'getSitewideTextSubtitle1' => getSitewideTextSubtitle1(),
            'getSitewideTextSubtitle2' => getSitewideTextSubtitle2(),
            'getSitewideTextBody1' => getSitewideTextBody1(),
            'getSitewideTextCaption' => getSitewideTextCaption(),
            'getSitewideTextOverline' => getSitewideTextOverline(),
            'getSitewideTextHighlight' => getSitewideTextHighlight(),
            'getSitewideLinksOnDarkBackground' => getSitewideLinksOnDarkBackground(),
            'getSitewideLinksTextLinks' => getSitewideLinksTextLinks(),
            'getSitewideContainedButtonsBackground' => getSitewideContainedButtonsBackground(),
            'getSitewideContainedButtonsText' => getSitewideContainedButtonsText(),
            'getSitewideContainedButtonsHover' => getSitewideContainedButtonsHover(),
            'getSitewideOutlinedButtonsBackground' => getSitewideOutlinedButtonsBackground(),
            'getSitewideOutlinedButtonsText' => getSitewideOutlinedButtonsText(),
            'getSitewideOutlinedButtonsHover' => getSitewideOutlinedButtonsHover(),
            'getSitewideTextButtonsColor' => getSitewideTextButtonsColor(),
            'getSitewideTextButtonsHover' => getSitewideTextButtonsHover(),
            'getSitewideIconsColor' => getSitewideIconsColor(),
            'getSitewideIconsHover' => getSitewideIconsHover(),
            'getSitewideIconsHoverBackground' => getSitewideIconsHoverBackground(),
            'getSitewideChipsTextAndIcon' => getSitewideChipsTextAndIcon(),
            'getSitewideChipsBackground' => getSitewideChipsBackground(),
            'getHeadingsFontUrl' => getHeadingsFontUrl(),
            'getHeadingsFontFamily' => getHeadingsFontFamily(),
            'getHeadingsFontStyle' => getHeadingsFontStyle(),
            'getHeadingsLetterSpacing' => getHeadingsLetterSpacing(),
            'getHeadingsTextTransform' => getHeadingsTextTransform(),
            'getParagraphsFontUrl' => getParagraphsFontUrl(),
            'getParagraphsFontFamily' => getParagraphsFontFamily(),
            'getParagraphsFontStyle' => getParagraphsFontStyle(),
            'getParagraphsLetterSpacing' => getParagraphsLetterSpacing(),
            'getParagraphsTextTransform' => getParagraphsTextTransform(),
            'getAuctionCardNumbersFontUrl' => getAuctionCardNumbersFontUrl(),
            'getAuctionCardNumbersFontFamily' => getAuctionCardNumbersFontFamily(),
            'getAuctionCardNumbersFontStyle' => getAuctionCardNumbersFontStyle(),
            'getAuctionCardNumbersLetterSpacing' => getAuctionCardNumbersLetterSpacing(),
            'getAuctionCardNumbersTextTransform' => getAuctionCardNumbersTextTransform(),
            'getOutlinedButtonFontUrl' => getOutlinedButtonFontUrl(),
            'getOutlinedButtonFontFamily' => getOutlinedButtonFontFamily(),
            'getOutlinedButtonFontStyle' => getOutlinedButtonFontStyle(),
            'getOutlinedButtonLetterSpacing' => getOutlinedButtonLetterSpacing(),
            'getOutlinedButtonTextTransform' => getOutlinedButtonTextTransform(),
            'getOutlinedButtonCornerRadius' => getOutlinedButtonCornerRadius(),
            'getContainedButtonFontUrl' => getContainedButtonFontUrl(),
            'getContainedButtonFontFamily' => getContainedButtonFontFamily(),
            'getContainedButtonFontStyle' => getContainedButtonFontStyle(),
            'getContainedButtonLetterSpacing' => getContainedButtonLetterSpacing(),
            'getContainedButtonTextTransform' => getContainedButtonTextTransform(),
            'getContainedButtonCornerRadius' => getContainedButtonCornerRadius(),
            'getTextButtonFontUrl' => getTextButtonFontUrl(),
            'getTextButtonFontFamily' => getTextButtonFontFamily(),
            'getTextButtonFontStyle' => getTextButtonFontStyle(),
            'getTextButtonLetterSpacing' => getTextButtonLetterSpacing(),
            'getTextButtonTextTransform' => getTextButtonTextTransform(),
            'getTextButtonCornerRadius' => getTextButtonCornerRadius(),
            'getCheckBoxAndRadioActiveColor' => getCheckBoxAndRadioActiveColor(),
            'getTabsHoverColor' => getTabsHoverColor(),
            'getTabsActiveColor' => getTabsActiveColor(),
            'getBudgetProgressBarColor' => getBudgetProgressBarColor(),
        ];
        dd($arr);
        $this->assertTrue(true);
    }
}
