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
					->where(function ($query) {
						$query->where('iklan.status','=','pending')
						  ->orWhere('iklan.status','=','aktif');
					})
					->get();
		return $dt;
	}
	
	public function get_jum_iklan(){
		$dt = iklan::select('iklan.*')
					->where('iklan.status','=','aktif')
					->get();
		return $dt;
	}
	
	public function getiklan_sisisalon($idsalon){
		$tgl = date("Y-m-d");
		$dt = iklan::select('iklan.*','salon.namasalon')
					->join('salon', 'salon.id', '=', 'iklan.idsalon')
					->where('iklan.idsalon','=',$idsalon)
					->where('iklan.tanggal_akhir','>',$tgl)
					->where(function ($query) {
						$query->where('iklan.status','=','pending')
						  ->orWhere('iklan.status','=','terima')
						  ->orWhere('iklan.status','=','aktif');
					})
					->get();
		return $dt;
	}
	
	public function getiklan_admin(){
		$dt = iklan::select('iklan.idiklan','iklan.tanggal','salon.username as idsalon','iklan.hargaiklan','iklan.tanggal_awal','iklan.tanggal_akhir','iklan.foto','iklan.status')
					->join('salon', 'salon.id', '=', 'iklan.idsalon')
					->where('iklan.status','=','pending')
					->get();
		return $dt;
	}
	
	public function getiklan_admin_acc(){
		$tgl = date("Y-m-d");
		$dt = iklan::select('iklan.idiklan','iklan.tanggal','salon.username','iklan.hargaiklan','iklan.tanggal_awal','iklan.tanggal_akhir','iklan.foto','iklan.status')
					->join('salon', 'salon.id', '=', 'iklan.idsalon')
					->where('iklan.status','=','aktif')
					->where('iklan.tanggal_akhir','>',$tgl)
					->get();
		return $dt;
	}
	
	/*public function getiklan_salon_ada(){
		$dt = iklan::select('iklan.idiklan','iklan.tanggal','salon.username','iklan.hargaiklan','iklan.tanggal_awal','iklan.tanggal_akhir','iklan.foto','iklan.status')
					->join('salon', 'salon.id', '=', 'iklan.idsalon')
					->where('iklan.status','=','aktif')
					->where('iklan.tanggal_akhir','>',$tgl)
					->get();
		return $dt;
	}*/
	
	public function terima_iklan($idiklan, $status){
		$cari = iklan::find($idiklan);
		$cari->status = $status;
		$cari->save();
	}
	
	public function laporan_salon_iklan($idsalon,$bulan,$status){
		//statusnya cuma selesai,proses/aktif,tolak,pending
		//ini kalo kosong semua
		if(empty($bulan) && empty($status)){
			return iklan::select('iklan.*')
			->join('salon', 'salon.id', '=', 'iklan.idsalon')
			->where('iklan.idsalon','=',$idsalon)
			->where('iklan.status','=','selesai')
			->Orwhere('iklan.status','=','aktif')
			->where('iklan.idsalon','=',$idsalon)
			->Orwhere('iklan.status','=','tolak')
			->where('iklan.idsalon','=',$idsalon)
			->Orwhere('iklan.status','=','pending')
			->where('iklan.idsalon','=',$idsalon)
			->orderBy('iklan.tanggal_awal', 'desc')
			->get();
		}
		//ini kalo bulan nya ada tapi statusnya kosong
		else if(!empty($bulan) && empty($status)){
			return iklan::select('iklan.*')
			->join('salon', 'salon.id', '=', 'iklan.idsalon')
			->where('iklan.idsalon','=',$idsalon)
			->where('iklan.status','=','selesai')
			->where('iklan.tanggal','=',$bulan)
			->Orwhere('iklan.status','=','aktif')
			->where('iklan.idsalon','=',$idsalon)
			->where('iklan.tanggal','=',$bulan)
			->Orwhere('iklan.status','=','tolak')
			->where('iklan.idsalon','=',$idsalon)
			->where('iklan.tanggal','=',$bulan)
			->Orwhere('iklan.status','=','pending')
			->where('iklan.idsalon','=',$idsalon)
			->where('iklan.tanggal','=',$bulan)
			->orderBy('iklan.tanggal_awal', 'desc')
			->get();
		}
		//ini kalo status nya ada isi tapi bulann kosong
		else if(empty($bulan) && !empty($status)){
			return iklan::select('iklan.*')
			->join('salon', 'salon.id', '=', 'iklan.idsalon')
			->where('iklan.idsalon','=',$idsalon)
			->where('iklan.status','=',$status)
			->orderBy('iklan.tanggal_awal', 'desc')
			->get();
		}
		//ini kalo sama2 ada isinya
		else{
			return iklan::select('iklan.*')
			->join('salon', 'salon.id', '=', 'iklan.idsalon')
			->where('iklan.idsalon','=',$idsalon)
			->where('iklan.status','=',$status)
			->where('iklan.tanggal','=',$bulan)
			->orderBy('iklan.tanggal_awal', 'desc')
			->get();
		}        
    }
	
	public function getalliklansalon(){
		$tgl = date("Y-m-d");
        $dt = iklan::select('iklan.*', 'salon.namasalon','salon.kota', 'salon.alamat', 'salon.username')
				->join('salon', 'salon.id', '=', 'iklan.idsalon')
				->where('iklan.tanggal_akhir','>=',$tgl)
				->where('iklan.tanggal_awal','<=',$tgl)
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
			for($i=0; $i < count($dt); $i++){
				$cari = $dt[$i];
				$cari->status	 = 'selesai';
				$cari->save();
			}
		}
				
		return $dt;
    }
	
	
}

