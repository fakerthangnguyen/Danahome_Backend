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
    public $appends = [
        'image_url',
        'human_readable_created_at'
    ];

    public function category(){
        return $this->belongsTo(Categories::class);
    }

    public function account(){
        return $this->belongsTo(Account::class);
    }

    public function getImageUrlAttribute(){
        return asset('storage/'.$this->image);
    }

    public function getHumanReadableCreatedAtAttribute(){
        return $this->created_at->diffForHumans();
    }

    public function imagechild(){
        return $this->hasMany(ImageChild::class);
    }
}
