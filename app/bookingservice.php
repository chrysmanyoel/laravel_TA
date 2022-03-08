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
		'idsalon',
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
		'keterangan',
		'service_charge'
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
	
	public function getlistbookingwithlayanansemua($idsalon){
        return bookingservice::select('bookingservice.*', 'layanansalon.peruntukan','layanansalon.namalayanan', 'layanansalon.jenjangusia', 'layanansalon.durasi','users.foto','salon.namasalon','pegawai.nama as namapegawai','salon.kota','bookingservice.kode_pesanan','layanansalon.toleransi_keterlambatan')
				->join('layanansalon', 'layanansalon.id', '=', 'bookingservice.idservice')
				->join('salon', 'salon.username', '=', 'layanansalon.username')
				->join('pegawai', 'pegawai.id', '=', 'bookingservice.idpegawai')
				->join('users', 'users.username', '=', 'salon.username')
				->where('bookingservice.status','=', 'terima')
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.status','=', 'pending')
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.status','=', 'rescheduleuser')
				->Orwhere('bookingservice.statusreschedule','=', 'pending')
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.status','=', 'reschedulesalon')
				->Orwhere('bookingservice.statusreschedule','=', 'pending')
				->where('bookingservice.idsalon','=',$idsalon)
				->Orwhere('bookingservice.status','=', 'datang')
				->where('bookingservice.idsalon','=',$idsalon)
				->orderBy('bookingservice.tanggalbooking', 'asc')
				->orderBy('bookingservice.jambooking', 'asc')
				->get();
    }
	
	public function laporan_salon_penjualan($idsalon,$bulan,$status){
		//statusnya cuma selesai,cancel,tolak
		//ini kalo kosong semua
		if(empty($bulan) && empty($status)){
			return bookingservice::select('bookingservice.*', 'layanansalon.peruntukan','layanansalon.namalayanan', 'layanansalon.jenjangusia', 'layanansalon.durasi','users.foto','salon.namasalon','pegawai.nama as namapegawai','salon.kota')
			->join('layanansalon', 'layanansalon.id', '=', 'bookingservice.idservice')
			->join('salon', 'salon.username', '=', 'layanansalon.username')
			->join('pegawai', 'pegawai.id', '=', 'bookingservice.idpegawai')
			->join('users', 'users.username', '=', 'salon.username')
			->where('bookingservice.idsalon','=',$idsalon)
			->orderBy('bookingservice.tanggalbooking', 'desc')
			->orderBy('bookingservice.jambooking', 'asc')
			->get();
		}
		//ini kalo bulan nya ada tapi statusnya kosong
		else if(!empty($bulan) && empty($status)){
			return bookingservice::select('bookingservice.*', 'layanansalon.peruntukan','layanansalon.namalayanan', 'layanansalon.jenjangusia', 'layanansalon.durasi','users.foto','salon.namasalon','pegawai.nama as namapegawai','salon.kota')
			->join('layanansalon', 'layanansalon.id', '=', 'bookingservice.idservice')
			->join('salon', 'salon.username', '=', 'layanansalon.username')
			->join('pegawai', 'pegawai.id', '=', 'bookingservice.idpegawai')
			->join('users', 'users.username', '=', 'salon.username')
			->where('bookingservice.idsalon','=',$idsalon)
			//->where('bookingservice.bulan','=',$bulan)
			->orderBy('bookingservice.tanggalbooking', 'desc')
			->orderBy('bookingservice.jambooking', 'asc')
			->get();
		}
		//ini kalo status nya ada isi tapi bulann kosong
		else if(empty($bulan) && !empty($status)){
			return bookingservice::select('bookingservice.*', 'layanansalon.peruntukan','layanansalon.namalayanan', 'layanansalon.jenjangusia', 'layanansalon.durasi','users.foto','salon.namasalon','pegawai.nama as namapegawai','salon.kota')
			->join('layanansalon', 'layanansalon.id', '=', 'bookingservice.idservice')
			->join('salon', 'salon.username', '=', 'layanansalon.username')
			->join('pegawai', 'pegawai.id', '=', 'bookingservice.idpegawai')
			->join('users', 'users.username', '=', 'salon.username')
			->where('bookingservice.idsalon','=',$idsalon)
			->where('bookingservice.status','=',$status)
			->orderBy('bookingservice.tanggalbooking', 'desc')
			->orderBy('bookingservice.jambooking', 'asc')
			->get();
		}
		//ini kalo sama2 ada isinya
		else{
			return bookingservice::select('bookingservice.*', 'layanansalon.peruntukan','layanansalon.namalayanan', 'layanansalon.jenjangusia', 'layanansalon.durasi','users.foto','salon.namasalon','pegawai.nama as namapegawai','salon.kota')
			->join('layanansalon', 'layanansalon.id', '=', 'bookingservice.idservice')
			->join('salon', 'salon.username', '=', 'layanansalon.username')
			->join('pegawai', 'pegawai.id', '=', 'bookingservice.idpegawai')
			->join('users', 'users.username', '=', 'salon.username')
			->where('bookingservice.idsalon','=',$idsalon)
			//->where('bookingservice.bulan','=',$bulan)
			->where('bookingservice.status','=',$status)
			->orderBy('bookingservice.tanggalbooking', 'desc')
			->orderBy('bookingservice.jambooking', 'asc')
			->get();
		}        
    }
	
	public function laporan_salon_keuntungan_penjualan($idsalon,$bulan,$status){
		//kalo laporan keuntungan harusnya nda pakai status jadi cuma bulan
		//ini kalo kosong semua
		if(empty($bulan)){
			return bookingservice::select('bookingservice.*', 'layanansalon.peruntukan','layanansalon.namalayanan', 'layanansalon.jenjangusia', 'layanansalon.durasi','users.foto','salon.namasalon','pegawai.nama as namapegawai','salon.kota')
			->join('layanansalon', 'layanansalon.id', '=', 'bookingservice.idservice')
			->join('salon', 'salon.username', '=', 'layanansalon.username')
			->join('pegawai', 'pegawai.id', '=', 'bookingservice.idpegawai')
			->join('users', 'users.username', '=', 'salon.username')
			->where('bookingservice.idsalon','=',$idsalon)
			->where('bookingservice.status','=','selesai')
			->Orwhere('bookingservice.status','=','selesairating')
			->where('bookingservice.idsalon','=',$idsalon)
			->orderBy('bookingservice.tanggalbooking', 'desc')
			->orderBy('bookingservice.jambooking', 'asc')
			->get();
		}
		//ini kalo bulan nya ada tapi statusnya kosong
		else {
			return bookingservice::select('bookingservice.*', 'layanansalon.peruntukan','layanansalon.namalayanan', 'layanansalon.jenjangusia', 'layanansalon.durasi','users.foto','salon.namasalon','pegawai.nama as namapegawai','salon.kota')
			->join('layanansalon', 'layanansalon.id', '=', 'bookingservice.idservice')
			->join('salon', 'salon.username', '=', 'layanansalon.username')
			->join('pegawai', 'pegawai.id', '=', 'bookingservice.idpegawai')
			->join('users', 'users.username', '=', 'salon.username')
			->where('bookingservice.idsalon','=',$idsalon)
			->where('bookingservice.status','=','selesai')
			//->where('bookingservice.bulan','=',$bulan)
			->orderBy('bookingservice.tanggalbooking', 'desc')
			->orderBy('bookingservice.jambooking', 'asc')
			->get();
		}
    }
	
	public function getlistbooking_laporan_customer($username,$tanggal_awal,$tanggal_akhir,$status){
		//ini kalo kosong semua
		if(empty($tanggal_awal) && empty($tanggal_akhir) && empty($status)){
			return bookingservice::select('bookingservice.*', 'layanansalon.peruntukan','layanansalon.namalayanan', 'layanansalon.jenjangusia', 'layanansalon.durasi','users.foto','salon.namasalon','pegawai.nama as namapegawai','salon.kota')
			->join('layanansalon', 'layanansalon.id', '=', 'bookingservice.idservice')
			->join('salon', 'salon.username', '=', 'layanansalon.username')
			->join('pegawai', 'pegawai.id', '=', 'bookingservice.idpegawai')
			->join('users', 'users.username', '=', 'salon.username')
			->where('bookingservice.username','=',$username)
			->where('bookingservice.status','=', 'selesai')
			->Orwhere('bookingservice.status','=', 'selesairating')
			->where('bookingservice.username','=',$username)
			->Orwhere('bookingservice.status','=', 'cancel')
			->where('bookingservice.username','=',$username)
			->Orwhere('bookingservice.status','=', 'tolak')
			->where('bookingservice.username','=',$username)
			->orderBy('bookingservice.tanggalbooking', 'desc')
			->orderBy('bookingservice.jambooking', 'asc')
			->get();
		}
		//ini kalo bulan nya ada tapi statusnya kosong
		else if(!empty($tanggal_awal) && !empty($tanggal_akhir) && empty($status)){
			return bookingservice::select('bookingservice.*', 'layanansalon.peruntukan','layanansalon.namalayanan', 'layanansalon.jenjangusia', 'layanansalon.durasi','users.foto','salon.namasalon','pegawai.nama as namapegawai','salon.kota')
			->join('layanansalon', 'layanansalon.id', '=', 'bookingservice.idservice')
			->join('salon', 'salon.username', '=', 'layanansalon.username')
			->join('pegawai', 'pegawai.id', '=', 'bookingservice.idpegawai')
			->join('users', 'users.username', '=', 'salon.username')
			->where('bookingservice.username','=',$username)
			->where('bookingservice.status','=', 'selesai')
			->where('bookingservice.tanggalbooking','>=',$tanggal_awal)
			->where('bookingservice.tanggalbooking','<=',$tanggal_akhir)
			->Orwhere('bookingservice.status','=', 'selesairating')
			->where('bookingservice.username','=',$username)
			->where('bookingservice.tanggalbooking','>=',$tanggal_awal)
			->where('bookingservice.tanggalbooking','<=',$tanggal_akhir)
			->Orwhere('bookingservice.status','=', 'cancel')
			->where('bookingservice.username','=',$username)
			->where('bookingservice.tanggalbooking','>=',$tanggal_awal)
			->where('bookingservice.tanggalbooking','<=',$tanggal_akhir)
			->Orwhere('bookingservice.status','=', 'tolak')
			->where('bookingservice.username','=',$username)
			->where('bookingservice.tanggalbooking','>=',$tanggal_awal)
			->where('bookingservice.tanggalbooking','<=',$tanggal_akhir)
			->orderBy('bookingservice.tanggalbooking', 'desc')
			->orderBy('bookingservice.jambooking', 'asc')
			->get();
		}
		//ini kalo status nya ada isi tapi bulann kosong
		else if(empty($tanggal_awal) && empty($tanggal_akhir) && !empty($status)){
			if($status == 'selesai'){
				return bookingservice::select('bookingservice.*', 'layanansalon.peruntukan','layanansalon.namalayanan', 'layanansalon.jenjangusia', 'layanansalon.durasi','users.foto','salon.namasalon','pegawai.nama as namapegawai','salon.kota')
				->join('layanansalon', 'layanansalon.id', '=', 'bookingservice.idservice')
				->join('salon', 'salon.username', '=', 'layanansalon.username')
				->join('pegawai', 'pegawai.id', '=', 'bookingservice.idpegawai')
				->join('users', 'users.username', '=', 'salon.username')
				->where('bookingservice.username','=',$username)
				->where('bookingservice.status','=', 'selesai')
				->Orwhere('bookingservice.status','=', 'selesairating')
				->where('bookingservice.username','=',$username)
				->orderBy('bookingservice.tanggalbooking', 'desc')
				->orderBy('bookingservice.jambooking', 'asc')
				->get();
			}else{
				return bookingservice::select('bookingservice.*', 'layanansalon.peruntukan','layanansalon.namalayanan', 'layanansalon.jenjangusia', 'layanansalon.durasi','users.foto','salon.namasalon','pegawai.nama as namapegawai','salon.kota')
				->join('layanansalon', 'layanansalon.id', '=', 'bookingservice.idservice')
				->join('salon', 'salon.username', '=', 'layanansalon.username')
				->join('pegawai', 'pegawai.id', '=', 'bookingservice.idpegawai')
				->join('users', 'users.username', '=', 'salon.username')
				->where('bookingservice.username','=',$username)
				->where('bookingservice.status','=', $status)
				->orderBy('bookingservice.tanggalbooking', 'desc')
				->orderBy('bookingservice.jambooking', 'asc')
				->get();
			}
			
		}
		//ini kalo sama2 ada isinya
		else{
			return bookingservice::select('bookingservice.*', 'layanansalon.peruntukan','layanansalon.namalayanan', 'layanansalon.jenjangusia', 'layanansalon.durasi','users.foto','salon.namasalon','pegawai.nama as namapegawai','salon.kota')
			->join('layanansalon', 'layanansalon.id', '=', 'bookingservice.idservice')
			->join('salon', 'salon.username', '=', 'layanansalon.username')
			->join('pegawai', 'pegawai.id', '=', 'bookingservice.idpegawai')
			->join('users', 'users.username', '=', 'salon.username')
			->where('bookingservice.username','=',$username)
			->where('bookingservice.tanggalbooking','>=',$tanggal_awal)
			->where('bookingservice.tanggalbooking','<=',$tanggal_akhir)
			->where('bookingservice.status','=', $status)
			->orderBy('bookingservice.tanggalbooking', 'desc')
			->orderBy('bookingservice.jambooking', 'asc')
			->get();
		}        
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
	
	//ini harus nya nda kepake karna ngapain juga ngitung count cancel tapi tanggal nya tanggal hari ini [$tgl]
	public function getcount_cancel_tgl($idsalon){
		$tgl = date("Y-m-d");
        return bookingservice::select('username')
			->where('bookingservice.status','=','terima')
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
				->Orwhere('bookingservice.status','=', 'tidak datang')
				->where('bookingservice.usernamecancel','=',$username)
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
				->where('bookingservice.status','!=', 'blocked')
				->where('bookingservice.status','!=', 'selesai')
				->where('bookingservice.status','!=', 'selesairating')
				->where('bookingservice.username','=',$username)
				->orderBy('bookingservice.tanggalbooking', 'asc')
				->orderBy('bookingservice.jambooking', 'asc')
				->get();
    }
	
	public function getlistbookingwithlayananuserselesai($username){
        return bookingservice::select('bookingservice.*', 'layanansalon.peruntukan','layanansalon.namalayanan', 'layanansalon.jenjangusia', 'layanansalon.durasi','users.foto','salon.namasalon','pegawai.nama as namapegawai','salon.kota')
				->join('layanansalon', 'layanansalon.id', '=', 'bookingservice.idservice')
				->join('salon', 'salon.username', '=', 'layanansalon.username')
				->join('pegawai', 'pegawai.id', '=', 'bookingservice.idpegawai')
				->join('users', 'users.username', '=', 'salon.username')
				->where('bookingservice.username','=',$username)
				->where('bookingservice.status','=', 'selesai')
				->Orwhere('bookingservice.status','=', 'selesairating')
				->where('bookingservice.username','=',$username)
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
	
	public function check_isblocked($username,$idsalon){
		$hari = date("Y-m-d");
		return bookingservice::select('bookingservice.*')
				->where('username', '=', $username)
				->where('tanggal', '=', $hari)
				->where('idsalon', '=', $idsalon)
				->where('status', '=', 'blocked')
				->get();
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
	public function cancelsemuabooking($idsalon,$idpegawai,$tanggal){
		return bookingservice::select("bookingservice.*")
			->where("idsalon","=",$idsalon)
			->where("tanggalbooking","=",$tanggal)
			->where("idpegawai","=",$idpegawai)
			->where(function ($query) {
				$query->where("status","=",'terima')
				  ->orWhere('status', '=', 'pending');
			})
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
			//selesai
			->where('idsalon','=',$idsalon)
			->where('tanggalbooking','=',$tanggal)
			->where('status','!=','selesai')
			->whereBetween('jambooking',[$jam1,$jam2])
			->where(function ($query) {
				$query->where('status','=','pending')
				  ->orWhere('status','=','terima');
			})
			->orwhereBetween('jambookingselesai',[$jam1,$jam2])
			->where(function ($query) {
				$query->where('status','=','pending')
				  ->orWhere('status','=','terima');
			})
			->where('idsalon','=',$idsalon)
			->where('tanggalbooking','=',$tanggal)
			->get();
	}
	
	public function cancel_tolak_booking($idsalon,$tanggal,$jam1,$jam2){
		$cari = bookingservice::select('bookingservice.*')
			->where('idsalon','=',$idsalon)
			->where('tanggalbooking','=',$tanggal)
			->where('status','!=','selesai')
			->whereBetween('jambooking',[$jam1,$jam2])
			->orwhereBetween('jambookingselesai',[$jam1,$jam2])
			->where('status','!=','selesai')
			->where('idsalon','=',$idsalon)
			->where('tanggalbooking','=',$tanggal)
			->get();
			
		for($i=0; $i<count($cari); $i++){
			if($cari[$i]->status == 'terima'){
				$cari[$i]->status = 'cancel';
				$cari[$i]->usernamecancel = '-';
				$cari[$i]->save();
			}else if($cari[$i]->status == 'pending'){
				$cari[$i]->status = 'cancel';
				$cari[$i]->save();
			}
		}
		
		return $cari;
	}
	
	
}



//kurang kalo data yg pertama kali di update akan selalu gagal
// sama kurang auto update waktu dipencet button update perlu pindah halaman dlu / ngga auto refresh
// kalo pencet terima blum ganti button
