<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;
use App\Http\Resources\UploadFileResource;
use App\Models\UploadFile;
use App\Http\Services\UploadFileService;
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
            $search = $request->query('search', '');

            $results = UploadFile::where('name', 'like', "%{$search}%")->orderBy($sort, $order)->paginate($limit, ['*'], 'page', $page);

            return UploadFileResource::collection($results)
                ->additional([
                    'status' => 'success',
                    'status_code' => 200,
                    'message' => 'Upload files retrieved successfully',
                ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'status_code' => $e->getCode() ?: 500,
                'message' => 'Failed to retrieve upload files',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(UploadFileRequest $request)
    {
        try {
            if (!$request->hasFile('file')) {
                return response()->json(['error' => 'No file uploaded'], 400);
            }

            $fileService = new UploadFileService();
            $uploadFile = $fileService->uploadFile($request->file('file'));

            return UploadFileResource::make($uploadFile)
                ->additional([
                    'status' => 'success',
                    'status_code' => 201,
                    'message' => 'Upload file created successfully',
                ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'status_code' => $e->getCode() ?: 500,
                'message' => 'Failed to create upload file',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(UploadFile $uploadFile)
    {
        try {
            return UploadFileResource::make($uploadFile)
                ->additional([
                    'status' => 'success',
                    'status_code' => 200,
                    'message' => 'Upload file retrieved successfully',
                ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'status_code' => $e->getCode() ?: 500,
                'message' => 'Failed to retrieve upload file',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function publishToUserStorageExample(Request $request)
    {
        try {
            $request->validate([
                'id_file' => 'required|exists:upload_files,id_file',
            ]);

            $idFile = $request->input('id_file');
            $primaryDirectory = 'users';

            $fileService = new UploadFileService();
            $newPath = $fileService->moveFileToPrimary($idFile, $primaryDirectory);

            if ($newPath) {
                return response()->json([
                    'status' => 'success',
                    'status_code' => 200,
                    'message' => 'File berhasil dipindahkan ke primary folder',
                    'data' => [
                        'new_path' => $newPath,
                        'full_url' => asset('storage/' . $newPath)
                    ]
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'status_code' => 404,
                'message' => 'File temp tidak ditemukan atau sudah kadaluarsa/terhapus',
            ], 404);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => 'Gagal memindahkan file',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
