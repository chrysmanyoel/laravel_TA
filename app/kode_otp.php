<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class kode_otp extends Model
{
    public $incrementing = false;
	protected $table = 'kode_otp';
	protected $primaryKey = 'i';
	protected $fillable = [
		'id',
		'email',
		'kode',
		'expire'
	];
	public $timestamps = false;
	
	
	
}

