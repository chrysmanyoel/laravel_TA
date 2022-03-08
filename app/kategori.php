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
	
	public function getidkat(){
		return kategori::select('idkategori')
			->distinct()
			->get();
	}
	
	public function getnamakat($idkategori){
		return kategori::select('kategori.*')
			->where('idkategori','=',$idkategori)
			->get();
	}
	
	public function delkategori($idkategori,$namakategori){
		return kategori::select('kategori.*')
			->where('idkategori','=',$idkategori)
			->where('namakategori','=',$namakategori)
			->delete();
	}
	
	public function getallidkategori(){
		$dt = kategori::select('idkategori')
			->distinct()
			->get();
			return $dt;
	}
	
}

