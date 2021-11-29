<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class absensi_pegawai extends Model
{
    public $incrementing = false;
	protected $table = 'absensi_pegawai';
	protected $primaryKey = 'id';
	protected $fillable = [
		'id',
		'idpegawai',
		'idsalon',
		'nama',
		'tanggal',
		'waktu',
		'keterangan'
	];
	public $timestamps = false;
	
	
	public function getabsensipegawai($idsalon){
		return absensi_pegawai::select('absensi_pegawai.*')
				->where('idsalon','=',$idsalon)
				->get();
	}
	
	public function getpegawai1(){
		$tgl = date("Y-m-d");
		return absensi_pegawai::select('absensi_pegawai.*')
				->where('tanggal','=',$tgl)
				->get();
	}
}

