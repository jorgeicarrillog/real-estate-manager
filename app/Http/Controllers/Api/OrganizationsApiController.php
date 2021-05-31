<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Organization;
use Illuminate\Http\Request;
use App\Http\Requests\OrganizationRequest;

class OrganizationsApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Organization::orderBy('name')->withTrashed()
        ->filter($request->only('search'));
        if(!$request->user()->owner){
            $query->where('user_id', auth()->id());
        }
        $organizations = $query->withCount('properties')->paginate()
        ->transform(function ($org) {
            return [
                'type' => 'organizations',
                'id' => $org->id,
                'name' => $org->name,
                'phone' => $org->phone,
                'photo' => $org->photoUrl(['w' => 40, 'h' => 40, 'fit' => 'crop']),
                'citie' => $org->citie,
                'deleted_at' => $org->deleted_at,
                'properties_count' => $org->properties_count,
            ];
        });

        return response()->json([
            $organizations
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  OrganizationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrganizationRequest $request)
    {
        $organization = new Organization();
        $organization->fill([
            'name' => $request->name,
            'citie_id' => $request->citie_id,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'photo_path' => $request->file('photo') ? $request->file('photo')->store('organizations') : null,
        ]);
        $organization->save();
        $request->user()->organizations()->attach($organization);

        $organization->type='organizations';

        return response()->json([
            'data' => $organization,
        ]);
    }

    /**
     * Display the specified resource in storage.
     *
     * @param  \App\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization)
    {
        $organization->type="organizations";
        
        return response()->json([
            'data' => $organization,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organization $organization)
    {
        $organization->update(
            $request->all()
        );

        if ($request->file('photo') && $request->file('photo')->isValid()) {
            $organization->update(['photo_path' => $request->file('photo')->store('organizations')]);
        }
        $organization->type="organizations";
        
        return response()->json([
            'data' => $organization,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization)
    {
        $organization->delete();

        return response()->json([
            'data' => $organization,
        ], 204);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Organization  $organization
     * @return \Illuminate\Http\Response
     */

    public function restore(Organization $organization)
    {
        $organization->restore();

        return response()->json([
            'data' => $organization,
        ]);
    }
}
