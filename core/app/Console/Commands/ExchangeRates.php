<?php

namespace App\Console\Commands;

use App\Models\ExchangeRate;
use App\Models\Extension;
use Illuminate\Console\Command;

class ExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ExchangeRate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Exchange Rates for Specfic Currency';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $extension=Extension::where('act','exchange_rate')->first();
        $shortcode = json_decode(json_encode($extension->shortcode), true);
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "http://api.apilayer.com/fixer/latest?base=USD",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: text/plain",
            "apikey:".$shortcode['app_key']['value']
        ),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET"
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {     
            $error_msg = curl_error($curl); 
            return  $error_msg; 
         } 
        curl_close($curl);
        $response=json_decode($response, true);
        foreach($response['rates'] as $key=>$value){
            $currency=ExchangeRate::where('Currency_Code',$key)->first();
            if($currency){
                $currency->update([
                    'Base_Currency'=>$response['base'],
                    'Exchange_Rate'=>$value,
                    'Exchange_Date'=>$response['date']
                ]);
            }else{
                ExchangeRate::create([
                    'Currency_Code'=>$key,
                    'Base_Currency'=>$response['base'],
                    'Exchange_Rate'=>$value,
                    'Exchange_Date'=>$response['date']
                ]);
            }
           
        }

        return "Get ExchangeRate Successfully";
    }
}
