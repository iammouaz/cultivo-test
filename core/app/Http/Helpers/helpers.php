<?php

use App\Lib\GoogleAuthenticator;
use App\Lib\SendSms;
use App\Models\Advertisement;
use App\Models\Bid;
use App\Models\EmailTemplate;
use App\Models\Extension;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use App\Models\Language;
use App\Models\Page;
use App\Models\SmsTemplate;
use App\Models\EmailLog;
use App\Models\Event;
use App\Models\Group;
use App\Models\Invitation;
use App\Models\OfferSpecification;
use App\Models\Product;
use App\Models\ProductSpecification;
use App\Models\UserPermission;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\DB;
use App\Models\UserRequest;
use Stripe\Stripe;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use Mockery\Undefined;

function highestbidder($product_id)
{

    $user = auth()->user();

    $product = Product::find($product_id);
    $event = Event::find($product->event_id);
    $result = __("Anonymous");
    if ($user) {
        $group_id = user_in_group($event, $user->id);
        $last_bid = Bid::where('product_id', $product_id)->orderby('amount', 'desc')->first();
        if ($last_bid) {
            if ($last_bid->user_id == auth()->user()->id) {
                $result = __("You");
            } elseif ($group_id > 0) {
                $group = Group::find($group_id);
                if ($last_bid->user_id == $group->leader->id) {
                    $result = __("Your group");
                }
            }
        }
    }
    return $result;
}

function getEventName($id)
{
    return Cache::remember('event_name_' . $id, 30, function () use ($id) {
        $event = Event::find($id);
        return $event->name;
    });
}

function getEventAgreement($id)
{
    return Cache::remember('event_agreement_' . $id, 30, function () use ($id) {
        $event = Event::find($id);
        return $event->agreement;
    });
}

function user_in_group($event, $user_id)
{

    $group_id = 0;
    foreach ($event->groups as $group) {
        $invitation = Invitation::where('user_id', $user_id)->where('group_id', $group->id)->where('status', 1)->first();
        if ($invitation) {
            $group_id = $group->id;
            break;
        }
    }

    return $group_id;
}

function find_group_leader($id)
{
    return Cache::remember('find_group_leader_' . $id, 30, function () use ($id) {
        $group = Group::find($id);
        if ($group->leader_id) {
            return $group->leader_id;
        }
        return 0;
    });
}

function sidebarVariation()
{

    $variation['sidebar'] = 'bg--dark';
    $variation['selector'] = 'capsule--rounded';
    $variation['overlay'] = 'none';
    $variation['opacity'] = 'overlay--opacity-8';
    return $variation;
}

function systemDetails()
{
    $system['name'] = 'viserbid';
    $system['version'] = '1.0'; //3.1.51
    return $system;
}

function getLatestVersion()
{
    $param['purchasecode'] = config("app.PURCHASE_CODE");
    $param['website'] = @$_SERVER['HTTP_HOST'] . @$_SERVER['REQUEST_URI'] . ' - ' . config("app.url");
    $url = 'https://license.viserlab.com/updates/version/' . systemDetails()['name'];
    $result = curlPostContent($url, $param);
    if ($result) {
        return $result;
    } else {
        return null;
    }
}


function slug($string)
{
    return Illuminate\Support\Str::slug($string);
}

function shortDescription($string, $length = 120)
{
    return Illuminate\Support\Str::limit($string, $length);
}

function shortCodeReplacer($shortCode, $replace_with, $template_string)
{
    return str_replace($shortCode, $replace_with, $template_string);
}

function verificationCode($length)
{
    if ($length == 0) return 0;
    $min = pow(10, $length - 1);
    $max = 0;
    while ($length > 0 && $length--) {
        $max = ($max * 10) + 9;
    }
    return random_int($min, $max);
}

function getNumber($length = 8)
{
    $characters = '1234567890';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

//moveable
function uploadImage($file, $location, $size = null, $old = null, $thumb = null)
{
    $path = makeDirectory($location);
    if (!$path) throw new Exception('File could not been created.');

    if ($old) {
        removeFile($location . '/' . $old);
        removeFile($location . '/thumb_' . $old);
    }
    $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
    $image = Image::make($file);
    if ($size) {
        $size = explode('x', strtolower($size));
        $image->resize($size[0], $size[1]);
    }
    $image->save($location . '/' . $filename);

    if ($thumb) {
        $thumb = explode('x', $thumb);
        Image::make($file)->resize($thumb[0], $thumb[1])->save($location . '/thumb_' . $filename);
    }

    return $filename;
}
function resizeImageKeepingAspectRatio ($image, $width, $height,$preserveWidth=true,$preserveHeight=true) {
    $newWidth = $width;
    $newHeight = $height;
    if ($preserveWidth && $preserveHeight) {
        $image->resize($newWidth, $newHeight);
        return $image;
    }
    if ($preserveWidth) {
        $newHeight = $width / $image->width() * $image->height();
    }
    if ($preserveHeight) {
        $newWidth = $height / $image->height() * $image->width();
    }
    $image->resize($newWidth, $newHeight);
    return $image;
}

function uploadImageToS3($file, $location, $size = null, $old = null, $thumb = null, $is_responsive = false, $sm_size = null, $md_size = null,$preserveHeight=true,$preserveWidth=true)
{
    $path = makeDirectory($location);
    if (!$path) throw new Exception('File could not been created.');

    $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
    $image = Image::make($file);
    if ($size) {
        $size = explode('x', strtolower($size));
        //check if image size is smaller than size
        $img = Image::make($file);
        if ($is_responsive && ($img->width() < $size[0] && $img->height() < $size[1])) {
            $ratio = $img->width() / $img->height();
            $size[0] = $img->width();
            $size[1] = $img->width() / $ratio;
        }
        resizeImageKeepingAspectRatio($image,$size[0], $size[1],$preserveWidth,$preserveHeight);
//        $image->resize($preserveWidth?$size[0]:null, $preserveHeight?$size[1]:null);
    }
    $image->save($location . '/' . $filename);
    Storage::disk('s3')->put($location . '/' . $filename, file_get_contents($location . '/' . $filename));

    if ($thumb) {
        $thumb = explode('x', $thumb);
        resizeImageKeepingAspectRatio(Image::make($file),$thumb[0],  $thumb[1],$preserveWidth,$preserveHeight)->save($location . '/thumb_' . $filename);
//        Image::make($file)->resize($preserveWidth?$thumb[0]:null,  $preserveHeight?$thumb[1]:null)->save($location . '/thumb_' . $filename);
        Storage::disk('s3')->put($location . '/thumb_' . $filename, file_get_contents($location . '/thumb_' . $filename));
    }
    if ($is_responsive) {
        if ($sm_size) {
            $sm_size = explode('x', strtolower($sm_size));
            //check if image size is smaller than sm size
            $img = Image::make($file);
            if ($img->width() < $sm_size[0] && $img->height() < $sm_size[1]) {
                $ratio = $img->width() / $img->height();
                $sm_size[0] = $img->width();
                $sm_size[1] = $img->width() / $ratio;
            }
            resizeImageKeepingAspectRatio(Image::make($file),$sm_size[0],  $sm_size[1],$preserveWidth,$preserveHeight)->save($location . '/sm_' . $filename);
//            Image::make($file)->resize($preserveWidth?$sm_size[0]:null,  $preserveHeight?$sm_size[1]:null)->save($location . '/sm_' . $filename);
            Storage::disk('s3')->put($location . '/sm_' . $filename, file_get_contents($location . '/sm_' . $filename));
        }
        if ($md_size) {
            $md_size = explode('x', strtolower($md_size));
            //check if image size is smaller than md size
            $img = Image::make($file);
            if ($img->width() < $md_size[0] && $img->height() < $md_size[1]) {
                $ratio = $img->width() / $img->height();
                $md_size[0] = $img->width();
                $md_size[1] = $img->width() / $ratio;
            }
            resizeImageKeepingAspectRatio(Image::make($file),$md_size[0],  $md_size[1],$preserveWidth,$preserveHeight)->save($location . '/md_' . $filename);
//            Image::make($file)->resize($preserveWidth?$md_size[0]:null,  $preserveHeight?$md_size[1]:null)->save($location . '/md_' . $filename);
            Storage::disk('s3')->put($location . '/md_' . $filename, file_get_contents($location . '/md_' . $filename));
        }
    }

    removeFile($location . '/' . $filename);
    if ($thumb) {
        removeFile($location . '/thumb_' . $filename);
    }

//    Storage::disk('s3')->delete([todo enable if deletion is approved
//        $location . '/' . $old,
//        $location . '/thumb_' . $old,
//        $location . '/sm_' . $old,
//        $location . '/md_' . $old]);
    return $filename;
}

function uploadFile($file, $location, $size = null, $old = null)
{
    $path = makeDirectory($location);
    if (!$path) throw new Exception('File could not been created.');

    if ($old) {
        removeFile($location . '/' . $old);
    }

    $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
    $file->move($location, $filename);
    return $filename;
}

function makeDirectory($path)
{
    if (file_exists($path)) return true;
    return mkdir($path, 0755, true);
}


function removeFile($path)
{
    return file_exists($path) && is_file($path) ? @unlink($path) : false;
}


function activeTemplate($asset = false)
{
    $general = getGeneralSettings();

    $template = $general->active_template;
    $sess = session()->get('template');
    if (trim($sess)) {
        $template = $sess;
    }
    if ($asset) return 'assets/templates/' . $template . '/';
    return 'templates.' . $template . '.';
}

/**
 * @return mixed
 */
function getGeneralSettings()
{
    $general = Cache::remember('general_settings', 30, function () {
        return GeneralSetting::first();
    });
    return $general;
}

function activeTemplateName()
{
    $general = getGeneralSettings();

    $template = $general->active_template;
    $sess = session()->get('template');
    if (trim($sess)) {
        $template = $sess;
    }
    return $template;
}


function loadReCaptcha()
{
    $reCaptcha = Extension::where('act', 'google-recaptcha2')->where('status', 1)->first();
    return $reCaptcha ? $reCaptcha->generateScript() : '';
}


function loadAnalytics()
{
    $analytics = Extension::where('act', 'google-analytics')->where('status', 1)->first();
    return $analytics ? $analytics->generateScript() : '';
}

function loadHotjar()
{
    $analytics = Extension::where('act', 'hotjar')->where('status', 1)->first();
    return $analytics ? $analytics->generateScript() : '';
}

function loadTawkto()
{
    $tawkto = Extension::where('act', 'tawk-chat')->where('status', 1)->first();
    return $tawkto ? $tawkto->generateScript() : '';
}


function loadFbComment()
{
    $comment = Extension::where('act', 'fb-comment')->where('status', 1)->first();
    return $comment ? $comment->generateScript() : '';
}

function loadCustomCaptcha($height = 46, $width = '300px', $bgcolor = '#003', $textcolor = '#abc')
{
    $textcolor = '#' . GeneralSetting::first()->base_color;
    $captcha = Extension::where('act', 'custom-captcha')->where('status', 1)->first();
    if (!$captcha) {
        return 0;
    }
    $code = rand(100000, 999999);
    $char = str_split($code);
    $ret = '<link href="https://fonts.googleapis.com/css?family=Henny+Penny&display=swap" rel="stylesheet">';
    $ret .= '<div style="height: ' . $height . 'px; line-height: ' . $height . 'px; width:' . $width . '; text-align: center; background-color: ' . $bgcolor . '; color: ' . $textcolor . '; font-size: ' . ($height - 20) . 'px; font-weight: bold; letter-spacing: 20px; font-family: \'Henny Penny\', cursive;  -webkit-user-select: none; -moz-user-select: none;-ms-user-select: none;user-select: none;  display: flex; justify-content: center;">';
    foreach ($char as $value) {
        $ret .= '<span style="    float:left;     -webkit-transform: rotate(' . rand(-60, 60) . 'deg);">' . $value . '</span>';
    }
    $ret .= '</div>';
    $captchaSecret = hash_hmac('sha256', $code, $captcha->shortcode->random_key->value);
    $ret .= '<input type="hidden" name="captcha_secret" value="' . $captchaSecret . '">';
    return $ret;
}


function captchaVerify($code, $secret)
{
    $captcha = Extension::where('act', 'custom-captcha')->where('status', 1)->first();
    $captchaSecret = hash_hmac('sha256', $code, $captcha->shortcode->random_key->value);
    if ($captchaSecret == $secret) {
        return true;
    }
    return false;
}

function getTrx($length = 12)
{
    $characters = 'ABCDEFGHJKMNOPQRSTUVWXYZ123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


function getAmount($amount, $length = 2)
{
    $amount = round($amount, $length);
    return $amount + 0;
}

function showAmount($amount, $decimal = 2, $separate = true, $exceptZeros = false)
{
    if (!is_numeric($amount)) {
        return 0;
    }

    $separator = '';
    if ($separate) {
        $separator = ',';
    }
    //check if $amount is float
    if (!is_float($amount)) {
        $amount = (float)$amount;
    }
    $printAmount = number_format($amount, $decimal, '.', $separator);
    if ($exceptZeros) {
        $exp = explode('.', $printAmount);
        if ($exp[1] * 1 == 0) {
            $printAmount = $exp[0];
        }
    }
    return $printAmount;
}


function removeElement($array, $value)
{
    return array_diff($array, (is_array($value) ? $value : array($value)));
}

function cryptoQR($wallet)
{

    return "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$wallet&choe=UTF-8";
}

//moveable
function curlContent($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

//moveable
function curlPostContent($url, $arr = null)
{
    if ($arr) {
        $params = http_build_query($arr);
    } else {
        $params = '';
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


function inputTitle($text)
{
    return ucfirst(preg_replace("/[^A-Za-z0-9 ]/", ' ', $text));
}


function titleToKey($text)
{
    return strtolower(str_replace(' ', '_', $text));
}


function str_limit($title = null, $length = 10)
{
    return \Illuminate\Support\Str::limit($title, $length);
}

//moveable
function getIpInfo()
{
    $ip = $_SERVER["REMOTE_ADDR"];

    //Deep detect ip
    if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }


    $xml = @simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=" . $ip);


    $country = @$xml->geoplugin_countryName;
    $city = @$xml->geoplugin_city;
    $area = @$xml->geoplugin_areaCode;
    $code = @$xml->geoplugin_countryCode;
    $long = @$xml->geoplugin_longitude;
    $lat = @$xml->geoplugin_latitude;

    $data['country'] = $country;
    $data['city'] = $city;
    $data['area'] = $area;
    $data['code'] = $code;
    $data['long'] = $long;
    $data['lat'] = $lat;
    $data['ip'] = request()->ip();
    $data['time'] = date('d-m-Y h:i:s A');


    return $data;
}

//moveable
function osBrowser()
{
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $osPlatform = "Unknown OS Platform";
    $osArray = array(
        '/windows nt 10/i' => 'Windows 10',
        '/windows nt 6.3/i' => 'Windows 8.1',
        '/windows nt 6.2/i' => 'Windows 8',
        '/windows nt 6.1/i' => 'Windows 7',
        '/windows nt 6.0/i' => 'Windows Vista',
        '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i' => 'Windows XP',
        '/windows xp/i' => 'Windows XP',
        '/windows nt 5.0/i' => 'Windows 2000',
        '/windows me/i' => 'Windows ME',
        '/win98/i' => 'Windows 98',
        '/win95/i' => 'Windows 95',
        '/win16/i' => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i' => 'Mac OS 9',
        '/linux/i' => 'Linux',
        '/ubuntu/i' => 'Ubuntu',
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile'
    );
    foreach ($osArray as $regex => $value) {
        if (preg_match($regex, $userAgent)) {
            $osPlatform = $value;
        }
    }
    $browser = "Unknown Browser";
    $browserArray = array(
        '/msie/i' => 'Internet Explorer',
        '/firefox/i' => 'Firefox',
        '/safari/i' => 'Safari',
        '/chrome/i' => 'Chrome',
        '/edge/i' => 'Edge',
        '/opera/i' => 'Opera',
        '/netscape/i' => 'Netscape',
        '/maxthon/i' => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i' => 'Handheld Browser'
    );
    foreach ($browserArray as $regex => $value) {
        if (preg_match($regex, $userAgent)) {
            $browser = $value;
        }
    }

    $data['os_platform'] = $osPlatform;
    $data['browser'] = $browser;

    return $data;
}

function siteName()
{
    $general = getGeneralSettings();

    $sitname = str_word_count($general->sitename);
    $sitnameArr = explode(' ', $general->sitename);
    if ($sitname > 1) {
        $title = "<span>$sitnameArr[0] </span> " . str_replace($sitnameArr[0], '', $general->sitename);
    } else {
        $title = "<span>$general->sitename</span>";
    }

    return $title;
}


//moveable
function getTemplates()
{
    $param['purchasecode'] = config("app.PURCHASE_CODE");
    $param['website'] = @$_SERVER['HTTP_HOST'] . @$_SERVER['REQUEST_URI'] . ' - ' . config("app.url");
    $url = 'https://license.viserlab.com/updates/templates/' . systemDetails()['name'];
    $result = curlPostContent($url, $param);
    if ($result) {
        return $result;
    } else {
        return null;
    }
}


function getPageSections($arr = false)
{

    $jsonUrl = resource_path('views/') . str_replace('.', '/', activeTemplate()) . 'sections.json';
    $sections = json_decode(file_get_contents($jsonUrl));
    if ($arr) {
        $sections = json_decode(file_get_contents($jsonUrl), true);
        ksort($sections);
    }
    return $sections;
}


function getImage($image, $size = null, $isAvatar = false, $responsive_image_size = null)
{


    $clean = '';
    $responsive_image_size = $responsive_image_size == 'lg' ? null : strtolower($responsive_image_size);
    if ($responsive_image_size) {
        $prefix = $responsive_image_size . '_';
        $imageFileNameArr = explode('/', $image);
        $imageFileName = $imageFileNameArr[count($imageFileNameArr) - 1];
        $imageFileName = $prefix . $imageFileName;
        $imageFileNameArr[count($imageFileNameArr) - 1] = $imageFileName;
        $imageResponsive = implode('/', $imageFileNameArr);
        //        Log::info('image: '.$imageResponsive);
        //return image from s3 if exists
        if (Storage::disk('s3')->exists($imageResponsive)) {
            return Storage::disk('s3')->url($imageResponsive);
        }
        if (file_exists($imageResponsive) && is_file($imageResponsive)) {
            return asset($imageResponsive) . $clean;
        }
    }
    //return image from s3 if exists
    if (Storage::disk('s3')->exists($image)) {
        return Storage::disk('s3')->url($image);
    }
    if (file_exists($image) && is_file($image)) {
        return asset($image) . $clean;
    }

    if ($isAvatar) {
        return asset('assets/images/avatar.jpg');
    }

    if ($size) {
        return route('placeholder.image', $size);
    }
    return asset('assets/images/default.png');
}



function notify($user, $type, $shortCodes = null, $userType = 'user')
{

    sendEmail($user, $type, $shortCodes, $userType);
    sendSms($user, $type, $shortCodes);
}


function sendSms($user, $type, $shortCodes = [])
{
    $general = getGeneralSettings();

    $smsTemplate = SmsTemplate::where('act', $type)->where('sms_status', 1)->first();
    $gateway = $general->sms_config->name;
    $sendSms = new SendSms;
    if ($general->sn == 1 && $smsTemplate) {
        $template = $smsTemplate->sms_body;
        foreach ($shortCodes as $code => $value) {
            $template = shortCodeReplacer('{{' . $code . '}}', $value, $template);
        }
        $message = shortCodeReplacer("{{message}}", $template, $general->sms_api);
        $message = shortCodeReplacer("{{name}}", $user->username, $message);
        $sendSms->$gateway($user->mobile, $general->sitename, $message, $general->sms_config);
    }
}

//send email by pass email to the function instead of user object
function sendEmail_v2($email, $type = null, $shortCodes = [], $userType = 'user')
{

    $general = getGeneralSettings();

    $emailTemplate = EmailTemplate::where('act', $type)->where('email_status', 1)->first();
    if ($general->en != 1 || !$emailTemplate) {
        return;
    }


    $message = shortCodeReplacer("{{fullname}}", "", $general->email_template);
    $message = shortCodeReplacer("{{username}}", $email, $message);
    $message = shortCodeReplacer("{{message}}", $emailTemplate->email_body, $message);

    if (empty($message)) {
        $message = $emailTemplate->email_body;
    }

    foreach ($shortCodes as $code => $value) {
        $message = shortCodeReplacer('{{' . $code . '}}', $value, $message);
    }

    $config = $general->mail_config;

    // $emailLog = new EmailLog();


    // if ($userType == 'user') {
    //     $emailLog->user_id = $user->id;
    // } elseif ($userType == 'merchant') {
    //     $emailLog->merchant_id = $user->id;
    // }


    // $emailLog->mail_sender = $config->name;
    // $emailLog->email_from = $general->sitename . ' ' . $general->email_from;
    // $emailLog->email_to = $email;
    // $emailLog->subject = $emailTemplate->subj;
    // $emailLog->message = $message;
    // $emailLog->save();


    if ($config->name == 'php') {
        if ($type == 'BID_WINNER'|| $type == 'Auction_Order_Confirmation') {
            sendPhpMail($email, $email, $emailTemplate->subj, $message, $general, $emailTemplate->cc);
        } else {
            sendPhpMail($email, $email, $emailTemplate->subj, $message, $general);
        }
    } else if ($config->name == 'smtp') {
        if ($type == 'BID_WINNER' || $type == 'Auction_Order_Confirmation') {
            sendSmtpMail($config, $email, $email, $emailTemplate->subj, $message, $general, $emailTemplate->cc);
        } else {
            sendSmtpMail($config, $email, $email, $emailTemplate->subj, $message, $general);
        }
    } else if ($config->name == 'sendgrid') {
        if ($type == 'BID_WINNER') {
            sendSendGridMail($config, $email, $email, $emailTemplate->subj, $message, $general, $emailTemplate->cc);
        } else {
            sendSendGridMail($config, $email, $email, $emailTemplate->subj, $message, $general);
        }
    } else if ($config->name == 'mailjet') {
        if ($type == 'BID_WINNER' || $type == 'Auction_Order_Confirmation') {
            sendMailjetMail($config, $email, $email, $emailTemplate->subj, $message, $general, $emailTemplate->cc);
        } else {
            sendMailjetMail($config, $email, $email, $emailTemplate->subj, $message, $general);
        }
    }
}


function sendEmail($user, $type = null, $shortCodes = [], $userType = 'user')
{
    $general = getGeneralSettings();

    $emailTemplate = EmailTemplate::where('act', $type)->where('email_status', 1)->first();
    if ($general->en != 1 || !$emailTemplate) {
        return;
    }


    $message = shortCodeReplacer("{{fullname}}", $user->fullname, $general->email_template);
    $message = shortCodeReplacer("{{username}}", $user->username, $message);
    $message = shortCodeReplacer("{{message}}", $emailTemplate->email_body, $message);

    if (empty($message)) {
        $message = $emailTemplate->email_body;
    }

    foreach ($shortCodes as $code => $value) {
        $message = shortCodeReplacer('{{' . $code . '}}', $value, $message);
    }

    $config = $general->mail_config;

    $emailLog = new EmailLog();


    if ($userType == 'user') {
        $emailLog->user_id = $user->id;
    } elseif ($userType == 'merchant') {
        $emailLog->merchant_id = $user->id;
    }


    $emailLog->mail_sender = $config->name;
    $emailLog->email_from = $general->sitename . ' ' . $general->email_from;
    $emailLog->email_to = $user->email;
    $emailLog->subject = $emailTemplate->subj;
    $emailLog->message = $message;
    $emailLog->save();


    if ($config->name == 'php') {
        if ($type == 'BID_WINNER' || $type == 'Auction_Order_Confirmation' ) {
            sendPhpMail($user->email, $user->username, $emailTemplate->subj, $message, $general, $emailTemplate->cc);
        } else {
            sendPhpMail($user->email, $user->username, $emailTemplate->subj, $message, $general);
        }
    } else if ($config->name == 'smtp') {
        if ($type == 'BID_WINNER' || $type == 'Auction_Order_Confirmation') {
            sendSmtpMail($config, $user->email, $user->username, $emailTemplate->subj, $message, $general, $emailTemplate->cc);
        } else {
            sendSmtpMail($config, $user->email, $user->username, $emailTemplate->subj, $message, $general);
        }
    } else if ($config->name == 'sendgrid') {
        if ($type == 'BID_WINNER' || $type == 'Auction_Order_Confirmation') {
            sendSendGridMail($config, $user->email, $user->username, $emailTemplate->subj, $message, $general, $emailTemplate->cc);
        } else {
            sendSendGridMail($config, $user->email, $user->username, $emailTemplate->subj, $message, $general);
        }
    } else if ($config->name == 'mailjet') {
        if ($type == 'BID_WINNER' || $type == 'Auction_Order_Confirmation') {
            sendMailjetMail($config, $user->email, $user->username, $emailTemplate->subj, $message, $general, $emailTemplate->cc);
        } else {
            sendMailjetMail($config, $user->email, $user->username, $emailTemplate->subj, $message, $general);
        }
    }
}


function sendPhpMail($receiver_email, $receiver_name, $subject, $message, $general, $cc = null)
{
    $headers = "From: $general->sitename <$general->email_from> \r\n";
    $headers .= "Reply-To: $general->sitename <$general->email_from> \r\n";
    if (isset($cc)) {
        $cc_array = explode(',', $cc);
        // $cc = implode(", ", $cc_array);
        $headers .= "CC:$cc_array\r\n";
    }
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    @mail($receiver_email, $subject, $message, $headers);
}


function sendSmtpMail($config, $receiver_email, $receiver_name, $subject, $message, $general, $cc = null)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = $config->host;
        $mail->SMTPAuth = true;
        $mail->Username = $config->username;
        $mail->Password = $config->password;
        if (config('app.env') == 'local' || config('app.env') == 'test') {

            $mail->SMTPDebug = 2; // 2 for debugging output
            //log output
            $mail->Debugoutput = function ($str, $level) {
                \Illuminate\Support\Facades\Log::info("debug level $level; message: $str");
            };
        }
        if ($config->enc == 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } else {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }
        $mail->Port = $config->port;
        $mail->CharSet = 'UTF-8';
        //Recipients
        $mail->setFrom($general->email_from, $general->sitename);
        $mail->addAddress($receiver_email, $receiver_name);
        $mail->addReplyTo($general->email_from, $general->sitename);
        if (isset($cc)) {
            $cc_array = explode(',', $cc);
            foreach ($cc_array as $el) {
                $mail->addCC($el);
            }
        }
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->send();
    } catch (Exception $e) {
        return back()->withErrors($e->getMessage());
        // throw new Exception($e);
    }
}


function sendSendGridMail($config, $receiver_email, $receiver_name, $subject, $message, $general, $cc = null)
{
    $sendgridMail = new \SendGrid\Mail\Mail();
    $sendgridMail->setFrom($general->email_from, $general->sitename);
    $sendgridMail->setSubject($subject);
    $sendgridMail->addTo($receiver_email, $receiver_name);
    $sendgridMail->addContent("text/html", $message);
    if (isset($cc)) {
        $cc_array = explode(',', $cc);
        foreach ($cc_array as $el) {
            $sendgridMail->addCc($el);
        }
    }
    $sendgrid = new \SendGrid($config->appkey);
    try {
        $response = $sendgrid->send($sendgridMail);
    } catch (Exception $e) {
        return back()->withErrors($e->getMessage());
    }
}


function sendMailjetMail($config, $receiver_email, $receiver_name, $subject, $message, $general, $cc = null)
{
    $mj = new \Mailjet\Client($config->public_key, $config->secret_key, true, ['version' => 'v3.1']);
    if (isset($cc)) {
        $cc_array = explode(',', $cc);
    }
    $body = [
        'Messages' => [
            [
                'From' => [
                    'Email' => $general->email_from,
                    'Name' => $general->sitename,
                ],
                'To' => [
                    [
                        'Email' => $receiver_email,
                        'Name' => $receiver_name,
                    ]
                ],
                'CC' => isset($cc) ? $cc_array : "",
                'Subject' => $subject,
                'TextPart' => "",
                'HTMLPart' => $message,
            ]
        ]
    ];
    $response = $mj->post(\Mailjet\Resources::$Email, ['body' => $body]);
}


function getPaginate($paginate = 20)
{
    return $paginate;
}

function paginateLinks($data, $design = 'admin.partials.paginate')
{
    return $data->appends(request()->all())->links($design);
}


function menuActive($routeName, $type = null)
{
    if ($type == 3) {
        $class = 'side-menu--open';
    } elseif ($type == 2) {
        $class = 'sidebar-submenu__open';
    } else {
        $class = 'active';
    }
    if (is_array($routeName)) {
        foreach ($routeName as $key => $value) {
            if (request()->routeIs($value)) {
                return $class;
            }
        }
    } elseif (request()->routeIs($routeName)) {
        return $class;
    }
}


function imagePath()
{
    $data['gateway'] = [
        'path' => 'assets/images/gateway',
        'size' => '800x800',
    ];
    $data['verify'] = [
        'withdraw' => [
            'path' => 'assets/images/verify/withdraw'
        ],
        'deposit' => [
            'path' => 'assets/images/verify/deposit'
        ]
    ];
    $data['image'] = [
        'default' => 'assets/images/default.png',
    ];
    $data['withdraw'] = [
        'method' => [
            'path' => 'assets/images/withdraw/method',
            'size' => '800x800',
        ]
    ];
    $data['ticket'] = [
        'path' => 'assets/support',
    ];
    $data['language'] = [
        'path' => 'assets/images/lang',
        'size' => '64x64'
    ];
    $data['logoIcon'] = [
        'path' => 'assets/images/logoIcon',
    ];
    $data['favicon'] = [
        'size' => '128x128',
    ];
    $data['extensions'] = [
        'path' => 'assets/images/extensions',
        'size' => '36x36',
    ];
    $data['seo'] = [
        'path' => 'assets/images/seo',
        'size' => '600x315'
    ];
    $data['profile'] = [
        'user' => [
            'path' => 'assets/images/user/profile',
            'size' => '350x300'
        ],
        'merchant' => [
            'path' => 'assets/images/merchant/profile',
            'size' => '350x300'
        ],
        'merchant_cover' => [
            'path' => 'assets/images/merchant/cover',
            'size' => '1300x520'
        ],
        'admin' => [
            'path' => 'assets/admin/images/profile',
            'size' => '400x400'
        ],
        'admin_cover' => [
            'path' => 'assets/admin/images/cover',
            'size' => '1300x520'
        ]
    ];
    $data['product'] = [
        'path' => 'assets/images/product',
        'size' => '1280x960',
        'size_md' => '800x600',
        'size_sm' => '640x480',
        'thumb' => '280x189'
    ];
    $data['event'] = [
        'path' => 'assets/images/event',
        'size' => '400x270',
        'thumb' => '280x189'
    ];
    $data['event_logo'] = [
        'path' => 'assets/images/event',
        'size' => '120x112',
        'thumb' => '120x112'
    ];
    $data['event_banner_image'] = [
        'path' => 'assets/images/event',
        'size_md' => '1920x1080',
        'size_sm' => '1000x562',
        'size' => '2800x1575',
        'thumb' => '280x189'
    ];
    $data['group'] = [
        'path' => 'assets/images/group',
        'size' => '400x270',
        'thumb' => '280x189'
    ];
    $data['advertisement'] = [
        'path' => 'assets/images/advertisement',
    ];
    $data['settings'] = [
        'path' => 'assets/images/settings',
    ];
    $data['site_theme'] = [
        'logoIcon' => [
            'path' => 'assets/images/logoIcon',
        ],
        'favicon' => [
            'size' => '128x128',
        ]
    ];
    return $data;
}

function diffForHumans($date)
{
    $lang = session()->get('lang');
    Carbon::setlocale($lang);
    return Carbon::parse($date)->diffForHumans();
}

function showDateTime($date, $format = 'Y-m-d h:i A')
{
    $lang = session()->get('lang');
    Carbon::setlocale($lang);
    return Carbon::parse($date)->translatedFormat($format);
}

//moveable
function sendGeneralEmail($email, $subject, $message, $receiver_name = '')
{

    $general = getGeneralSettings();


    if ($general->en != 1 || !$general->email_from) {
        return;
    }


    $message = shortCodeReplacer("{{message}}", $message, $general->email_template);
    $message = shortCodeReplacer("{{fullname}}", $receiver_name, $message);
    $message = shortCodeReplacer("{{username}}", $email, $message);

    $config = $general->mail_config;

    if ($config->name == 'php') {
        sendPhpMail($email, $receiver_name, $subject, $message, $general);
    } else if ($config->name == 'smtp') {
        sendSmtpMail($config, $email, $receiver_name, $subject, $message, $general);
    } else if ($config->name == 'sendgrid') {
        sendSendGridMail($config, $email, $receiver_name, $subject, $message, $general);
    } else if ($config->name == 'mailjet') {
        sendMailjetMail($config, $email, $receiver_name, $subject, $message, $general);
    }
}

function getContent($data_keys, $singleQuery = false, $limit = null, $orderById = false)
{
    if ($singleQuery) {
        $content = Frontend::where('data_keys', $data_keys)->orderBy('id', 'desc')->first();
    } else {
        $article = Frontend::query();
        $article->when($limit != null, function ($q) use ($limit) {
            return $q->limit($limit);
        });
        if ($orderById) {
            $content = $article->where('data_keys', $data_keys)->orderBy('id')->get();
        } else {
            $content = $article->where('data_keys', $data_keys)->orderBy('id', 'desc')->get();
        }
    }
    return $content;
}


function gatewayRedirectUrl($type = false)
{
    if ($type) {
        return 'user.deposit.history';
    } else {
        return 'user.deposit';
    }
}

function verifyG2fa($user, $code, $secret = null)
{
    $ga = new GoogleAuthenticator();
    if (!$secret) {
        $secret = $user->tsc;
    }
    $oneCode = $ga->getCode($secret);
    $userCode = $code;
    if ($oneCode == $userCode) {
        $user->tv = 1;
        $user->save();
        return true;
    } else {
        return false;
    }
}


function urlPath($routeName, $routeParam = null)
{
    if ($routeParam == null) {
        $url = route($routeName);
    } else {
        $url = route($routeName, $routeParam);
    }
    $basePath = route('home');
    $path = str_replace($basePath, '', $url);
    return $path;
}

function displayAvgRating($rating = 0)
{
    $ratingStar = '';

    for ($i = 0; $i < floor($rating); $i++) {
        $ratingStar .= '<i class="las la-star"></i>';
    }

    if (0 < $rating - floor($rating) && $rating - floor($rating) <= 0.25) {
        $ratingStar .= '<i class="lar la-star"></i>';
    } elseif (0.25 < $rating - floor($rating) && $rating - floor($rating) <= 0.75) {
        $ratingStar .= '<i class="las la-star-half-alt"></i>';
    } elseif (0.75 <= $rating - floor($rating) && $rating - floor($rating) < 1) {
        $ratingStar .= '<i class="las la-star"></i>';
    }

    for ($i = 0; $i < 5 - ceil($rating); $i++) {
        $ratingStar .= '<i class="lar la-star"></i>';
    }

    return $ratingStar;
}

function showAd($size)
{
    $ad = Advertisement::where('size', $size)->where('status', 1)->inRandomOrder()->first();

    if ($ad) {
        $ad->impression += 1;
        $ad->save();
        if ($ad->type == 'image') {
            $html = '<a href="' . route('adRedirect', encrypt($ad->id)) . '" target="_blank"><img src="' . getImage(imagePath()['advertisement']['path'] . '/' . $ad->value, $size) . '"></a>';
            echo $html;
            return true;
        }
        return $ad->value;
    }
    return false;
}
function getImages($imagesName, $imageData)
{
    $images = [];
    foreach ($imagesName as $key => $imageName) {
        $images[$key]['image'] = getImage($imageData['path'] . '/' . $imageName, $imageData['size']);
        $images[$key]['image_size'] = $imageData['size'];
    }
    return $images;
}

function getSeoContents($collection, $imageData, $imageColumnName)
{
    $seoContents['keywords'] = $collection->meta_keywords ?? [];
    $seoContents['description'] = htmlspecialchars_decode($collection->meta_description);
    $seoContents['social_title'] = htmlspecialchars_decode($collection->meta_title);
    $seoContents['social_description'] = htmlspecialchars_decode($collection->meta_description);
    $seoContents['image'] = getImage($imageData['path'] . '/' . $collection->$imageColumnName, $imageData['size'], false, 'sm');
    $seoContents['image_size'] = $imageData['size'];
    return $seoContents;
}

/**
 * This function returns all events (ID) that the user can participate in
 *
 * @param int $id
 * @return array
 */
function get_allowed_events($id)
{
    $groups_id_array = array();

    $groups_id = DB::table('user_permission_group')->select('group_id')->where('user_id', $id)->get();
    foreach ($groups_id as $g) {
        $groups_id_array[] = $g->group_id;
    }


    $allowed_events = array();
    $events_id_groups_id = DB::table('event_permission_group')->select('event_id')->leftJoin('permission_group', 'event_permission_group.group_id', '=', 'permission_group.id')->whereIn('group_id', $groups_id_array)->where('status', 1)->get();
    $events_id_permission_id = DB::table('user_events')->select('event_id')->where(['user_id' => $id, 'is_active' => 1])->get();
    foreach ($events_id_groups_id as $e) {
        if (in_array($e->event_id, $allowed_events)) {
            continue;
        }
        $allowed_events[] = $e->event_id;
    }
    foreach ($events_id_permission_id as $e) {
        if (in_array($e->event_id, $allowed_events)) {
            continue;
        }
        $allowed_events[] = $e->event_id;
    }
    return $allowed_events;
}


/**
 * This function returns all events (ID) that the user requested for participate in
 *
 * @param int $id
 * @return array
 */
function get_pending_events($id)
{
    $pending_events = UserRequest::latest()->where('user_id', $id)->where(function ($q) {
        $q->where('status', -1)->orWhere('terms_accept', 0);
    })->pluck('event_id')->toArray();
    return $pending_events;
}

/**
 * This function returns all products (ID) that the user can bid on
 *
 * @param int $id
 * @return array
 */
function get_allowed_products($id)
{
    return Cache::remember('get_allowed_products_user_' . $id, 30, function () use ($id) {

        $groups_id_array = array();

        $groups_id = DB::table('user_permission_group')->select('group_id')->where('user_id', $id)->get();
        foreach ($groups_id as $g) {
            $groups_id_array[] = $g->group_id;
        }


        $allowed_products = array();
        $products_id_groups_id = DB::table('product_permission_group')->select('product_id')->leftJoin('permission_group', 'product_permission_group.group_id', '=', 'permission_group.id')->whereIn('group_id', $groups_id_array)->where('status', 1)->get();
        $products_id_permission_id = DB::table('user_events')->select('product_id')->where(['user_id' => $id, 'is_active' => 1])->get();
        foreach ($products_id_groups_id as $p) {
            if (in_array($p->product_id, $allowed_products)) {
                continue;
            }
            $allowed_products[] = $p->product_id;
        }
        foreach ($products_id_permission_id as $p) {
            if (in_array($p->product_id, $allowed_products)) {
                continue;
            }
            $allowed_products[] = $p->product_id;
        }
        return $allowed_products;
    });
}


/**
 * This function returns if
 *
 * @return bool
 */
function stop_live_in_not_auth_users()
{
    $general = getGeneralSettings();
    return $general->stop_live_in_not_auth_users;
}


/**
 * This function returns if
 *
 * @return bool
 */
function get_is_stop_cart()
{
    $general = getGeneralSettings();
    return $general->stop_cart ?? false;
}


/**
 * Flash  Cache
 */
function clear_all_cache()
{
    //Cache::flush();
    Cache::store(config('app.CACHE_DRIVER'))->flush();
}


/**
 * Flush Cache for favorite products
 */


function flush_favorite_products_cache($product_id, $user_id)
{
    Cache::forget('product_user_is_fav_' . $product_id . '_user_' . $user_id);
}

function clear_general_settings_cache()
{
    Cache::forget('general_settings');
}

function solve_storage_permission()
{
    $path = storage_path();
    //run chown -R www-data:www-data storage/
    $pros = new Process(['/usr/bin/chown', '-R', 'www-data:www-data', $path]);
    $pros->run();

    if (!$pros->isSuccessful()) {
        //get error
        $error = $pros->getErrorOutput();
        return $error;
    }
    return 'Permission Granted';
}
function getLanguages()
{
    return Cache::remember('languages', 30, function () {
        return Language::all();
    });
}

function getTemplatePages($activeTemplate)
{
    return Cache::remember('template_pages_' . $activeTemplate, 30, function () use ($activeTemplate) {
        return Page::where('tempname', $activeTemplate)->where('is_default', 0)->get();
    });
}
// get available languages
function get_available_languages()
{
    copy_default_languages_files_if_not_existent();
    //get codes from lang folder in resources
    $langFiles = glob(resource_path('lang/*'));
    $codes = [];
    foreach ($langFiles as $langFile) {
        $basename = basename($langFile);
        //remove extension
        $basename = substr($basename, 0, strpos($basename, '.'));
        $codes[] = $basename;
    }
    $languages = \App\Models\Language::query()->whereIn('code', $codes)->get();
    return $languages;
}
function copy_default_languages_files_if_not_existent()
{
    $destinationFolder = glob(resource_path('lang/*'));
    $sourceFolder = glob(resource_path('lang/default_languages/*'));
    foreach ($sourceFolder as $file) {
        $basename = basename($file);
        $destinationFile = resource_path('lang/' . $basename);
        if (!file_exists($destinationFile)) {
            copy($file, $destinationFile);
        } else {
            $data_dest = json_decode(file_get_contents($destinationFile), true) ?? [];
            $data_default = json_decode(file_get_contents($file), true) ?? [];
            foreach ($data_default as $key => $value) {
                if (!array_key_exists($key, $data_dest)) {
                    $data_dest[$key] = $value;
                }
            }
            // remove keys are not exist in data_default
            foreach ($data_dest as $key => $value) {
                if (!array_key_exists($key, $data_default)) {
                    unset($data_dest[$key]);
                }
            }
            file_put_contents($destinationFile, json_encode($data_dest, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }
}

function get_policy_id()
{

    return Cache::remember('Terms and Conditions policy id', 30, function () {
        $policyPages = getContent('policy_pages.element');
        foreach ($policyPages as $policyPage) {
            if ($policyPage->data_values->title == 'Terms and Conditions') {
                $policy_id = $policyPage->id;
            }
        }

        return $policy_id;
    });
}

function get_offer_specifications($offerSheetId = null)
{

    $query = \App\Models\OfferSpecification::query();
    if ($offerSheetId) {
        $query->whereHas('offer', function ($q) use ($offerSheetId) {
            $q->where('offer_sheet_id', $offerSheetId);
        });
    }
    return $query->distinct('spec_key')->pluck('spec_key');
}


function get_extention_keys($ext_name)
{

    return Cache::remember('codes', 30, function () use ($ext_name) {

        $extention = Extension::where('act', $ext_name)->first();
        $shortcodes = json_decode(json_encode($extention->shortcode), true);
        foreach ($shortcodes as $key => $code) {
            $codes[$key] = $shortcodes[$key]['value'];
        }
        if (
            !isset($codes['PUSHER_APP_KEY']) || !trim(str_replace('-', '', $codes['PUSHER_APP_KEY'])) ||
            !isset($codes['PUSHER_APP_SECRET']) || !trim(str_replace('-', '', $codes['PUSHER_APP_SECRET'])) ||
            !isset($codes['PUSHER_APP_ID']) || !trim(str_replace('-', '', $codes['PUSHER_APP_ID'])) ||
            !isset($codes['PUSHER_APP_CLUSTER']) || !trim(str_replace('-', '', $codes['PUSHER_APP_CLUSTER']))
        ) {
            \Illuminate\Support\Facades\Log::error('PUSHER_APP_KEY, PUSHER_APP_SECRET, PUSHER_APP_ID, PUSHER_APP_CLUSTER are not set in the admin dashboard, will use .env values:' . json_encode(config('broadcasting.connections.pusher')));
            return [
                'PUSHER_APP_KEY' => config('broadcasting.connections.pusher.key'),
                'PUSHER_APP_SECRET' => config('broadcasting.connections.pusher.secret'),
                'PUSHER_APP_ID' => config('broadcasting.connections.pusher.app_id'),
                'PUSHER_APP_CLUSTER' => config('broadcasting.connections.pusher.options.cluster')
            ];
        }
        return $codes;
    });
}
function set_pusher_config($codes)
{
    $pusherConfig = [
        'driver' => 'pusher',
        'key' => $codes['PUSHER_APP_KEY'],
        'secret' => $codes['PUSHER_APP_SECRET'],
        'app_id' => $codes['PUSHER_APP_ID'],
    ];
    config(['broadcasting.connections.pusher' => $pusherConfig]);
    config(['broadcasting.connections.pusher.options.cluster' => $codes['PUSHER_APP_CLUSTER']]);
    config(['broadcasting.connections.pusher.options.useTLS' => true]);
}
function getRoundPrecision()
{
    return 4;
}
function setStripeConfig()
{
    Stripe::setApiKey(config('app.STRIPE_API_KEY'));
    Stripe::setApiVersion("2020-03-02");
}

#region site Settings Helpers

/*
 * site title
site description
home page logo
home page url
navbar links
past_auction_label
new_auction_label
new_auction_id
favicon
social image
login image
facebook link
instagram link
linkedin link
youtube link
vimeo link
email
is_template_footer
footer facebook link
footer instagram link
footer linkedin link
footer youtube link
footer vimeo link
footer email
footer logo
is_footer_image
footer_image
footer_image_link
navbar_background
navbar_nav_links
navbar_nav_icons
navbar_hover
footer_background
footer_links
footer_icons
footer_hover
sitewide_page_background
sitewide_text_heading1
sitewide_text_heading2
sitewide_text_heading3
sitewide_text_heading4
sitewide_text_heading5
sitewide_text_subtitle1
sitewide_text_subtitle2
sitewide_text_body1
sitewide_text_caption
sitewide_text_overline
sitewide_text_highlight
sitewide_links_on_dark_background
sitewide_links_text_links
sitewide_contained_buttons_background
sitewide_contained_buttons_text
sitewide_contained_buttons_hover
sitewide_outlined_buttons_background
sitewide_outlined_buttons_text
sitewide_outlined_buttons_hover
sitewide_text_buttons_color
sitewide_text_buttons_hover
sitewide_icons_color
sitewide_icons_hover
sitewide_icons_hover_background
sitewide_chips_text_and_icon
sitewide_chips_background

headings_font_url
headings_font_family
headings_font_style
headings_letter_spacing
headings_text_transform
paragraphs_font_url
paragraphs_font_family
paragraphs_font_style
paragraphs_letter_spacing
paragraphs_text_transform
auction_card_numbers_font_url
auction_card_numbers_font_family
auction_card_numbers_font_style
auction_card_numbers_letter_spacing
auction_card_numbers_text_transform
outlined_button_font_url
outlined_button_font_family
outlined_button_font_style
outlined_button_letter_spacing
outlined_button_text_transform
outlined_button_corner_radius
contained_button_font_url
contained_button_font_family
contained_button_font_style
contained_button_letter_spacing
contained_button_text_transform
text_button_font_url
text_button_font_family
text_button_font_style
text_button_letter_spacing
text_button_text_transform
text_button_corner_radius

 */
function getSiteTitle()
{
    //    return 'my site title';
    return getSiteSettingValue('site_title');
}
function getSiteDescription()
{
    //    return 'my site description';
    return getSiteSettingValue('site_description');
}
function getHomePageLogo($size = null)
{
    if (!getSiteSettingValue('home_page_logo'))
        return (getImage(imagePath()['logoIcon']['path'] . '/logo.png', $size));
    return getImage(imagePath()['settings']['path'] . '/' . getSiteSettingValue('home_page_logo'), $size);
}
function getHomePageUrl()
{
    if (!getSiteSettingValue('home_page_url'))
        return route('home');
    return getSiteSettingValue('home_page_url');
}
function getNavbarLinks()
{
    $links = json_decode(getSiteSettingValue('navbar_links'), true);
    return $links; //todo add default links
}
function getPastAuctionLabel()
{
    return getSiteSettingValue('past_auction_label');
}
function getNewAuctionLabel()
{
    return getSiteSettingValue('new_auction_label');
}
function getNewAuctionId()
{
    return getSiteSettingValue('new_auction_id');
}
function getFavicon()
{
    if (!getSiteSettingValue('favicon'))
        return (getImage(imagePath()['logoIcon']['path'] . '/favicon.png'));
    return getImage(imagePath()['settings']['path'] . '/' . getSiteSettingValue('favicon'));
}
function getSocialImage($size = null)
{
    if (!getSiteSettingValue('social_image'))
        return route('placeholder.image', ['size' => '600x315']);
    return getImage(imagePath()['settings']['path'] . '/' . getSiteSettingValue('social_image'), $size);
}
function getLoginImage($size = null)
{
    if (!getSiteSettingValue('login_image'))
        return route('placeholder.image', ['size' => '600x315']);
    return getImage(imagePath()['settings']['path'] . '/' . getSiteSettingValue('login_image'), $size);
}
function getFacebookLink()
{
    return getSiteSettingValue('facebook_url');
}
function getInstagramLink()
{
    return getSiteSettingValue('instagram_url');
}
function getLinkedinLink()
{
    return getSiteSettingValue('linkedin_url');
}
function getYoutubeLink()
{
    return getSiteSettingValue('youtube_url');
}
function getTwitterLink()
{
    return getSiteSettingValue('twitter_url');
}
function getVimeoLink()
{
    return getSiteSettingValue('vimeo_url');
}
function getEmail()
{
    return getSiteSettingValue('email');
}
function getIsTemplateFooter()
{
    return getSiteSettingValue('is_template_footer');
}
function getFooterFacebookLink()
{
    return getSiteSettingValue('footer_facebook_link');
}
function getFooterInstagramLink()
{
    return getSiteSettingValue('footer_instagram_link');
}
function getFooterTwitterLink()
{
    return getSiteSettingValue('footer_twitter_link');
}
function getFooterLinkedinLink()
{
    return getSiteSettingValue('footer_linkedin_link');
}
function getFooterYoutubeLink()
{
    return getSiteSettingValue('footer_youtube_link');
}
function getFooterVimeoLink()
{
    return getSiteSettingValue('footer_vimeo_link');
}
function getFooterEmail()
{
    return getSiteSettingValue('footer_email');
}
function getFooterLogo($size = null)
{
    if (!getSiteSettingValue('footer_logo'))
        return (getImage(imagePath()['logoIcon']['path'] . '/logo.png', $size));
    return getImage(imagePath()['settings']['path'] . '/' . getSiteSettingValue('footer_logo'), $size);
}
function getIsFooterImage()
{
    return getSiteSettingValue('is_footer_image');
}
function getFooterImage($size = null)
{
    if (!getSiteSettingValue('footer_image'))
        return route('placeholder.image', ['size' => '600x315']); //todo remove fake values
    return getImage(imagePath()['settings']['path'] . '/' . getSiteSettingValue('footer_image'), $size);
}
function getFooterImageLink()
{
    return getSiteSettingValue('footer_image_link');
}
function getPastAuctionIds()
{
    $ids = getSiteSettingValue('past_auction_ids') ?? '';
    return explode(',', $ids);
}
function getNavbarBackground()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values

    return getColorSettingValue('nav_background_color')['color'];
}

function getNavbarNavLinks()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('nav_links_color')['color'];
}
function getNavbarNavIcons()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('nav_icons_color')['color'];
}
function getNavbarHover()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('nav_hover_color')['color'];
}
function getFooterBackground()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('footer_background_color')['color'];
}
function getFooterLinks()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('footer_links_color')['color'];
}
function getFooterIcons()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('footer_icons_color')['color'];
}
function getFooterHover()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('footer_hover_color')['color'];
}

function getSitewidePageBackground()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('page_background_color')['color'];
}
function getSitewideTextHeading1()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('text_h1_color')['color'];
}
function getSitewideTextHeading2()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('text_h2_color')['color'];
}
function getSitewideTextHeading3()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('text_h3_color')['color'];
}
function getSitewideTextHeading4()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('text_h4_color')['color'];
}
function getSitewideTextHeading5()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('text_h5_color')['color'];
}
function getSitewideTextSubtitle1()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('text_subtitle_1_color')['color'];
}
function getSitewideTextSubtitle2()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('text_subtitle_2_color')['color'];
}
function getSitewideTextBody1()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('text_body_1_color')['color'];
}
function getSitewideTextBody2()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('text_body_2_color')['color'];
}
function getSitewideTextCaption()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('text_caption_color')['color'];
}
function getSitewideTextOverline()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('text_overline_color')['color'];
}
function getSitewideTextHighlight()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('text_highlight_color')['color'];
}
function getSitewideLinksOnDarkBackground()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('links_on_dark_background_color')['color'];
}
function getSitewideLinksTextLinks()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('links_color')['color'];
}
function getSitewideContainedButtonsBackground()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('button_background_color')['color'];
}
function getSitewideContainedButtonsText()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('button_text_color')['color'];
}
function getSitewideContainedButtonsHover()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('button_hover_background_color')['color'];
}
function getSitewideOutlinedButtonsBackground()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('secondary_button_background_color')['color'];
}
function getSitewideOutlinedButtonsText()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('secondary_button_text_color')['color'];
}
function getSitewideOutlinedButtonsHover()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('secondary_button_hover_background_color')['color'];
}
function getSitewideTextButtonsColor()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('secondary_text_color')['color'];
}
function getSitewideTextButtonsHover()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('secondary_hover_text_color')['color'];
}
function getSitewideIconsColor()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('icon_color')['color'];
}
function getSitewideIconsHover()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('icon_hover_active_color')['color'];
}
function getSitewideIconsHoverBackground()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('icon_hover_background_color')['color'];
}
function getSitewideChipsTextAndIcon()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('chip_text_and_icon_color')['color'];
}
function getSitewideChipsBackground()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('chip_background_color')['color'];
}
function getCheckBoxAndRadioActiveColor()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('checkbox_and_radio_active_color')['color'];
}
function getTabsHoverColor()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('tabs_hover_color')['color'];
}
function getTabsActiveColor()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('tabs_active_color')['color'];
}
function getBudgetProgressBarColor()
{
    //    return 'rgba(0,0,0,0.5)'; //todo add default style values
    return getColorSettingValue('budget_progress_bar_color')['color'];
}
function getHeadingsFontUrl()
{
    //    return 'https://fonts.googleapis.com/css?family=Arial'; //todo remove fake values
    return getFontUrlSettingValue('head_family');
}
function getHeadingsFontFamily()
{
    //    return 'Arial'; //todo remove fake values
    return getFontUrlSettingValue('head_family');
}
function getHeadingsFontStyle()
{
    //    return 'normal'; //todo remove fake values
    return getSiteSettingValue('head_style');
}
function getHeadingsLetterSpacing()
{
    //    return '0'; //todo remove fake values
    return getSiteSettingValue('head_letter_spacing');
}
function getHeadingsTextTransform()
{
    //    return 'none'; //todo remove fake values
    return getSiteSettingValue('head_text_transform');
}
function getParagraphsFontUrl()
{
    //    return 'https://fonts.googleapis.com/css?family=Arial'; //todo remove fake values
    return getFontUrlSettingValue('paragraph_family');
}
function getParagraphsFontFamily()
{
    //    return 'Arial'; //todo remove fake values
    return getFontUrlSettingValue('paragraph_family');
}
function getParagraphsFontStyle()
{
    //    return 'normal'; //todo remove fake values
    return getSiteSettingValue('paragraph_style');
}
function getParagraphsLetterSpacing()
{
    //    return '0'; //todo remove fake values
    return getSiteSettingValue('paragraph_letter_spacing');
}
function getParagraphsTextTransform()
{
    //    return 'none'; //todo remove fake values
    return getSiteSettingValue('paragraph_text_transform');
}
function getAuctionCardNumbersFontUrl()
{
    //    return 'https://fonts.googleapis.com/css?family=Arial'; //todo remove fake values
    return getFontUrlSettingValue('card_family');
}
function getAuctionCardNumbersFontFamily()
{
    //    return 'Arial'; //todo remove fake values
    return getFontUrlSettingValue('card_family');
}
function getAuctionCardNumbersFontStyle()
{
    //    return 'normal'; //todo remove fake values
    return getSiteSettingValue('card_style');
}
function getAuctionCardNumbersLetterSpacing()
{
    //    return '0'; //todo remove fake values
    return getSiteSettingValue('card_letter_spacing');
}
function getAuctionCardNumbersTextTransform()
{
    //    return 'none'; //todo remove fake values
    return getSiteSettingValue('card_text_transform');
}
function getOutlinedButtonFontUrl()
{
    //    return 'https://fonts.googleapis.com/css?family=Arial'; //todo remove fake values
    return getFontUrlSettingValue('outlined_button_family');
}
function getOutlinedButtonFontFamily()
{
    //    return 'Arial'; //todo remove fake values
    return getFontUrlSettingValue('outlined_button_family');
}
function getOutlinedButtonFontStyle()
{
    //    return 'normal'; //todo remove fake values
    return getSiteSettingValue('outlined_button_style');
}
function getOutlinedButtonLetterSpacing()
{
    //    return '0'; //todo remove fake values
    return getSiteSettingValue('outlined_button_letter_spacing');
}
function getOutlinedButtonTextTransform()
{
    //    return 'none'; //todo remove fake values
    return getSiteSettingValue('outlined_button_text_transform');
}
function getOutlinedButtonCornerRadius()
{
    //    return '0'; //todo remove fake values
    return getSiteSettingValue('outlined_custom_corners');
}
function getContainedButtonFontUrl()
{
    //    return 'https://fonts.googleapis.com/css?family=Arial'; //todo remove fake values
    return getFontUrlSettingValue('button_family');
}
function getContainedButtonFontFamily()
{
    //    return 'Arial'; //todo remove fake values
    return getFontUrlSettingValue('button_family');
}
function getContainedButtonFontStyle()
{
    //    return 'normal'; //todo remove fake values
    return getSiteSettingValue('button_style');
}
function getContainedButtonLetterSpacing()
{
    //    return '0'; //todo remove fake values
    return getSiteSettingValue('button_letter_spacing');
}
function getContainedButtonTextTransform()
{
    //    return 'none'; //todo remove fake values
    return getSiteSettingValue('button_text_transform');
}
function getContainedButtonCornerRadius()
{
    //    return '0'; //todo remove fake values
    return getSiteSettingValue('custom_corners');
}

function getTextButtonFontUrl()
{
    //    return 'https://fonts.googleapis.com/css?family=Arial'; //todo remove fake values
    return getFontUrlSettingValue('text_button_family');
}
function getTextButtonFontFamily()
{
    //    return 'Arial'; //todo remove fake values
    return getFontUrlSettingValue('text_button_family');
}
function getTextButtonFontStyle()
{
    //    return 'normal'; //todo remove fake values
    return getSiteSettingValue('text_button_style');
}
function getTextButtonLetterSpacing()
{
    //    return '0'; //todo remove fake values
    return getSiteSettingValue('text_button_letter_spacing');
}
function getTextButtonTextTransform()
{
    //    return 'none'; //todo remove fake values
    return getSiteSettingValue('text_button_text_transform');
}
function getTextButtonCornerRadius()
{
    //    return '0'; //todo remove fake values
    return getSiteSettingValue('text_custom_corners');
}
////event styles
//hero_show_action_name
//hero_text_color
//hero_primary_button_color
//hero_image_overlay
//hero_outlined_button_color
function getHeroShowActionName($event)
{
    return $event->hero_show_action_name;
}
function getHeroTextColor($event)
{
    //    return ($event->hero_text_color);
    return getColorArray($event->hero_text_color)['color'];
}
function getHeroPrimaryButtonColor($event)
{
    //    return ($event->hero_primary_button_color);
    return getColorArray($event->hero_primary_button_color)['color'];
}
function getHeroImageOverlay($event)
{
    //    return ($event->hero_image_overlay);
    return getColorArray($event->hero_image_overlay)['color'];
}
function getEventOutlinedButtonColor($event)
{
    //    return ($event->hero_outlined_button_color);
    return getColorArray($event->hero_outlined_button_color)['color'];
}

function getFontUrlSettingValue($key)
{
    $val = getSiteSettingValue($key);
    if ($val && !str_contains($val, 'http') && (str_contains($val, '.ttf') || str_contains($val, '.otf'))) {
        if(config('filesystems.default') == 's3'){
            $val = Storage::disk('s3')->url('public/'.$val);
        }
        else{
            $val = asset('storage/' . $val);
        }
    }
    return $val;
}
function getColorSettingValue($key)
{
    $settings = getSiteSettingValue($key);
    return getColorArray($settings);
}

/**
 * @param $settings
 * @return null[]
 */
function getColorArray($settings): array
{
    if (!$settings)
        return ['color' => null, 'is_with_glass_effect' => null, 'is_no_color' => null];
    $settings = json_decode($settings, true);
    $color = $settings['color'] ?? null;
    $is_with_glass_effect = $settings['is_with_glass_effect'] ?? null;
    $is_no_color = $settings['is_no_color'] ?? null;
    return ['color' => $color, 'is_with_glass_effect' => $is_with_glass_effect, 'is_no_color' => $is_no_color];
}

function getSiteSettingValue($key)
{
    return Cache::remember('site_theme_settings', 30, function () use ($key) {
        $siteSettings = \App\Models\SiteThemeSetting::all();
        $settings = [];
        foreach ($siteSettings as $siteSetting) {
            $settings[$siteSetting->name] = $siteSetting->value; //
        }
        return $settings;
    })[$key] ?? null;
}

function setMailConfig()
{

    $general = GeneralSetting::first();

    $email_setting = $general->mail_config;

    $mailConfig = [
        'transport' => $email_setting->name??null,
        'host' => $email_setting->host??null,
        'port' => $email_setting->port??null,
        'encryption' => $email_setting->enc??null,
        'username' => $email_setting->username??null,
        'password' => $email_setting->password??null,
        'timeout' => null
    ];

    config(['mail.mailers.smtp' => $mailConfig]);
    config(['mail.from.address' => $general->email_from]);
}

#endregion
