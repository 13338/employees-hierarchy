<?php

namespace App;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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
        'avatar',
        'fio',
        'position',
        'employment_at',
        'wages'
    ];

    /**
     * The attributes that are auto appends to model object (getMyattrAttribute() method).
     *
     * @var array
     */
    protected $appends = [
        'count_subordinates'
    ];

    public static function create(array $attributes = [])
    {
        $attributes['avatar'] = $attributes['avatar']->storeAs('avatars', uniqid().'.'.$attributes['avatar']->getClientOriginalExtension(), 'public');

        $model = static::query()->create($attributes);
        
        return $model;
    }

    public function update(array $attributes = [], array $options = [])
    {
        if (! $this->exists) {
            return false;
        }

        if (array_key_exists('avatar', $attributes)) {
            if ($attributes['avatar'] === null){
                $attributes['avatar'] = null;
            } else {
                $attributes['avatar'] = $attributes['avatar']->storeAs('avatars', uniqid().'.'.$attributes['avatar']->getClientOriginalExtension(), 'public');
            }
            if (!empty($this->avatar)) {
                Storage::disk('public')->delete($this->avatar);
            }
        }

        return $this->fill($attributes)->save($options);
    }

    public function delete()
    {
        if (!empty($this->avatar)) {
            Storage::disk('public')->delete($this->avatar);
        }
        
        return parent::delete();
    }

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

    /**
     * Get subordinates number
     * @return int
     */
    public function getCountSubordinatesAttribute()
    {
        return $this->hasMany('App\Employee', 'director_id', 'id')->count();
    }
}
