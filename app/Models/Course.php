<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'img', 'price', 'duration', 'desc', 'preq', 'trainer_id', 'category_id'];

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
}
