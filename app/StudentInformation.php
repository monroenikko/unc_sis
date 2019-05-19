<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentInformation extends Model
{
    public function user ()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    
    public function enrolled_class () 
    {
        return $this->hasMany(Enrollment::class, 'student_information_id', 'id');
    }
}
