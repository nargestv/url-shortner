<?php 
namespace App\Models;
 
use DB;
use Illuminate\Database\Eloquent\Model;
 
class Url extends Model
{ 

   protected $table = "urls";

   protected $fillable = ['long_url', 'short_url', 'created_at', 'updated_at']; 
   

   /**
    * Get total count of url's records in the database
    *
    * @return int
    */
   public function getTotalCount(){

     return Url::all()->count();

   }

}
