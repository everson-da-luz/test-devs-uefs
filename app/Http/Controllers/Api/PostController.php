<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        $modelPost = new Post();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => '',
            'data' => $modelPost->getAll()
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $dataPost = $request->post();
        $validator = Validator::make($dataPost, Post::rules('insert'), Post::errorMessage('insert'));

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 422,
                'message' => $validator->errors()->all(),
                'data' => $dataPost
            ], 422);
        }

        try {
            $postCreated = Post::create($request->all());

            if (! $postCreated) {
                throw new \Exception('Houve um erro ao tentar criar a postagem.');
            }

            $response = [
                'success' => true,
                'code' => 200,
                'message' => 'Postagem criada com sucesso.',
                'data' => $postCreated
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
        $modelPost = new Post();
        $post = $modelPost->getById($id);

        if (! $post) {
            $response = [
                'success' => false,
                'code' => 404,
                'message' => 'Post não encontrado.',
                'data' => []
            ];
        } else {
            $response = [
                'success' => true,
                'code' => 200,
                'message' => '',
                'data' => $post
            ];
        }

        return response()->json($response, $response['code']);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $dataRequest = $request->all();
        $dataRequest['id'] = $id;
        $validator = Validator::make($dataRequest, Post::rules('update'), Post::errorMessage('update'));

        if (empty($dataRequest) || $validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 422,
                'message' => $validator->errors()->all(),
                'data' => $dataRequest
            ], 422);
        }

        try {
            $modelPost = new Post();
            $postUpdated = $modelPost->updatePost($dataRequest);

            if (! $postUpdated) {
                throw new \Exception('Houve um erro ao tentar atualizar a postagem.');
            }

            $response = [
                'success' => true,
                'code' => 200,
                'message' => 'Postagem atualizada com sucesso.',
                'data' => $postUpdated
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
        $validator = Validator::make($dataRequest, Post::rules('delete'), Post::errorMessage('delete'));

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 422,
                'message' => $validator->errors()->all(),
                'data' => $dataRequest
            ], 422);
        }

        try {
            if (! Post::destroy($id)) {
                throw new \Exception('Houve um erro ao tentar excluir a postagem.');
            }

            $response = [
                'success' => true,
                'code' => 200,
                'message' => 'Postagem excluida com sucesso.',
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
