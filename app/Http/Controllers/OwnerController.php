<?php

namespace App\Http\Controllers;

use App\Owner;
use App\Organization;
use Inertia\Inertia;
use Illuminate\Http\Request;
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
        ->paginate()
        ->transform(function ($owner) {
            return [
                'id' => $owner->id,
                'name' => $owner->name,
                'email' => $owner->email,
                'photo' => $owner->photoUrl(['w' => 40, 'h' => 40, 'fit' => 'crop']),
                'properties_count' => $owner->properties_count,
            ];
        });
        return Inertia::render('Owners/Index', [
            'filters' => $request->only('search'),
            'owners' => $owners,
        ]);
    }
}
