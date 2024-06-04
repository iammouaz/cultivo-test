<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function events()
    {
        $emptyMessage   = __('No events found');
        $categories     = Category::with('events')->where('status', 1)->get();
        $names          = $categories->pluck('name');
        $pageTitle      = request()->search_key ? 'Search Events' : $names->implode(', ');

        $events       = Event::live();
        $events       = $events->where('name', 'like', '%' . request()->search_key . '%')->with('category');

        $allevents    = clone $events->get();

        if (request()->category_id) {
            $events      = $events->where('category_id', request()->category_id);
            $category = Category::find(request()->category_id);
            $pageTitle  = $category->name. ' Auctions';
        }
        $eventid =array();
        $eventid = get_allowed_events(Auth::id());
        $events = $events->paginate(getPaginate(18));

        return view($this->activeTemplate . 'event.filtered', compact('pageTitle', 'emptyMessage', 'events','category', 'allevents','eventid', 'categories'));
    }
}
