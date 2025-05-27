<?php

namespace App\Http\Controllers;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use App\Services\ResponseService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    protected $userService;
    protected $response;

    public function __construct(UserService $userService, ResponseService $response) {
        $this->userService = $userService;
        $this->response = $response;
    }
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
            ]);
            $user = $this->userService->register($validated);
            $user=[
                'userId'=>$user->id,
                'userName'=>$user->name,
                'userEmail'=>$user->email,
            ];
            return $this->response->successResponse($user, 'User registered successfully');
         }
         catch (ValidationException $e) {
                return $this->response->validationError($e->errors());}
        catch (\Throwable $e) {
            return $this->response->errorResponse($e->getMessage(), 500);
        }
    }
    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email'    => 'required|email|exists:users,email',
                'password' => 'required|string|min:6',
            ]);
            $user = $this->userService->login($validated);
            return $this->response->successResponse($user, 'User login successfully');
         }
         catch (ValidationException $e) {
                return $this->response->validationError($e->errors());}
        catch (\Throwable $e) {
            return $this->response->errorResponse($e->getTraceAsString(),  $e->getCode() ?: 500);
        }
    }

    public function logout()
    {
        try {
            $user = Auth::user();

            if (! $user || ! $user->currentAccessToken()) {
                $message ='Already logged out or token has expired';
                return $this->response->errorResponse($message, 401);
            }
            $userData = [
                'name' => $user->name,
                'email' => $user->email,
            ];
            $user->currentAccessToken()->delete();

            return $this->response->successResponse($userData, 'Logged out successfully');
        } catch (\Throwable $e) {
            return $this->response->errorResponse($e->getTraceAsString(),  $e->getCode() ?: 500);
        }
    }
}
