<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $connection = 'beltsMaintenances';

    public function getFullNameAttribute()
    {
        if($this->firstname && $this->lastname) {
            return $this->firstname . ' ' . $this->lastname;
        }elseif($this->firstname) {
            return $this->firstname;
        }elseif($this->lastname) {
            return $this->lastname;
        }
    }
}
