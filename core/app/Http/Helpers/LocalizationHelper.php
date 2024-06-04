<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;

class LocalizationHelper
{


    public static function jsonToCsv($source, $dest,$fileName,$default_source=false)
    {
        if (config('filesystems.default') == 's3') {
            try {
                $client = new S3Client([
                    'version' => config('sqs.version'),
                    'region' => config('filesystems.disks.s3.region'),
                    'credentials' => [
                        'key' => config('filesystems.disks.s3.key'),
                        'secret' => config('filesystems.disks.s3.secret'),
                    ],
                ]);
                $client->registerStreamWrapper();
            }
            catch (\Exception $e){
                log::error($e->getMessage());
                $notify= ['error', $e->getMessage()];
                return back()->withNotify($notify);
            }

            if($default_source){
                $json = file_get_contents($source);
            }else{
                $json = file_get_contents('s3://' . config('filesystems.disks.s3.bucket') . '/' . $source);
            }
            $data = json_decode($json, true);
            //add key from default language from resources/lang/en.json
            //thane if there is  json file in the storage folder we get the json file from the resources folder
            $resources = base_path('resources/lang/' . $fileName . '.json');
            if (file_exists($resources)) {
                $jsonString = file_get_contents($resources);
                $jsonArray = json_decode($jsonString, true);

                foreach ($jsonArray as $key => $value) {
                    // Assuming notSet() is a custom function to check if key doesn't exist
                    if (!isset($data[$key])) {
                        $data[$key] = $value;
                    }
                }
            }

            $fp = fopen('s3://' . config('filesystems.disks.s3.bucket') . '/' . $dest, 'w');
        }
        if(config('filesystems.default')=='local'){
            if($default_source){
                $json = file_get_contents($source);
            }else{
                $json = file_get_contents(Storage::path($source));
            }
            $data = json_decode($json, true);
            //thane if there is  json file in the storage folder we get the json file from the resources folder
            $resources = base_path('resources/lang/' . $fileName . '.json');
            if (file_exists($resources)) {
                $jsonString = file_get_contents($resources);
                $jsonArray = json_decode($jsonString, true);

                foreach ($jsonArray as $key => $value) {
                    // Assuming notSet() is a custom function to check if key doesn't exist
                    if (!isset($data[$key])) {
                        $data[$key] = $value;
                    }
                }
            }

            $fp = fopen(Storage::path($dest), 'w');
        }

        // Write CSV header
        $header = ['Key','Value'];

        if (!is_array($header)) {
            fclose($fp);
            return; // Header must be an array
        }
        fputcsv($fp, $header);
        foreach ($data as $key => $value) {
            if ($key !== 'header') {
                if (is_array($value)) {
                    fputcsv($fp, $value);
                } else {
                    fputcsv($fp, [$key, $value]);
                }
            }
        }
        fclose($fp);
    }

    // function to convert file en.csv to en.json
    public static function csvToJson($source, $dest)
    {
        if (config('filesystems.default') == 's3') {
            try {
                $client = new S3Client([
                    'version' => config('sqs.version'),
                    'region' => config('filesystems.disks.s3.region'),
                    'credentials' => [
                        'key' => config('filesystems.disks.s3.key'),
                        'secret' => config('filesystems.disks.s3.secret'),
                    ],
                ]);
                $client->registerStreamWrapper();
                $fp = fopen('s3://' . config('filesystems.disks.s3.bucket') . '/' . $source, 'r');
            }
            catch (\Exception $e){
                log::error($e->getMessage());
                return $notify= ['error', $e->getMessage()];
            }

        }
        if(config('filesystems.default')=='local'){
            $fp = fopen(Storage::path($source), 'r');
        }
        $json = [];
        $header = fgetcsv($fp);
        while ($row = fgetcsv($fp)) {
            $json[$row[0]] = $row[1];
        }
        fclose($fp);
        file_put_contents(resource_path($dest), json_encode($json, JSON_PRETTY_PRINT));

//        Storage::put($dest, json_encode($json, JSON_PRETTY_PRINT));
    }
}
