<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'img'];

    /**
     * Get the courses for this category.
     */


      /**
     * Get the courses for this category.
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

}
