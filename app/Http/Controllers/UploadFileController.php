<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;
use App\Http\Resources\UploadFileResource;
use App\Models\UploadFile;
use Exception;
use Illuminate\Http\Request;

class UploadFileController extends Controller
{
    public function index(Request $request)
    {
        try {
            $limit = $request->query('limit', 10);
            $page = $request->query('page', 1);
            $sort = $request->query('sort', 'created_at');
            $order = $request->query('order', 'desc');

            $results = UploadFile::orderBy($sort, $order)->paginate($limit, ['*'], 'page', $page);

            return UploadFileResource::collection($results)
                ->additional([
                    'status' => 'success',
                    'message' => 'Upload files retrieved successfully',
                ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve upload files',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(UploadFileRequest $request, UploadFile $fileService)
    {
        try {
            if (!$request->hasFile('file')) {
                return response()->json(['error' => 'No file uploaded'], 400);
            }

            $uploadFile = $fileService->uploadFile($request->file('file'));

            return response()->json([
                'message' => 'Upload file created successfully',
                'data' => new UploadFileResource($uploadFile)
            ], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(UploadFile $uploadFile)
    {
        try {
            return response()->json(['message' => 'Upload file retrieved successfully', 'data' => new UploadFileResource($uploadFile)], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, UploadFile $uploadFile)
    {
        //
    }

    public function destroy(UploadFile $uploadFile)
    {
        //
    }
}
