<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class seting extends Model
{
    public $incrementing = false;
	protected $table = 'seting';
	protected $primaryKey = 'persenatse';
	protected $fillable = [
		'persenatse'
	];
	public $timestamps = false;
	
	
	
	
}

