<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    public function index(): JsonResponse
    {
        $modelTag = new Tag();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => '',
            'data' => $modelTag->getAll()
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $dataPost = $request->post();
        $validator = Validator::make($dataPost, Tag::rules('insert'), Tag::errorMessage('insert'));

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 422,
                'message' => $validator->errors()->all(),
                'data' => $dataPost
            ], 422);
        }

        try {
            $tagCreated = Tag::create($request->all());

            if (! $tagCreated) {
                throw new \Exception('Houve um erro ao tentar criar a tag.');
            }

            $response = [
                'success' => true,
                'code' => 200,
                'message' => 'Tag criada com sucesso.',
                'data' => $tagCreated
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
        $modelTag = new Tag();
        $tag = $modelTag->getById($id);

        if (! $tag) {
            $response = [
                'success' => false,
                'code' => 404,
                'message' => 'Tag não encontrada.',
                'data' => []
            ];
        } else {
            $response = [
                'success' => true,
                'code' => 200,
                'message' => '',
                'data' => $tag
            ];
        }

        return response()->json($response, $response['code']);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $dataRequest = $request->all();
        $dataRequest['id'] = $id;
        $validator = Validator::make($dataRequest, Tag::rules('update'), Tag::errorMessage('update'));

        if (empty($dataRequest) || $validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 422,
                'message' => $validator->errors()->all(),
                'data' => $dataRequest
            ], 422);
        }

        try {
            $modelTag = new Tag();
            $tagUpdated = $modelTag->updateTag($dataRequest);

            if (! $tagUpdated) {
                throw new \Exception('Houve um erro ao tentar atualizar a tag.');
            }

            $response = [
                'success' => true,
                'code' => 200,
                'message' => 'Tag atualizada com sucesso.',
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

    public function destroy(Request $request, $id): JsonResponse
    {
        $dataRequest = $request->all();
        $dataRequest['id'] = $id;
        $validator = Validator::make($dataRequest, Tag::rules('delete'), Tag::errorMessage('delete'));

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 422,
                'message' => $validator->errors()->all(),
                'data' => $dataRequest
            ], 422);
        }

        try {
            if (! Tag::destroy($id)) {
                throw new \Exception('Houve um erro ao tentar excluir a tag.');
            }

            $response = [
                'success' => true,
                'code' => 200,
                'message' => 'Tag excluida com sucesso.',
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
