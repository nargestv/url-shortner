<?php
namespace App\Http\Controllers;

use App\Url;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL as UrlFacade;
use Illuminate\Routing\UrlGenerator;
use App\Http\Repositories\UrlsRepository;
use Illuminate\Support\Str;

/**
 * UrlShortenerController class handles logic for the Url Shortener API
 * 
 * 
 */
class UrlShortenerController extends Controller
{
    /**
     *  A Url Model Instance
     * 
     * @var string
     */
    private $urlModel;

    /**
     *  The short Url
     * 
     * @var string
     */
    public $shortUrl;

    /**
     * Represents the url database record
     *
     * @var array
     */
    public $url = array();

    public function __construct(UrlsRepository $urlsRepository){
        $this->urlModel = $urlsRepository;
    }
 

    /**
     * Redirects to the original long url if exists
     *
     * @param string $shortUrl
     * @return void
     */
    public function createLink(Request $request)
    {
        $this->longUrl = $request->get('long_url');

        if(!$this->isValidUrl($request)){
            return response()->json(array('results' => $this->result, 'status_code' => 422),422);
        }

        if($this->urlExists($this->longUrl)){
            $this->message = "Resource found in database";
            $this->url->short_url = $this->generateShortenUrl();
            return response()->json(array('results' => $this->url,'message' => $this->message, 'status_code' => 200),200);
        }

        $this->shortUrl = $this->createShortUrl();
        $this->url = $this->urlModel->insertInDatabase($request->all(), $this->shortUrl);

        if(!$this->url){
            $this->message = "Could not create resource";
            return response()->json(array('message' => $this->message, 'status_code' => 404),404);
        }
        /****TODO: add expire date */
        $this->url->short_url = $this->generateShortenUrl();
        $this->message = "resource succesfully created";      
        return response()->json(array('results' =>  $this->url,'message' => $this->message, 'status_code' => 201),201); 
    }

     /**
     * Validates an url is valid
     *
     * @param  Illuminate\Http\Request $request
     * @return boolean
     */
    public function isValidUrl($request){

        $validator = \Validator::make($request->all(),
         [
            'long_url' => 'required|url|max:2048',
         ]);

        if ($validator->fails()) {

            $this->result = [
                'error' => [
                    'message' => $validator->errors()
                ]
            ];

            return false;

        }      
        
        return true;
        
    }


    /**
     * Determines if an url exist in the database
     *
     * @param string $longUrl the long url
     * 
     * @return boolean
     */
    public function urlExists($longUrl){

        $this->url = $this->urlModel->findByLongUrl($longUrl);

        if(!isset($this->url)){
            return false;   
        }
        return true;

    }

    /**
     * Generates the shorten url
     *
     * @return string
     */
    public function generateShortenUrl(){

       return $this->getAppBaseUrl().$this->url->short_url;
    }

     /**
    * Gets the application base URL 
    *
    * @return void
    */  
    public function getAppBaseUrl(){
        
        return UrlFacade::to('/').'/';
    }
    
    /**
     * Generates a new short url
     *
     * @return string
     */
    public function createShortUrl(){

        $this->shortUrl = Str::random(6);

        if(!$this->shortUrlExists($this->shortUrl)){
            return $this->shortUrl;
        }

        return $this->createShortUrl();

    }
     /**
     * Determines if the given short url exist in the database
     *
     * @param string $shortUrl
     * 
     * @return boolean
     */
    public function shortUrlExists($shortUrl){
        
        if(!$this->urlModel->findShortUrl($shortUrl)){
            return false;
        }
        return true;
    }

   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shortenLink($short_url)
    {
        $short_url = $this->urlModel->findShortUrl($short_url);
        return redirect($short_url->long_url);
    }
}
