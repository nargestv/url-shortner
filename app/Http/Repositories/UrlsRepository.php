<?php

namespace App\Http\Repositories;

class UrlsRepository extends Repository
{
    public function model(){
        return \App\Models\Url::class;
    }
}
