<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Course;


class Student extends Authenticatable implements JWTSubject
{
    use  HasFactory, Notifiable;

    protected $fillable = ['fname', 'lname','gender','phone','img','email','password' ];

    /**
     * Get the feedbacks of the student.
     */



    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
