<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TablesOfProjectsStatusLog;
use App\Models\TablesOfProject;
use Illuminate\Support\Facades\Log;

class TablesOfProjectsStatusLogController extends Controller
{
    public function index()
    {
        try {
            $logs = TablesOfProjectsStatusLog::select('table_id', 'status', 'created')->get();

            return response()->json([
                'data'    => $logs,
                'success' => true,
                'status'  => 200,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch status logs: ' . $e->getMessage());

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
                'status'   => 'required|integer|min:0|max:10',
                'table_id' => 'required|exists:tables_of_projects,unicode',
            ]);

            $log = TablesOfProjectsStatusLog::create([
                'status'   => $validated['status'],
                'created'  => now(),
                'table_id' => $validated['table_id'],
            ]);

            $table = TablesOfProject::where('unicode', $validated['table_id'])->firstOrFail();
            $table->status = $validated['status'];
            $table->updated = now();
            $table->save();

            return response()->json([
                'data' => [
                    'status'   => $log->status,
                    'created'  => $log->created,
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
            Log::error('Failed to log and update table status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'status'  => 500,
            ], 500);
        }
    }
}
