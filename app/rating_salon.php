<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class rating_salon extends Model
{
    public $incrementing = false;
	protected $table = 'rating_salon';
	protected $primaryKey = 'id';
	protected $fillable = [
		'id',
		'idsalon',
		'idlayanan',
		'idbooking',
		'rating_layanan',
		'ulasan'
	];
	public $timestamps = false;
	
	
	public function getRating($idsalon){
		$dt = rating_salon::select('rating_salon.*')
				->where('idsalon','=',$idsalon)
				->get();
		return $dt;
	}
}

