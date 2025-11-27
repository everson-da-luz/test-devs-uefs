<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $modelUser = new User();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => '',
            'data' => $modelUser->getAll()
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $dataPost = $request->post();
        $validator = Validator::make($dataPost, User::rules('insert'), User::errorMessage('insert'));

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 422,
                'message' => $validator->errors()->all(),
                'data' => $dataPost
            ], 422);
        }

        try {
            $userCreated = User::create($request->all());

            if (! $userCreated) {
                throw new \Exception('Houve um erro ao tentar criar o usuário.');
            }

            $response = [
                'success' => true,
                'code' => 200,
                'message' => 'Usuário criado com sucesso.',
                'data' => $userCreated
            ];
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }

        return response()->json($response, $response['code']);
    }

    public function show($id): JsonResponse
    {
        $userModel = new User();
        $user = $userModel->getById($id);

        if (! $user) {
            $response = [
                'success' => false,
                'code' => 404,
                'message' => 'Usuário não encontrado.',
                'data' => []
            ];
        } else {
            $response = [
                'success' => true,
                'code' => 200,
                'message' => '',
                'data' => $user
            ];
        }

        return response()->json($response, $response['code']);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $dataRequest = $request->all();
        $dataRequest['id'] = $id;
        $validator = Validator::make($dataRequest, User::rules('update', $id), User::errorMessage('update'));

        if (empty($dataRequest) || $validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 422,
                'message' => $validator->errors()->all(),
                'data' => $dataRequest
            ], 422);
        }

        try {
            $userModel = new User();
            $userUpdated = $userModel->updateUser($dataRequest);

            if (! $userUpdated) {
                throw new \Exception('Houve um erro ao tentar atualizar o usuário.');
            }

            $response = [
                'success' => true,
                'code' => 200,
                'message' => 'Usuário atualizado com sucesso.',
                'data' => $userUpdated
            ];
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }

        return response()->json($response, $response['code']);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $dataRequest = $request->all();
        $dataRequest['id'] = $id;
        $validator = Validator::make($dataRequest, User::rules('delete'), User::errorMessage('delete'));

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 422,
                'message' => $validator->errors()->all(),
                'data' => $dataRequest
            ], 422);
        }

        try {
            if (! User::destroy($id)) {
                throw new \Exception('Houve um erro ao tentar excluir o usuário.');
            }

            $response = [
                'success' => true,
                'code' => 200,
                'message' => 'Usuário excluido com sucesso.',
                'data' => $dataRequest
            ];
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }

        return response()->json($response, $response['code']);
    }
}
