<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    use HasFactory;

    protected $fillable = [
       'title',
        'slug',
        'description',
        'image_url',
        'link',
        'tech_stack',
        'status',
        'user_id',
    ];

   public function user(){
    return $this->belongsTo(User::class);
   }

   public function scopePublished($query){
    return $query->where('status', 'published');
   }
}
