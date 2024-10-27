<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Buyer;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function admin_login(Request $request) : JsonResponse{
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'lowercase', 'email',],
            'password' => ['required'],
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $user = User::where('email','=', $request->email)->first();
            $token = $user->createToken('indie')->plainTextToken;
            $buyer_count = Buyer::all()->count(); 
            $seller_count = Seller::all()->count(); 
            $total_users = $buyer_count + $seller_count;
            return response()->json([
                'message' => 'Admin successfully logged in.',
                'total_buyers' => $buyer_count,
                'total_sellers' => $seller_count,
                'total_users' => $total_users,
                'token' => $token
            ]);
        }
        else {
            return response()->json([
                'message' => 'invalid login credentials.',
            ], 401);
        }
    }
    public function admin_register(Request $request) : JsonResponse {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:30'],
            'email' => ['required', 'lowercase', 'email',],
            'password' => ['required', 'confirmed'],
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return response()->json(["message" => "Admin successfully registered."]);
    }
    public function login(Request $request) : JsonResponse {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'lowercase', 'email',],
            'password' => ['required'],
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        if ($request->role === "Buyer" or $request->role === "buyer") {
            if (Auth::guard('buy')->attempt([
                'email' => $request->email,
                'password' => $request->password]
                )) {
                    $user = Buyer::where('email', '=', $request->email)->first();
                    $token = $user->createToken('indie')->plainTextToken;
                    return response()->json([
                        'token' => $token,
                        'message' => 'Buyer successfully logged in.'
                    ]);
                    
                } else {
                    return response()->json(['message' => 'invalid credentials.']);
                }
            }
            else if ($request->role === "Seller" or $request->role === "seller") {
                if (Auth::guard('sell')->attempt([
                    'email' => $request->email,
                    'password' => $request->password
                    ])) {
                        $user = Seller::where('email', '=', $request->email)->first();
                        $token = $user->createToken('indie')->plainTextToken;
                        return response()->json([
                            'token' => $token,
                            'message' => 'Seller successfully logged in.'
                        ]);
                    } else {
                        return response()->json(['message' => 'invalid credentials.']);
                    }
            }
            else return response()->json(['message' => 'no role specified.']);
    } 
    public function store_a_user(Request $request) : JsonResponse {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'lowercase', 'email',],
            'password' => ['required'],
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(),422);
        }
    
        if ($request['role'] === "Buyer" or $request['role'] === "buyer"){
           return $this->store_a_buyer($request);
        } else if ($request['role'] === "Seller" or $request['role'] === "seller") {
            return $this->store_a_seller($request);
        } else {
            return response()->json(["message" => "Error on how we received the buyer or seller."]);       
        }
        
    }
    private function store_a_buyer($request) : JsonResponse {
        Buyer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return response()->json(["message" => "Buyer successfully registered."]);
    }
    private function store_a_seller($request) : JsonResponse {
        Seller::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return response()->json(["message" => "Seller successfully registered."]);

    }

}
