<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class layanansalon extends Model
{
    public $incrementing = false;
	protected $table = 'layanansalon';
	protected $primaryKey = 'id';
	protected $fillable = [
		'id',
		'idsalon',
		'username',
		'namalayanan',
		'jumlah_kursi',
		'idkategori',
		'peruntukan',
		'jenjangusia',
		'hargapriadewasa',
		'hargawanitadewasa',
		'hargawanitaanak',
		'hargapriaanak',
		'durasi',
		'deskripsi',
		'status',
		'toleransi_keterlambatan',
		'foto'
	];
	public $timestamps = false;
	
	public function getlayanansalon($idsalon){
		$dt = layanansalon::select('layanansalon.*')
					->where('idsalon','=',$idsalon)
					->where('status','=','aktif')
					->orderBy('namalayanan', 'asc')
					->get();
		return $dt;
	}
	
	public function getlayanansalon_halamansalon($idsalon){
		$dt = layanansalon::select('layanansalon.*')
					->where('idsalon','=',$idsalon)
					->orderBy('namalayanan', 'asc')
					->get();
		return $dt;
	}
	
	public function iskembarlayanan($namalayanan,$idsalon){
		$dt = layanansalon::select('layanansalon.*')
					->where('namalayanan','=',$namalayanan)
					->where('idsalon','=',$idsalon)
					->get();
		return $dt;
	}
	
	public function getlayanansalondetail($idsalon,$namalayanan){
		$dt = layanansalon::select('layanansalon.*')
					->where('idsalon','=',$idsalon)
					->where('namalayanan','=',$namalayanan)
					->get();
		return $dt;
	}
	
	public function getlayananwithuser($username){
        return layanansalon::select('layanansalon.*', 'users.email', 'users.username', 'users.password', 'users.nama', 'users.alamat', 'users.kota', 'users.telp', 'users.foto', 'users.saldo', 'users.tgllahir', 'users.jeniskelamin', 'users.role', 'users.status')
				->join('users', 'users.username', '=', 'layanansalon.username')
				->where('layanansalon.username','=',$username)
				->where('layanansalon.status','=',"aktif")
				->distinct()
				->get();
    }
	
	/*
	$request->idsalon,$request->username,$request->namalayanan,$request->jumlah_kursi,$request->idkategori,$request->jenjangusia,$request->peruntukan,$request->hargapriadewasa,$request->hargawanitadewasa,$request->hargawanitaanak,$request->hargapriaanak,$request->durasi,$request->deskripsi,$request->status,$request->keterlambatan_waktu, $request->mfile */
	
	public function updateservice($id,$idsalon, $username,$namalayanan,$jumlah_kursi,$idkategori,$jenjangusia,$peruntukan,$hargapriadewasa,$hargawanitadewasa,$hargawanitaanak,$hargapriaanak,$durasi,$deskripsi,$status,$keterlambatan_waktu, $file){
		if($file != ""){
			$cari = layanansalon::find($id);
			$cari->foto	 				= $file;
			$cari->idsalon	 			= $idsalon;
			$cari->username	 			= $username;
			$cari->namalayanan	 		= $namalayanan;
			$cari->jumlah_kursi	 		= $jumlah_kursi;
			$cari->idkategori	 		= $idkategori;
			$cari->jenjangusia	 		= $jenjangusia;
			$cari->peruntukan	 		= $peruntukan;
			$cari->hargapriadewasa	 	= $hargapriadewasa;
			$cari->hargawanitadewasa	= $hargawanitadewasa;
			$cari->hargawanitaanak	 	= $hargawanitaanak;
			$cari->hargapriaanak	 	= $hargapriaanak;
			$cari->durasi	 			= $durasi;
			$cari->deskripsi		 	= $deskripsi;
			$cari->status	 			= $status;
			$cari->toleransi_keterlambatan	= $keterlambatan_waktu;
			$cari->save();
		}else{
			$cari = layanansalon::find($id);
			$cari->idsalon	 			= $idsalon;
			$cari->username	 			= $username;
			$cari->namalayanan	 		= $namalayanan;
			$cari->jumlah_kursi	 		= $jumlah_kursi;
			$cari->idkategori	 		= $idkategori;
			$cari->jenjangusia	 		= $jenjangusia;
			$cari->peruntukan	 		= $peruntukan;
			$cari->hargapriadewasa	 	= $hargapriadewasa;
			$cari->hargawanitadewasa	= $hargawanitadewasa;
			$cari->hargawanitaanak	 	= $hargawanitaanak;
			$cari->hargapriaanak	 	= $hargapriaanak;
			$cari->durasi	 			= $durasi;
			$cari->deskripsi		 	= $deskripsi;
			$cari->status	 			= $status;
			$cari->toleransi_keterlambatan	= $keterlambatan_waktu;
			$cari->save();
		}
	}
	
}



