<?php

namespace App\Http\Controllers;

use Redirect;
use Carbon\Carbon;
use App\Models\Page;
use App\Models\Admin;
use App\Models\Event;
use App\Models\Product;
use App\Models\Category;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\Merchant;
use App\Models\OfferSheet;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\Config;


class SiteController extends Controller
{
    protected $blogUrl;

    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
        $this->blogUrl = "https://mcultivo.beehiiv.com";
    }

    public function index()
    {
        $pageTitle = __('Home');
        // $sections = Page::where('tempname',$this->activeTemplate)->where('slug','home')->first();
        $enableMcHomePage = config('app.enable_mc_home_page');
        if($enableMcHomePage){
        return view($this->activeTemplate . 'home', compact('pageTitle'));
        }
        // return redirect()->route('buyers');
        $event=Event::find(getNewAuctionId());
        return redirect()->route('event.details', [$event->id, slug($event->name)]);
    }

    public function pages($slug)
    {
        $page = Page::where('tempname', $this->activeTemplate)->where('slug', $slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        return view($this->activeTemplate . 'pages', compact('pageTitle', 'sections'));
    }

    public function contact()
    {
        $pageTitle = __("Contact Us");
        $page = Page::where('tempname', $this->activeTemplate)->where('slug', 'contact')->first();
        $sections = $page->secs;
        return view($this->activeTemplate . 'contact', compact('pageTitle', 'sections'));
    }


    public function contactSubmit(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|max:191',
            'email' => 'required|max:191',
            'subject' => 'required|max:100',
            'message' => 'required',
        ]);

        $random = getNumber();

        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id() ?? 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = 2;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = 0;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title = __('A new support ticket has opened ');
        $adminNotification->click_url = urlPath('admin.user.ticket.view', $ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->supportticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ['success', __('ticket created successfully!')];

        return redirect()->route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return redirect()->back();
    }

    public function blogs()
    {
        // Instruction from mcultivo to redirect all traffic to a new, external blog
        return Redirect::to($this->blogUrl);
        // $pageTitle      = __('Blog Posts');
        // $emptyMessage   = __('No blog post found');
        // $blogs          = Frontend::where('data_keys', 'blog.element')->latest()->paginate(getPaginate());
        // $page           = Page::where('tempname', $this->activeTemplate)->where('slug', 'blog')->first();
        // $sections       = $page->secs;

        // return view($this->activeTemplate . 'blogs', compact('pageTitle', 'emptyMessage', 'blogs', 'sections'));
        // return view($this->activeTemplate . 'blogs', compact('pageTitle', 'emptyMessage', 'blogs', 'sections'));
    }

    public function blogDetails($id, $slug)
    {
        // Instruction from mcultivo to redirect all traffic to a new, external blog
        return Redirect::to($this->blogUrl);
        // $pageTitle = __('Blog Details');
        // $blog = Frontend::where('id', $id)->where('data_keys', 'blog.element')->firstOrFail();
        // $recentBlogs = Frontend::where('data_keys', 'blog.element')->where('id', '!=', $blog->id)->latest()->limit(10)->get();
        // Instruction from mcultivo to redirect all traffic to a new, external blog
        return Redirect::to($this->blogUrl);
        // $pageTitle = __('Blog Details');
        // $blog = Frontend::where('id', $id)->where('data_keys', 'blog.element')->firstOrFail();
        // $recentBlogs = Frontend::where('data_keys', 'blog.element')->where('id', '!=', $blog->id)->latest()->limit(10)->get();

        // return view($this->activeTemplate . 'blog_details', compact('blog', 'pageTitle', 'recentBlogs'));
        // return view($this->activeTemplate . 'blog_details', compact('blog', 'pageTitle', 'recentBlogs'));
    }

    public function cookieAccept()
    {
        // session()->put('cookie_accepted',true);
        $cookie = cookie('cookie_accepted', true, 60 * 24 * 365 * 10, null, null, null, false);
        return back()->withCookie($cookie);
    }

    public function placeholderImage($size = null)
    {
        $imgWidth = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text = $imgWidth . '×' . $imgHeight;
        $fontFile = realpath('assets/font') . DIRECTORY_SEPARATOR . 'RobotoMono-Regular.ttf';
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 175, 175, 175);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function policy($id)
    {
        $page = Frontend::where('id', $id)->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle = $page->data_values->title;
        $description = $page->data_values->details;
        return view($this->activeTemplate . 'policy', compact('pageTitle', 'description'));
    }

    public function merchants()
    {
        $pageTitle = __('Merchant List');
        $emptyMessage = __('No merchant found');
        $merchants = Merchant::where('status', 1)->paginate(getPaginate());

        return view($this->activeTemplate . 'merchants', compact('pageTitle', 'emptyMessage', 'merchants'));
    }

    public function adminProfile($id, $name)
    {
        $pageTitle = __('Merchant Profile');
        $merchant = Admin::findOrFail($id);
        $products = Product::live()->where('admin_id', $id)->paginate(getPaginate());
        $admin = true;

        return view($this->activeTemplate . 'merchant_profile', compact('pageTitle', 'merchant', 'products', 'admin'));
    }

    public function merchantProfile($id, $name)
    {
        $pageTitle = __('Merchant Profile');
        $merchant = Merchant::findOrFail($id);
        $products = Product::live()->where('merchant_id', $id)->paginate(getPaginate());
        $admin = false;

        return view($this->activeTemplate . 'merchant_profile', compact('pageTitle', 'merchant', 'products', 'admin'));
    }

    public function aboutUs()
    {
        $pageTitle = __('About Us');
        $page = Page::where('tempname', $this->activeTemplate)->where('slug', 'about-us')->first();
        $sections = $page->secs;
        return view($this->activeTemplate . 'about_us', compact('pageTitle', 'sections'));
    }

    public function externalIFrame(Request $request)
    {
        $link = $request->input('link');
        $pageTitle = __(''); // Add your page title here if needed
        $page = Page::where('tempname', $this->activeTemplate)->where('slug', 'external')->first();
        return view($this->activeTemplate . 'external', compact('pageTitle', 'link'));
    }
    function adRedirect($hash)
    {
        $id = decrypt($hash);
        $ad = Advertisement::findOrFail($id);
        $ad->click += 1;
        $ad->save();
        if ($ad->type == 'image') {
            return redirect($ad->redirect_url);
        } else {
            return back();
        }
    }

    public function categories()
    {
        $pageTitle = __('All Categories');
        $emptyMessage = __('No category found');
        $categories = Category::where('status', 1)->paginate(getPaginate());

        return view($this->activeTemplate . 'product.categories', compact('pageTitle', 'emptyMessage', 'categories'));
    }

    public function liveProduct()
    {
        $pageTitle = __('Live Products');
        $emptyMessage = __('No live product found');
        $products = Product::live()->latest()->paginate(getPaginate());

        return view($this->activeTemplate . 'product.live_products', compact('pageTitle', 'emptyMessage', 'products'));
    }

    public function upcomingProduct()
    {
        $pageTitle = __('Upcoming Products');
        $emptyMessage = __('No upcoming product found');
        $products = Product::upcoming()->latest()->paginate(getPaginate());

        return view($this->activeTemplate . 'product.upcoming_products', compact('pageTitle', 'emptyMessage', 'products'));
    }

    public function solution()
    {
        $pageTitle = __('Solution');
        return view($this->activeTemplate . 'solution', compact('pageTitle'));
    }


    public function producers()
    {
        $pageTitle = __('Producers');
        return view($this->activeTemplate . 'producers', compact('pageTitle'));
    }

    public function buyers()
    {

        $offer_sheets = OfferSheet::latest()->paginate(getPaginate());



        $pageTitle = __('Buyers');

        $sections = Page::where('tempname', $this->activeTemplate)->where('slug', 'buyers')->first();

        $policy_id = get_policy_id();

        return view($this->activeTemplate . 'buyers', compact('pageTitle', 'sections', 'policy_id', 'offer_sheets'));
    }
}
