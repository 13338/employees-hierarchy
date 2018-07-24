<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'director_id',
    	'fio',
    	'position',
    	'employment_at',
    	'wages'
    ];

    /**
     * Get director that owns the employee
     */
    public function director()
    {
    	return $this->belongsTo('App\Employee', 'director_id');
    }

    /**
     * Get subordinates
     */
    public function subordinates()
    {
        return $this->hasMany('App\Employee', 'director_id', 'id');
    }
}
