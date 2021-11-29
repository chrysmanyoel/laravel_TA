<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Carbon\Carbon;

class bookingservice extends Model
{
    public $incrementing = true;
	protected $table = 'bookingservice';
	protected $primaryKey = 'id';
	protected $fillable = [
		'id',
		'tanggal',
		'username',
		'namauser',
		'usernamesalon',
		'idservice',
		'tanggalbooking',
		'jambooking',
		'jambookingseesai',
		'idpegawai',
		'pembayaran',
		'total',
		'usernamecancel',
		'status',
		'jamres',
		'tglres',
		'statusreschedule',
		'kode_pesanan',
		'keterangan'
	];
	public $timestamps = false;
	public function getlistbookingwithlayanan($idsalon){
	
		$tgl = date("Y-m-d");
        return bookingservice::select('bookingservice.*', 'layanansalon.peruntukan','layanansalon.namalayanan', 'layanansalon.jenjangusia', 'layanansalon.durasi','users.foto','salon.namasalon','pegawai.nama as namapegawai','salon.kota','bookingservice.kode_pesanan','layanansalon.toleransi_keterlambatan')
				->join('layanansalon', 'layanansalon.id', '=', 'bookingservice.idservice')
				->join('salon', 'salon.username', '=', 'layanansalon.username')
				->join('pegawai', 'pegawai.id', '=', 'bookingservice.idpegawai')
				->join('users', 'users.username', '=', 'salon.username')
				->where('bookingservice.status','=', 'terima')
				->where('bookingservice.tanggalbooking','=',$tgl)
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.status','=', 'pending')
				->where('bookingservice.tanggalbooking','=',$tgl)
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.status','=', 'rescheduleuser')
				->Orwhere('bookingservice.statusreschedule','=', 'pending')
				->where('bookingservice.tglres','=',$tgl)
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.status','=', 'reschedulesalon')
				->Orwhere('bookingservice.statusreschedule','=', 'pending')
				->where('bookingservice.tglres','=',$tgl)
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.status','=', 'datang')
				->where('bookingservice.tanggalbooking','=',$tgl)
				->where('bookingservice.idsalon','=',$idsalon)
				->orderBy('bookingservice.tanggalbooking', 'asc')
				->orderBy('bookingservice.jambooking', 'asc')
				->get();
    }
	
	public function getusername_cancel_tgl($idsalon){
		$tgl = date("Y-m-d");
        return bookingservice::select('username')
				->where('bookingservice.status','=', 'terima')
				->where('bookingservice.tanggalbooking','=',$tgl)
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.status','=', 'pending')
				->where('bookingservice.tanggalbooking','=',$tgl)
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.status','=', 'rescheduleuser')
				->Orwhere('bookingservice.statusreschedule','=', 'pending')
				->where('bookingservice.tglres','=',$tgl)
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.status','=', 'reschedulesalon')
				->Orwhere('bookingservice.statusreschedule','=', 'pending')
				->where('bookingservice.tglres','=',$tgl)
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.status','=', 'datang')
				->where('bookingservice.tanggalbooking','=',$tgl)
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.status','=', 'cancel')
				->where('bookingservice.tanggalbooking','=',$tgl)
				->where('bookingservice.idsalon','=',$idsalon)
				->orderBy('bookingservice.tanggalbooking', 'asc')
				->orderBy('bookingservice.jambooking', 'asc')
				->get();
    }
	public function getcount_cancel_tgl($idsalon){
		$tgl = date("Y-m-d");
        return bookingservice::select('username')
				->where('bookingservice.status','=', 'terima')
				->where('bookingservice.tanggalbooking','=',$tgl)
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.status','=', 'pending')
				->where('bookingservice.tanggalbooking','=',$tgl)
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.statusreschedule','=', 'rescheduleuser')
				->where('bookingservice.status','=', 'pending')
				->where('bookingservice.tglres','=',$tgl)
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.statusreschedule','=', 'reschedulesalon')
				->where('bookingservice.status','=', 'pending')
				->where('bookingservice.tglres','=',$tgl)
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.status','=', 'datang')
				->where('bookingservice.tanggalbooking','=',$tgl)
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.status','=', 'cancel')
				->where('bookingservice.idsalon','=',$idsalon)
				->orderBy('bookingservice.tanggalbooking', 'asc')
				->orderBy('bookingservice.jambooking', 'asc')
				->get();
    }
	
	public function getusername_cancel($idsalon){
        return bookingservice::select('username')
				->where('bookingservice.status','=', 'terima')
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.status','=', 'pending')
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.status','=', 'datang')
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.statusreschedule','=', 'rescheduleuser')
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.statusreschedule','=', 'reschedulesalon')
				->where('bookingservice.idsalon','=',$idsalon)
				->orderBy('bookingservice.tanggalbooking', 'asc')
				->orderBy('bookingservice.jambooking', 'asc')
				->get();
    }
	public function getcount_cancel($username){
        return bookingservice::select('usernamecancel')
				->where('bookingservice.status','=', 'terima')
				->where('bookingservice.usernamecancel','=',$username)
				->Orwhere('bookingservice.status','=', 'pending')
				->where('bookingservice.usernamecancel','=',$username)
				->Orwhere('bookingservice.statusreschedule','=', 'rescheduleuser')
				->where('bookingservice.usernamecancel','=',$username)
				->Orwhere('bookingservice.statusreschedule','=', 'reschedulesalon')
				->where('bookingservice.usernamecancel','=',$username)
				->Orwhere('bookingservice.status','=', 'datang')
				->where('bookingservice.usernamecancel','=',$username)
				->Orwhere('bookingservice.status','=', 'cancel')
				->where('bookingservice.usernamecancel','=',$username)
				->orderBy('bookingservice.tanggalbooking', 'asc')
				->orderBy('bookingservice.jambooking', 'asc')
				->get();
    }
		
	public function getlistbookingwithlayanansemua($idsalon){
        return bookingservice::select('bookingservice.*','layanansalon.namalayanan', 'layanansalon.peruntukan', 'layanansalon.jenjangusia', 'layanansalon.durasi','users.foto')
				->join('layanansalon', 'layanansalon.id', '=', 'bookingservice.idservice')
				->join('salon', 'salon.username', '=', 'layanansalon.username')
				->join('users', 'users.username', '=', 'salon.username')
				->where('bookingservice.status','=', 'terima')
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.status','=', 'pending')
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.status','=', 'rescheduleuser')
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.status','=', 'reschedulesalon')
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.status','=', 'datang')
				->where('bookingservice.idsalon','=',$idsalon)
				->orderBy('bookingservice.tanggalbooking', 'asc')
				->orderBy('bookingservice.jambooking', 'asc')
				->get();
    }
	
	public function getlistbookingwithlayananuser($username){ //ini ttampilin data booking untuk user di sedang berjalan where status != selesai / tolak
       return bookingservice::select('bookingservice.*', 'layanansalon.peruntukan','layanansalon.namalayanan', 'layanansalon.jenjangusia', 'layanansalon.durasi','users.foto','salon.namasalon','pegawai.nama as namapegawai','salon.kota','bookingservice.kode_pesanan')
				->join('layanansalon', 'layanansalon.id', '=', 'bookingservice.idservice')
				->join('salon', 'salon.username', '=', 'layanansalon.username')
				->join('pegawai', 'pegawai.id', '=', 'bookingservice.idpegawai')
				->join('users', 'users.username', '=', 'salon.username')
				->where('bookingservice.status','!=', 'cancel')
				->where('bookingservice.status','!=', 'tidak hadir')
				->where('bookingservice.status','!=', 'tolak')
				->where('bookingservice.status','!=', 'selesai')
				->where('bookingservice.username','=',$username)
				->orderBy('bookingservice.tanggalbooking', 'asc')
				->orderBy('bookingservice.jambooking', 'asc')
				->get();
    }
	
	public function getlistbookingwithlayananuserselesai($username){
        return bookingservice::select('bookingservice.*', 'layanansalon.peruntukan','layanansalon.namalayanan', 'layanansalon.jenjangusia', 'layanansalon.durasi','users.foto')
				->join('layanansalon', 'layanansalon.id', '=', 'bookingservice.idservice')
				->join('salon', 'salon.username', '=', 'layanansalon.username')
				->join('users', 'users.username', '=', 'salon.username')
				->where('bookingservice.username','=',$username)
				->where('bookingservice.status','=', 'selesai')
				->orderBy('bookingservice.tanggalbooking', 'asc')
				->orderBy('bookingservice.jambooking', 'asc')
				->get();
    }
	
	public function updatestatusbooking($id,$status,$usernamecancel,$keterangan){
		$cari = bookingservice::find($id);
		$cari->status = $status;
		$cari->keterangan = $keterangan;
		$cari->usernamecancel = $usernamecancel;
		$cari->save();
	}
	
	public function konfirm_kodepesanan($id,$kodepesanan){
		return bookingservice::select('kode_pesanan')
				->where('id','=',$id)
				->where('kode_pesanan','=',$kodepesanan)
				->get();
	}
	
	public function updatestatusreschedule($id,$status,$statusreschedule){
		$cari = bookingservice::find($id);
		$cari->status = $status;
		$cari->statusreschedule = $statusreschedule;
		$cari->save();
	}
	
	//ini cek diganti
	public function cancelsemuabooking($usernamesalon,$requestpegawai,$tanggal){
		return bookingservice::select("bookingservice.*")
			->where("usernamesalon","=",$usernamesalon)
			->where("tanggalbooking","=",$tanggal)
			->where("requestpegawai","=",$requestpegawai)
			->where("status","=",'terima')
			->Orwhere("status","=",'pending')
			->where("requestpegawai","=",$requestpegawai)
			->where("usernamesalon","=",$usernamesalon)
			->where("tanggalbooking","=",$tanggal)
			->get();		
	}
		
	public function getwaktuyangsama($tanggalbooking,$idpegawai,$jambooking,$jambookingselesai){
		return bookingservice::select("bookingservice.*")
			->where("tanggalbooking","=",$tanggalbooking)
			->where("idpegawai","=",$idpegawai)
			->where("status","=",'pending')
			->whereBetween('jambooking',[$jambooking,$jambookingselesai])
			->orwhereBetween('jambookingselesai',[$jambooking,$jambookingselesai])
			->where("idpegawai","=",$idpegawai)
			->where("status","=",'pending')
			->where("tanggalbooking","=",$tanggalbooking)
			->Orwhere("status","=",'terima')
			->where("idpegawai","=",$idpegawai)
			->where("tanggalbooking","=",$tanggalbooking)
			->whereBetween('jambooking',[$jambooking,$jambookingselesai])
			->orwhereBetween('jambookingselesai',[$jambooking,$jambookingselesai])
			->where("idpegawai","=",$idpegawai)
			->where("status","=",'terima')
			->where("tanggalbooking","=",$tanggalbooking)
			->get();
    }
	
	public function tolaksemua_selainyangdipilih($tempid,$tanggalbooking,$idpegawai,$jambooking,$jambookingselesai){
		return bookingservice::select("bookingservice.*")
			->where("tanggalbooking","=",$tanggalbooking)
			->where("idpegawai","=",$idpegawai)
			->where("status","=",'pending')
			->where("id","!=",$tempid)
			->whereBetween('jambooking',[$jambooking,$jambookingselesai])
			->orwhereBetween('jambookingselesai',[$jambooking,$jambookingselesai])
			->where("idpegawai","=",$idpegawai)
			->where("status","=",'pending')
			->where("id","!=",$tempid)
			->where("tanggalbooking","=",$tanggalbooking)
			->get();
    }
	
	public function cekyangsama_reshcedule($tglres,$idpegawai,$jamres,$jambookingselesai){
		return bookingservice::select("bookingservice.*")
			//pending
			->where("tanggalbooking","=",$tglres)
			->where("idpegawai","=",$idpegawai)
			->where("status","=",'pending')
			->whereBetween('jambooking',[$jamres,$jambookingselesai])
			->orwhereBetween('jambookingselesai',[$jamres,$jambookingselesai])
			->where("idpegawai","=",$idpegawai)
			->where("status","=",'pending')
			->where("tanggalbooking","=",$tglres)
			//terima
			->Orwhere("tanggalbooking","=",$tglres)
			->where("idpegawai","=",$idpegawai)
			->where("status","=",'terima')
			->whereBetween('jambooking',[$jamres,$jambookingselesai])
			->orwhereBetween('jambookingselesai',[$jamres,$jambookingselesai])
			->where("idpegawai","=",$idpegawai)
			->where("status","=",'terima')
			->where("tanggalbooking","=",$tglres)
			//datang
			->Orwhere("tanggalbooking","=",$tglres)
			->where("idpegawai","=",$idpegawai)
			->where("status","=",'datang')
			->whereBetween('jambooking',[$jamres,$jambookingselesai])
			->orwhereBetween('jambookingselesai',[$jamres,$jambookingselesai])
			->where("idpegawai","=",$idpegawai)
			->where("status","=",'datang')
			->where("tanggalbooking","=",$tglres)
			->get();
    }
	
	public function cekpesanan($idsalon,$tanggal,$jam1,$jam2){
		return bookingservice::select('bookingservice.*')
			->where('idsalon','=',$idsalon)
			->where('tanggalbooking','=',$tanggal)
			->where('status','!=','selesai')
			->whereBetween('jambooking',[$jam1,$jam2])
			->orwhereBetween('jambookingselesai',[$jam1,$jam2])
			->where('status','!=','selesai')
			->where('idsalon','=',$idsalon)
			->where('tanggalbooking','=',$tanggal)
			
			/*->Orwhere('idsalon','=',$idsalon)
			->where('tanggalbooking','=',$tanggal)
			->where('status','!=','tolak')
			->whereBetween('jambooking',[$jam1,$jam2])
			->orwhereBetween('jambookingselesai',[$jam1,$jam2])
			->where('status','!=','tolak')
			->where('idsalon','=',$idsalon)
			->where('tanggalbooking','=',$tanggal)*/
			
			
			/*->Orwhere('idsalon','=',$idsalon)
			->where('tanggalbooking','=',$tanggal)
			->where('status','!=','cancel')
			->whereBetween('jambooking',[$jam1,$jam2])
			->orwhereBetween('jambookingselesai',[$jam1,$jam2])
			->where('status','!=','cancel')
			->where('idsalon','=',$idsalon)
			->where('tanggalbooking','=',$tanggal)*/
			->get();
	}
	
	
}



//kurang kalo data yg pertama kali di update akan selalu gagal
// sama kurang auto update waktu dipencet button update perlu pindah halaman dlu / ngga auto refresh
// kalo pencet terima blum ganti button
