<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\BidHistory;
use App\Models\BidsHistoryAllData;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;
class ExportController extends Controller
{
    public function BidExport(Request $request){
        set_time_limit(0);
        $event_id = $request->input('event_id');
        $product_id = $request->input('product_id');
        if($event_id==0){
        $notify[] = ['error', __('Unable to Export all Events Data')];
        return back()->withNotify($notify);
        }

        // $path = public_path('/exports/Bids.csv');
        if ($product_id) {
            $logs = BidsHistoryAllData::where('product_id', $product_id)->orderby('id','desc');
            // $query = sprintf("SELECT 'ID','user_id','product_id','event_id','firstname','lastname','company_name','billing_country','billing_state','billing_city','billing_address_1','event_name','product_name','new_bid','previous_bid','user_previous_bid','created_at','updated_at','rank' UNION select * from bids_history_all_data where product_id=".$product_id." INTO OUTFILE '%s' FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n'",$path);
        } else if ($event_id) {
            //  $product_ids = Product::where('event_id', $event_id)->pluck('id')->toArray();
            // $logs->whereIn('product_id', $product_ids);
            $logs = BidsHistoryAllData::where('event_id', $event_id)->orderby('id','desc');
        }
           
            // $query = sprintf("SELECT 'ID','user_id','product_id','event_id','firstname','lastname','company_name','billing_country','billing_state','billing_city','billing_address_1','event_name','product_name','new_bid','previous_bid','user_previous_bid','created_at','updated_at','rank' UNION select * from bids_history_all_data where event_id=".$event_id." INTO OUTFILE '%s' FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n'",$path);
        // else{
        //     $query = sprintf("SELECT 'ID','user_id','product_id','event_id','firstname','lastname','company_name','billing_country','billing_state','billing_city','billing_address_1','event_name','product_name','new_bid','previous_bid','user_previous_bid','created_at','updated_at','rank' UNION select * from bids_history_all_data INTO OUTFILE '%s' FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n'",$path);
        // }
        // $logs=$logs->get();
        $logs=$this->DataGenerator($logs);
        (new FastExcel($logs))->export('Bids.csv');
        return response()->download(public_path('Bids.csv'));
        // $filename="\Bids.csv";
        // if (File::exists(public_path('/exports/Bids.csv'))) {
        //     File::delete(public_path('/exports/Bids.csv'));
        // }
        // DB::statement($query);
        // session()->put('filename','Bids.csv');
        // $notify[] = ['success', 'Export Successfully'];
        // return back()->withNotify($notify);
        // $fileName = 'Bids.csv';

        //      $headers = array(
        //          "Content-type"        => "text/csv",
        //          "Content-Disposition" => "attachment; filename=$fileName",
        //          "Pragma"              => "no-cache",
        //          "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        //          "Expires"             => "0"
        //      );

        //      $columns = array('id','user','company','country','state','city','Address 1','Event Name','Rank', 'product', 'new bid', 'previous bid','user previous bid','created at','updated at');

        //      $callback = function() use($logs,$columns) {
        //          $file = fopen('php://output', 'w');
        //          fputcsv($file, $columns);
                //  $logs->chunk(500, function($logs) use($file) {
                //  foreach ($logs as $log) {
                //      $row['id']  = $log->id;
                //      $row['user']  = $log->user->fullname;
                //      $row['company']  = $log->user->company_name;
                //      $row['country']=  isset($log->user->country) ? $log->user->country->Name : '' ;
                //      $row['state']=$log->user->billing_state;
                //      $row['city']=$log->user->billing_city;
                //      $row['Address']=$log->user->billing_address_1;
                //      $row['Event Name']=$log->product->event->name.' '.$log->product->event->sname;
                //      if(count($log->product->product_specification)>0){
                //         foreach($log->product->product_specification as $spec){
                //             if(strtoupper($spec->spec_key)=='RANK'){
                //                 $row['Rank']=$spec->Value;
                //             }
                //          }
                //      }
                //      $row['product']=$log->product->name;
                //      $row['new bid']=$log->new_bid;
                //      $row['previous bid']=$log->previous_bid;
                //      $row['user previous bid']=$log->user_previous_bid;
                //      $row['created at']=$log->created_at;
                //      $row['updated at']=$log->updated_at;
                //      fputcsv($file, array($row['id'] ,$row['user'], $row['company'],$row['country'], $row['state'],$row['city'], $row['Address'],$row['Event Name'],$row['Rank'], $row['product'], $row['new bid'], $row['previous bid'],$row['user previous bid'],$row['created at'],$row['updated at']));
                //  }
                // });

            //      fclose($file);
            //  };

            //  return response()->stream($callback, 200, $headers);
    }
    public function ProductBidExport($id){
        $product=Product::find($id);
        $fileName = $product->name.'.csv';
        $logs =Bid::where('product_id', $id)->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('id','user', 'amount', 'bid time');

        $callback = function() use($logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($logs as $log) {
                $row['id']  = $log->id;
                $row['user']  = $log->user->fullname;
                $row['amount']=$log->amount;
                $row['bid time']=$log->created_at;
                fputcsv($file, array($row['id'] ,$row['user'],  $row['amount'],  $row['bid time']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    function DataGenerator($data) {
        foreach ($data->cursor() as $row) {
            yield $row->setAppends([]);
        }
    }

}
