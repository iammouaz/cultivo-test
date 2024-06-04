<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Product;
use App\Models\EmailLog;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Models\WithdrawMethod;
use App\Rules\FileTypeValidate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;





class ManageAdminsController extends Controller
{
    public function allAdmins()
    {
        $pageTitle = __('Manage Admins');
        $emptyMessage = __('No admin found');
        $admins = Admin::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.admin_management.list', compact('pageTitle', 'emptyMessage', 'admins'));
    }
    public function addAdmin()
    {
        $pageTitle = __('Add Admin');
        $info = json_decode(json_encode(getIpInfo()), true);
        $mobile_code = @implode(',', $info['code']);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.admin_management.add_admin', compact('pageTitle','mobile_code','countries'));
    }
    protected function validation($request, $imgValidation){
        $request->validate([
            'name' => 'required|max:50',
            'username' => 'required|max:50',
            'email' => 'nullable|email|max:90|unique:admins,email,',
           
        ]);
    }

    public function regAdmin(Request $request)
    {


        $this->validation($request, 'required');

        try {
        $admin = new Admin();
        $admin->username = $request->username;
        $admin->name = $request->name;
        $admin->password = Hash::make($request->password);
        $admin->email = $request->email;
        $admin->save();

        $notify[] = ['success', __('The account has been added successfully')];
        return redirect()->back()->withNotify($notify);
        } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Failed to create admin.']);
    }
       
    }

   
    public function search(Request $request, $scope)
    {
        $search = $request->search;
        $admins = Admin::where(function ($admin) use ($search) {
            $admin->where('username', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        });
        $pageTitle = '';
        if ($scope == 'active') {
            $pageTitle = 'Active ';
            $admins = $admins->where('status', 1);
        }elseif($scope == 'banned'){
            $pageTitle = 'Banned';
            $admins = $admins->where('status', 0);
        }elseif($scope == 'emailUnverified'){
            $pageTitle = 'Email Unverified ';
            $admins = $admins->where('ev', 0);
        }elseif($scope == 'smsUnverified'){
            $pageTitle = 'SMS Unverified ';
            $admins = $admins->where('sv', 0);
        }elseif($scope == 'withBalance'){
            $pageTitle = 'With Balance ';
            $admins = $admins->where('balance','!=',0);
        }

        $admins = $admins->paginate(getPaginate());
        $pageTitle .= 'Admin Search - ' . $search;
        $emptyMessage = __('No search result found');
        return view('admin.admin_management.list', compact('pageTitle', 'search', 'scope', 'emptyMessage', 'admins'));
    }


    public function detail($id)
    {
        $pageTitle = __('Admin Detail');
        $admin = Admin::findOrFail($id);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.admin_management.detail', compact('pageTitle', 'admin'));
    }


    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        $request->validate([
            'name' => 'required|max:50',
            // 'lastname' => 'required|max:50',
            'email' => 'nullable|email|max:90|unique:admins,email,' . $admin->id,
            // 'mobile' => 'nullable|unique:admins,mobile,' . $admin->id,
            'country' => 'nullable',
            'image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png'])],
            'cover_image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png'])]
        ]);
        $countryCode = $request->country;
        $admin->mobile = $request->mobile;
        $admin->country_code = $countryCode;
        $admin->firstname = $request->firstname;
        $admin->lastname = $request->lastname;
        $admin->email = $request->email;
        $admin->address = [
                            'address' => $request->address,
                            'city' => $request->city,
                            'state' => $request->state,
                            'zip' => $request->zip,
                            'country' => @$countryData->$countryCode->country,
                        ];
        $admin->status = $request->status ? 1 : 0;
        $admin->ev = $request->ev ? 1 : 0;
        $admin->sv = $request->sv ? 1 : 0;
        $admin->ts = $request->ts ? 1 : 0;
        $admin->tv = $request->tv ? 1 : 0;
        if ($request->hasFile('image')) {
            try {
                $old = $admin->image ?: null;
                $admin->image = uploadImageToS3($request->image, imagePath()['profile']['admin']['path'], imagePath()['profile']['admin']['size'], $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', __('Image could not be uploaded.')];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('cover_image')) {
            try {
                $old = $admin->cover_image ?: null;
                $admin->cover_image = uploadImageToS3($request->cover_image, imagePath()['profile']['admin_cover']['path'], imagePath()['profile']['admin_cover']['size'], $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', __('Image could not be uploaded.')];
                return back()->withNotify($notify);
            }
        }
        $admin->save();

        $notify[] = ['success', __('Admin detail has been updated')];
        return redirect()->back()->withNotify($notify);
    }

    public function login($id){
        $admin = Admin::findOrFail($id);
        Auth::guard('admin')->login($admin);
        return redirect()->route('admin.dashboard');
    }

}
