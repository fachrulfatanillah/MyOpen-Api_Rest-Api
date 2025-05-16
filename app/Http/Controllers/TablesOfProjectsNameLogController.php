<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TablesOfProjectsNameLog;
use App\Models\TablesOfProject;
use Illuminate\Support\Facades\Log;

class TablesOfProjectsNameLogController extends Controller
{
    public function index()
    {
        try {
            $logs = TablesOfProjectsNameLog::select('table_id', 'table_name', 'created')->get();

            return response()->json([
                'data'    => $logs,
                'success' => true,
                'status'  => 200,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch name logs: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'status'  => 500,
                'message' => 'An error occurred while fetching logs.',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'table_name' => 'required|string|max:100',
                'table_id'   => 'required|exists:tables_of_projects,unicode',
            ]);

            $log = TablesOfProjectsNameLog::create([
                'table_name' => $validated['table_name'],
                'created'    => now(),
                'table_id'   => $validated['table_id'],
            ]);

            $table = TablesOfProject::where('unicode', $validated['table_id'])->firstOrFail();
            $table->table_name = $validated['table_name'];
            $table->updated = now();
            $table->save();

            return response()->json([
                'data' => [
                    'table_name' => $log->table_name,
                    'created'    => $log->created,
                ],
                'success' => true,
                'status'  => 200,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'status'  => 422,
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to log and update table name: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'status'  => 500,
            ], 500);
        }
    }
}
