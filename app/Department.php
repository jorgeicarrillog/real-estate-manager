<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'countrie_id'];

    public function countrie()
    {
        return $this->belongsTo(Countrie::class);
    }

    public function cities()
    {
        return $this->hasMany(Citie::class);
    }
}
