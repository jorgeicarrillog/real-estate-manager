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
}
