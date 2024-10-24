<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Helper\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Exception;

class AuthController extends Controller
{

    /**
     *FUNCTION: REGISTER NEW USER.
     * @param App\Requests\RegisterRequest $request
     * @return JSONResponse 
     */
    public function register(RegisterRequest $request)
    {
        try {
            $user = user::create([
                'name'=> $request->name, 
                'email'=> $request->email,
                'password'=> Hash::make($request->password),
                'phone_number'=> $request->phone_number,  
            ]);

            if ($user) {  
  
                return ResponseHelper::success(message: 'user has been registered successfully!', data: $user, statusCode: 201);
            }
            return ResponseHelper::error(message: 'unable to register user, please try again.', statusCode: 400);
        }
        catch (Exception $e) {
            Log::error('unable to register user : ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return ResponseHelper::error(message: 'unable to register user, please try again.' . $e->getMessage(), statusCode: 500);
        }
    }

 
    /**
     * FUNCTION: LOGIN USER.
     * @param App\Request\LoginRequest $request
     */
    public function login(LoginRequest $request)
    {
        try{

            // If credentials are incorrect
            if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return ResponseHelper::error(message: 'Invalid! please try again.', statusCode: 400);
            }
 
            $user = Auth::user();
            
            //create API Token
            $token = $user->createToken('My API Token')->plainTextToken;
           
            $authUser = [
                'user'=> $user,
                'token' => $token

            ];

            return ResponseHelper::success(message: 'You are logged in successfully!', data: $authUser, statusCode: 200);
           
        }
        catch(Exception $e){

            Log::error('unable to login user : ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return ResponseHelper::error(message: 'unable to login! please try again.' . $e->getMessage(), statusCode: 500);
        }
    }

    /** 
     * function: Auth user data / profile data
     * @param NA 
     * @return JSONResponse 
    */

    public function userProfile() {
        try {
            $user = Auth::user();

            if ( $user){
                return ResponseHelper::success(message: 'user profile fetched successfully!', data: $user, statusCode: 200);  
            }

            return ResponseHelper::error(message: 'unable to fetch user data! invalid token.', statusCode: 400);
        }
        catch(Exception $e){

            Log::error('unable to fetch user profile data : ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return ResponseHelper::error(message: 'unable to fetch user profile data.' . $e->getMessage(), statusCode: 500);
        }

    }
/**
 * FUNCTION: LOGOUT USER 
 * @param NA
 * @return JSONResponse
 */  

 public function userLogout() {
    try {
    
        $user = Auth::user();

        if ($user) {
            $accessToken = $user->currentAccessToken();
            
            if ($accessToken) {
                $accessToken->delete();
                return ResponseHelper::success(message: 'user logged out successfully!', statusCode: 200);  
            }

            return ResponseHelper::error(message: 'No active access token found.', statusCode: 400);
        } 

        return ResponseHelper::error(message: 'unable to logout! invalid token.', statusCode: 400);
    }
    catch (Exception $e) {
        Log::error('unable to logout due to some exception: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
        return ResponseHelper::error(message: 'unable to logout due to some exception: ' . $e->getMessage(), statusCode: 500);
    }
}

    
}
