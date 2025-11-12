<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ObjectSentece extends Model
{
    use HasFactory;

    protected $table = 'object_sentences';
    protected $fillable = [
        'sentence',
        'image',
        'description'
    ];
    public $appends=[
        'image_url'
    ];
    public function getImageUrlAttribute()
    {
        return asset($this->image);
    }
}
