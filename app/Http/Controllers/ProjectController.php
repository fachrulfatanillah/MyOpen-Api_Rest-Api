<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function index($user_id)
    {
        try {
            $projects = Project::where('user_id', $user_id)
                ->select('project_name', 'is_active', 'created', 'updated')
                ->get();

            return response()->json([
                'data'    => $projects,
                'success' => true,
                'status'  => 200
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch projects: ' . $e->getMessage());

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
                'user_id'      => 'required|string|size:36',
            ]);

            $user = Users::where('unicode', $validated['user_id'])->firstOrFail();

            $project = Project::create([
                'project_name' => $validated['project_name'],
                'status'       => 1,
                'is_active'    => 1,
                'user_id'      => $user->unicode,
                'created'      => now(),
                'updated'      => now(),
            ]);

            return response()->json([
                'data' => [
                    'unicode'      => $project->unicode,
                    'project_name' => $project->project_name,
                    'user_id'      => $project->user_id,
                    'created'      => $project->created,
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
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'status'  => 404,
            ], 404);
        } catch (\Exception $e) {
            Log::error('An error occurred while attempting to save the project: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'status'  => 500,
            ], 500);
        }
    }
}
