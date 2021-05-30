<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Organization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\OrganizationRequest;

class OrganizationsController extends Controller
{
    public function index()
    {
        $query = Organization::orderBy('name')
        ->filter(Request::only('search', 'trashed'));
        if(!auth()->user()->owner){
            $query->where('user_id', auth()->id());
        }
        $organizations = $query->withCount('properties')->paginate()
        ->transform(function ($org) {
            return [
                'id' => $org->id,
                'name' => $org->name,
                'phone' => $org->phone,
                'photo' => $org->photoUrl(['w' => 40, 'h' => 40, 'fit' => 'crop']),
                'citie' => $org->citie,
                'deleted_at' => $org->deleted_at,
                'properties_count' => $org->properties_count,
            ];
        });

        return Inertia::render('Organizations/Index', [
            'filters' => Request::all('search', 'trashed'),
            'organizations' => $organizations,
        ]);
    }

    public function create()
    {
        return Inertia::render('Organizations/Create');
    }

    public function store(OrganizationRequest $request)
    {
        Auth::user()->organizations()->create(
            [
                'name' => $request->name,
                'citie_id' => $request->citie_id,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'postal_code' => $request->postal_code,
                'photo_path' => $request->file('photo') ? $request->file('photo')->store('organizations') : null,
            ]
        );

        return Redirect::route('organizations')->with('success', 'Inmobiliaria creada.');
    }

    public function edit(Organization $organization)
    {
        $organization->load('citie.department.countrie');
        $organization->photo = $organization->photoUrl(['w' => 60, 'h' => 60, 'fit' => 'crop']);
        return Inertia::render('Organizations/Edit', [
            'filters' => Request::all('search', 'trashed'),
            'organization' => $organization,
            'properties' => $organization->properties()->with('propertiesType', 'citie.department.countrie')->paginate(15),
        ]);
    }

    public function update(Organization $organization, OrganizationRequest $request)
    {
        $organization->update(
            $request->all()
        );

        if ($request->file('photo') && $request->file('photo')->isValid()) {
            $organization->update(['photo_path' => $request->file('photo')->store('organizations')]);
        }

        return Redirect::back()->with('success', 'Inmobiliaria actualizada.');
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();

        return Redirect::back()->with('success', 'Inmobiliaria desactivada.');
    }

    public function restore(Organization $organization)
    {
        $organization->restore();

        return Redirect::back()->with('success', 'Inmobiliaria activada.');
    }
}
