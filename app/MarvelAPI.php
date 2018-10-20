<?php

namespace App;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;

class MarvelAPI extends Model
{
    protected $key;
    protected $secret;
    protected $client;

    function __construct()
    {
        $this->key = config('app.marvel_key');
        $this->secret = config('app.marvel_secret');
        $this->client = new Client([
            'base_uri' => 'http://gateway.marvel.com/v1/public/'
        ]);
    }

    function getComics($numberPerPage, $page, $params)
    {
        $minutes = 60 * 12;
        $data = Cache::remember(
            'comics-' . $numberPerPage . '-' . $page,
            $minutes,
            function () use ($numberPerPage, $page, $params) {
                $currentTime = time();
                $response = $this->client
                    ->request('GET', 'comics', [
                        'query' => array_merge($params, [
                            'apikey' => $this->key,
                            'ts' => $currentTime,
                            'hash' => md5(
                                $currentTime . $this->secret . $this->key
                            ),
                            'limit' => $numberPerPage,
                            'offset' => $page * $numberPerPage
                        ])
                    ])
                    ->getBody()
                    ->getContents();
                try {
                    //return json_decode($response)->data->results;
                    return json_decode($response);
                } catch (Exception $e) {
                    return false;
                    throw new Exception('Comic update failed!');
                }
            }
        );
        return $data;
    }
}
