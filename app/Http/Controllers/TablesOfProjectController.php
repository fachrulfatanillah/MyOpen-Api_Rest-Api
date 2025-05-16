<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TablesOfProject;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

class TablesOfProjectController extends Controller
{
    public function index()
    {
        try {
            $tables = TablesOfProject::select('unicode', 'table_name', 'status', 'created', 'updated', 'project_id')->get();

            return response()->json([
                'data'    => $tables,
                'success' => true,
                'status'  => 200
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch tables: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'status'  => 500,
            ], 500);
        }
    }

    public function show($unicode)
    {
        try {
            $table = TablesOfProject::where('unicode', $unicode)
                ->select('unicode', 'table_name', 'status', 'created', 'updated', 'project_id')
                ->firstOrFail();

            return response()->json([
                'data'    => $table,
                'success' => true,
                'status'  => 200
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'status'  => 404,
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to fetch table detail: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'status'  => 500,
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'table_name' => 'required|string|max:100',
                'project_id' => 'required|exists:projects,unicode',
                'status'     => 'nullable|integer|min:0|max:10',
            ]);

            $table = TablesOfProject::create([
                'table_name' => $validated['table_name'],
                'project_id' => $validated['project_id'],
                'status'     => 1,
                'created'    => now(),
                'updated'    => now(),
            ]);

            return response()->json([
                'data' => [
                    'unicode'     => $table->unicode,
                    'table_name'  => $table->table_name,
                    'status'      => $table->status,
                    'project_id'  => $table->project_id,
                    'created'     => $table->created,
                ],
                'success' => true,
                'status'  => 200
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'status'  => 422,
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to create table of project: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'status'  => 500,
            ], 500);
        }
    }
}
