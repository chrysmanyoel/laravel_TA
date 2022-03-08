<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class pegawai extends Model
{
    public $incrementing = false;
	protected $table = 'pegawai';
	protected $primaryKey = 'id';
	protected $fillable = [
		'id',
		'idsalon',
		'nama',
		'alamat',
		'telp',
		'status'
	];
	public $timestamps = false;
	
	
	public function getidpegawai($nama){
		$dt = pegawai::select('pegawai.*')
					->where('nama','=',$nama)
					->get();
		return $dt;
	}
	
	public function getpegawai($idsalon){
		return pegawai::select('pegawai.*')
				->where('idsalon','=',$idsalon)
				->get();
		return $dt;
	}
	
	public function getpegawai_halamanmember($idsalon,$kodelayanan){
		$tgl = date("Y-m-d");
		return pegawai::select('pegawai.*','detailpegawai.idkategori')
				->join('detailpegawai', 'detailpegawai.idpegawai', '=', 'pegawai.id')
				->whereNotIn('pegawai.id', DB::table('absensi_pegawai')->select('absensi_pegawai.idpegawai')->where('absensi_pegawai.tanggal', '=', $tgl))
				->where('pegawai.idsalon','=',$idsalon)
				->where('pegawai.status','=','aktif')
				->where('detailpegawai.idkategori','=',$kodelayanan)
				->get();
		return $dt;;
	}
	
	public function getpegawai_absen($idsalon){
		return pegawai::select('pegawai.*')
				->where('idsalon','=',$idsalon)
				->distinct()
				->get();
		return $dt;
	}
	
	public function maxidpegawai(){
		$dt = pegawai::select(max('pegawai.id'))					
					->get();
		return $dt;
	}
	
	
	public function getdatapegawai($idsalon){
        $qry =  pegawai::select('pegawai.*', 'detailpegawai.idkategori')
				->join('detailpegawai', 'detailpegawai.idpegawai', '=', 'pegawai.id')
				->where('pegawai.idsalon','=',$idsalon)
				->get();
	
		
		$qry2 = pegawai::select('pegawai.*')
						->whereNotIn('id', function($query) {
								$query->select('idpegawai')->from('detailpegawai');
						})
						->where('pegawai.idsalon','=',$idsalon)
						->get();				
						
		$arr = []; 
		foreach($qry as $row) {
			$arr[] = $row; 
		}
		foreach($qry2 as $row) {
			$arr[] = $row; 
			$arr[count($arr) - 1]->idkategori = ""; 
		}
		return $arr; 
    }

}

