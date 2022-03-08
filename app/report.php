<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class report extends Model
{
    public $incrementing = false;
	protected $table = 'report';
	protected $primaryKey = 'id';
	protected $fillable = [
		'id',
		'username1',
		'username2',
		'alasan',
		'foto',
		'tanggal',
		'status'
	];
	public $timestamps = false;
	
	public function insert_report($username1,$username2,$alasan,$file){
		$hari = date("Y-m-d");
		if($file != ""){
			$userbaru = new report;
			$userbaru->id 			= 0;
			$userbaru->username1	= $username1;
			$userbaru->username2	= $username2;
			$userbaru->alasan		= $alasan;
			$userbaru->tanggal		= $hari;
			$userbaru->status		= 'proses';
			$userbaru->foto	 		= $file;
			$userbaru->save();
		}else{
			$userbaru = new report;
			$userbaru->id 			= 0;
			$userbaru->username1	= $username1;
			$userbaru->username2	= $username2;
			$userbaru->alasan		= $alasan;
			$userbaru->tanggal		= $hari;
			$userbaru->status		= 'proses';
			$userbaru->foto	 		= '';
			$userbaru->save();
		}		
	}
	
	public function report_terakhir_laporan ($username,$bulan,$status){
		//ini kalo semuanya kosong
		if(empty($bulan) && empty($status)){			
			return report::select('report.id','report.username1','salon.username as username2','report.alasan','report.foto','report.status','report.tanggal','users.foto as fotosalon')
				->join('salon','salon.id','=','report.username2')
				->join('users','users.username','=','salon.username')
				->where('report.username1','=',$username)
				->get(); 
		}
		//ini kalo bulan nya ada isi tapi status kosong
		else if(!empty($bulan) && empty($status)){
			return report::select('report.id','report.username1','salon.username as username2','report.alasan','report.foto','report.status','report.tanggal','users.foto as fotosalon')
				->join('salon','salon.id','=','report.username2')
				->join('users','users.username','=','salon.username')
				//->where('report.bulan','=',$bulan)
				->get(); 
		}
		//ini kalo status nya ada isi tapi bulann kosong
		else if(empty($bulan) && !empty($status)){
			return report::select('report.id','report.username1','salon.username as username2','report.alasan','report.foto','report.status','report.tanggal','users.foto as fotosalon')
				->join('salon','salon.id','=','report.username2')
				->join('users','users.username','=','salon.username')
				->where('report.status','=',$status)
				->get(); 
		} 
		//ini kalo semua nya ada
		else{
			return report::select('report.id','report.username1','salon.username as username2','report.alasan','report.foto','report.status','report.tanggal','users.foto as fotosalon')
				->join('salon','salon.id','=','report.username2')
				->join('users','users.username','=','salon.username')
				->where('report.username1','=',$username)
				//->where('report.bulan','=',$bulan)
				->where('report.status','=',$status)
				->get(); 
		}
	}
	
	public function getreport_histori (){
		return report::select('report.id','report.username1','salon.username as username2','report.alasan','report.foto','report.status','report.tanggal')		
			->join('salon','salon.id','=','report.username2')
			->where('report.status','=','selesai')
			->get(); 
	}
	
	public function getreport (){
		return report::select('report.id','report.username1','salon.username as username2','report.alasan','report.foto','report.status','report.tanggal')
			->join('salon','salon.id','=','report.username2')
			->where('report.status','=','proses')
			->get(); 
	}


}

