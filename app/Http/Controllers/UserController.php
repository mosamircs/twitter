<?php

namespace App\Http\Controllers;
use JWTAuth;
use App\Http\Resources\UserResource;
use App\Models\Tweet;
use App\Models\User;
use App\Http\Controllers\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserController extends Controller
{

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {

            if (!$token = FacadesJWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }

        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

//
    public function register(Request $request)
    {
            $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => FacadesHash::make($request->get('password')),
        ]);
        

        $token = FacadesJWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client = User::with('tweets')->get();
        $ids = $client->pluck('id');
        $tweet = Tweet::whereIN('base_id', $ids)->get();
        return $client->merge($tweet);
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tweet = $this->user->tweets()->find($id);

        if (!$tweet) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, tweet not found.'
            ], 400);
        }

        return $tweet;
    }



}
