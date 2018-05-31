<?php

namespace App\Data\Entities;

use Illuminate\Database\Eloquent\Model;

class HomeEntity extends Model
{
      protected $table = 'hogar';
      protected $primaryKey='id_hogar';

      protected $fillable =[
       'nick_hogar',
       'avatar',    	
       'mail',
       'password',       

   ];
    
}
