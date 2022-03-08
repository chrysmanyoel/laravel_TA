<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class hari_libur extends Model
{
    public $incrementing = false;
	protected $table = 'hari_libur';
	protected $primaryKey = 'id';
	protected $fillable = [
		'id',
		'idsalon',
		'tanggal',
		'jam1',
		'jam2',
		'keterangan',
		'status'
	];
	public $timestamps = false;
	
	
	public function getharilibur($idsalon){
		$dt = hari_libur::select('hari_libur.*')
			->where('idsalon','=',$idsalon)
			->where('status','!=',2)
			->get();
		return $dt;
	}
	
	public function cek_insert($idsalon,$tanggal,$jam1,$jam2){
		$dt = hari_libur::select('hari_libur.*')
			->where('idsalon','=',$idsalon)
			->where('tanggal','=',$tanggal)
			->where('status','!=',0)
			->get();
		return $dt;
	}
	
}

