<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
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
}
