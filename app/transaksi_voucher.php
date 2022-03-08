<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class transaksi_voucher extends Model
{
    public $incrementing = false;
	protected $table = 'transaksi_voucher';
	protected $primaryKey = 'id';
	protected $fillable = [
		'id',
		'id_voucher',
		'tanggal_beli',
		'tanggal_exp',
		'idsalon',
		'harga_beli',
		'status'
	];
	public $timestamps = false;
	
	public function cek_valid_voucher($hari, $tanggal_exp,$id_voucher, $idsalon){
		return transaksi_voucher::select('transaksi_voucher.*')
			->where('id_voucher','=',$id_voucher)
			->where('idsalon','=',$idsalon)
			->where('tanggal_beli','>=',$hari)
			->where('tanggal_exp','<=',$tanggal_exp)
			->get();
	}
	
	
}

