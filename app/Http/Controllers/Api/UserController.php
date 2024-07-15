<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;

class UserController extends BaseController
{
    public function index()
    {
        $users = User::all();

        if (!auth()->check()) {
            return $this->sendError('Unauthorized.', ['error' => 'Unauthorized'], 401);
        }

        if ($users->isEmpty()) {
            return $this->sendError('No users found.', [], 404);
        }
        return $this->sendResponse($users->toArray(), 'Users retrieved successfully.');
    }

    public function profile()
    {
        $user = auth()->user();

        if (!$user) {
            return $this->sendError('User not found.', [], 404);
        }

        return $this->sendResponse($user->toArray(), 'User profile retrieved successfully.');
    }
}

