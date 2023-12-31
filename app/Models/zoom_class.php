<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class zoom_class extends Model
{
    use HasFactory;
    public $fillable= ['trainer_id', 'course_id','meeting_id','topic','start_at','duration','password','start_url','join_url'];

    public function course()
    {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }
    
    public function trainer()
    {
        return $this->belongsTo('App\Models\Trainer', 'trainer_id');
    }
}
