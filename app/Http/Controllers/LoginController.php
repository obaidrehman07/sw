<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Client;
use Validator;

class LoginController extends Controller
{
    private $api_key = '7b711ba0480a782a4064367e51cf119b';
    private $_token = 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI3YjcxMWJhMDQ4MGE3ODJhNDA2NDM2N2U1MWNmMTE5YiIsInN1YiI6IjY0ZDM3OGRkYmYzMWYyMDFjZDRkNTNkZiIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.mInP0VvQXZENEjd0vQotuudBEvdJy0qiK50PxbkKFHE';

    public function index(){
        $data = $this->actionURL('authentication/guest_session/new', $this->_token);
        return response()->json($data);
    }

    public function loginAsGuest(){
        $data = $this->actionURL('authentication/token/new', $this->_token);
        return response()->json($data);
    }

    
    private function actionURL($route, $token){
        $getApi = new Client();
        try {
            $response = $getApi->get('https://api.themoviedb.org/3/'. $route  . "?language=en-US", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            return $data;
            // return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching data.'], 500);
        }
    }



}
