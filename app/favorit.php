<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class favorit extends Model
{
    public $incrementing = false;
	protected $table = 'favorit';
	protected $primaryKey = 'idfavorit';
	protected $fillable = [
		'idfavorit',
		'idsalon',
		'username'
	];
	public $timestamps = false;
	
	public function getallfavoritjoinuser($username){
		$dt = favorit::select('favorit.*', 'salon.namasalon','salon.kota', 'salon.alamat','salon.username as usernamesalon' ,'users.foto')
				->join('salon', 'salon.id', '=', 'favorit.idsalon')
				->join('users', 'users.username', '=', 'salon.username')
				->where('favorit.username','=',$username)
				->get();
		return $dt;
	}
	
	public function deletefav($idfavorit){
		$dt = favorit::where('idfavorit','=',$idfavorit)->delete();
		
		return $dt;
	}
	
	
	
}

