<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Room;
class ImageChild extends Model
{
    use HasFactory;
    protected $fillable = [
        'image_child',
        'room_id',
    ];
    public function rom(){
        return $this->belongsTo(Room::class);
    }

    public $appends = [
        'image_url',
        'human_readable_created_at'
    ];
    public function getImageUrlAttribute(){
        return asset('storage/'.$this->image_child);
    }
    public function getHumanReadableCreatedAtAttribute(){
        return $this->created_at->diffForHumans();
    }
}
