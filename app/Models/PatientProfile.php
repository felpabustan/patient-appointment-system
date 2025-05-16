<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dob',
        'gender',
        'address',
        'phone',
        'symptoms'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
