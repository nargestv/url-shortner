# URL Shortener API

## Index
- [Description](#description)
- [Screenshots](#screenshots)
- [Table Schema](#how-data-is-stored)
- [How To Use It](#usage)
- [Contributing](#contributing)

## Description 

A simple URL Shortener application in Laravel Lumen. The official documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).



## Screenshots



## How data is stored

Data is stored in the urls table. See migration file below for reference.




#### Clone the project:

Once you are into your project folder as specified in your Vagrant file clone the repository:

    git clone 


#### Create .env file:

    mv .env.example .env
    

#### Create migration file:

As shown in previously in the screenshots

```php
    public function up()
    {
        Schema::create('urls', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->string('long_url')->unique()->index();
            $table->string('short_url')->unique()->nullable()->index();
            $table->timestamps();
        });
    }
```

#### Run migrations:

    php artisan migrate

## Usage

How it works and how to use it? This Simple application consist on an API with the following two end points:


     POST http://localhost/api/v1/url/create?long_url={LONG_URL}
    
     GET  http://localhost/{SHORT_URL}

The first end point creates a new shorten link and the second gets the original URLS. If you access through 
the web browser to the shorten link it should redirect you to the intended URL.
