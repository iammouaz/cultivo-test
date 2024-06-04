<?php

namespace App\Http\Controllers\Admin;


class LogController extends AdminController
{
    public function index(){
        $pageTitle  = "Log Viewer";
        return view("vendor.log-viewer.log-view", compact('pageTitle'));
    }
}
