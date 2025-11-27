<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $dataRequest = $request->post();
        $validator = Validator::make($dataRequest, User::rules('login'), User::errorMessage('login'));

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 422,
                'message' => $validator->errors()->all(),
                'data' => $dataRequest
            ], 422);
        }

        $modelUser = new User();
        $user = $modelUser->getByEmail($dataRequest['email']);

        if (! $user || ! Hash::check($dataRequest['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'code' => 401,
                'message' => 'Credenciais inválidas.',
                'data' => $dataRequest
            ], 401);
        }

        $token = Str::random(32);
        $user->api_token = Hash::make($token);
        $user->save();
        
        $dataRequest['token'] = $user->api_token;

       return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Login efetuado com sucesso.',
            'data' => $dataRequest
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->api_token = null;
        $user->save();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Logout efetuado com sucesso.',
            'data' => []
        ], 200);
    }
}
