<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class transaksi extends Model
{
    public $incrementing = false;
	protected $table = 'transaksi';
	protected $primaryKey = 'id';
	protected $fillable = [
		'id',
		'atasnama',
		'nama_bank',
		'jenis_transaksi',
		'melalui',
		'norek',
		'jumlah',
		'status',
		'foto',
		'tanggal'
	];
	public $timestamps = false;
	
	public function gettopupsaldobank (){
		$tgl = date("Y-m-d");
		return transaksi::select('transaksi.*')
			->where('transaksi.jenis_transaksi','=','Top Up')
			->where('transaksi.melalui','=','Bank')
			->where('transaksi.status','=','pending')
			//->where('transaksi.tanggal','=',$tgl)
			->get(); 
			
		/*
		return transaksi::select('transaksi.*','users.foto')
			->join('users', 'users.email', '=', 'transaksi.atasnama')
			->where('transaksi.jenis_transaksi','=','Top Up')
			->where('transaksi.melalui','=','bank')
			->where('transaksi.tanggal','=',$tgl)
			->get(); */
	}
	
	public function getwithdraw (){
		$tgl = date("Y-m-d");
		return transaksi::select('transaksi.*')
			->where('transaksi.jenis_transaksi','=','Withdraw')
			->where(function ($query) {
				$query->where('transaksi.melalui','=','Bank')
				  ->orWhere('transaksi.melalui','=','Payment Gateway');
			})
			->where('transaksi.status','=','pending')
			//->where('transaksi.tanggal','=',$tgl)
			->get(); 
			
		/*
		return transaksi::select('transaksi.*','users.foto')
			->join('users', 'users.email', '=', 'transaksi.atasnama')
			->where('transaksi.jenis_transaksi','=','Top Up')
			->where('transaksi.melalui','=','bank')
			->where('transaksi.tanggal','=',$tgl)
			->get(); */
	}
	
	public function getwithdraw_histori (){
		$tgl = date("Y-m-d");
		return transaksi::select('transaksi.*')
			->where('transaksi.jenis_transaksi','=','Withdraw')
			->where(function ($query) {
				$query->where('transaksi.melalui','=','Bank')
				  ->orWhere('transaksi.melalui','=','Payment Gateway');
			})
			->where('transaksi.status','!=','pending')
			->get(); 
	}
	
	public function gettopup_histori (){
		$tgl = date("Y-m-d");
		return transaksi::select('transaksi.*')
			->where('transaksi.melalui','=','Bank')
			->where('transaksi.jenis_transaksi','=','Top Up')
			->where('transaksi.status','!=','pending')
			->Orwhere('transaksi.melalui','=','Payment Gateway')
			->where('transaksi.status','!=','pending')
			->where('transaksi.jenis_transaksi','=','Top Up')
			->get(); 
	}
	
	public function transaksi_terakhir ($username){
		return transaksi::select('transaksi.*')
			->where('transaksi.melalui','=','Bank')
			->where('transaksi.jenis_transaksi','=','Top Up')
			->where('transaksi.atasnama','=',$username)
			->Orwhere('transaksi.melalui','=','Payment Gateway')
			->where('transaksi.jenis_transaksi','=','Withdraw')
			->where('transaksi.atasnama','=',$username)
			->Orwhere('transaksi.melalui','=','Payment Gateway')
			->where('transaksi.jenis_transaksi','=','Top Up')
			->where('transaksi.atasnama','=',$username)
			->get(); 
	}
	
	public function transaksi_terakhir_laporan ($username,$tanggal_awal,$tanggal_akhir,$status){
		//ini kalo kosong semua
		if(empty($tanggal_awal) && empty($tanggal_akhir) && empty($status)){
			return transaksi::select('transaksi.*')
			->where('transaksi.melalui','=','Bank')
			->where('transaksi.jenis_transaksi','=','Top Up')
			->where('transaksi.atasnama','=',$username)
			->Orwhere('transaksi.melalui','=','Payment Gateway')
			->where('transaksi.jenis_transaksi','=','Withdraw')
			->where('transaksi.atasnama','=',$username)
			->Orwhere('transaksi.melalui','=','Payment Gateway')
			->where('transaksi.jenis_transaksi','=','Top Up')
			->where('transaksi.atasnama','=',$username)
			->get(); 
		}
		//ini kalo tanggal nya ada tapi statusnya kosong
		else if(!empty($tanggal_awal) && !empty($tanggal_akhir) && empty($status)){
			return transaksi::select('transaksi.*')
			->where('transaksi.melalui','=','Bank')
			->where('transaksi.jenis_transaksi','=','Top Up')
			->where('transaksi.atasnama','=',$username)
			->where('transaksi.tanggal','>=',$tanggal_awal)
			->where('transaksi.tanggal','<=',$tanggal_akhir)
			->Orwhere('transaksi.melalui','=','Payment Gateway')
			->where('transaksi.jenis_transaksi','=','Withdraw')
			->where('transaksi.atasnama','=',$username)
			->where('transaksi.tanggal','>=',$tanggal_awal)
			->where('transaksi.tanggal','<=',$tanggal_akhir)
			->Orwhere('transaksi.melalui','=','Payment Gateway')
			->where('transaksi.jenis_transaksi','=','Top Up')
			->where('transaksi.atasnama','=',$username)
			->where('transaksi.tanggal','>=',$tanggal_awal)
			->where('transaksi.tanggal','<=',$tanggal_akhir)
			->get(); 
		}
		//ini kalo status nya ada isi tapi bulann kosong
		else if(empty($tanggal_awal) && empty($tanggal_akhir) && !empty($status)){
			return transaksi::select('transaksi.*')
			->where('transaksi.melalui','=','Bank')
			->where('transaksi.jenis_transaksi','=','Top Up')
			->where('transaksi.atasnama','=',$username)
			->where('transaksi.status','=',$status)
			->Orwhere('transaksi.melalui','=','Payment Gateway')
			->where('transaksi.jenis_transaksi','=','Withdraw')
			->where('transaksi.atasnama','=',$username)
			->where('transaksi.status','=',$status)
			->Orwhere('transaksi.melalui','=','Payment Gateway')
			->where('transaksi.jenis_transaksi','=','Top Up')
			->where('transaksi.atasnama','=',$username)
			->where('transaksi.status','=',$status)
			->get(); 
			
		}
		//ini kalo sama2 ada isinya
		else{
			return transaksi::select('transaksi.*')
			->where('transaksi.melalui','=','Bank')
			->where('transaksi.jenis_transaksi','=','Top Up')
			->where('transaksi.atasnama','=',$username)
			->where('transaksi.status','=',$status)
			->where('transaksi.tanggal','>=',$tanggal_awal)
			->where('transaksi.tanggal','<=',$tanggal_akhir)
			->Orwhere('transaksi.melalui','=','Payment Gateway')
			->where('transaksi.jenis_transaksi','=','Withdraw')
			->where('transaksi.atasnama','=',$username)
			->where('transaksi.status','=',$status)
			->where('transaksi.tanggal','>=',$tanggal_awal)
			->where('transaksi.tanggal','<=',$tanggal_akhir)
			->Orwhere('transaksi.melalui','=','Payment Gateway')
			->where('transaksi.jenis_transaksi','=','Top Up')
			->where('transaksi.atasnama','=',$username)
			->where('transaksi.status','=',$status)
			->where('transaksi.tanggal','>=',$tanggal_awal)
			->where('transaksi.tanggal','<=',$tanggal_akhir)
			->get(); 
		} 
	}
	
}

