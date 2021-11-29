<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class users extends Model
{
    public $incrementing = false;
	protected $table = 'users';
	protected $primaryKey = 'username';
	protected $fillable = [
		'email',
		'username',
		'password',
		'nama',
		'alamat',
		'kota',
		'telp',
		'foto',
		'saldo',
		'tgllahir',
		'jeniskelamin',
		'role',
		'status'
	];
	public $timestamps = false;
	
	public function insertdata($username, $password, $email, $roleuser, $jeniskelamin){
		$usersbaru = new users;
		$usersbaru->email	 = $email;
		$usersbaru->username = $username;
		$usersbaru->password = $password;
		$usersbaru->nama	 = $username;
		$usersbaru->alamat	 = "-";
		$usersbaru->kota	 = "-";
		$usersbaru->telp	 = "-";
		$usersbaru->foto	 = "default.png";
		$usersbaru->saldo	 = 0;
		$usersbaru->tgllahir = now();
		$usersbaru->jeniskelamin	= $jeniskelamin;
		$usersbaru->role 	 = $roleuser;
		$usersbaru->status   = "aktif";
		$usersbaru->save();
	}
	
	public function updatedata($username, $password, $nama, $alamat, $kota, $telp, $tgllahir, $jeniskelamin, $file){
		if($file != ""){
			$cari = users::find($username);
			$cari->password = $password;
			$cari->nama	 	= $nama;
			$cari->alamat	= $alamat;
			$cari->kota	 	= $kota;
			$cari->foto	 	= $file;
			$cari->telp	 	= $telp;
			$cari->tgllahir	 	= $tgllahir;
			$cari->jeniskelamin	= $jeniskelamin;
			$cari->save();
		}else{
			$cari = users::find($username);
			$cari->password = $password;
			$cari->nama	 	= $nama;
			$cari->alamat	= $alamat;
			$cari->kota	 	= $kota;
			$cari->telp	 	= $telp;
			$cari->tgllahir	 	= $tgllahir;
			$cari->jeniskelamin	= $jeniskelamin;
			$cari->save();
		}
	}
	
	public function updatestatus($username, $datastatus){
		$cari = users::find($username);
		$cari->status = $datastatus;
		$cari->save();
	}
	
	public function logindata($username, $password){
		$dt = users::where('username', '=', $username)
				   ->where('password', '=', $password)
				   ->where('status', '=', 'tutup'  ,'OR' ,'status', '=', 'aktif')
				   ->get();
				   
		return $dt;
	}
	
	public function getusers($username){
		$dt = users::select('users.*')
					->where('username','=',$username)
					->get();
					
		return $dt;
	}
	
	public function getuserswithsalon(){
        return users::select('users.*', 'salon.id', 'salon.username', 'salon.namasalon', 'salon.alamat', 'salon.kota', 'salon.telp', 'salon.longitude', 'salon.latitude', 'salon.keterangan', 'salon.status')
                        ->join('salon', 'salon.username', '=', 'users.username')
                        ->get();
    }
	
	public function getuserswithsalondgnusername($username){
        return users::select('users.*', 'salon.id', 'salon.username', 'salon.namasalon', 'salon.alamat', 'salon.kota', 'salon.telp', 'salon.pembayaran', 'salon.diskon', 'salon.longitude', 'salon.latitude', 'salon.keterangan', 'salon.status')
                        ->join('salon', 'salon.username', '=', 'users.username')
						->where('salon.username','=',$username)
                        ->get();
    }
	
	public function getrole($username){
		$dt = users::select('users.*')
					->where('username','=',$username)
					->get();
					
		return $dt;
	}
	
	public function finduser($nama){
		$dt = users::select('users.*')
					->where('nama','like','%'.$nama.'%')
					->where('role', '!=','admin')
					->get();
		return $dt;
	}
	
	
	public function getallsalonuser(){
		$jam = date("H:i:s");
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
		 return users::select('users.*', 'jadwalsalon.idsalon', 'jadwalsalon.hari', 'jadwalsalon.jambuka','jadwalsalon.jamtutup')
		 ->join('salon', 'salon.username', '=', 'users.username')
		 ->join('jadwalsalon', 'jadwalsalon.idsalon', '=', 'salon.id')
		 ->where('users.role','=', 'salon')
		 ->where('users.status','=', 'aktif')
		 //->where('jadwalsalon.jambuka','<', $jam)
		 //->where('jadwalsalon.jamtutup','>', $jam)
		 ->where('jadwalsalon.hari','=', $sekarang)
		->get();
		return $dt;
	}
	
	public function statussalon($username, $status){
		$cari = users::where('username','=',$username)->first();
		$cari->status		= $status;
		$cari->save();
	}
	
	public function getsaldouser($username){
		$dt = users::select('users.*')
					->where('username','=',$username)
					->get();
					
		return $dt;
	}
}

