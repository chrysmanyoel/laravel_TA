<?php
//pemanggilan setelah userbaru -> [namayangdimodelnya]
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\users;
use App\layanansalon;
use App\kategori;
use App\salon;
use App\pegawai;
use App\transaksi;
use App\detailpegawai;
use App\absensi_pegawai;
use App\jadwalsalon;
use App\iklan;
use App\bookingservice;
use Illuminate\Support\Str;
use App\favorit;
use App\kode_otp;
use App\hari_libur;
use Carbon\Carbon;
use Mail;

class Controller extends BaseController
{
	public function register(Request $request){
		$usersbaru = new users;
		$usersbaru ->insertdata($request->username,$request->password,$request->email,$request->roleuser,$request->jeniskelamin);
		
		$return = [];
		$return[0]['status'] = "sukses";
		echo json_encode($return);
	}
	
	public function insertsalon(Request $request){
		$usersbaru = new salon;
		$usersbaru ->insertsalon($request->username);
		
		$return = [];
		$return[0]['status'] = "sukses";
		echo json_encode($return);
	}
	
	public function insertharilibur(Request $request){
		$cek = new bookingservice;
		$hsl = $cek->cekpesanan($request->idsalon,$request->tanggal,$request->jam1,$request->jam2);
		
		if(count($hsl) == 0){
			$input = new hari_libur;
			$input->id 		= 0;
			$input->idsalon	 	= $request->idsalon;
			$input->tanggal		= $request->tanggal;
			$input->jam1	= $request->jam1;
			$input->jam2	= $request->jam2;
			$input->keterangan	= $request->keterangan;
			$input->status	 	= 0;
			$input->save();
			$hsl = 'sukses';
		}else{
			$hsl = 'ada pesanan';
		}
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function updateuser(Request $request){
		$usersbaru = new users;
		$usersbaru ->updatedata($request->username,$request->password,$request->nama,$request->alamat, $request->kota,$request->telp,$request->tgllahir, $request->jeniskelamin, $request->mfile);
		$datagambar = base64_decode($request->mimage);
		file_put_contents("gambar/".$request->mfile, $datagambar);
		
		$return = [];
		$return[0]['status'] = "sukses";
		echo json_encode($return);
	}
	
	public function updatesalon(Request $request){
		$usersbaru = new salon;
		
		$pembayaran= "";
		if($request->pembayarancash == true) {
			$pembayaran = "cash"; 
			
			if($request->pembayaransaldo == true) { 
				$pembayaran .= ",saldo";
			} 
		}
		else { 
			if($request->pembayaransaldo == true) {
				$pembayaran = "saldo";
			}
		}
		
		$usersbaru ->updatesalon($request->username,$request->namasalon,$request->alamat, $request->kota,$request->telp, $pembayaran, $request->diskon, $request->latitude, $request->longitude, $request->keterangan, $request->status);
		
		$return = [];
		$return[0]['status'] = 'sukses';
		echo json_encode($return);
	}
	
	public function daftariklan(Request $request){		
		$datagambar = base64_decode($request->mimage);
		file_put_contents("gambar/".$request->mfile, $datagambar);
		
		$hari = date("Y-m-d");	
		$userbaru = new iklan;
		$userbaru->idiklan 		= 0;
		$userbaru->tanggal	 	= $hari;
		$userbaru->idsalon		= $request->idsalon;
		$userbaru->hargaiklan	= $request->hargaiklan;
		$userbaru->tanggal_awal	= $request->tanggal_awal;
		$userbaru->tanggal_akhir= $request->tanggal_akhir;
		$userbaru->foto	 		= $request->mfile;
		$userbaru->status	 	= "pending";
		$userbaru->save();
		
		$return = [];
		$return[0]['status'] = "sukses";
		echo json_encode($return);
	}
	
	public function statussalon(Request $request){
		$usersbaru = new salon;
		$usersbaru ->statussalon($request->username, $request->status);
		$usersbaru1 = new users;
		$usersbaru1 ->statussalon($request->username, $request->status);
		
		$kembalian = salon::select('salon.*')
					 ->where('username','=',$request->username)
					 ->get();
		
		$return = [];
		$return[0]['status'] = $kembalian;
		echo json_encode($return);
	}
	
	public function updatenewpass(Request $request){
		$dt = users::select('users.*')
				->where('email','=',$request->email)
				->get();
		
		$time = date('Y-m-d H:i:s');
		$cari = kode_otp::select('kode_otp.*')
				->where('email','=',$request->email)
				->where('kode','=',$request->kode)
				->where('expire','>',$time)
				->get();
				
		if(count($cari) == 0){
			$dt = 'gagal';
		}else{
			if(count($dt) > 0){
				$dt[0]->password = $request->password;
				$dt[0]->save();
				$dt = 'sukses';
			}else{
				$dt = 'gagal1';
			}	
		}
				
		$return = [];
		$return[0]['status'] = $dt;
		echo json_encode($return);
	}
	
	public function cek_email(Request $request){		
		$dt = users::select('users.*')
					 ->where('email','=',$request->email)
					 ->get();
		
		if(count($dt) == 0){
			$dt = 'gagal';
		}else{
			$dt = 'sukses';
		}
		
		$return = [];
		$return[0]['status'] = $dt;
		echo json_encode($return);
	}
	
	/*public function cek_count_tidakhadir(Request $request){		
		$dt = bookingservice::select('bookingservice.*')
					 ->get();
		
		if(count($dt) == 0){
			$dt = 'gagal';
		}
		
		$return = [];
		$return[0]['status'] = $dt;
		echo json_encode($return);
	}*/
	
	public function kirim_OTP(Request $request){
		$kodeotp = Str::random(5);
		$otp = new kode_otp();
		$otp->id = 0;
		$otp->kode = $kodeotp;
		$otp->email = $request->email;
		$otp->expire = Carbon::now()->addMinutes(10);
		$otp->save();
		$data['kodeotp'] = $kodeotp;
		Mail::send('confirm', ['data'=> $data],
		function($message) use ($request)
		{
			$message->subject("[KODE VERIFIKASI]");
			$message->from("chrysmanyoel12@gmail.com","chrysmanyoel12@gmail.com");
			$message->to($request->email);
		}
	  );
	}
	
	public function updatestatusreschedule(Request $request){
		$usersbaru = new bookingservice;
		$usersbaru ->updatestatusreschedule($request->id,$request->status,$request->statusreschedule);
				
		$return = [];
		$return[0]['status'] = 'sukses';
		echo json_encode($return);
	}
	
	public function konfirm_kodepesanan(Request $request){
		$usersbaru = new bookingservice;
		$hsl = $usersbaru ->konfirm_kodepesanan($request->id,$request->kodepesanan);
		
		if(count($hsl) == 0){
			$hsl = "gagal";
		}else{
			$hsl = "sukses";
		}
				
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function updatereschedule_customer(Request $request){
		$usersbaru = new bookingservice;
		$usersbaru ->updatestatusbooking($request->id,$request->status,$request->usernamecancel,$request->keterangan);
		$tempid = $request->id;
		
		//cari jambookingselesai dulu
		$durasi = layanansalon::find($request->idservice);
		$jambookingselesai = $request->jamres;
		$jambookingselesai = date('H:i:s', strtotime($jambookingselesai . $durasi->durasi .'minutes'));
		
		// kalo uda cari jambooking maka cari req peg yang sama
		$temp = bookingservice::find($request->id);
		$idpegawai	 	= $temp->idpegawai;
		
		
		//kalo uda, baru cari data yang sama.. kalo nda ada data yang sama maka update kalo ada data yang sama maka nda bisa update
		$qry = $usersbaru ->cekyangsama_reshcedule($request->tglres,$idpegawai,$request->jamres,$jambookingselesai);
		
		if(count($qry) > 0){
			$qry = new bookingservice;
			$status = 'terima';
			$qry ->updatestatusbooking($request->id,$status,$request->usernamecancel,$request->keterangan);
			//$qry->save();
			$qry = 'gagal';
		}else{
			$qry  = bookingservice::find($request->id);;	
			$qry->jamres 	 		= $request->jamres;
			$qry->tglres 	 		= $request->tglres;
			$qry->statusreschedule	= 'pending';
			$qry->jamresselesai	= $jambookingselesai;
			//$qry->save();
			$qry ='sukses';
		}
		
		$return = [];
		$return[0]['status'] = $qry;
		echo json_encode($return);
	}
	
	public function updatestatusbooking(Request $request){
		$usersbaru = new bookingservice;
		$tempid = $request->id;
		$usersbaru ->updatestatusbooking($request->id,$request->status,$request->usernamecancel,$request->keterangan);
				
		$hsl1 = $usersbaru->getlistbookingwithlayanan($request->usernamesalon);
		$hsl2 = $usersbaru->getlistbookingwithlayanansemua($request->usernamesalon);
		$hsl3 = $usersbaru->getlistbookingwithlayananuser($request->username);
		
		//ini cari ada ngga yang data nya sama kalo ada status diubah tolak		
		if($request->status == "terima"){
			$dt 				= bookingservice::find($request->id);
			$idpegawai 			= $dt->idpegawai;
			$tanggalbooking 	= $dt->tanggalbooking;
			$jambooking 		= $dt->jambooking;
			$jambookingselesai 	= $dt->jambookingselesai;
		
			$hsl4 = $usersbaru->tolaksemua_selainyangdipilih($tempid,$tanggalbooking,$idpegawai,$jambooking,$jambookingselesai);
			for($i=0;$i < count($hsl4); $i++){
				$hsl4[$i]->status = 'tolak';
				$hsl4[$i]->save();
				
			}
			$hsl4 = count($hsl4);
		}else{
			$hsl4 = "kosong";
		}
		
		
		
		$return = [];
		$return[0]['status'] = $hsl4;
		$return[0]['bookingharini'] = $hsl1;
		$return[0]['bookingsemua'] = $hsl2;
		$return[0]['bookinguser'] = $hsl3;
		echo json_encode($return);
	}
	
	public function updatejadwalsalon(Request $request){
		$usersbaru = new jadwalsalon;
		$usersbaru ->updatejadwalsalon($request->idsalon,$request->hari,$request->jambuka, $request->jamtutup);
		
		$return = [];
		$return[0]['status'] = "sukses";
		echo json_encode($return);
	}
	
	public function getalliklansalon(Request $request){
		$usersbaru = new iklan;
		$hsl = $usersbaru ->getalliklansalon();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	
	public function autoselesai(Request $request){
		$usersbaru = new iklan;
		$hsl = $usersbaru ->autoselesai();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getallfavoritjoinuser(Request $request){
		$usersbaru = new favorit;
		$hsl = $usersbaru ->getallfavoritjoinuser($request->username);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function terima_iklan(Request $request){
		$usersbaru = new iklan;
		$usersbaru ->terima_iklan($request->idiklan,$request->status);
		
		$return = [];
		$return[0]['status'] = "sukses";
		echo json_encode($return);
	}
	
	public function getlistbookingwithlayanansemua(Request $request){
		$usersbaru = new bookingservice;
		$hsl = $usersbaru->getlistbookingwithlayanansemua($request->idsalon);
		$usersbaru = new bookingservice;		
		$hsl1 = $usersbaru->getusername_cancel($request->idsalon);
		$hitung = [];
		for($i=0;$i<count($hsl1);$i++){
			$hsl2 = $usersbaru->getcount_cancel($hsl1[$i]->username);
			$hitung[$i] = count($hsl2);
			
		}
				
		$return = [];
		$return[0]['status'] = $hsl;
		$return[0]['jumlah'] = $hitung;
		echo json_encode($return);
	}
	
	public function getlistbookingwithlayanan(Request $request){
		$usersbaru = new bookingservice;		
		$hsl = $usersbaru->getlistbookingwithlayanan($request->idsalon);
		$usersbaru = new bookingservice;		
		$hsl1 = $usersbaru->getusername_cancel_tgl($request->idsalon);
		$hitung = [];
		for($i=0;$i<count($hsl1);$i++){
			$hsl2 = $usersbaru->getcount_cancel_tgl($hsl1[$i]->username);
			$hitung[$i] = count($hsl2);
		}
		
		$return = [];
		$return[0]['status'] = $hsl;
		$return[0]['jumlah'] = $hitung;
		echo json_encode($return);
	}
	
	public function getlistbookingwithlayananuser(Request $request){
		$usersbaru = new bookingservice;		
		$hsl = $usersbaru->getlistbookingwithlayananuser($request->username);
		
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getlistbookingwithlayananuserselesai(Request $request){
		$usersbaru = new bookingservice;		
		$hsl = $usersbaru->getlistbookingwithlayananuserselesai($request->username);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function login(Request $request){
		$usersbaru = new users;		
		$hsl = $usersbaru->logindata($request->username,$request->password);
		
		if(count($hsl) > 0){
			$temp = $hsl[0]->username;
			$qry = new salon;
			$qry1 = $qry->findidsalon($temp);
		}else{
			$qry1 ='gagal';
		}		
		
		$return = [];
		$return[0]['status'] = $hsl;
		$return[0]['idsalon'] = $qry1;
		echo json_encode($return);
	}
	
	public function getuser(Request $request){
		$usersbaru = new users;		
		$hsl = $usersbaru->getusers($request->username);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getuserswithsalondgnusername(Request $request){
		$usersbaru = new users;		
		$hsl = $usersbaru->getuserswithsalondgnusername($request->username);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getjadwalsalon(Request $request){
		$tgl = Carbon::now();
		$hsl = $tgl->format('l');
		
		if($hsl ==  'Monday'){
			$sekarang = "senin";
		}else if($hsl ==  'Tuesday'){
			$sekarang = "selasa";
		}else if($hsl ==  'Wednesday'){
			$sekarang = "rabu";
		}else if($hsl ==  'Thursday'){
			$sekarang = "kamis";
		}else if($hsl ==  'Friday'){
			$sekarang = "jumat";
		}else if($hsl ==  'Saturday'){
			$sekarang = "sabtu";
		}else if($hsl ==  'Sunday'){
			$sekarang = "minggu";
		}
		//echo $sekarang;
		
		$usersbaru = new jadwalsalon;
		$hsl = $usersbaru->getjadwalsalon($request->idsalon,$sekarang);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getjadwalsalon_set(Request $request){
		$tgl = Carbon::now();
		$hsl = $tgl->format('l');
		
		if($hsl ==  'Monday'){
			$sekarang = "senin";
		}else if($hsl ==  'Tuesday'){
			$sekarang = "selasa";
		}else if($hsl ==  'Wednesday'){
			$sekarang = "rabu";
		}else if($hsl ==  'Thursday'){
			$sekarang = "kamis";
		}else if($hsl ==  'Friday'){
			$sekarang = "jumat";
		}else if($hsl ==  'Saturday'){
			$sekarang = "sabtu";
		}else if($hsl ==  'Sunday'){
			$sekarang = "minggu";
		}
		//echo $sekarang;
		
		$usersbaru = new jadwalsalon;
		$hsl = $usersbaru->getjadwalsalon_set($request->idsalon);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getuserswithsalon(){
		$usersbaru = new users;		
		$hsl = $usersbaru->getuserswithsalon();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getlayanansalon(Request $request){
		$usersbaru = new layanansalon;		
		$hsl = $usersbaru->getlayanansalon($request->idsalon);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getlayanansalondetail(Request $request){
		$usersbaru = new layanansalon;		
		$hsl = $usersbaru->getlayanansalondetail($request->idsalon, $request->namalayanan);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getlayananwithuser(Request $request){
		$usersbaru = new layanansalon;		
		$hsl = $usersbaru->getlayananwithuser($request->username);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function carisalon(Request $request){
		$usersbaru = new salon;	
		$temp = $request->username;
		if(!empty($temp)){
			$hsl=$usersbaru->carisalon($request->username);
		}else{
			$hsl="";
		}
		
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getdatauser(Request $request){
		$usersbaru = new users;		
		$hsl = $usersbaru->getdatauser($request->username);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getrole(Request $request){
		$usersbaru = new users;		
		$hsl = $usersbaru->getrole($request->username);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getiklan(Request $request){
		$usersbaru = new iklan;		
		$hsl = $usersbaru->getiklan($request->idsalon);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function deletefav(Request $request){
		$usersbaru = new favorit;		
		$usersbaru->deletefav($request->idfavorit);
		
		$return = [];
		$return[0]['status'] = "sukses";
		echo json_encode($return);
	}
	
	public function getiklan_admin(Request $request){
		$usersbaru = new iklan;		
		$hsl = $usersbaru->getiklan_admin();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getiklan_admin_acc(Request $request){
		$usersbaru = new iklan;		
		$hsl = $usersbaru->getiklan_admin_acc();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function finduser(Request $request){
		$usersbaru = new users;		
		$hsl = $usersbaru->finduser($request->nama);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getidsalon(Request $request){
		$usersbaru = new salon;		
		$hsl = $usersbaru->getidsalon($request->username);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function ubahstatus(Request $request){
		$usersbaru = new users;
		$usersbaru ->updatestatus($request->username, $request->datastatus);
		
		$return = [];
		$return[0]['status'] = "sukses";
		echo json_encode($return);
	}
	
	public function getallsalon(){
		$usersbaru = new salon;		
		$hsl = $usersbaru->getallsalon();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getallsalonuser(){
		$usersbaru = new users;		
		$hsl = $usersbaru->getallsalonuser();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function insertpegawai(Request $request){
		$usersbaru = new pegawai;
		$usersbaru->id			= 0;
		$usersbaru->idsalon		= $request->idsalon;
		$usersbaru->nama 		= $request->nama;
		$usersbaru->alamat 		= $request->alamat;
		$usersbaru->telp		= $request->telp;
		$usersbaru->status 		= $request->status;
		$usersbaru->save();
		
		$return = [];
		$return[0]['status'] = "sukses";
		echo json_encode($return);
	}
	
	public function insertfav(Request $request){
		
		$dt = favorit::select('favorit.*')
			->where('username','=',$request->username)
			->where('idsalon','=',$request->idsalon)
			->get();
			
		if(count($dt) == 0){
			$usersbaru = new favorit;
			$usersbaru->idfavorit	= 0;
			$usersbaru->idsalon		= $request->idsalon;
			$usersbaru->username 	= $request->username;
			$usersbaru->save();
			$dt = 'sukses';
		}else{
			$dt = 'gagal';
		}
		
		
		$return = [];
		$return[0]['status'] = $dt;
		echo json_encode($return);
	}
	
	public function insertlayanansalon(Request $request){
		
		$usersbaru = new layanansalon;
		$hsl = $usersbaru->iskembarlayanan($request->namalayanan,$request->idsalon);
		
		if(count($hsl) > 0){
			$hsl = 'gagal';
		}else{
			$usersbaru->id						= 0;
			$usersbaru->idsalon					= $request->idsalon;
			$usersbaru->username				= $request->username;
			$usersbaru->namalayanan 			= $request->namalayanan;
			$usersbaru->jumlah_kursi 			= $request->jumlah_kursi;
			$usersbaru->idkategori 				= $request->idkategori;
			$usersbaru->jenjangusia				= $request->jenjangusia;
			$usersbaru->peruntukan 				= $request->peruntukan;
			$usersbaru->hargapriadewasa			= $request->hargapriadewasa;
			$usersbaru->hargawanitadewasa		= $request->hargawanitadewasa;
			$usersbaru->hargawanitaanak			= $request->hargawanitaanak;
			$usersbaru->hargapriaanak			= $request->hargapriaanak;
			$usersbaru->durasi	 				= $request->durasi;
			$usersbaru->deskripsi				= $request->deskripsi;
			$usersbaru->status 	 				= $request->status;
			$usersbaru->toleransi_keterlambatan = $request->keterlambatan_waktu;
			$usersbaru->foto					= $request->mfile;
			$usersbaru->save();
			$datagambar = base64_decode($request->mimage);
			file_put_contents("gambar/".$request->mfile, $datagambar);
			$hsl = 'sukses';
		}
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function transaksiTopUp_bank(Request $request){
		$usersbaru = new transaksi;
		$usersbaru->id				= 0;
		$usersbaru->atasnama		= $request->atasnama;
		$usersbaru->nama_bank 		= $request->nama_bank;
		$usersbaru->jenis_transaksi = $request->jenis_transaksi;
		$usersbaru->melalui			= $request->melalui;
		$usersbaru->norek 			= $request->norek;
		$usersbaru->jumlah			= $request->jumlah;
		$usersbaru->status	 		= $request->status;
		$usersbaru->save();
		
		$return = [];
		$return[0]['status'] = "sukses";
		echo json_encode($return);
	}
		
	public function insertbookingservice(Request $request){
		$statussalon = salon::find($request->idsalon);
		$cek = $statussalon->status;
		
		$usersbaru  = new bookingservice;	
		$durasi = layanansalon::find($request->idservice);
		$jambookingselesai = $request->jambooking;
		$jambookingselesai = date('H:i:s', strtotime($jambookingselesai . $durasi->durasi .'minutes'));
		$jambookingselesai = date('H:i:s', strtotime($jambookingselesai . $durasi->toleransi_keterlambatan .'minutes'));
		
		//$idsalon, $idservice, $tanggalbooking, $jambooking,$idpegawai
		$validbooking = $this->isvalidbooking($request->idsalon, $request->idservice, $request->tanggalbooking, $request->jambooking, $request->idpegawai); 
		
		
		$tgl = date("Y-m-d");
		
		if($cek == "aktif"){
			$kode_pesanan = Str::random(5);
			
			if($validbooking == 0){		
				/*$usersbaru->id					= 0;
				$usersbaru->tanggal				= $tgl;
				$usersbaru->username			= $request->username;
				$usersbaru->namauser			= $request->namauser;
				$usersbaru->idsalon				= $request->idsalon;
				$usersbaru->idservice 			= $request->idservice;
				$usersbaru->tanggalbooking		= $request->tanggalbooking;
				$usersbaru->jambooking			= $request->jambooking;
				$usersbaru->jambookingselesai	= $jambookingselesai;
				$usersbaru->idpegawai			= $request->idpegawai;
				$usersbaru->pembayaran			= $request->pembayaran;
				$usersbaru->total				= $request->total;
				$usersbaru->usernamecancel		= "";
				$usersbaru->status 	 			= "pending";
				$usersbaru->jamres 	 			= null;
				$usersbaru->tglres 	 			= null;
				$usersbaru->statusreschedule 	= null;
				$usersbaru->jamresselesai   	= null;
				$usersbaru->kode_pesanan	   	= $kode_pesanan;
				$usersbaru->keterangan   		= "";
				$usersbaru->save();*/
				$isvalidbooking = 'sukses';
			}else if($validbooking == -2){
				$isvalidbooking = '-2';
			}else {
				$isvalidbooking = '-1';
			}
		}else{
			$isvalidbooking= 'tutup';
		}
		
		// kalo return -2 = pegawai tsb berhalangan
		// kalo return -1 = jam tsb sudah penuh booking
		$return = [];
		$return[0]['status'] = $isvalidbooking;
		echo json_encode($return);
	}
	
	function getquotalayanan($idservice)
    {
		//$idlayanan
		 $br = layanansalon::where('id', '=', $idservice)
				->first();
		 $qry  = $br->jumlah_kursi;
		return $qry;
    }
	
	function getquotapegawai($idsalon, $tanggal)
    {
	 //$idsalon, $tanggal
	 
     // get quota pegawai 
     $br = pegawai::where('idsalon', '=', $idsalon)
        ->get(); 
     $jumpeg = count($br); 
     
     // get pegawai absen 
     $br = absensi_pegawai::select('absensi_pegawai.*')
		 ->where('tanggal', '=', $tanggal)
		 ->where('idsalon', '=', $idsalon)
         ->get(); 
     $jumabs = count($br); 
         
     return $jumpeg-$jumabs ;
    }
	
	function apakahPegawaiTsbSiap($idservice, $tanggalbooking, $jambooking,$idpegawai) {
		//$idservice, $tanggalbooking, $jambooking
		// get pegawai lagi bekerja 
		$usersbaru  = new bookingservice;	
		$durasi = layanansalon::find($idservice);
		$jambookingselesai = $jambooking;
		$jambookingselesai = date('H:i:s', strtotime($jambookingselesai . $durasi->durasi .'minutes'));
		$jambookingselesai = date('H:i:s', strtotime($jambookingselesai . $durasi->toleransi_keterlambatan .'minutes'));
		$hsl = $usersbaru->getwaktuyangsama($tanggalbooking,$idpegawai,$jambooking,$jambookingselesai);
		
		if(count($hsl) > 0){
			return false;
		}else{
			return true;
		}
   }
	
	//idservice, tanggalbooking, jambooking
	function hitungTransaksiBerjalan($idservice,$tanggalbooking,$jambooking) {
	   
	    $usersbaru  = new bookingservice;	
		$durasi = layanansalon::find($idservice);
		$jambookingselesai = $jambooking;
		$jambookingselesai = date('H:i:s', strtotime($jambookingselesai . $durasi->durasi .'minutes'));
		//$jambookingselesai = date('H:i:s', strtotime($jambookingselesai . $durasi->toleransi_keterlambatan .'minutes'));
	   //$idservice, $tanggalbooking, $jambooking
	   
	    $qry = layanansalon::where('id','=',$idservice)->get();
	    foreach($qry as $row){
		    $idkategori = $row->idkategori;
	    }
		
		 $qry = bookingservice::where('layanansalon.idkategori', '=', $idkategori)
			->join('layanansalon','layanansalon.id','=','bookingservice.idservice')
			->where('tanggalbooking', '=', $tanggalbooking)
			->whereBetween('jambooking',[$jambooking,$jambookingselesai])
			->orwhereBetween('jambookingselesai',[$jambooking,$jambookingselesai])
			->where('bookingservice.status', '=', 'terima')
			->Orwhere('bookingservice.status', '=', 'datang')
			->where('tanggalbooking', '=', $tanggalbooking)
			->whereBetween('jambooking',[$jambooking,$jambookingselesai])
			->orwhereBetween('jambookingselesai',[$jambooking,$jambookingselesai])
			->get(); 
	   
       /*$qry = bookingservice::where('idservice', '=', $request->idservice)
			->where('tanggalbooking', '=', $request->tanggalbooking)
			->whereBetween('jambooking',[$request->jambooking,$jambookingselesai])
			->orwhereBetween('jambookingselesai',[$request->jambooking,$jambookingselesai])
			->where('status', '=', 'terima')
			->Orwhere('status', '=', 'datang')
			->where('tanggalbooking', '=', $request->tanggalbooking)
			->whereBetween('jambooking',[$request->jambooking,$jambookingselesai])
			->orwhereBetween('jambookingselesai',[$request->jambooking,$jambookingselesai])
			->get(); */
        return count($qry); 
   }
   
   function isvalidbooking($idsalon, $idservice, $tanggalbooking, $jambooking,$idpegawai)
    {
		$tanggal = Date('Y-m-d');
	 //$idsalon, $idservice, $tanggalbooking, $jambooking, $idpegawai
     $u = $this->getquotalayanan($idservice); 
     $v = $this->getquotapegawai($idsalon, $tanggal); 
     $w = min($u, $v); 
	 
	 if($w > 0) {
		 $tb = $this->hitungTransaksiBerjalan($idservice, $tanggalbooking, $jambooking); 
		 if($w - $tb > 0) {
            $x = $this->apakahPegawaiTsbSiap($idservice, $tanggalbooking, $jambooking, $idpegawai); 

            if($x == true) { return 0; }    // boleh 
            else { return -2; }             // -2 = pegawai tsb berhalangan
         }
	 }
	 else {
		// jam tsb sudah penuh booking
		return -1 ;
	 }
    }
	
		
	public function getidpegawai(Request $request){
		$usersbaru = new pegawai;		
		$hsl = $usersbaru->getidpegawai($request->nama);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getpegawai(Request $request){
		$usersbaru = new pegawai;		
		$hsl = $usersbaru->getpegawai($request->idsalon,$request->kodelayanan);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getpegawai_halamanmember(Request $request){
		$usersbaru = new pegawai;		
		$hsl = $usersbaru->getpegawai_halamanmember($request->idsalon,$request->kodelayanan);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getabsensipegawai(Request $request){
		$usersbaru = new absensi_pegawai;		
		$hsl = $usersbaru->getabsensipegawai($request->idsalon);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getpegawai_absen(Request $request){
		$usersbaru = new pegawai;		
		$hsl = $usersbaru->getpegawai_absen($request->idsalon);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function insertabsenpegawai(Request $request){
		
		$tgl = date("Y-m-d");
		$qry = absensi_pegawai::select('absensi_pegawai.*')
				->where('tanggal','=',$tgl)
				->where('nama','=',$request->nama)
				->get();
		$dt = bookingservice::select('bookingservice.*')
				->where('tanggalbooking' ,'=', $tgl)
				->where('requestpegawai' ,'=', $request->nama)
				->where('status' ,'=', 'terima')
				->Orwhere('status' ,'=', 'pending')
				->where('tanggalbooking' ,'=', $tgl)
				->where('requestpegawai' ,'=', $request->nama)
				->get();
		
		if(count($qry) == 0){
			if(count($dt) == 0){
			$usersbaru = new absensi_pegawai;
			$usersbaru->id				= 0;
			$usersbaru->idpegawai		= $request->idpegawai;
			$usersbaru->idsalon			= $request->idsalon;
			$usersbaru->nama			= $request->nama;
			$usersbaru->tanggal			= $request->tanggal;
			$usersbaru->waktu			= $request->waktu;
			$usersbaru->keterangan 		= $request->keterangan;
			$usersbaru->save();
			$temp='sukses';
			}else{
				$temp = count($dt);
			}
		}else{
			$temp = 'gagal';
		}
		
		$return = [];
		$return[0]['status'] = $temp;
		echo json_encode($return);
	}
	
	public function insertkategori(Request $request){
		$usersbaru = new kategori;
		
		$dt = kategori::select('kategori.*')
			->where('namakategori','=',$request->namakategori)
			->get();
		
		if(count($dt) == 0){
			$usersbaru->id		= 0;
			$usersbaru->idkategori		= $request->idkategori;
			$usersbaru->namakategori	= $request->namakategori;
			$usersbaru->save();
			$dt ='sukses';
		}else{
			$dt ='gagal';
		}
		
		
		$return = [];
		$return[0]['status'] = $dt;
		echo json_encode($return);
	}
	
	public function getdatapegawai(Request $request){
		$usersbaru = new pegawai;		
		//$hsl = $usersbaru->getdatapegawai($request->idsalon);
		$hsl = $usersbaru->getpegawai($request->idsalon);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getdatapegawai_tampil(Request $request){
		$usersbaru = new pegawai;		
		$hsl = $usersbaru->getdatapegawai($request->idsalon);
		
		/*$peg = pegawai::where('idsalon','=',$request->idsalon)
		->get();
		
		for($i=0; $i<count($peg); $i++){
			$model = new pegawai;
			$detail = $model->getdatapegawai($peg[$i]->id);
			$return[0]['pegawai'][$i] = $peg[$i];
			$return[0]['detail'][$i] = $detail;
		}*/
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getsaldouser(Request $request){
		$usersbaru = new users;		
		$hsl = $usersbaru->getsaldouser($request->username);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getkategori(Request $request){
		$usersbaru = new kategori;		
		$hsl = $usersbaru->getallkategori();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function insertdetailpegawai(Request $request){
		$data = json_decode($request->idkategori);
		for($i=0; $i<count($data);$i++){
			$userbaru = new detailpegawai;
			$hsl = $userbaru->getdetailpegawaikategori($request->idpegawai,$data[$i]->idkategori);
			if($data[$i]->aktif == "1" && count($hsl) == 0){
				
				$userbaru = new detailpegawai;
				$hsl = $userbaru->getdetailpegawai($request->idpegawai);
				
				$usersbaru1 = new detailpegawai;
				$usersbaru1->id			= 0;
				$usersbaru1->idpegawai	= $request->idpegawai;
				$usersbaru1->idkategori	= $data[$i]->idkategori;
				$usersbaru1->save();
			}else if ($data[$i]->aktif == "0" && count($hsl) > 0){
				$user = detailpegawai::find($hsl[0]->id)->delete();
			}else{
				//echo "keluar";
			}
		}
		
		
		/*
		$return = [];
		$return[0]['status'] = "sukses";
		echo json_encode($return);*/
	}
	
	public function cancelsemuabooking(Request $request){
		$usersbaru = new bookingservice;
		$hsl  = $usersbaru ->cancelsemuabooking($request->usernamesalon, $request->requestpegawai,$request->tanggal);
		
		if(count($hsl) == 0){
			$hsl = 'sukses';
		}else{
			for($i=0; $i<count($hsl);$i++){
				$hsl[$i]->status = 'cancel';
				$hsl[$i]->tanggalbooking = $request->tanggal;
				$hsl[$i]->usernamecancel = $request->requestpegawai;
				$hsl[$i]->save();
			}
			$hsl = 'gagal';
		}		
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getallidkategori (Request $request){
		$userbaru = new kategori;
		$hsl  = $userbaru->getallidkategori();
		
		if($request->idpegawai) {
			if($request->idpegawai == "") {
				for($i = 0; $i < count($hsl); $i++) {
					$hsl[$i]->aktif = "0"; 
				}							
			}
			else {
				// 
				$usr 	= new detailpegawai;
				$hsldet = $usr->getdetailpegawai($request->idpegawai);
				
				for($i = 0; $i < count($hsl); $i++) {
					$hsl[$i]->aktif = "0"; 
					for($j = 0; $j < count($hsldet); $j++) {
						if($hsldet[$j]->idkategori == $hsl[$i]->idkategori) {
							$hsl[$i]->aktif = "1"; 							
						}
					}
				}
			}
		}
		else {
			for($i = 0; $i < count($hsl); $i++) {
				$hsl[$i]->aktif = "0"; 
			}			
		}
		
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getallkategori(Request $request){
		$usersbaru = new kategori;		
		$hsl = $usersbaru->getallkategori();
		
		if($request->idpegawai) {
			if($request->idpegawai == "") {
				for($i = 0; $i < count($hsl); $i++) {
					$hsl[$i]->aktif = "0"; 
				}							
			}
			else {
				// 
				$usr 	= new detailpegawai;
				$hsldet = $usr->getdetailpegawai($request->idpegawai);
				
				for($i = 0; $i < count($hsl); $i++) {
					$hsl[$i]->aktif = "0"; 
					for($j = 0; $j < count($hsldet); $j++) {
						if($hsldet[$j]->idkategori == $hsl[$i]->idkategori) {
							$hsl[$i]->aktif = "1"; 							
						}
					}
				}
			}
		}
		else {
			for($i = 0; $i < count($hsl); $i++) {
				$hsl[$i]->aktif = "0"; 
			}			
		}
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
}

