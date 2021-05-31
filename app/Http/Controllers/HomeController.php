<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Propertie;
use App\Organization;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Propertie::filter($request->only('search'))
        ->orderBy('updated_at', 'desc')->orderBy('created_at', 'desc')
        ->whereNull('deleted_at');
        $properties = $query->with('propertiesType', 'citie.department.countrie', 'organization')->limit(16)->get()
        ->transform(function ($pty) {
            return [
                'id' => $pty->id,
                'title' => $pty->title,
                'description' => $pty->description,
                'photo' => $pty->photoUrl(['w' => 420, 'h' => 260, 'fit' => 'crop']),
                'value' => $pty->value,
                'citie' => $pty->citie,
                'properties_type' => $pty->propertiesType,
                'organization' => $pty->organization
            ];
        });

        $organizations = Organization::with('citie.department.countrie')->inRandomOrder()->limit(12)->get()
        ->transform(function ($org) {
            return [
                'id' => $org->id,
                'name' => $org->name,
                'phone' => $org->phone,
                'photo' => $org->photoUrl(['w' => 80, 'h' => 80, 'fit' => 'crop']),
                'citie' => $org->citie,
                'deleted_at' => $org->deleted_at,
                'properties_count' => $org->properties_count,
            ];
        });

        return Inertia::render('Web/Home', [
            'filters' => $request->all('search'),
            'properties' => $properties,
            'organizations' => $organizations,
        ]);
    }
}
