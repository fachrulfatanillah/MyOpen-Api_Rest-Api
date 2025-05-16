<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectIsActiveLog;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

class ProjectIsActiveLogController extends Controller
{
    public function index()
    {
        try {
            $logs = ProjectIsActiveLog::select('project_id', 'is_active', 'created')->get();

            return response()->json([
                'data'    => $logs,
                'success' => true,
                'status'  => 200
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch is_active logs: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'status'  => 500,
                'message' => 'An error occurred while fetching logs.'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'is_active'  => 'required|boolean',
                'project_id' => 'required|exists:projects,unicode',
            ]);

            $log = ProjectIsActiveLog::create([
                'is_active'  => $validated['is_active'],
                'created'    => now(),
                'project_id' => $validated['project_id'],
            ]);

            $project = Project::where('unicode', $validated['project_id'])->firstOrFail();
            $project->is_active = $validated['is_active'];
            $project->updated = now();
            $project->save();

            return response()->json([
                'data' => [
                    'project_id' => $log->project_id,
                    'is_active'  => $log->is_active,
                    'created'    => $log->created,
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
            Log::error('Failed to log and update is_active: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'status'  => 500,
                'message' => 'An error occurred while saving the log.'
            ], 500);
        }
    }
}
