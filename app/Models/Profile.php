<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'nickname',
        'date_of_birth',
        'gender',
        'contact_number',
        'address',
        'marital_status',
        'anniversary_date',
        'number_of_children',
        'membership_status',
        'baptism_date',
        'allergies_medical_notes',
        'emergency_contact_name',
        'emergency_contact_phone',
        'profile_photo_url',
        'user_id'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'anniversary_date' => 'date',
        'baptism_date' => 'date',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}
