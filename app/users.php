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
	
	public function top_up_saldo($jumlah, $username){
		$cari = users::find($username);
		$cari->saldo = $cari->saldo + $jumlah;
		$cari->save();
	}
	
	public function block_unblock_user($username, $status){
		$cari = users::find($username);
		$cari->status = $status;
		$cari->save();
		
	}
	
	public function getsemuauser(){
		return users::select('users.*')
				->where('role', '!=', 'admin')
				//->where('status', '=', 'aktif')
				->get();
	}
	
	public function cariuser($nama){
		return users::select('users.*')
				->where('role', '!=', 'admin')
				//->where('status', '=', 'aktif')
				->where('nama', 'like', '%' . $nama . '%')
				->get();
	}
	
	public function logindata($username, $password){
		$dt = users::where('username', '=', $username)
				   ->where('password', '=', $password)
				   ->where(function ($query) {
						$query->where('status', '=', 'aktif')
						  ->orWhere('status', '=', 'tutup');
					})
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
        return users::select('users.*', 'salon.id', 'salon.username', 'salon.namasalon', 'salon.alamat', 'salon.kota', 'salon.telp', 'salon.longitude', 'salon.latitude', 'salon.keterangan', 'salon.status','salon.kategori')
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
		 return users::select('users.*', 'jadwalsalon.idsalon', 'jadwalsalon.hari', 'jadwalsalon.jambuka','jadwalsalon.jamtutup','salon.rating','salon.ulasan')
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
	
	public function getallsalonuser_seeall(){
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
		 return users::select('users.*', 'jadwalsalon.idsalon', 'jadwalsalon.hari', 'jadwalsalon.jambuka','jadwalsalon.jamtutup','salon.rating','salon.ulasan')
		 ->join('salon', 'salon.username', '=', 'users.username')
		 ->join('jadwalsalon', 'jadwalsalon.idsalon', '=', 'salon.id')
		 ->where('users.role','=', 'salon')
		 ->where('jadwalsalon.hari','=', $sekarang)
		 ->orderBy('users.status', 'asc')
		->get();
		return $dt;
	}
	
	public function getallsalonuser_terpopuler(){
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
		 return users::select('users.*', 'jadwalsalon.idsalon', 'jadwalsalon.hari', 'jadwalsalon.jambuka','jadwalsalon.jamtutup','salon.rating','salon.ulasan')
		 ->join('salon', 'salon.username', '=', 'users.username')
		 ->join('jadwalsalon', 'jadwalsalon.idsalon', '=', 'salon.id')
		 ->join('bookingservice', 'bookingservice.idsalon', '=', 'salon.id')
		 ->where('users.role','=', 'salon')
		 ->where('jadwalsalon.hari','=', $sekarang)
		 ->where(function ($query) {
				$query->where('bookingservice.status', '=', 'selesai')
				  ->orWhere('bookingservice.status', '=', 'selesairating');
			})
		 ->orderBy('salon.ulasan', 'desc')
		 ->distinct()
		->get();
		return $dt;
	}
	
	public function getallsalonuser_selaludiskon(){
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
		 return users::select('users.*', 'jadwalsalon.idsalon', 'jadwalsalon.hari', 'jadwalsalon.jambuka','jadwalsalon.jamtutup','salon.rating','salon.ulasan')
		 ->join('salon', 'salon.username', '=', 'users.username')
		 ->join('transaksi_voucher', 'transaksi_voucher.idsalon', '=', 'salon.id')
		 ->join('jadwalsalon', 'jadwalsalon.idsalon', '=', 'salon.id')
		 ->join('bookingservice', 'bookingservice.idsalon', '=', 'salon.id')
		 ->where('users.role','=', 'salon')
		 ->where('jadwalsalon.hari','=', $sekarang)
		 ->where('transaksi_voucher.tanggal_exp','>=', $hari)
		 ->where('transaksi_voucher.status','=', 'aktif')
		 ->distinct()
		->get();
		return $dt;
	}
	
	public function getallsalonuser_24jam(){
		//$jam = date("H:i:s");
		$jam = '20:00:00';
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
		 return users::select('users.*', 'jadwalsalon.idsalon', 'jadwalsalon.hari', 'jadwalsalon.jambuka','jadwalsalon.jamtutup','salon.rating','salon.ulasan')
		 ->join('salon', 'salon.username', '=', 'users.username')
		 ->join('jadwalsalon', 'jadwalsalon.idsalon', '=', 'salon.id')
		 ->where('users.role','=', 'salon')
		 ->where('users.status','=', 'aktif')
		 ->where('salon.status','=', 'aktif')
		 ->where('jadwalsalon.hari','=', $sekarang)
		 ->where('jadwalsalon.jambuka','<=', $jam)
		 ->where('jadwalsalon.jamtutup','>=', $jam)
		 ->orderBy('salon.ulasan', 'desc')
		->get();
	}
	
	public function statussalon($username, $status){
		$cari = users::where('username','=',$username)->first();
		$cari->status		= $status;
		$cari->save();
	}
	
	//ini func kalo pembayaran booking menggunakan saldo bagian salon (waktu selesai baru nambah saldo)
	public function tambah_administrasi_saldo($username, $total_kirim, $service_charge){
		//ini bagian salon ditambah saldonya .. username di sini sebagai username salon
		$cari 			= users::where('username','=',$username)->first();
		$cari->saldo	= $cari->saldo + $total_kirim;
		$cari->save();
		
		//ini kurangin saldo punya salon untuk uang charge 
		$cari = users::where('username','=',$username)->first();
		$cari->saldo	= $cari->saldo - $service_charge;
		$cari->save();
	}
	
	//ini func kalo pembayaran booking menggunakan saldo bagian customer (waktu booking langsung kurang saldo)
	public function kurang_administrasi_saldo_customer($username, $total){		
		//ini bagian customer saldonya di kurangin .. username di sini sebagai username customer
		$cari 			= users::where('username','=',$username)->first();
		$cari->saldo	= $cari->saldo - $total;
		$cari->save();
	}
	
	public function uang_kembali($total,$username){
		$cari 				= users::find($username);
		$cari->saldo 		= $cari->saldo + $total;
		$cari->save();
	}
	
	public function cari_saldo($idsalon){
		$cari = users::select('users.saldo')
			->join('salon', 'salon.username', '=', 'users.username')
			->where('salon.id', '=', $idsalon)
			->first();
			
		return $cari;
	}
	
	public function tambah_kurang_administrasi($username,$service_charge){
		
		//ini kurangin saldo punya salon untuk uang charge 
		$cari = users::where('username','=',$username)->first();
		$cari->saldo	= $cari->saldo - $service_charge;
		$cari->save();
		
	}
	
	public function getsaldouser($username){
		$dt = users::select('users.*')
					->where('username','=',$username)
					->get();
					
		return $dt;
	}
}

