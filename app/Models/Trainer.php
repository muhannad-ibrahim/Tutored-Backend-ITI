<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Course;


class Trainer extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = ['fname', 'lname','gender','phone','img','email','password' ,'facebook' , 'twitter' ,'linkedin' ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


      /**
     * Get the courses for this trainer.
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }



    /**
    * The attributes that should be cast.
    *
    * @var array<string, string>
    */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

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
