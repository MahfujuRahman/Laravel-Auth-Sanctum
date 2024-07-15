<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $requestData = $request->all();
        $input['name'] = $requestData['name'];
        $input['email'] = $requestData['email'];
        $input['password'] = bcrypt($requestData['password']);

        $user = User::create($input);

        $token = $user->createToken('authToken')->plainTextToken;
        return $this->sendResponse(['token' => $token], 'User created successfully.');

    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */

    public function login(Request $request)
    {
        $requestData = $request->all();
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return $this->sendError('Unauthorized.', ['error' => 'Unauthorized'], 401);
        }
        $user = $request->user();
        $token = $user->createToken('authToken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token,
        ];
        return $this->sendResponse($response, 'User logged in successfully.');

    }

    public function logout()
    {
        $user = Auth::user();
        if ($user) {
            $name = $user->name;
            $user->currentAccessToken()->delete();
            return $this->sendResponse([], $name.' Successfully logged out.');
        } else {
            return $this->sendError('Unauthorized.', ['error' => 'Unauthorized'], 401);
        }
    }
}
