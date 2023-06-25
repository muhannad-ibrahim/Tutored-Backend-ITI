<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category ;



class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'img', 'price', 'duration', 'desc', 'preq', 'trainer_id', 'category_id'];


       /**
     * Get the category that have this course.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


     /**
     * Get the trainer that teaches this course.
     */
    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }
    


}
