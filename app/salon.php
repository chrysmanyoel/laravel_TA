<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class salon extends Model
{
    public $incrementing = false;
	protected $table = 'salon';
	protected $primaryKey = 'id';
	protected $fillable = [
		'id',
		'idsalon',
		'username',
		'namasalon',
		'alamat',
		'kota',
		'telp',
		'pembayaran',
		'diskon',
		'longitude',
		'latitude',
		'keterangan',
		'status',
		'kategori',
		'rating',
		'ulasan'
	];
	public $timestamps = false;
	
	public function insertsalon($username){
		$usersbaru = new salon;
		$usersbaru->id	 		= 0;
		$usersbaru->username	= $username;
		$usersbaru->namasalon	= "-";
		$usersbaru->alamat	 	= "-";
		$usersbaru->kota	 	= "-";
		$usersbaru->telp	 	= 0;
		$usersbaru->longitude   = 0;
		$usersbaru->latitude	= 0;
		$usersbaru->keterangan  = "-";
		$usersbaru->status   	= "aktif";
		$usersbaru->kategori   	= "-";
		$usersbaru->rating   	= 0;
		$usersbaru->ulasan   	= 0;
		$usersbaru->save();
	}
		
	public function getallsalon(){
		$dt = salon::select('salon.*')
					->get();
		return $dt;
	}
	
	public function getidsalon($username){
		$dt = salon::select('salon.*')
					->where('username','=',$username)
					->get();
		return $dt;
	}
	
	public function updatesalon($username, $namasalon, $alamat, $kota, $telp, $latitude, $longitude, $keterangan, $status, $kategori){
		$cari = salon::where('username','=',$username)->first();
		$cari->namasalon 	= $namasalon;
		$cari->alamat	 	= $alamat;
		$cari->kota	 		= $kota;
		$cari->telp	 		= $telp;
		$cari->latitude 	= $latitude;
		$cari->longitude 	= $longitude;
		$cari->keterangan	= $keterangan;
		$cari->status		= $status;
		$cari->kategori		= $kategori;
		$cari->save();
	}
	
	public function statussalon($username, $status){
		$cari = salon::where('username','=',$username)->first();
		$cari->status		= $status;
		$cari->save();
	}
	
	public function findidsalon($temp){
		$cari = salon::select('id')
				->where('username','=',$temp)
				->get();
		return $cari;
	}
	
	public function carisalon($username,$kategori){
		$hari = date("Y-m-d");
		$timestamp = strtotime($hari);
		$day = date('l', $timestamp);
		if($day ==  'Monday'){
			$sekarang = "senin";
		}else if($day ==  'Tuesday'){
			$sekarang = "selasa";
		}else if($day ==  'Wednesday'){
			$sekarang = "rabu";
		}else if($day ==  'Thursday'){
			$sekarang = "kamis";
		}else if($day ==  'Friday'){
			$sekarang = "jumat";
		}else if($day ==  'Saturday'){
			$sekarang = "sabtu";
		}else if($day ==  'Sunday'){
			$sekarang = "minggu";
		}
		
		$temp = explode(" ",$kategori);
		//ngecek kalo username ada tapi kat kosong
		if($kategori == '' && $username != ''){
			 $dt = salon::select('salon.*', 'users.foto','jadwalsalon.jambuka','jadwalsalon.hari','jadwalsalon.jamtutup')
				->join('users', 'users.username', '=', 'salon.username')
				->join('jadwalsalon', 'jadwalsalon.idsalon', '=', 'salon.id')
				->where('salon.namasalon', 'like', '%' . $username . '%')
				->where('jadwalsalon.hari','=',$sekarang)
				->distinct()
				->orderBy('salon.username', 'asc')
				->get();
		}
		//ngecek kalo username ada tapi kat ada1
		else if(count($temp) == 1 && $username != ''){
			$dt = salon::select('salon.*', 'users.foto','jadwalsalon.jambuka','jadwalsalon.hari','jadwalsalon.jamtutup')
				->join('users', 'users.username', '=', 'salon.username')
				->join('jadwalsalon', 'jadwalsalon.idsalon', '=', 'salon.id')
				->where('salon.namasalon', 'like', '%' . $username . '%')
				->where('salon.kategori','=',$kategori)
				->where('jadwalsalon.hari','=',$sekarang)
				->distinct()
				->orderBy('salon.username', 'asc')
				->get();
		}
		//ngecek kalo username ada tapi kat ada2
		else if(count($temp) == 2 && $username != ''){
			$dt = salon::select('salon.*', 'users.foto','jadwalsalon.jambuka','jadwalsalon.hari','jadwalsalon.jamtutup')
					->join('users', 'users.username', '=', 'salon.username')
					->join('jadwalsalon', 'jadwalsalon.idsalon', '=', 'salon.id')
					->where('salon.namasalon', 'like', '%' . $username . '%')
					->where('jadwalsalon.hari','=',$sekarang)
					->where('salon.kategori','=',$temp[0])
					->Orwhere('salon.kategori','=',$temp[1])
					->where('salon.namasalon', 'like', '%' . $username . '%')
					->where('jadwalsalon.hari','=',$sekarang)
					->distinct()
					->orderBy('salon.username', 'asc')
					->get();
		}
		//ngecek kalo username ada tapi kat ada3
		else if(count($temp) == 3 && $username != ''){
			$dt = salon::select('salon.*', 'users.foto','jadwalsalon.jambuka','jadwalsalon.hari','jadwalsalon.jamtutup')
				->join('users', 'users.username', '=', 'salon.username')
				->join('jadwalsalon', 'jadwalsalon.idsalon', '=', 'salon.id')
				->where('salon.namasalon', 'like', '%' . $username . '%')
				->where('jadwalsalon.hari','=',$sekarang)
				->where('salon.kategori','=',$temp[0])
				->Orwhere('salon.kategori','=',$temp[1])
				->where('salon.namasalon', 'like', '%' . $username . '%')
				->where('jadwalsalon.hari','=',$sekarang)
				->Orwhere('salon.kategori','=',$temp[2])
				->where('salon.namasalon', 'like', '%' . $username . '%')
				->where('jadwalsalon.hari','=',$sekarang)
				->distinct()
				->orderBy('salon.username', 'asc')
				->get();
		}else{
			//$temp = explode(" ",$kategori);
			//ngecek kalo username kosong tapi kat ada1
			if(count($temp) == 1){
				$dt = salon::select('salon.*', 'users.foto','jadwalsalon.jambuka','jadwalsalon.hari','jadwalsalon.jamtutup')
					->join('users', 'users.username', '=', 'salon.username')
					->join('jadwalsalon', 'jadwalsalon.idsalon', '=', 'salon.id')
					->where('salon.kategori','=',$kategori)
					->where('jadwalsalon.hari','=',$sekarang)
					->distinct()
					->orderBy('salon.username', 'asc')
					->get();
			}
			//ngecek kalo username kosong tapi kat ada2
			else if(count($temp) == 2){
				$dt = salon::select('salon.*', 'users.foto','jadwalsalon.jambuka','jadwalsalon.hari','jadwalsalon.jamtutup')
					->join('users', 'users.username', '=', 'salon.username')
					->join('jadwalsalon', 'jadwalsalon.idsalon', '=', 'salon.id')
					->where('jadwalsalon.hari','=',$sekarang)
					->where('salon.kategori','=',$temp[0])
					->Orwhere('salon.kategori','=',$temp[1])
					->where('jadwalsalon.hari','=',$sekarang)
					->distinct()
					->orderBy('salon.username', 'asc')
					->get();
			}
			//ngecek kalo username kosong tapi kat ada3
			else{
				$dt = salon::select('salon.*', 'users.foto','jadwalsalon.jambuka','jadwalsalon.hari','jadwalsalon.jamtutup')
				->join('users', 'users.username', '=', 'salon.username')
				->join('jadwalsalon', 'jadwalsalon.idsalon', '=', 'salon.id')
				->where('jadwalsalon.hari','=',$sekarang)
				->where('salon.kategori','=',$temp[0])
				->Orwhere('salon.kategori','=',$temp[1])
				->where('jadwalsalon.hari','=',$sekarang)
				->Orwhere('salon.kategori','=',$temp[2])
				->where('jadwalsalon.hari','=',$sekarang)
				->distinct()
				->orderBy('salon.username', 'asc')
				->get();
			}
		}
		return $dt;
    }
}

