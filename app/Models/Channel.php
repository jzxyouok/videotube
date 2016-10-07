<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    
	Protected $fillable = [
			'name',
			'slug',
			'description',
			'image_filename',
	];


	public function user(){
		return $this->belongsTo(User::class);
	}

	public function getRouteKeyName(){
		return 'slug';
	}


	public function videos(){
		return $this->hasMany(Video::class);
	}


}
