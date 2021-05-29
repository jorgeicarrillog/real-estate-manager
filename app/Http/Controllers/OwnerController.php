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
}
