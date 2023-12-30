<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasFactory;
    protected $fillable = ['name'] ;

    /**
     * Get all of the users for the Rule
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
        public function getNameAttribute($value)
    {
        switch ($value) {
            case 'admin':
                return 'مدير';
            case 'superAdmin':
                return 'رئيس';
            case 'saler':
                return 'بائع';
            default:
                return $value;
        }
    }
}

