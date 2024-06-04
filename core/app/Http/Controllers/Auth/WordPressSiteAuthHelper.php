<?php

namespace App\Http\Controllers\Auth;

class WordPressSiteAuthHelper
{
    static public function check($username, $password, $is_email = 0)
    {
        $url = "https://allianceforcoffeeexcellence.org/wp-admin/admin-ajax.php";
        $client = new \GuzzleHttp\Client();
        $data = [
            'action' => 'm_cultivo_check_user_api', 'username' => $username, 'password' => $password, 'is_email' => $is_email
        ];
        $params = [
            'form_params' => $data,
            'headers' => [
                'Accept' => 'application/json'
            ]
        ];

        $res = $client->post($url, $params);

        $code =  $res->getStatusCode();

        if($code == 200){
            $decodedBody = json_decode($res->getBody()->getContents(), true);

           if($decodedBody['status'] == 200){
               return $decodedBody['data'];
           }

        }

        return false;


    }
}
