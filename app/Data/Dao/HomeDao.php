<?php

namespace App\Data\Dao;

use App\Data\Entities\HomeEntity;

class HomeDao
{
	
	public static function all()
	{
		$homes = HomeEntity::all();
		if($homes->count() > 0){
			return $homes;
		}
		return [];
	}
	
	public static function byId($id)
	{
		$home = HomeEntity::find($id)->first();
		if($home){
			return $home;
		}
		return null;
	}
	
	public static function save($nick_hogar, $avatar, $mail, $password , $id = null)
	{
		$home = null;
		if($id != null){
			$home = HomeEntity::find($id)->first();
			if(!$home){
				return null;
			}
		}else{
			$home = new HomeEntity();
		}	
		$home->nick_hogar = $nick_hogar;
		$home->avatar = $avatar;
        $home->mail = $mail;
        $home->password = $password;
        if($home->save())
        {
			return $home;
		}
		return null;
	}
    
    public static function getMail($mail)
    {
        $mailHome = HomeEntity::where('mail', '=',$mail)->first();
		if($mailHome){
			return $mailHome;
		}
		return null;
    }

}