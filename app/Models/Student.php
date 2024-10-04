<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    protected $fillable = ['name', 'surname', 'age'];

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'student_subject');
    }
}
