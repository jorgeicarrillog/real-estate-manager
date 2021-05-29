<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'citie_id', 'email', 'phone', 'address', 'postal_code'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function citie()
    {
        return $this->belongsTo(Citie::class);
    }

    public function properties()
    {
        return $this->hasMany(Propertie::class);
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
}
