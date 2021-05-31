<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Propertie;
use App\Organization;
use Illuminate\Http\Request;
use App\Http\Requests\PropertieRequest;

class PropertieApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Organization $organization, Request $request)
    {
        
        $query = $organization->properties()->orderBy('title')->withTrashed()
        ->with('citie.department.countrie', 'propertiesType')
        ->filter($request->only('search'));
        $properties = $query->paginate()
        ->transform(function ($org) {
            return [
                'type' => 'properties',
                'id' => $org->id,
                'title' => $org->name,
                'description' => $org->description,
                'address' => $org->address,
                'area' => $org->area,
                'bedrooms' => $org->bedrooms,
                'toilets' => $org->toilets,
                'value' => $org->value,
                'floor' => $org->floor,
                'citie' => $org->citie,
                'deleted_at' => $org->deleted_at,
                'photo' => $org->photoUrl(['w' => 40, 'h' => 40, 'fit' => 'crop']),
            ];
        });

        return response()->json([
            $properties
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PropertieRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Organization $organization, PropertieRequest $request)
    {
        $propertie = new Propertie();
        $propertie->fill($request->all());
        $propertie->organization_id = $organization->id;
        
        if ($request->file('photo') && $request->file('photo')->isValid()) {
            $propertie->update(['photo_path' => $request->file('photo')->store('properties')]);
        }

        if($propertie->save()){
            $propertie->load('citie.department.countrie');
            return response()->json([
                'type' => 'properties',
                'id' => $propertie->id,
                'title' => $propertie->name,
                'description' => $propertie->description,
                'address' => $propertie->address,
                'area' => $propertie->area,
                'bedrooms' => $propertie->bedrooms,
                'toilets' => $propertie->toilets,
                'value' => $propertie->value,
                'floor' => $propertie->floor,
                'citie' => $propertie->citie,
                'deleted_at' => $propertie->deleted_at,
                'photo' => $propertie->photoUrl(['w' => 40, 'h' => 40, 'fit' => 'crop']),
            ]);
        }
        return respose()->json(['error' => 'La propiedad no pudo ser creada.'], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Propertie  $propertie
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization, Propertie $propertie)
    {
        $propertie->load('citie.department.countrie');
        return response()->json(['data' => [
            'type' => 'properties',
            'id' => $propertie->id,
            'title' => $propertie->name,
            'description' => $propertie->description,
            'address' => $propertie->address,
            'area' => $propertie->area,
            'bedrooms' => $propertie->bedrooms,
            'toilets' => $propertie->toilets,
            'value' => $propertie->value,
            'floor' => $propertie->floor,
            'citie' => $propertie->citie,
            'deleted_at' => $propertie->deleted_at,
            'photo' => $propertie->photoUrl(['w' => 40, 'h' => 40, 'fit' => 'crop']),
        ]]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Propertie  $propertie
     * @return \Illuminate\Http\Response
     */
    public function update(Organization $organization, Propertie $propertie, PropertieRequest $request)
    {$propertie->fill($request->all());
        
        if ($request->file('photo') && $request->file('photo')->isValid()) {
            $propertie->update(['photo_path' => $request->file('photo')->store('properties')]);
        }

        if($propertie->save()){
            $propertie->load('citie.department.countrie');
            return response()->json([
                'type' => 'properties',
                'id' => $propertie->id,
                'title' => $propertie->name,
                'description' => $propertie->description,
                'address' => $propertie->address,
                'area' => $propertie->area,
                'bedrooms' => $propertie->bedrooms,
                'toilets' => $propertie->toilets,
                'value' => $propertie->value,
                'floor' => $propertie->floor,
                'citie' => $propertie->citie,
                'deleted_at' => $propertie->deleted_at,
                'photo' => $propertie->photoUrl(['w' => 40, 'h' => 40, 'fit' => 'crop']),
            ]);
        }
        return respose()->json(['error' => 'La propiedad no pudo ser creada.'], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Propertie  $propertie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization, Propertie $propertie)
    {
        $propertie->delete();
        $propertie->load('citie.department.countrie');

        return response()->json([
            'type' => 'properties',
            'id' => $propertie->id,
            'title' => $propertie->name,
            'description' => $propertie->description,
            'address' => $propertie->address,
            'area' => $propertie->area,
            'bedrooms' => $propertie->bedrooms,
            'toilets' => $propertie->toilets,
            'value' => $propertie->value,
            'floor' => $propertie->floor,
            'citie' => $propertie->citie,
            'deleted_at' => $propertie->deleted_at,
            'photo' => $propertie->photoUrl(['w' => 40, 'h' => 40, 'fit' => 'crop']),
        ]);
    }

    public function restore(Organization $organization, Propertie $propertie)
    {
        $propertie->restore();
        $propertie->load('citie.department.countrie');

        return response()->json([
            'type' => 'properties',
            'id' => $propertie->id,
            'title' => $propertie->name,
            'description' => $propertie->description,
            'address' => $propertie->address,
            'area' => $propertie->area,
            'bedrooms' => $propertie->bedrooms,
            'toilets' => $propertie->toilets,
            'value' => $propertie->value,
            'floor' => $propertie->floor,
            'citie' => $propertie->citie,
            'deleted_at' => $propertie->deleted_at,
            'photo' => $propertie->photoUrl(['w' => 40, 'h' => 40, 'fit' => 'crop']),
        ]);
    }
}
