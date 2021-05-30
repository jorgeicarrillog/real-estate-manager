<?php

namespace App\Http\Controllers;

use App\Propertie;
use App\PropertiesType;
use App\Organization;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\PropertieRequest;

class PropertieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Organization $organization, Request $request)
    {
        $types = PropertiesType::orderBy('name')->get();
        $owners = $organization->owners()->orderBy('first_name')->orderBy('last_name')->get();

        return Inertia::render('Properties/Create', [
            'types' => $types,
            'owners' => $owners,
            'owner_id' => $request->owner_id,
            'organization_id' => $organization->id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Organization $organization,PropertieRequest $request)
    {
        $propertie = new Propertie();
        $propertie->fill($request->all());
        $propertie->organization_id = $organization->id;
        
        if ($request->file('photo') && $request->file('photo')->isValid()) {
            $propertie->update(['photo_path' => $request->file('photo')->store('properties')]);
        }

        if($propertie->save()){
            return Redirect::route('organizations.edit', $organization->id)->with('success', 'Propiedad creada.');
        }
        return Redirect::route('organizations.edit', $organization->id)->with('error', 'La propiedad no pudo ser creada.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Propertie  $propertie
     * @return \Illuminate\Http\Response
     */
    public function show(Propertie $propertie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Propertie  $propertie
     * @return \Illuminate\Http\Response
     */
    public function edit(Propertie $propertie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Propertie  $propertie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Propertie $propertie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Propertie  $propertie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Propertie $propertie)
    {
        //
    }
}
