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
	
}



