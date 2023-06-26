<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category ;
use App\Models\Trainer ;
use App\Models\Student;


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



    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

      /**
     * Get the course_content associated with the course.
     */
    public function course_content()
    {
        return $this->hasOne(Course_Content::class);
    }



}
