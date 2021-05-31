<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Validation\ValidationException;
use App\Propertie;
use App\Countrie;
use App\Department;
use App\Citie;
use App\User;

class ApiController extends Controller
{
    public function countries(Request $request)
    {
        if (isset($request->field) && isset($request->value)) {
            if (is_numeric($request->value)) {
                $countries = Countrie::where('name','!=','')->whereNotNull('name')->where($request->field,$request->value)->orderBy('name','desc')->get();
            }elseif (is_array($request->value)) {
                $countries = Countrie::where('name','!=','')->whereNotNull('name')->whereIn($request->field,$request->value)->orderBy('name','desc')->get();
            }
		}else{
			$countries = Countrie::orderBy('name')->get();
		}

        return response()->json($countries);
    }
    public function departments(Request $request)
    {
        if (isset($request->field) && isset($request->value)) {
            if (is_numeric($request->value)) {
                $departments = Department::where('name','!=','')->whereNotNull('name')->where($request->field,$request->value)->orderBy('name','desc')->get();
            }elseif (is_array($request->value)) {
                $departments = Department::where('name','!=','')->whereNotNull('name')->whereIn($request->field,$request->value)->orderBy('name','desc')->get();
            }
		}else{
			$departments = Department::orderBy('name')->get();
		}

        return response()->json($departments);
    }
    public function cities(Request $request)
    {
        if (isset($request->field) && isset($request->value)) {
            if (is_numeric($request->value)) {
                $cities = Citie::where('name','!=','')->whereNotNull('name')->where($request->field,$request->value)->orderBy('name','desc')->get();
            }elseif (is_array($request->value)) {
                $cities = Citie::where('name','!=','')->whereNotNull('name')->whereIn($request->field,$request->value)->orderBy('name','desc')->get();
            }
		}else{
			$cities = Citie::orderBy('name')->get();
		}

        return response()->json($cities);
    }

    public function token(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
    
        return $user->createToken($request->device_name)->plainTextToken;
    }

    public function GeoProperties(Request $request)
    {
        if ($request->latitude && $request->longitude) {
            $properties = Propertie::select(['latitude', 'longitude', 'photo_path', 'title', DB::raw('(6371 * acos (cos ( radians('.$request->latitude.') )* cos( radians( latitude ) )* cos( radians( longitude ) - radians('.$request->longitude.') )+ sin ( radians('.$request->latitude.') )* sin( radians( latitude ) )))')])
            ->havingRaw('(6371 * acos (cos ( radians('.$request->latitude.') )* cos( radians( latitude ) )* cos( radians( longitude ) - radians('.$request->longitude.') )+ sin ( radians('.$request->latitude.') )* sin( radians( latitude ) ))) < '.(($request->dst)?$request->dst:5))->limit(40)->get()
            ->transform(function ($pty) {
                return [
                    'lat' => $pty->latitude,
                    'lng' => $pty->longitude,
                    'popup' => [
                        'isOpen' => false,
                        'text' => '<center>'.(($pty->photo_path)?'<img src="'.$pty->photoUrl(['w' => 120, 'h' => 60, 'fit' => 'crop']).'"><br>':'').'<b>'.$pty->title.'</b></center>',
                    ],
                    'icon' => [
                        'iconUrl' => "./storage/images/marker.png",
                        'iconSize' => [48, 48],
                    ],
                ];
            });

            return response()->json($properties);
        }
        return response()->json([]);
    }
}
