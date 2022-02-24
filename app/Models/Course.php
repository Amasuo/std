<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = ['name', 'capacity'];

    protected $hidden = ['created_at', 'updated_at'];


    /** For the many-to-many relation (check student model) */
    public function students()
    {
        return $this->belongsToMany(Student::class);
    }
}
