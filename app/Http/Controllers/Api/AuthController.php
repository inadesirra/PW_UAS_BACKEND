<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\User; 
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registrationData = $request->all();
        $validate = Validator::make($registrationData, [
            'name' => 'required|max:60',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required'
        ]); 

        if($validate->fails())
            return response(['message' => $validate->errors()],400); 

        $registrationData['password'] = bcrypt($request->password);
        $user = User::create($registrationData);
        return response([
            'message' => 'Register Success',
            'user' => $user
        ],200); 
    }

    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns' ,
            'password' => 'required'
        ]); 

        if ($validate->fails())
            return response(['message' => $validate->errors()],400); 

        if(!Auth::attempt($loginData))
            return response(['message' => 'Invalid Credentials'], 401); 
        
        $user = Auth::user();
        $token = $user->createToken('Authentication Token')->accessToken; 

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' =>'Bearer',
            'access_token' => $token
        ]); 
    }

    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

    public function index()
    {
        $users = User::all(); 

        if(count($users)> 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $users
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); 

    }

    public function show($id)
    {
        $user = User::find($id); 

        if(!is_null($user)) {
            return response([
                'message' => 'Retrieve User Success',
                'data' => $user
            ], 200);
        } 

        return response([
            'message' => 'User Not Found',
            'data' => null
        ], 404); 
    }

    public function store(Request $request)
    {
        $storeData = $request->all(); 
        $validate = Validator::make($storeData, [
            'name' => 'required|max:60',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
            $storeData['password'] = bcrypt($request->password);
        
            $user = User::create($storeData);
            return response([
                'message' => 'Add User Success',
                'data' => $user
            ], 200); 
    }

    public function destroy($id)
    {
        $user = User::find($id); 
        
        if (is_null($user)) {
            return response([
                'message' =>'User Not Found',
                'data' => null
            ], 404);
        }

        if($user->delete()) {
            return response([
                'message' =>'Delete User Success',
                'data' => $user
            ], 200); 
        } 

        return response([
            'message' => 'Delete User Failed',
            'data' => null,
        ], 400); 

    }

    public function update(Request $request, $id)
    {
        $user = User::find($id); 
        if (is_null($user)) {
            return response([
                'message' =>'User Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all(); 
        $validate = Validator::make($updateData, [
            'name' => 'required|max:60',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required'
        ]); 

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); 
        
        // $updateData['password'] = bcrypt($request->password);
        $user->name = $updateData['name'];
        $user->email = $updateData['email'];
        $user->password = $updateData['password'] = bcrypt($request->password);

        if($user->save()) {
            return response([
                'message' => 'Update User Success',
                'data' =>$user
            ], 200);
        } 
        return response([
            'message' => 'Update User Failed',
            'data' => null,
        ], 400); 
    }
}
