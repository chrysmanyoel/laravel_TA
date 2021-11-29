<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class jadwalsalon extends Model
{
    public $incrementing = true;
	protected $table = 'jadwalsalon';
	protected $primaryKey = 'id';
	protected $fillable = [
		'id',
		'idsalon',
		'hari',
		'jambuka',
		'jamtutup'
	];
	public $timestamps = false;	

	public function updatejadwalsalon($idsalon, $hari, $jambuka, $jamtutup){
		$qry    = jadwalsalon::where('idsalon', '=', $idsalon)
			->where('hari','=',$hari)
			->get(); 
		if(count($qry) == 0) {
			$usersbaru = new jadwalsalon;
			$usersbaru->idsalon	 = $idsalon;
			$usersbaru->hari	 = $hari;
			$usersbaru->jambuka	 = $jambuka;
			$usersbaru->jamtutup = $jamtutup;
			$usersbaru->save();
		}else{
			$cari = $qry[0];
			$cari->idsalon	 = $idsalon;
			$cari->hari	 	 = $hari;
			$cari->jambuka	 = $jambuka;
			$cari->jamtutup	 = $jamtutup;
			$cari->save();
		}
	}
	
	public function getjadwalsalon($idsalon,$sekarang){
		$dt = jadwalsalon::select('jadwalsalon.*')
					->where('idsalon','=',$idsalon)
					->where('hari','=',$sekarang)
					->get();
					
		return $dt;
	}
	
	public function getjadwalsalon_set($idsalon){
		$dt = jadwalsalon::select('jadwalsalon.*')
					->where('idsalon','=',$idsalon)
					->get();
					
		return $dt;
	}

}
