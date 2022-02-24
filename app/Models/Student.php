<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'students';

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'created_at', 'updated_at'];

    /** For the many-to-many relation (check course model) */
    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
}
