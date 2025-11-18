<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityPhotoComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_photo_id',
        'user_id',
        'content',
        'is_approved'
    ];

    public function photo()
    {
        return $this->belongsTo(FacilityPhoto::class, 'facility_photo_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getNameAttribute()
    {
        return $this->user?->name ?? "Anonymous";
    }
}
