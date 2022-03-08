<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class chat extends Model
{
    public $incrementing = true;
	protected $table = 'chat';
	protected $primaryKey = 'id';
	protected $fillable = [
		'id',
		'username1',
		'username2'
	];
	public $timestamps = false;
	
	public function getListChatUser($username){
		 return chat::select('chat.*','users.username','users.nama','users.foto')
				->join('users','chat.username2','=','users.username')
				->where('chat.username1','=',$username)
				->get();
	}
	
	public function cekUsernamePesan($username1,$username2){
		return chat::select('chat.*')
				->where('username1','=',$username1)
				->where('username2','=',$username2)
				->Orwhere('username2','=',$username1)
				->where('username1','=',$username2)
				->get();
	}
	
	
	
}

