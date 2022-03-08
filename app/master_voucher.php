<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class master_voucher extends Model
{
    public $incrementing = false;
	protected $table = 'master_voucher';
	protected $primaryKey = 'id';
	protected $fillable = [
		'id',
		'nama_voucher',
		'jenis',
		'harga_beli',
		'nilai',
		'status'
	];
	public $timestamps = false;
	
	public function cek_voucher ($nama_voucher){
		return master_voucher::select('master_voucher.*')
				->where('nama_voucher','=',$nama_voucher)
				->get();
	}
	
	public function getiklan_voucher (){
		return master_voucher::select('master_voucher.*')
				->get();
	}
	
	public function getiklan_voucher_aktif (){
		return master_voucher::select('master_voucher.*')
				->where('status', '=', 'aktif')
				->get();
	}
	
	public function getiklan_voucher_aktif_join_tr ($idsalon){
		$hari = date("Y-m-d");
		return master_voucher::select('master_voucher.*','transaksi_voucher.tanggal_beli','transaksi_voucher.tanggal_exp')
				->join('transaksi_voucher', 'transaksi_voucher.id_voucher', '=', 'master_voucher.id')
				->where('transaksi_voucher.status', '=', 'aktif')
				->where('transaksi_voucher.tanggal_beli', '<=', $hari)
				->where('transaksi_voucher.idsalon', '=', $idsalon)
				->where('transaksi_voucher.tanggal_exp', '>=', $hari)
				->get();
	}
	
	
}

