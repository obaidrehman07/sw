<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Client;
use Validator;

class MovieController extends Controller
{
    private $_token = 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI3YjcxMWJhMDQ4MGE3ODJhNDA2NDM2N2U1MWNmMTE5YiIsInN1YiI6IjY0ZDM3OGRkYmYzMWYyMDFjZDRkNTNkZiIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.mInP0VvQXZENEjd0vQotuudBEvdJy0qiK50PxbkKFHE';

    private $api_key = '7b711ba0480a782a4064367e51cf119b';

    public function index(){
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $get_api = $this->urlAction('trending/movie/day', $this->_token, $this->api_key, $current_page);
        $data['movies'] = $get_api;
        $data['current_page_now'] = $current_page;
        $data['add_query_string'] = null;

        // echo "<pre>"; print_r($data); die;
        // return response()->json($data);

        return view('movie.movies', $data);
    }


    public function searchMovies(){
        $query = isset($_GET['query']) ? $_GET['query'] : null;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $get_api = $this->searchAction('search/movie', $this->_token, $this->api_key, $query, $current_page);
        $data['movies'] = $get_api;
        $data['current_page_now'] = $current_page;
        $data['add_query_string'] = isset($_GET['query']) ? '&query='.$_GET['query'] : null;

        // echo "<pre>"; print_r($data); die;
        // return response()->json($data);

        return view('movie.movies', $data);
    }


    private function urlAction($route, $token, $api_key, $page=1){
        $getApi = new Client();
        try {
            $response = $getApi->get('https://api.themoviedb.org/3/'. $route .'?api_key=' . $api_key . "&page=" . $page . "&language=en-US", [
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


    private function searchAction($route, $token, $api_key, $query, $page=1, $include_adult=false){
        $getApi = new Client();
        try {
            $response = $getApi->get('https://api.themoviedb.org/3/'. $route .'?query=' . $query . '&api_key=' . $api_key . "&page=" . $page . "&include_adult=".$include_adult."&language=en-US", [
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
