<?php

namespace App\Http\Controllers;

use App\Infastructures\Response;
use App\Applications\UserApplication;
use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class UserController extends Controller
{
    protected $userApplication;
    protected $userRepository;
    protected $response;

    public function __construct(
        UserApplication $userApplication,
        UserRepository $userRepository,
        Response $response)
    {
        $this->userApplication = $userApplication;
        $this->userRepository = $userRepository;
        $this->response = $response;
    }

    public function register(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email',
            'password'  => 'required|min:8|confirmed'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create user
        $user = $this->userApplication
            ->preparation($request)
            ->create()
            ->execute();

        //return response JSON user is created
        if($user) {
            return $this->response->successResponse("Successfully register user data", $user->original['data']);
        }

        //return JSON process insert failed 
        return $this->response->errorResponse("Failed register user data");
    }

    public function login(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'email'     => 'required',
            'password'  => 'required'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //get credentials from request
        $credentials = $request->only('email', 'password');

        //if auth failed
        if(!$token = auth()->guard('api')->attempt($credentials)) {
            return $this->response->errorResponse("Your email or password was wrong");
        }

        //if auth success
        return response()->json([
            'success' => true,
            'data'    => auth()->guard('api')->user(),    
            'token'   => $token   
        ], 200);
    }

    public function logout(Request $request)
    {
        //remove token
        $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

        if($removeToken) {
            return $this->response->successResponse("Successfully register user data", null);
        }
    }

    public function index()
    {
        $users = $this->userRepository->index();
        return $this->response->successResponse("Successfully get users data", $users);
    }

    public function profile()
    {
        return auth()->guard('api')->user();
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($userId)
    {
        $users = $this->userRepository->findById($userId);
        return $this->response->successResponse("Successfully get user data", $users);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $userId)
    {
        $update = $this->userApplication
            ->preparation($request, $userId)
            ->update()
            ->execute();

        if ($update->original['status'])
        {
            return $this->response->successResponse("Successfully update user data", $update->original['data']); 
        }
        
        return $this->response->successResponse("Failed update user data", $update->original['data']); 
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($userId)
    {
        $delete = $this->userApplication
            ->preparation(null, $userId)
            ->delete()
            ->execute();

        if ($delete->original['status'])
        {
            return $this->response->successResponse("Successfully delete user data", $delete->original['data']); 
        }
        
        return $this->response->successResponse("Failed delete user data", $delete->original['data']); 
    }
}