<?php

namespace  App\Util;

class ValidatorUtil 
{
    public static function checkMail($mail)
    {
         $Sintaxis='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';

         $homeMail = null;

          if(preg_match($Sintaxis,$mail))
          {
            $homeMail = $mail;
           
            return $homeMail;
          }else{
           
            return null;
          }
            
    }
    
 
}