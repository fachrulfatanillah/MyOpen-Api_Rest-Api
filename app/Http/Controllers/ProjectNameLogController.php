<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectNameLog;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

class ProjectNameLogController extends Controller
{
    public function index()
    {
        try {
            $logs = ProjectNameLog::select('project_name', 'created', 'project_id')->get();

            return response()->json([
                'data'    => $logs,
                'success' => true,
                'status'  => 200
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch project name logs: ' . $e->getMessage());

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
                'project_name' => 'required|string|max:100',
                'project_id'   => 'required|exists:projects,unicode',
            ]);

            // Simpan log perubahan nama
            $log = ProjectNameLog::create([
                'project_name' => $validated['project_name'],
                'created'      => now(),
                'project_id'   => $validated['project_id'],
            ]);

            $project = Project::where('unicode', $validated['project_id'])->firstOrFail();
            $project->project_name = $validated['project_name'];
            $project->updated = now();
            $project->save();

            return response()->json([
                'data'    => [
                    'project_id'   => $log->project_id,
                    'project_name' => $log->project_name,
                    'created'      => $log->created,
                ],
                'success' => true,
                'status'  => 200
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'status'  => 422,
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to save project name log: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'status'  => 500,
            ], 500);
        }
    }
}
