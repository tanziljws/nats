<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolProfile extends Model
{
    protected $fillable = [
        'title',          
        'logo',           
        'about',          
        'address',        
        'phone',          
        'email',          
        'map_embed',      
        'vision',         
        'mission',        
        'facebook_url',   
        'instagram_url',  
        'youtube_url',    
        'twitter_url',    
        'hero_image',
        'founded_year',     
        'motto',            
        'headmaster_name',  
        'headmaster_photo',
        'history',
        'house_banners',
    ];

    protected $casts = [
        'house_banners' => 'array',
    ];

    public function founders()
    {
        return $this->hasMany(Founder::class);
    }
}

