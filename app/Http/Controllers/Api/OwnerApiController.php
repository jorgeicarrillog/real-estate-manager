<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Requests\OwnerRequest;
use App\Owner;
use App\Organization;

class OwnerApiController extends Controller
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
                'type' => 'owners',
                'id' => $owner->id,
                'name' => $owner->name,
                'email' => $owner->email,
                'photo' => $owner->photoUrl(['w' => 40, 'h' => 40, 'fit' => 'crop']),
                'properties_count' => $owner->properties_count,
                'organization' => $owner->organization->name,
            ];
        });

        return response()->json($owners);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  OwnerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OwnerRequest $request)
    {
        $owner = Owner::create([
            'organization_id' => $request->organization_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'photo_path' => $request->file('photo') ? $request->file('photo')->store('owners') : null,
        ]);

        $owner->type='owners';

        return response()->json([
            'data' => $owner->makeHidden('organization'),
            'relationships' => [
                'organization' => [
                    'data' => [
                        'type' => 'organizations',
                        'id' => $owner->organization->id
                    ],
                    'links' => [
                        "self" => route('api.v1.organizations.edit', $owner->organization->id),
                        "related" => url()->current()
                    ]
                ]
                    ],
                    'included' => [
                        [
                            'type' => 'organizations',
                            'id' => $owner->organization->id,
                            'attributes' => $owner->organization,
                            'links' => [
                                "self" => route('api.v1.organizations.edit', $owner->organization->id),
                            ]
                        ]
                    ]
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
        $owner->fill($request->only('organization_id', 'first_name', 'last_name', 'email'));
        $owner->save();

        if ($request->file('photo') && $request->file('photo')->isValid()) {
            $owner->update(['photo_path' => $request->file('photo')->store('owners')]);
        }
        
        $owner->type='owners';

        return response()->json([
            'data' => $owner->makeHidden('organization'),
            'relationships' => [
                'organization' => [
                    'data' => [
                        'type' => 'organizations',
                        'id' => $owner->organization->id
                    ],
                    'links' => [
                        "self" => route('api.v1.organizations.edit', $owner->organization->id),
                        "related" => url()->current()
                    ]
                ]
                    ],
                    'included' => [
                        [
                            'type' => 'organizations',
                            'id' => $owner->organization->id,
                            'attributes' => $owner->organization,
                            'links' => [
                                "self" => route('api.v1.organizations.edit', $owner->organization->id),
                            ]
                        ]
                    ]
        ]);
    }
}
