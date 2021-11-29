<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class detailpegawai extends Model
{
    public $incrementing = false;
	protected $table = 'detailpegawai';
	protected $primaryKey = 'id';
	protected $fillable = [
		'id',
		'idpegawai',
		'idkategori'
	];
	public $timestamps = false;
	
	public function getdetailpegawai($idpegawai){
		$dt = detailpegawai::select('detailpegawai.*')
					->where('idpegawai','=',$idpegawai)
					->get();
		return $dt;
	}
	
	public function getdetailpegawaikategori($idpegawai,$idkategori){
		$dt = detailpegawai::select('detailpegawai.*')
					->where('idpegawai','=',$idpegawai)
					->where('idkategori','=',$idkategori)
					->get();
		return $dt;
	}
		
	/*
	public function updatedetailpegawai($idpegawai, $idkategori){
		$user = detailpegawai::where('idpegawai','=',$idpegawai)->first();
		$user->idkategori = $user->idkategori.','.$idkategori;
		$user->save();
	}*/
	
	public function updatedetailpegawai($idpegawai, $idkategori){
		$user = detailpegawai::where('idpegawai','=',$idpegawai)->first();
		$user->idkategori = $idkategori;
		$user->save();
	}
	
}

