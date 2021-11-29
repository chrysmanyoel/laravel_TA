<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class iklan extends Model
{
    public $incrementing = false;
	protected $table = 'iklan';
	protected $primaryKey = 'idiklan';
	protected $fillable = [
		'idiklan',
		'tanggal',
		'idsalon',
		'hargaiklan',
		'tanggal_awal',
		'tanggal_akhir',
		'foto',
		'status'
	];
	public $timestamps = false;
	
	public function getiklan($idsalon){
		$tgl = date("Y-m-d");
		$dt = iklan::select('iklan.*','salon.namasalon')
					->join('salon', 'salon.id', '=', 'iklan.idsalon')
					->where('iklan.idsalon','=',$idsalon)
					->where('iklan.tanggal_akhir','>',$tgl)
//					->where('status','=','aktif')
					->get();
		return $dt;
	}
	
	public function getiklan_admin(){
		$dt = iklan::select('iklan.*')
					->where('status','=','pending')
					->get();
		return $dt;
	}
	
	public function getiklan_admin_acc(){
		$tgl = date("Y-m-d");
		$dt = iklan::select('iklan.*')
					->where('status','=','aktif')
					->where('tanggal_akhir','>',$tgl)
					->get();
		return $dt;
	}
	
	public function terima_iklan($idiklan, $status){
		$cari = iklan::find($idiklan);
		$cari->status = $status;
		$cari->save();
	}
	
	public function getalliklansalon(){
		$tgl = date("Y-m-d");
        $dt = iklan::select('iklan.*', 'salon.namasalon','salon.kota', 'salon.alamat', 'salon.username')
				->join('salon', 'salon.id', '=', 'iklan.idsalon')
				->where('iklan.tanggal_akhir','>',$tgl)
				->where('iklan.status','=', 'aktif')
				->orderBy('iklan.tanggal_akhir', 'asc')
				->get();
				
		return $dt;
    }
	
	public function autoselesai(){
		$tgl = date("Y-m-d");
        $dt = iklan::select('iklan.*')
				->where('tanggal_akhir','=',$tgl)
				->where('status','=','aktif')
				->get();
				
		if(count($dt) == 0) {
			
		}else{
			$cari = $dt[0];
			$cari->status	 = 'selesai';
			$cari->save();
		}
				
		return $dt;
    }
	
	
}

