<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $appends = ['fullname'];
    protected $casts = [
        'activated' => 'boolean'
    ];
    public function setPasswordAttribute($password) {
        $this->attributes['password'] = \Hash::make($password);
    }

    public function getFullnameAttribute() {
        return $this->firstname.' '.$this->lastname;
    }

    public function setBirthDateAttribute($date) {
        $this->attributes['birth_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $date)->toDateString();
    }

    public function getBirthDateAttribute($date) {
        return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
    }
}
