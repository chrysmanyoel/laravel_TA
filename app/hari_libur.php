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
	
	
	
	
}

