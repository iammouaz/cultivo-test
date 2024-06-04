<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\LocalizationHelper;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use App\Models\Language;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class LanguageController extends Controller
{

    public function langManage($lang = false)
    {
        copy_default_languages_files_if_not_existent();
        $pageTitle = __('Language Manager');
        $emptyMessage = __('No language has been added.');
        $languages = Language::orderBy('is_default','desc')->get();
        return view('admin.language.lang', compact('pageTitle', 'emptyMessage', 'languages'));
    }

    public function langStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:languages'
        ]);



        $data = file_get_contents(resource_path('lang/') . 'en.json');
        $json_file = strtolower($request->code) . '.json';
        $path = resource_path('lang/') . $json_file;

        File::put($path, $data);


        $language = new  Language();
        if ($request->is_default) {
            $lang = $language->where('is_default', 1)->first();
            if ($lang) {
                $lang->is_default = 0;
                $lang->save();
            }
        }
        $language->name = $request->name;
        $language->code = strtolower($request->code);
        $language->is_default = $request->is_default ? 1 : 0;
        $language->save();

        $notify[] = ['success', __('Create successfully')];
        return back()->withNotify($notify);
    }

    public function langUpdate(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $language = Language::findOrFail($id);

        if ($request->is_default) {
            $lang = $language->where('is_default', 1)->first();
            if ($lang) {
                $lang->is_default = 0;
                $lang->save();
            }
        }


        $language->name = $request->name;
        $language->is_default = $request->is_default ? 1 : 0;
        $language->save();

        $notify[] = ['success', __('Update successfully')];
        return back()->withNotify($notify);
    }

    public function langDel($id)
    {
        $lang = Language::find($id);
        removeFile(resource_path('lang/') . $lang->code . '.json');
        $lang->delete();
        $notify[] = ['success', __('Language deleted successfully')];
        return back()->withNotify($notify);
    }

    public function langEdit($id)
    {
        $lang = Language::find($id);
        $pageTitle = "Update " . $lang->name . " Keywords";
        $json = file_get_contents(resource_path('lang/') . $lang->code . '.json');
        $list_lang = Language::all();


        if (empty($json)) {
            $notify[] = ['error', __('File not found')];
            return back()->withNotify($notify);
        }
        $json = json_decode($json);

        return view('admin.language.edit_lang', compact('pageTitle', 'json', 'lang', 'list_lang'));
    }

    public function langImport(Request $request)
    {
        $tolang = Language::find($request->toLangid);
        $fromLang = Language::find($request->id);
        $json = file_get_contents(resource_path('lang/') . $fromLang->code . '.json');

        $json_arr = json_decode($json, true);

        file_put_contents(resource_path('lang/') . $tolang->code . '.json', json_encode($json_arr));

        return 'success';
    }

    public function storeLanguageJson(Request $request, $id)
    {
        $lang = Language::find($id);
        $this->validate($request, [
            'key' => 'required',
            'value' => 'required'
        ]);

        $items = file_get_contents(resource_path('lang/') . $lang->code . '.json');

        $reqKey = trim($request->key);

        if (array_key_exists($reqKey, json_decode($items, true))) {
            $notify[] = ['error', "`$reqKey` Already exist"];
            return back()->withNotify($notify);
        } else {
            $newArr[$reqKey] = trim($request->value);
            $itemsss = json_decode($items, true);
            $result = array_merge($itemsss, $newArr);
            file_put_contents(resource_path('lang/') . $lang->code . '.json', json_encode($result));
            $notify[] = ['success', "`".trim($request->key)."` has been added"];
            return back()->withNotify($notify);
        }

    }
    public function deleteLanguageJson(Request $request, $id)
    {
        $this->validate($request, [
            'key' => 'required',
            'value' => 'required'
        ]);

        $reqkey = $request->key;
        $lang = Language::find($id);
        $data = file_get_contents(resource_path('lang/') . $lang->code . '.json');

        $json_arr = json_decode($data, true);
        unset($json_arr[$reqkey]);

        file_put_contents(resource_path('lang/'). $lang->code . '.json', json_encode($json_arr));
        $notify[] = ['success', "`".trim($request->key)."` has been removed"];
        return back()->withNotify($notify);
    }
    public function updateLanguageJson(Request $request, $id)
    {
        $this->validate($request, [
            'key' => 'required',
            'value' => 'required'
        ]);

        $reqkey = trim($request->key);
        $reqValue = $request->value;
        $lang = Language::find($id);

        $data = file_get_contents(resource_path('lang/') . $lang->code . '.json');

        $json_arr = json_decode($data, true);

        $json_arr[$reqkey] = $reqValue;

        file_put_contents(resource_path('lang/'). $lang->code . '.json', json_encode($json_arr));

        $notify[] = ['success', __('Updated successfully')];
        return back()->withNotify($notify);
    }

    public function convertJsonToCsvAndDownLoad($filename)
    {
        // create folder if not exist in storage app/lang/$filename
        $path = 'lang' ;
        if (!Storage::exists($path)) {
            File::makeDirectory(Storage::path($path), 775, true, true);
        }
        //create csv file in storage app/lang/$filename/$filename.csv
        $pathCsv = $path . '/' . $filename . '.csv';
        // get the json file from the storage folder if it exists or from the resources folder
        Storage::exists($path . '/' . $filename . '.json') ? $source = $path . '/' . $filename . '.json' :
            $default_source = base_path('resources/lang/' . $filename . '.json');
        //convert json file in default source to csv file
        if (!isset($source)) {
            if (!File::exists($default_source)) {
                abort(404);
            }
            LocalizationHelper::jsonToCsv($default_source, $pathCsv,$filename, true);
        } else {

            LocalizationHelper::jsonToCsv($source, $pathCsv,$filename);
        }


        return Storage::download($pathCsv, $filename . '.csv');
    }

    public function uploadCsvFileAndConvertToJson(Request $request)
    {
        // Example: Save the uploaded file
        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            $newFileName = $request->input('lang_name');
            if (is_null($newFileName)) {
                $notify[]= ['error', __('lang_name is required')];
                return back()->withNotify($notify) ;
//                throw new Exception(__('lang_name is required'));
            }

//            $fileName = $file->getClientOriginalName(); // You can generate a unique name
            $fileName = $newFileName . '.' . $file->getClientOriginalExtension(); // You can generate a unique name
            if ($file->extension() != 'csv')
                return $notify= ['error'=> __('Only CSV files are allowed')];
            $filenameWithoutExtension = pathinfo($fileName, PATHINFO_FILENAME);

            $path = 'lang';
            Storage::putFileAs($path, $file, $fileName);

            $jsonFileName = pathinfo($fileName, PATHINFO_FILENAME) . '.json';
            $jsonFilePath = $path . '/' . $jsonFileName;

            LocalizationHelper::csvToJson($path . '/' . $fileName,  'lang/' . $filenameWithoutExtension . '.json');

            // Redirect back with a success message
//            return __('CSV file uploaded and processed.');
            $notify[]= ['success', __('CSV file uploaded and processed.')];
            return back()->withNotify($notify);

        } else {
            $notify[]= ['error', __('No CSV file was uploaded.')];
            return back()->withNotify($notify);
//            return __('No CSV file was uploaded.');
        }
    }


    // update display language in users table
    public function updateDisplayLanguage(Request $request)
    {
        $en_id = DB::table('languages')->where('code', 'en')->first()->id;
        $lang_id = $request->language_id ?? $en_id;

        $user = auth()->user();
        if ($user) {
            $user->language_id = $lang_id;
            $user->save();
        }
        $lang_code = DB::table('languages')->where('id', $lang_id)->first()->code??'en';
        session()->put('lang_code', $lang_code);
        return __("language updated successfully");
    }
}
