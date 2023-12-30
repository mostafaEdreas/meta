<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory ,SoftDeletes;
    protected $fillable = ['name','email','phone','img','address','user_id'] ;
    
    protected $hidden = ['password','email_verified_at','email','img'];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
