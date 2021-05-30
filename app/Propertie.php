<?php

namespace App;

use League\Glide\Server;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
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

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%'.$search.'%');
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    public function photoUrl(array $attributes)
    {
        if ($this->photo_path) {
            return URL::to(App::make(Server::class)->fromPath($this->photo_path, $attributes));
        }
    }
}
