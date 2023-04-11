<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'title',
        'description',
        'address',
        'price',
        'area',
        'image',
        'category_id',
        'account_id',
        'city',
        'district',
        'ward',
        'status'

    ];

    public function category(){
        return $this->belongsTo(Categories::class);
    }

}
