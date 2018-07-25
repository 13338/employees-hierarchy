<?php

namespace App;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Employee extends Model
{
    use Sortable, FiltersRecords;

    /**
     * The attributes that are sortable.
     *
     * @var array
     */
    public $sortable = [
        'id',
        'director_id',
        'fio',
        'position',
        'employment_at',
        'wages',
        'created_at',
        'updated_at'
    ];

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
