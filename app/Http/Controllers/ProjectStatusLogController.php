<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectStatusLog;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

class ProjectStatusLogController extends Controller
{
    public function index()
    {
        try {
            $logs = ProjectStatusLog::select('project_id', 'status', 'created')->get();

            return response()->json([
                'data'    => $logs,
                'success' => true,
                'status'  => 200,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch project status logs: ' . $e->getMessage());

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
                'status'     => 'required|integer|min:0|max:10',
                'project_id' => 'required|exists:projects,unicode',
            ]);

            $log = ProjectStatusLog::create([
                'status'     => $validated['status'],
                'created'    => now(),
                'project_id' => $validated['project_id'],
            ]);

            $project = Project::where('unicode', $validated['project_id'])->firstOrFail();
            $project->status = $validated['status'];
            $project->updated = now();
            $project->save();

            return response()->json([
                'data' => [
                    'project_id' => $log->project_id,
                    'status'     => $log->status,
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
            Log::error('Failed to log and update project status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'status'  => 500,
            ], 500);
        }
    }
}
