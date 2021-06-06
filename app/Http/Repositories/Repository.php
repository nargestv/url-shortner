<?php

namespace App\Http\Repositories;

abstract class Repository 
{
    protected $model;

    public function __construct()
    {
        $this->model = app($this->model());
    }

    abstract public function model();

     /**
    * Finds a long url in the database
    *
    * @param string $longUrl the long url
    *
    * @return array|null
    */
   public function findByLongUrl($longUrl = null){
        return $this->model->where('long_url','=', $longUrl)->first();
    }

      /**
    * Creates a new resource url in the database
    *
    * @param array $data contains the data to be inserted
    * @param string $shortUrl the given short url
    *
    * @return array|null
    */   
   public function insertInDatabase($data = null, $shortUrl){
      
        return $this->model->create(array_merge($data, ['short_url' => $shortUrl]));
    }

   /**
    * Find an url by its short url
    *
    * @param string $shortUrl a given short url
    * 
    * @return array|null
    */
    public function findShortUrl($shortUrl = null){
        return $this->model->where('short_url','=', $shortUrl)->first();
     }
}
