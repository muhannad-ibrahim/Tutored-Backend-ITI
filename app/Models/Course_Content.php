<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course ;

class Course_Content extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'content', 'course_id'];


    /**
     * Get the course that owns the course_content.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
