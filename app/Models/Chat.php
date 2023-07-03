<?php

namespace App\Models;
use App\Models\Student;
use App\Models\Trainer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $fillable = [
        'trainer_id',
        'student_id',
        'message',
    ];


    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }



}
