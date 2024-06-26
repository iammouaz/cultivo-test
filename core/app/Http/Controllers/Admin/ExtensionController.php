<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Extension;
use Illuminate\Http\Request;

class ExtensionController extends Controller
{
    public function index()
    {
        $pageTitle = __('Extensions');
        $extensions = Extension::orderBy('status','desc')->get();
        return view('admin.extension.index', compact('pageTitle', 'extensions'));
    }

    public function update(Request $request, $id)
    {
        $extension = Extension::findOrFail($id);

        foreach ($extension->shortcode as $key => $val) {
            $validation_rule = [$key => 'required'];
        }
        $request->validate($validation_rule);

        $shortcode = json_decode(json_encode($extension->shortcode), true);
        foreach ($shortcode as $key => $code) {
            $shortcode[$key]['value'] = $request->$key;
        }

        $extension->shortcode = $shortcode;
        $extension->save();
        $notify[] = ['success', $extension->name . ' has been updated'];
        return redirect()->route('admin.extensions.index')->withNotify($notify);
    }

    public function activate(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        $extension = Extension::findOrFail($request->id);
        $extension->status = 1;
        $extension->save();
        $notify[] = ['success', $extension->name . ' has been activated'];
        return redirect()->route('admin.extensions.index')->withNotify($notify);
    }

    public function deactivate(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        $extension = Extension::findOrFail($request->id);
        $extension->status = 0;
        $extension->save();
        $notify[] = ['success', $extension->name . ' has been disabled'];
        return redirect()->route('admin.extensions.index')->withNotify($notify);
    }
}
