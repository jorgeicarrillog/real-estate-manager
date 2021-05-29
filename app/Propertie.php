<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Propertie extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['organization_id', 'owner_id', 'properties_type_id', 'citie_id', 'title', 'description', 'address', 'longitude', 'latitude', 'area', 'bedrooms', 'toilets', 'floor', 'value'];

    public function propertiesType()
    {
        return $this->belongsTo(PropertiesType::class);
    }

    public function citie()
    {
        return $this->belongsTo(Citie::class);
    }
}
