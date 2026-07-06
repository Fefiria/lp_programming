<?php

namespace App\Http\Controllers;

use App\Http\Resources\DivisionResource;
use App\Models\Division;
use Exception;
use Illuminate\Http\Request;


class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $limit = $request->query('limit', 10);
            $page = $request->query('page', 1);
            $sort = $request->query('sort', 'created_at');
            $order = $request->query('order', 'desc');
            $search = $request->query('search', '');

            $results = Division::where('name', 'like', "%{$search}%")->orderBy($sort, $order)->paginate($limit, ['*'], 'page', $page);

            return DivisionResource::collection($results)
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


    public function store(Request $request)
    {
        try {
            if (!$request->hasFile('file')) {
                return response()->json(['error' => 'No file uploaded'], 400);
            }

            return DivisionResource::make($uploadFile)
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


    public function show(Division $division)
    {
        //
    }


    public function update(Request $request, Division $division)
    {
        //
    }


    public function destroy(Division $division)
    {
        //
    }
}
