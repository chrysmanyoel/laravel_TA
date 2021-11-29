<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class kategori extends Model
{
    public $incrementing = false;
	protected $table = 'kategori';
	protected $primaryKey = 'id';
	protected $fillable = [
	'id',
		'idkategori',
		'namakategori'
	];
	public $timestamps = false;
	
	public function getallkategori(){
		$dt = kategori::select('kategori.*')
					->get();
		return $dt;
	}
	
	public function getallidkategori(){
		$dt = kategori::select('idkategori')
			->distinct()
			->get();
			return $dt;
	}
	
}

