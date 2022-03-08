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
use App\chat;
use App\seting;
use App\transaksi;
use App\detailpegawai;
use App\report;
use App\rating_salon;
use App\absensi_pegawai;
use App\jadwalsalon;
use App\iklan;
use App\bookingservice;
use Illuminate\Support\Str;
use App\favorit;
use App\transaksi_voucher;
use App\kode_otp;
use App\master_voucher;
use App\hari_libur;
use Carbon\Carbon;
use Mail;

class Controller extends BaseController
{
	public function register(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_cekstatusvoucher();
		$this->auto_selesai_iklan();
		$usersbaru = new users;
		$usersbaru ->insertdata($request->username,$request->password,$request->email,$request->roleuser,$request->jeniskelamin);
		
		$return = [];
		$return[0]['status'] = "sukses";
		echo json_encode($return);
	}
	
	public function insertsalon(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$this->auto_cekstatusvoucher();
		$usersbaru = new salon;
		$usersbaru ->insertsalon($request->username);
		
		$return = [];
		$return[0]['status'] = "sukses";
		echo json_encode($return);
	}
	
	public function hapus_jadwallibur(Request $request){
		$this->auto_cekbooking();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$this->auto_cekharilibur();
		$cari = hari_libur::find($request->id)->delete();
		
		$return = [];
		$return[0]['status'] = "sukses";
		echo json_encode($return);
	}
	
	public function insertharilibur(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_selesai_iklan();
		$this->auto_mulai_iklan();
		
		$this->auto_cekstatusvoucher();
		$cek = new bookingservice;
		$hsl = $cek->cekpesanan($request->idsalon,$request->tanggal,$request->jam1,$request->jam2);
		
		$cekharilibur = new hari_libur;
		$hsl1 = $cekharilibur->cek_insert($request->idsalon,$request->tanggal,$request->jam1,$request->jam2);
		
		if(count($hsl) == 0){
			if(count($hsl1) == 0){
				$input = new hari_libur;
				$input->id 		= 0;
				$input->idsalon	 	= $request->idsalon;
				$input->tanggal		= $request->tanggal;
				$input->jam1		= $request->jam1;
				$input->jam2		= $request->jam2;
				$input->keterangan	= $request->keterangan;
				$input->status	 	= 0;
				$input->save();
				$hsl = 'sukses';
			}else{
				$hsl = 'sudah ada';
			}
		}else{
			$hsl = 'ada pesanan';
		}
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function cancel_tolak_booking(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_selesai_iklan();
		$this->auto_mulai_iklan();
		$this->auto_cekstatusvoucher();
		$cek = new bookingservice;
		
		$hsl = $cek->cancel_tolak_booking($request->idsalon,$request->tanggal,$request->jam1,$request->jam2);
		$cekharilibur = new hari_libur;
		$hsl1 = $cekharilibur->cek_insert($request->idsalon,$request->tanggal,$request->jam1,$request->jam2);
		
		if(count($hsl1) == 0){
			$input = new hari_libur;
			$input->id 		= 0;
			$input->idsalon	 	= $request->idsalon;
			$input->tanggal		= $request->tanggal;
			$input->jam1		= $request->jam1;
			$input->jam2		= $request->jam2;
			$input->keterangan	= $request->keterangan;
			$input->status	 	= 0;
			$input->save();
			$hsl = 'sukses';
		}else{
			$hsl = 'sudah ada';
		}
		
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function insert_voucher(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_selesai_iklan();
		$this->auto_mulai_iklan();
		$this->auto_cekstatusvoucher();
		$cek = new master_voucher;
		$hsl = $cek->cek_voucher($request->nama_voucher);
		
		if(count($hsl) == 0){
			$input 				= new master_voucher;
			$input->id 			= 0;
			$input->nama_voucher= $request->nama_voucher;
			$input->jenis		= $request->jenis;
			$input->harga_beli	= $request->harga_beli;
			$input->nilai		= $request->nilai;
			$input->status	 	= 'aktif';
			$input->save();
			$hsl = 'sukses';
		}else{
			$hsl = 'gagal';
		}
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function tambahsaldo(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_selesai_iklan();
		$this->auto_mulai_iklan();
		$this->auto_cekstatusvoucher();
		$ambil = new users;
		$hsl = $ambil->top_up_saldo($request->jumlah, $request->username);
		$hsl = 'sukses';
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function updatestatus_voucher(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$this->auto_cekstatusvoucher();
		
		//cek status nya voucher
		$cek = transaksi_voucher::find($request->id);
		$status = $cek->status;
		
		if($status =='aktif'){
			$temp = 'non-aktif';
			$cek = transaksi_voucher::find($request->id);
			$cek->status = $temp;
			$cek->save();
		}else{
			$temp = 'aktif';
			$cek = transaksi_voucher::find($request->id);
			$cek->status = $temp;
			$cek->save();
		}
		
		$return = [];
		$return[0]['status'] = $temp;
		echo json_encode($return);
	}
	
	public function beli_voucher(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$this->auto_cekstatusvoucher();
		$hari = date("Y-m-d");
		$cek = new transaksi_voucher;
		$hsl = $cek->cek_valid_voucher($hari, $request->tanggal_exp,$request->id_voucher, $request->idsalon);
		
		if(count($hsl) == 0){
			$input 				= new transaksi_voucher;
			$input->id 			= 0;
			$input->id_voucher  = $request->id_voucher;
			$input->tanggal_beli= $hari;
			$input->tanggal_exp = $request->tanggal_exp;
			$input->idsalon		= $request->idsalon;
			$input->harga_beli	= $request->harga_beli;
			$input->status	 	= 'aktif';
			$input->save();
			$hsl = 'sukses';
			
			//kurang saldo salon || tapi cari dulu username salon pake id salon
			$kurang = salon::find($request->idsalon);
			$usernamesalon = $kurang->username;
			
			$kurang = users::find($usernamesalon);
			$kurang->saldo -= $request->harga_beli;
			$kurang->save();
		}else{
			$hsl = 'gagal';
		}
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function updateuser(Request $request){
		$usersbaru = new users;
		$this->auto_cekbooking();
		$this->auto_mulai_iklan();
		$this->auto_cekharilibur();
		$this->auto_selesai_iklan();
		$this->auto_cekstatusvoucher();
		$usersbaru ->updatedata($request->username,$request->password,$request->nama,$request->alamat, $request->kota,$request->telp,$request->tgllahir, $request->jeniskelamin, $request->mfile);
		$datagambar = base64_decode($request->mimage);
		file_put_contents("gambar/".$request->mfile, $datagambar);
		
		$return = [];
		$return[0]['status'] = "sukses";
		echo json_encode($return);
	}
	
	public function updateservice(Request $request){
		$usersbaru = new layanansalon;
		$this->auto_cekbooking();
		$this->auto_mulai_iklan();
		$this->auto_cekharilibur();
		$this->auto_selesai_iklan();
		$this->auto_cekstatusvoucher();
		
		$usersbaru ->updateservice($request->id,$request->idsalon,$request->username,$request->namalayanan,$request->jumlah_kursi,$request->idkategori,$request->jenjangusia,$request->peruntukan,$request->hargapriadewasa,$request->hargawanitadewasa,$request->hargawanitaanak,$request->hargapriaanak,$request->durasi,$request->deskripsi,$request->status,$request->keterlambatan_waktu, $request->mfile);
		
		if($request->mfile != ''){
			$datagambar = base64_decode($request->mimage);
			file_put_contents("gambar/".$request->mfile, $datagambar);
		}
		
		$return = [];
		$return[0]['status'] = "sukses";
		echo json_encode($return);
	}
	
	public function updatesalon(Request $request){
		$usersbaru = new salon;
		$this->auto_cekbooking();
		$this->auto_mulai_iklan();
		$this->auto_cekharilibur();
		$this->auto_selesai_iklan();
		$this->auto_cekstatusvoucher();
		$usersbaru ->updatesalon($request->username,$request->namasalon,$request->alamat, $request->kota,$request->telp, $request->latitude, $request->longitude, $request->keterangan, $request->status, $request->kategori);
		
		$return = [];
		$return[0]['status'] = 'sukses';
		echo json_encode($return);
	}
	
	public function hapus_layanan_salon(Request $request){
		$this->auto_cekbooking();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$this->auto_cekharilibur();
		$this->auto_cekstatusvoucher();
		$cari = layanansalon::find($request->id)->delete();
		
		$return = [];
		$return[0]['status'] = 'sukses';
		echo json_encode($return);
	}
	
	public function getidkat (){
		$this->auto_cekbooking();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$this->auto_cekharilibur();
		$this->auto_cekstatusvoucher();
		$userbaru = new kategori;
		$hsl = $userbaru->getidkat();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getnamakat (Request $request){
		$this->auto_cekbooking();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$this->auto_cekharilibur();
		$this->auto_cekstatusvoucher();
		$userbaru = new kategori;
		$hsl = $userbaru->getnamakat($request->idkategori);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getharilibur (Request $request){
		$this->auto_cekbooking();
		$this->auto_selesai_iklan();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_cekstatusvoucher();
		$userbaru = new hari_libur;
		$hsl = $userbaru->getharilibur($request->idsalon);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function delkategori (Request $request){
		$this->auto_cekbooking();
		$this->auto_selesai_iklan();
		$userbaru = new kategori;
		$this->auto_cekharilibur();
		$this->auto_cekstatusvoucher();
		$hsl = $userbaru->delkategori($request->idkategori, $request->namakategori);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function daftariklan(Request $request){		
		$this->auto_cekbooking();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$this->auto_cekharilibur();
		$this->auto_cekstatusvoucher();
		$datagambar = base64_decode($request->mimage);
		file_put_contents("gambar/".$request->mfile, $datagambar);
		
		//cari username salon dulu
		$cari = salon::find($request->idsalon);
		$usersal = $cari->username;
		
		//cek saldo cukkup apa nda
		$cek = users::find($usersal);
		$tempsaldo = $cek->saldo;
		
		//cek jumlah iklan max 10
		$userbaru = new iklan;
		$jumiklan = $userbaru->get_jum_iklan();
		
		if(count($jumiklan) < 10){
			if($tempsaldo >= $request->hargaiklan){
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
				
				
				//kurang saldo
				$kurang = users::find($usersal);
				$kurang->saldo -= $request->hargaiklan;
				$kurang->save();
				
				$hsl = 'sukses';
			}else{
				$hsl = 'saldo';	
			}	
		}else{
			$hsl = 'penuh';	
		}
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function insert_report(Request $request){		
		$this->auto_cekbooking();	
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$this->auto_cekharilibur();		
		$this->auto_cekstatusvoucher();
		
		$usersbaru = new report;
		$usersbaru ->insert_report($request->username1,$request->username2,$request->alasan,$request->mfile);
		
		if($request->mfile != null || $request->mfile != ''){
			$datagambar = base64_decode($request->mimage);
			file_put_contents("gambar/".$request->mfile, $datagambar);
		}else{}
		
		$return = [];
		$return[0]['status'] = 'sukses';
		echo json_encode($return);
	}
	
	public function beri_penilaian(Request $request){
		$this->auto_cekbooking();
		$this->auto_mulai_iklan();
		$this->auto_cekharilibur();
		$this->auto_selesai_iklan();
		$this->auto_cekstatusvoucher();
		//ini masuk ke table rating salon
		$userbaru = new rating_salon;
		$userbaru->id 				= 0;
		$userbaru->idsalon	 		= $request->idsalon;
		$userbaru->idlayanan		= $request->idlayanan;
		$userbaru->idbooking		= $request->idbooking;
		$userbaru->rating_layanan	= $request->rating_layanan;
		$userbaru->ulasan			= $request->ulasan;
		$userbaru->save();
		
		//ini update rating yang dibagian table salon utnuk di tmapilkan ke halaman lain [ulsan ditable salon a/ jumlah user yang sudah beri rating.. beda sama table rating salon yg isinya deskripsi rating tiap cus]
		$usersbaru = new rating_salon;		
		$hsl = $usersbaru->getRating($request->idsalon);
		
		$temp_rating = 0;
		
		for($i = 0; $i < count($hsl); $i++){
			$temp_rating  = $temp_rating + $hsl[$i]->rating_layanan;
		}
		
		$qry = new salon;
		$hsl = salon::find($request->idsalon);	
		$tempulasan = $hsl->ulasan + 1;
		
		$temp_hsl_rating = $temp_rating / $tempulasan;
		
		
		$hsl = salon::where('id','=',$request->idsalon)->first();	
				$hsl->rating		= $temp_hsl_rating;
				$hsl->ulasan		= $tempulasan;
				$hsl->save();
		
		$cari = bookingservice::where('id','=',$request->id)->first();
		$cari->status		= 'selesairating';
		$cari->save();
		
		$return = [];
		$return[0]['status'] = 'sukses';
		echo json_encode($return);
	}
	
	public function statussalon(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_selesai_iklan();
		$this->auto_mulai_iklan();
		$this->auto_cekstatusvoucher();
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
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_selesai_iklan();
		$this->auto_mulai_iklan();
		$this->auto_cekstatusvoucher();
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
	$this->auto_cekbooking();
	$this->auto_cekstatusvoucher();
	$this->auto_selesai_iklan();
	$this->auto_mulai_iklan();
		$this->auto_cekharilibur();
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
		$this->auto_cekbooking();
		$this->auto_cekstatusvoucher();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$this->auto_cekharilibur();
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
		$this->auto_cekbooking();
		$this->auto_cekstatusvoucher();
		$this->auto_mulai_iklan();
		$this->auto_cekharilibur();
		$usersbaru = new bookingservice;
		$usersbaru ->updatestatusreschedule($request->id,$request->status,$request->statusreschedule);
				
		$return = [];
		$return[0]['status'] = 'sukses';
		echo json_encode($return);
	}
	
	public function konfirm_kodepesanan(Request $request){
		$usersbaru = new bookingservice;
		$this->auto_cekstatusvoucher();
		$this->auto_selesai_iklan();
		$this->auto_mulai_iklan();
		$this->auto_cekharilibur();
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
		$this->auto_cekbooking();
		$this->auto_cekstatusvoucher();
		$this->auto_cekharilibur();
		$this->auto_selesai_iklan();
		$this->auto_mulai_iklan();
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
			$qry->save();
			$qry = 'gagal';
		}else{
			$qry  = bookingservice::find($request->id);	
			$qry->jamres 	 		= $request->jamres;
			$qry->tglres 	 		= $request->tglres;
			$qry->statusreschedule	= 'pending';
			$qry->jamresselesai	= $jambookingselesai;
			$qry->save();
			$qry ='sukses';
		}
		
		$return = [];
		$return[0]['status'] = $qry;
		echo json_encode($return);
	}
	
	public function tambah_kurang_administrasi(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekstatusvoucher();
		$this->auto_cekharilibur();
		$this->auto_selesai_iklan();
		$this->auto_mulai_iklan();
		$userbaru = new users;
		if($request->pembayaran == 'saldo'){
			$userbaru ->tambah_administrasi_saldo($request->username,$request->total_kirim,$request->service_charge);
		}else{
			$userbaru ->tambah_kurang_administrasi($request->username,$request->service_charge);
			//kalo pake cash ya cuma ngurangin saldo salon untuk servis charge
		}
		
		$kembalian = users::select('users.*')
					 ->where('username','=',$request->username)
					 ->get();
		$return = [];
		$return[0]['status'] = $kembalian;
		echo json_encode($return);
	}
	
	public function updatestatusbooking(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekstatusvoucher();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$usersbaru = new bookingservice;
		
		//ngambil datanya 
		$tempid 		= $request->id;
		$cek_pembayaran = bookingservice::find($tempid);
		$total 	  		= $cek_pembayaran->total;
		$username 		= $cek_pembayaran->username;
		
		//ini ngecek jenis pembayaran saldo apa cash
		if($cek_pembayaran->pembayaran == 'saldo'){
			//uang balik kalo cancel
			if($request->status == 'cancel'){
				$users_saldo = new users;
				$users_saldo ->uang_kembali($total,$username);
				$usersbaru ->updatestatusbooking($request->id,$request->status,$request->usernamecancel,$request->keterangan);
			}else{
				$usersbaru ->updatestatusbooking($request->id,$request->status,$request->usernamecancel,$request->keterangan);
			}			
		}else{
			$usersbaru ->updatestatusbooking($request->id,$request->status,$request->usernamecancel,$request->keterangan);
		}
						
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
		$this->auto_cekbooking();
		$this->auto_cekstatusvoucher();
		$this->auto_cekharilibur();
		$this->auto_selesai_iklan();
		$this->auto_mulai_iklan();
		$usersbaru = new jadwalsalon;
		$usersbaru ->updatejadwalsalon($request->idsalon,$request->hari,$request->jambuka, $request->jamtutup);
		
		$return = [];
		$return[0]['status'] = "sukses";
		echo json_encode($return);
	}
	
	public function getalliklansalon(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekstatusvoucher();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$usersbaru = new iklan;
		$hsl = $usersbaru ->getalliklansalon();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getiklan_voucher(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekstatusvoucher();
		$this->auto_cekharilibur();
		$this->auto_selesai_iklan();
		$this->auto_mulai_iklan();
		$usersbaru = new master_voucher;
		$hsl = $usersbaru->getiklan_voucher();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getiklan_voucher_aktif(){
		$this->auto_cekbooking();
		$this->auto_cekstatusvoucher();
		$this->auto_cekharilibur();
		$this->auto_selesai_iklan();
		$this->auto_mulai_iklan();
		$usersbaru = new master_voucher;
		$hsl = $usersbaru->getiklan_voucher_aktif();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	//ini join buat halaman salon
	public function getiklan_voucher_aktif_join_tr(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekstatusvoucher();
		$this->auto_cekharilibur();
		$this->auto_selesai_iklan();
		$this->auto_mulai_iklan();
		$usersbaru = new master_voucher;
		$hsl = $usersbaru->getiklan_voucher_aktif_join_tr($request->idsalon);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function autoselesai(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekstatusvoucher();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$usersbaru = new iklan;
		$hsl = $usersbaru ->autoselesai();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getallfavoritjoinuser(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekstatusvoucher();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		
		$usersbaru = new favorit;
		$hsl = $usersbaru ->getallfavoritjoinuser($request->username);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function terima_iklan(Request $request){
		$usersbaru = new iklan;
		
		$hari = date("Y-m-d");
		//ngecek tanggal mulai iklan apakah hari ini
		$cekmulai = iklan::find($request->idiklan);
		if($hari == $cekmulai->tanggal_awal){
			if($request->status == 'aktif'){
				$usersbaru ->terima_iklan($request->idiklan,'aktif');
			}else{
				//cari jum uang dari id iklan
				$total = iklan::find($request->idiklan);
				$temptot = $total->hargaiklan;
				
				//balikin uang salon
				$cari = users::find($request->username);
				$cari->saldo += $temptot;
				$cari->save();
				
				$usersbaru ->terima_iklan($request->idiklan,$request->status);
			}
		}else{
			if($request->status == 'terima'){
				$usersbaru ->terima_iklan($request->idiklan,'terima');
			}else{
				//cari jum uang dari id iklan
				$total = iklan::find($request->idiklan);
				$temptot = $total->hargaiklan;
				
				//balikin uang salon
				$cari = users::find($request->username);
				$cari->saldo += $temptot;
				$cari->save();
				
				$usersbaru ->terima_iklan($request->idiklan,$request->status);
			}
		}
		
		$this->auto_cekbooking();
		$this->auto_cekstatusvoucher();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();		
		
		$return = [];
		$return[0]['status'] = $request->status;
		echo json_encode($return);
	}
	
	public function getlistbookingwithlayanansemua(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekstatusvoucher();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
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
	
	public function auto_cekbooking (){
		$jam = date("H:i:s");
		$tgl = date("Y-m-d");
		$user = bookingservice::all();
		
		for($i=0; $i<count($user); $i++){
			$temp = $user[$i]->jambooking;
			if($jam > $temp && $user[$i]->status == 'pending' && $user[$i]->tanggal_booking == $tgl){
				$user[$i]->status = 'tolak';
				$user[$i]->save();
			}
		}		
	}
	
	public function auto_mulai_iklan (){
		$tgl = date("Y-m-d");
		$user = iklan::all();
		
		for($i=0; $i<count($user); $i++){
			$temp = $user[$i]->tanggal_awal;
			if($tgl == $temp && $user[$i]->status == 'terima'){
				$user[$i]->status = 'aktif';
				$user[$i]->save();
			}
		}		
	}
	
	public function auto_selesai_iklan (){
		$tgl = date("Y-m-d");
		$user = iklan::all();
		
		for($i=0; $i<count($user); $i++){
			$temp = $user[$i]->tanggal_akhir;
			if($tgl > $temp && $user[$i]->status == 'aktif'){
				$user[$i]->status = 'selesai';
				$user[$i]->save();
			}
		}		
	}
	
	public function auto_cekharilibur (){
		$jam = date("H:i:s");
		$tgl = date("Y-m-d");
		
		$cek_status_0 = hari_libur::select('hari_libur.*')
			->where('tanggal','=',$tgl)
			->where('status','=','0')
			->where('jam1','<=',$jam)
			->Orwhere('jam2','>=',$jam)
			->where('tanggal','=',$tgl)
			->where('status','=','0')
			->get();
			
		$cek_status_1 = hari_libur::select('hari_libur.*')
			->where('tanggal','=',$tgl)
			->where('jam2', '<', $jam)
			->where('status','=','1')
			->get();
		
		if(count($cek_status_0) > 0){
			for($i=0; $i<count($cek_status_0); $i++){
				$cek_status_0[$i]->status = 1;
				$cek_status_0[$i]->save();
			}		
		}
		if(count($cek_status_1) > 0){
			for($i=0; $i<count($cek_status_1); $i++){
				$cek_status_1[$i]->status = 2;
				$cek_status_1[$i]->save();
			}			
		}
	}
	
	public function auto_cekstatusvoucher (){
		$jam = date("H:i:s");
		$tgl = date("Y-m-d");
		
		$cek_status = transaksi_voucher::select('transaksi_voucher.*')
			->where('tanggal_exp','<',$tgl)
			->where(function ($query) {
				$query->where('status', '=', 'aktif')
				  ->orWhere('status', '=', 'non-aktif');
			})
			->get();
		
		if(count($cek_status) > 0){
			for($i=0; $i<count($cek_status); $i++){
				$cek_status[$i]->status = 'selesai';
				$cek_status[$i]->save();
			}		
		}
	}
	
	public function getlistbookingwithlayanan(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$usersbaru = new bookingservice;		
		$hsl = $usersbaru->getlistbookingwithlayanan($request->idsalon);
		
		$usersbaru = new bookingservice;		
		$hsl1 = $usersbaru->getusername_cancel_tgl($request->idsalon);
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
	
	public function getlistbookingwithlayananuser(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$usersbaru = new bookingservice;		
		$hsl = $usersbaru->getlistbookingwithlayananuser($request->username);
		
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getlistbookingwithlayananuserselesai(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$usersbaru = new bookingservice;		
		$hsl = $usersbaru->getlistbookingwithlayananuserselesai($request->username);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getlistbooking_laporan_customer(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$usersbaru = new bookingservice;
		
		$hsl = $usersbaru->getlistbooking_laporan_customer($request->username,$request->tanggal_awal,$request->tanggal_akhir,$request->status);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function sendNotification_user(Request $request)
    {
		//get token dulu
		$cari = users::find($request->username);		
        $tkn = $cari->token; 
		
		//$rtkn = []; 
        //array_push($rtkn, $tkn); 
        $ttt = base64_decode('QUFBQTJvRXZRclE6QVBBOTFiRTdHMkp2NmRLSlZMby1vcEh5ZkdxRVpFS2tyT0xBdkpRQVNsdS0zYk5hdHhuaEhLTEM3elR3UkRHSXc1Z3Q4UXNCX0kxUkhRQlAxalRyTUJKRk9tSzJJSGVUbktvUThfaVlYRWJ1MFEybTVoSERBblY2anhSdm8xZ2pVRnl2ZWlKMkRGWUU');

        $data = [
            "registration_ids" => $tkn,
            "notification" => [
                "title" => "Salon Online",
                "body" => $request->message,  
            ]
        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $ttt,
            'Content-Type: application/json',
        ];
  
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
              
        $response = curl_exec($ch);

        dd($response);
    }
	
	public function sendNotification()
    {
        $rtkn = []; 
        $tkn = "dqR4-6R7S4K23B7Vmb_W_h:APA91bGTUBPtOL2YxmbqNYYBhLsfOAgsjHxEHXqP1cEzXCQpnAvFtHLDofgN_-0xDE9WcC-gFsCR-cJa6jTcscCadUxCm30ci2YhAT8VrqaqYfTyJxDegj0jjLAp8P3ylngrd2SBudva"; 
        array_push($rtkn, $tkn); 
        $ttt = base64_decode('QUFBQTJvRXZRclE6QVBBOTFiRTdHMkp2NmRLSlZMby1vcEh5ZkdxRVpFS2tyT0xBdkpRQVNsdS0zYk5hdHhuaEhLTEM3elR3UkRHSXc1Z3Q4UXNCX0kxUkhRQlAxalRyTUJKRk9tSzJJSGVUbktvUThfaVlYRWJ1MFEybTVoSERBblY2anhSdm8xZ2pVRnl2ZWlKMkRGWUU');

        $data = [
            "registration_ids" => $rtkn,
            "notification" => [
                "title" => "hello",
                "body" => "dari laravel ayo makan",  
            ]
        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $ttt,
            'Content-Type: application/json',
        ];
  
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
              
        $response = curl_exec($ch);

        dd($response);
    }
	
	public function sendNotification_salon(Request $request)
    {
		//get username salon dulu
		$cari = salon::find($request->idsalon);	
		$username = $cari->username;
		
		//get token
		$caritoken = users::find($username);
        $tkn = $caritoken->token; 
		
		$rtkn = []; 
        array_push($rtkn, $tkn); 
        $ttt = base64_decode('QUFBQTJvRXZRclE6QVBBOTFiRTdHMkp2NmRLSlZMby1vcEh5ZkdxRVpFS2tyT0xBdkpRQVNsdS0zYk5hdHhuaEhLTEM3elR3UkRHSXc1Z3Q4UXNCX0kxUkhRQlAxalRyTUJKRk9tSzJJSGVUbktvUThfaVlYRWJ1MFEybTVoSERBblY2anhSdm8xZ2pVRnl2ZWlKMkRGWUU');

        $data = [
            "registration_ids" => $rtkn,
            "notification" => [
                "title" => "Salon Online",
                "body" => $request->message,  
            ]
        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $ttt,
            'Content-Type: application/json',
        ];
  
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
              
        $response = curl_exec($ch);

        dd($response);
    }
	
	public function login(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$usersbaru = new users;		
		$hsl = $usersbaru->logindata($request->username,$request->password);
		
		if(count($hsl) > 0){
			$temp = $hsl[0]->username;
			$qry = new salon;
			$qry1 = $qry->findidsalon($temp);
			
			$cari  = users::find($request->username);	
			$cari->token = $request->token;
			$cari->save();
		}else{
			$qry1 ='gagal';
		}		
		
		$return = [];
		$return[0]['status'] = $hsl;
		$return[0]['idsalon'] = $qry1;
		echo json_encode($return);
	}
	
	public function getuser(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$usersbaru = new users;		
		$hsl = $usersbaru->getusers($request->username);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getuserswithsalondgnusername(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new users;		
		$hsl = $usersbaru->getuserswithsalondgnusername($request->username);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getjadwalsalon(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
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
	
	public function getjadwalsalon_another_day(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$tgl = $request->tanggal;
		$hsl = date('l', strtotime($tgl));
		
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
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
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
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$usersbaru = new users;		
		$hsl = $usersbaru->getuserswithsalon();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getlayanansalon(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$usersbaru = new layanansalon;		
		$hsl = $usersbaru->getlayanansalon($request->idsalon);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getlayanansalon_halamansalon(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$usersbaru = new layanansalon;		
		$hsl = $usersbaru->getlayanansalon_halamansalon($request->idsalon);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getlayanansalondetail(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$usersbaru = new layanansalon;		
		$hsl = $usersbaru->getlayanansalondetail($request->idsalon, $request->namalayanan);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getlayananwithuser(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$usersbaru = new layanansalon;		
		$hsl = $usersbaru->getlayananwithuser($request->username);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function carisalon(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$usersbaru = new salon;	
		$temp = $request->username;
		$temp1 = $request->kategori;
		//kondisi username harus ada [apakah username ada isinya? && kategori kosong? || kategori ada isinya? && username kosong??
		if(!empty($temp) && empty($temp1) || !empty($temp1) && empty($temp) || !empty($temp1) && !empty($temp)){
			$hsl=$usersbaru->carisalon($request->username,$request->kategori);
		}else{
			$hsl="";
		}
		
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getListChatUser(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
        $model = new chat;
        $hsl = $model->getListChatUser($request->username);
        $return[0]['listchat'] = $hsl;
        echo json_encode($return);
    }
	
	public function cekPesan(Request $req){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
        $model = new chat;
        $hsl = $model->cekUsernamePesan($req->username1, $req->username2);
        if(count($hsl) == 0){
            $chatbaru = new chat;
            $chatbaru->id = 0;
            $chatbaru->username1 = $req->username1;
            $chatbaru->username2 = $req->username2;
            $chatbaru->save();
            $chatbaru = new chat;
            $chatbaru->id = 0;
            $chatbaru->username1 = $req->username2;
            $chatbaru->username2 = $req->username1;
            $chatbaru->save();
            $status = "selesai";
        }
        else{
            $status = "gagal";
        }
        $return[0]['status'] = $status;
        echo json_encode($return);
    }
	
	public function getinfo_salon(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$dt = salon::select('salon.*')
			->where('id','=',$request->idsalon)
			->get();
			
		$return = [];
		$return[0]['status'] = $dt;
		echo json_encode($return);
	}
	
	public function getdatauser(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$usersbaru = new users;		
		$hsl = $usersbaru->getdatauser($request->username);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getrole(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new users;		
		$hsl = $usersbaru->getrole($request->username);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getiklan(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new iklan;		
		$hsl = $usersbaru->getiklan($request->idsalon);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getiklan_sisisalon(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new iklan;		
		$hsl = $usersbaru->getiklan_sisisalon($request->idsalon);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function deletefav(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new favorit;		
		$usersbaru->deletefav($request->idfavorit);
		
		$return = [];
		$return[0]['status'] = "sukses";
		echo json_encode($return);
	}
	
	public function getiklan_admin(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new iklan;		
		$hsl = $usersbaru->getiklan_admin();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getiklan_admin_acc(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new iklan;		
		$hsl = $usersbaru->getiklan_admin_acc();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function finduser(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new users;		
		$hsl = $usersbaru->finduser($request->nama);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getsemuauser(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new users;		
		if($request->nama == ""){
			$hsl = $usersbaru->getsemuauser();
		}else{
			$hsl = $usersbaru->cariuser($request->nama);//cari user
		}
		
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function block_unblock_user(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new users;		
		$hsl = $usersbaru->block_unblock_user($request->username,$request->status);
				
		$return = [];
		$return[0]['status'] = users::all();
		echo json_encode($return);
	}
	
	public function transaksi_terakhir(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new transaksi;		
		$hsl = $usersbaru->transaksi_terakhir($request->username);
				
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function transaksi_terakhir_laporan(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new transaksi;		
		$hsl = $usersbaru->transaksi_terakhir_laporan($request->username,$request->tanggal_awal,$request->tanggal_akhir,$request->status);
				
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function laporan_salon_penjualan(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new bookingservice;		
		$hsl = $usersbaru->laporan_salon_penjualan($request->idsalon,$request->bulan,$request->status);
				
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function laporan_salon_keuntungan_penjualan(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new bookingservice;		
		$hsl = $usersbaru->laporan_salon_keuntungan_penjualan($request->idsalon,$request->bulan,$request->status);
				
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function laporan_salon_iklan(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new iklan;		
		$hsl = $usersbaru->laporan_salon_iklan($request->idsalon,$request->bulan,$request->status);
				
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function report_terakhir_laporan(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new report;		
		$hsl = $usersbaru->report_terakhir_laporan($request->username,$request->bulan,$request->status);
				
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function update_status_report(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$cari = report::find($request->id);
		$cari->status = $request->status;
		$cari->save();
				
		$return = [];
		$return[0]['status'] = users::all();
		echo json_encode($return);
	}
	
	public function gettopupsaldobank(){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new transaksi;		
		$hsl = $usersbaru->gettopupsaldobank();
				
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getwithdraw(){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new transaksi;		
		$hsl = $usersbaru->getwithdraw();
				
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function gettopup_histori(){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new transaksi;		
		$hsl = $usersbaru->gettopup_histori();
				
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getreport_histori(){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new report;		
		$hsl = $usersbaru->getreport_histori();
				
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function get_report(){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new report;		
		$hsl = $usersbaru->getreport();
				
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getwithdraw_histori(){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new transaksi;		
		$hsl = $usersbaru->getwithdraw_histori();
				
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function tambahsaldo_user(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$qry  = users::find($request->username);	
		$qry->saldo	 += $request->jumlah;
		$qry->save();
		
		$qry1  = transaksi::find($request->id);	
		$qry1->status		= $request->status;
		$qry1->save();
		$hsl = $request->status;
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function kurang_saldo_user(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$qry  = users::find($request->username);	
		$qry->saldo	 -= $request->jumlah;
		$qry->save();
		
		$qry1  = transaksi::find($request->id);	
		$qry1->status		= $request->status;
		$qry1->save();
		$hsl = $request->status;
		
		$return = [];
		//$return[0]['status'] = $hsl;
		$return[0]['status'] = 'sukses';
		echo json_encode($return);
	}
	
	public function changemap(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$qry  = salon::where('username','=',$request->username)->first();
		$qry->longitude	= $request->longitude;
		$qry->latitude	= $request->latitude;
		$qry->save();
				
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getidsalon(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new salon;		
		$hsl = $usersbaru->getidsalon($request->username);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function ubahstatus(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new users;
		$usersbaru ->updatestatus($request->username, $request->datastatus);
		
		$return = [];
		$return[0]['status'] = "sukses";
		echo json_encode($return);
	}
	
	public function getallsalon(){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new salon;		
		$hsl = $usersbaru->getallsalon();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getRating(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new rating_salon;		
		$hsl = $usersbaru->getRating($request->idsalon);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getallsalonuser(){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new users;		
		$hsl = $usersbaru->getallsalonuser();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getallsalonuser_seeall(){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new users;		
		$hsl = $usersbaru->getallsalonuser_seeall();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getallsalonuser_terpopuler(){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new users;		
		$hsl = $usersbaru->getallsalonuser_terpopuler();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	public function getallsalonuser_selaludiskon(){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new users;		
		$hsl = $usersbaru->getallsalonuser_selaludiskon();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getallsalonuser_24jam(){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new users;		
		$hsl = $usersbaru->getallsalonuser_24jam();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function insertpegawai(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
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
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
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
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new layanansalon;
		$hsl = $usersbaru->iskembarlayanan($request->namalayanan,$request->idsalon);
				
		if(count($hsl) > 0){
			$hsl = 'gagal';
		}else{
			$datagambar = base64_decode($request->mimage);
			file_put_contents("gambar/".$request->mfile, $datagambar);
			
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
		}
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function transaksiTopUp_bank(Request $request){
		$tgl = date("Y-m-d");
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$usersbaru = new transaksi;
		$usersbaru->id				= 0;
		$usersbaru->atasnama		= $request->atasnama;
		$usersbaru->nama_bank 		= $request->nama_bank;
		$usersbaru->jenis_transaksi = $request->jenis_transaksi;
		$usersbaru->melalui			= $request->melalui;
		$usersbaru->norek 			= $request->norek;
		$usersbaru->jumlah			= $request->jumlah;
		$usersbaru->status	 		= $request->status;
		$usersbaru->foto	 		= $request->mfile;
		$usersbaru->tanggal	 		= $tgl;
		$usersbaru->save();
		$datagambar = base64_decode($request->mimage);
		file_put_contents("gambar/".$request->mfile, $datagambar);
		
		$return = [];
		$return[0]['status'] = "sukses";
		echo json_encode($return);
	}
		
	public function insertbookingservice(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		//ambil persentase pembayaran ke admin
		$persentase = seting::select('seting.*')->first();
		$totl = $request->total;
		$biaya_admmin = $totl * ($persentase->persentase / 100);
		
		//ngecek saldo salon apakah >= dari jumlah biaya admin apabila menggunakan cash	
		$cari_saldo = new users;
		$hsl = $cari_saldo->cari_saldo($request->idsalon,$biaya_admmin);
		
		if($hsl->saldo >= $biaya_admmin){
			$statussalon = salon::find($request->idsalon);
			$cek = $statussalon->status;
			
			$usersbaru  = new bookingservice;	
			$durasi = layanansalon::find($request->idservice);
			$jambookingselesai = $request->jambooking;
			$jambookingselesai = date('H:i:s', strtotime($jambookingselesai . $durasi->durasi .'minutes'));
			$jambookingselesai = date('H:i:s', strtotime($jambookingselesai . $durasi->toleransi_keterlambatan .'minutes'));
			
			//$idsalon, $idservice, $tanggalbooking, $jambooking,$idpegawai
			$validbooking = $this->isvalidbooking($request->idsalon, $request->idservice, $request->tanggalbooking, $request->jambooking, $request->idpegawai); 
			//$total_pendapatan = $request->total - $biaya_admmin;
			
			$tgl = date("Y-m-d");
			
			//ini cek salon tuutp apa nda
			if($cek == "aktif"){
				$ceklibursalon = hari_libur::select('hari_libur.*')
								->where('idsalon','=',$request->idsalon)
								->where('tanggal','=',$request->tanggalbooking)
								->where('status','=', 1)
								->get();
				
				//cek salon ada hari libur dihari booking nda
				if(count($ceklibursalon) == 0 ){
					$kode_pesanan = Str::random(5);
				
					if($validbooking == 0){		
						//pengecekan apakah user di blok sama salon nda
						$isblocked = new bookingservice;
						$temp = $isblocked->check_isblocked($request->username,$request->idsalon);
						
						if(count($temp) == 0){
							$usersbaru->id					= 0;
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
							$usersbaru->service_charge   	= $biaya_admmin;
							$usersbaru->save();
							$isvalidbooking = 'sukses';
							if($request->pembayaran == 'saldo'){
								$userbaru  = new users;	
								$userbaru ->kurang_administrasi_saldo_customer($request->username,$request->total);
							}
						}else{
							$isvalidbooking = 'blocked';
						}
					}else if($validbooking == -2){
						$isvalidbooking = '-2';
					}else {
						$isvalidbooking = '-1';
					}
				}else{
					$isvalidbooking = 'salonlibur';
				}
			}else{
				$isvalidbooking= 'tutup';
			}
			// kalo return -2 = pegawai tsb berhalangan
			// kalo return -1 = jam tsb sudah penuh booking
			$return = [];
			$return[0]['status'] = $isvalidbooking;
			echo json_encode($return);
		}else{
			$return = [];
			$return[0]['status'] ='gagal';
			echo json_encode($return);
		}		
	}
	
	function getquotalayanan($idservice)
    {
		$this->auto_cekbooking();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		//$idlayanan
		 $br = layanansalon::where('id', '=', $idservice)
				->first();
		 $qry  = $br->jumlah_kursi;
		return $qry;
    }
	
	function getquotapegawai($idsalon, $tanggal)
    {
		$this->auto_cekbooking();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
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
		$this->auto_cekbooking();
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
	   $this->auto_cekbooking();
	   $this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
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
		$this->auto_cekbooking();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
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
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		
		$this->auto_cekstatusvoucher();
		$usersbaru = new pegawai;		
		$hsl = $usersbaru->getidpegawai($request->nama);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getpegawai(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekstatusvoucher();
		$this->auto_cekharilibur();
		$usersbaru = new pegawai;		
		$hsl = $usersbaru->getpegawai($request->idsalon,$request->kodelayanan);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getpegawai_halamanmember(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_cekstatusvoucher();
		$usersbaru = new pegawai;		
		$hsl = $usersbaru->getpegawai_halamanmember($request->idsalon,$request->kodelayanan);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getabsensipegawai(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_cekstatusvoucher();
		$usersbaru = new absensi_pegawai;		
		$hsl = $usersbaru->getabsensipegawai($request->idsalon);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getpegawai_absen(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_cekstatusvoucher();
		$usersbaru = new pegawai;		
		$hsl = $usersbaru->getpegawai_absen($request->idsalon);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function insertabsenpegawai(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekstatusvoucher();
		$this->auto_cekharilibur();
		$tgl = date("Y-m-d");
		$qry = absensi_pegawai::select('absensi_pegawai.*')
				->where('tanggal','=',$tgl)
				->where('nama','=',$request->nama)
				->get();
		//find idpegawai
		$cari = pegawai::select('pegawai.*')
				->where('nama','=',$request->nama)
				->where('idsalon','=',$request->idsalon)
				->get();
		$idpeg = $cari[0]->id;
		
		$dt = bookingservice::select('bookingservice.*')
				->where('tanggalbooking' ,'=', $tgl)
				->where('idpegawai' ,'=', $idpeg)
				->where('status' ,'=', 'terima')
				->Orwhere('status' ,'=', 'pending')
				->where('tanggalbooking' ,'=', $tgl)
				->where('idpegawai' ,'=', $idpeg)
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
		$this->auto_cekbooking();
		$this->auto_cekstatusvoucher();
		$this->auto_cekharilibur();
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
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$this->auto_cekstatusvoucher();
		$usersbaru = new pegawai;		
		//$hsl = $usersbaru->getdatapegawai($request->idsalon);
		$hsl = $usersbaru->getpegawai($request->idsalon);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getdatapegawai_tampil(Request $request){
		$usersbaru = new pegawai;	
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$this->auto_cekharilibur();		
		$this->auto_cekbooking();
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
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_cekstatusvoucher();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$usersbaru = new users;		
		$hsl = $usersbaru->getsaldouser($request->username);
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function getkategori(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_cekstatusvoucher();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$usersbaru = new kategori;		
		$hsl = $usersbaru->getallkategori();
		
		$return = [];
		$return[0]['status'] = $hsl;
		echo json_encode($return);
	}
	
	public function insertdetailpegawai(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
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
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$this->auto_cekstatusvoucher();
		$usersbaru = new bookingservice;
		
		//find idpegawai
		$cari = pegawai::select('pegawai.*')
				->where('nama','=',$request->requestpegawai)
				->where('idsalon','=',$request->idsalon)
				->get();
		$idpeg = $cari[0]->id;
		
		$hsl  = $usersbaru ->cancelsemuabooking($request->idsalon, $idpeg,$request->tanggal);
		
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
	
	public function update_status_pegawai(Request $request){
		$this->auto_cekbooking();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$this->auto_cekstatusvoucher();
				
		//find idpegawai
		$cari = pegawai::find($request->id);
		$cari->status = $request->status;
		$cari->save();
		
		$return = [];
		$return[0]['status'] = $cari->status;
		echo json_encode($return);
	}
	
	public function getallidkategori (Request $request){
		$this->auto_cekbooking();
		$this->auto_cekstatusvoucher();
		$this->auto_cekharilibur();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
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
		$this->auto_cekbooking();
		$this->auto_mulai_iklan();
		$this->auto_selesai_iklan();
		$this->auto_cekstatusvoucher();
		$this->auto_cekharilibur();
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

