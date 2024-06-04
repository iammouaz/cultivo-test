<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $pageTitle = __('All Categories');
        $emptyMessage = __('No category found');
        $categories = Category::latest()->with('events')->paginate(getPaginate());

        return view('admin.category.index', compact('pageTitle', 'emptyMessage', 'categories'));
    }

    public function saveCategory(Request $request, $id=0)
    {
        $request->validate([
            'name' => 'required',
            'description'=>'required',
            'icon' => 'required'
        ]);

        $category = new Category();
        $notification =  __('Category created successfully');

        if($id){
            $category = Category::findOrFail($id);
            $category->status = $request->status ? 1 : 0;
            $notification = __('Category updated successfully');
        }

        $category->name = $request->name;
        $category->description = $request->description;
        $category->icon = $request->icon;
        $category->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);

    }
}
