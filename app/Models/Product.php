<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
	
	protected $fillable = [
		'title',
		'code',
		'description',
		'price',
		'image',
 ];
	
	 public function ratings()
    {
        return $this->hasMany(Rating::class);
    }


    public function hasUserRated($userId)
    {
        return $this->ratings()->where('user_id', $userId)->exists();
    }


    public function userRating($userId)
    {
        return $this->ratings()->where('user_id', $userId)->first();
    }
	
}
