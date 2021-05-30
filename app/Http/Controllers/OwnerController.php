<?php

namespace App\Http\Controllers;

use App\Owner;
use App\Organization;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use  App\Http\Requests\OwnerRequest;

class OwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Owner::whereIn('organization_id', auth()->user()->organizationsArrayId())
        ->orderByName()
        ->filter($request->only('search'));
        $owners = $query
        ->withCount('properties')
        ->with('organization')
        ->paginate()
        ->transform(function ($owner) {
            return [
                'id' => $owner->id,
                'name' => $owner->name,
                'email' => $owner->email,
                'photo' => $owner->photoUrl(['w' => 40, 'h' => 40, 'fit' => 'crop']),
                'properties_count' => $owner->properties_count,
                'organization' => $owner->organization->name,
            ];
        });
        return Inertia::render('Owners/Index', [
            'filters' => $request->only('search'),
            'owners' => $owners,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Owners/Create', [
            'organizations' => auth()->user()->organizations()->orderBy('name')->select('id', 'name')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  OwnerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OwnerRequest $request)
    {
        Owner::create([
            'organization_id' => $request->organization_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'photo_path' => $request->file('photo') ? $request->file('photo')->store('owners') : null,
        ]);

        return Redirect::route('owners')->with('success', 'Propietario creado.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Owner  $owner
     * @return \Illuminate\Http\Response
     */
    public function edit(Owner $owner)
    {
        return Inertia::render('Owners/Edit', [
            'owner' => [
                'organization_id' => $owner->organization_id,
                'id' => $owner->id,
                'first_name' => $owner->first_name,
                'last_name' => $owner->last_name,
                'email' => $owner->email,
                'photo' => $owner->photoUrl(['w' => 60, 'h' => 60, 'fit' => 'crop']),
                'organization' => $owner->organization->name,
            ],
            'properties' => $owner->properties()->with('propertiesType', 'citie.department.countrie')->paginate(),
            'organizations' => auth()->user()->organizations()->orderBy('name')->select('id', 'name')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  OwnerRequest  $request
     * @param  \App\Owner  $owner
     * @return \Illuminate\Http\Response
     */
    public function update(OwnerRequest $request, Owner $owner)
    {
        $owner->update($request->only('organization_id', 'first_name', 'last_name', 'email'));

        if ($request->file('photo') && $request->file('photo')->isValid()) {
            $owner->update(['photo_path' => $request->file('photo')->store('owners')]);
        }

        return Redirect::back()->with('success', 'Propietario actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Owner  $owner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Owner $owner)
    {
        if(in_array($owner->organization_id, auth()->user()->organizationsArrayId())){
            if($owner->properties()->count()){
                return Redirect::back()->with('error', 'No puede eliminar este Propietario ya que tiene propiedades en el sistema.');
            }else{
                $owner->delete();
    
                return Redirect::route('owners')->with('success', 'Propietario eliminado.');
            }
        }else{
            return Redirect::back()->with('error', 'No puede eliminar este Propietario.');
        }
    }
}
