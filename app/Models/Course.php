<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category ;
use App\Models\Trainer ;
use App\Models\Student;
use App\Models\Course_Content ;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'img', 'price', 'duration', 'desc', 'preq','average_rating' ,'trainer_id', 'category_id'];


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



    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'course_student')
            ->withPivot('progress');
    }

      /**
     * Get the course_content associated with the course.
     */
    public function course_content()
    {
        return $this->hasOne(Course_Content::class);
    }



    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
