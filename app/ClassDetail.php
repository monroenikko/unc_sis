<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassDetail extends Model
{
    public function class_subjects () 
    {
        return $this->hasMany(ClassSubjectDetail::class, 'class_details_id', 'id')->where('status', '!=', 0);
    }
}
