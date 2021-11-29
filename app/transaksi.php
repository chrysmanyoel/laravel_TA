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
		'status'
	];
	public $timestamps = false;
	
}

