<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\UserStatusLog;
use Illuminate\Support\Facades\Log;

class UserStatusLogController extends Controller
{
    public function index()
    {
        try {
            $logs = UserStatusLog::select('user_id', 'status', 'created')->get();

            return response()->json([
                'data'    => $logs,
                'success' => true,
                'status'  => 200
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching status log: ' . $e->getMessage());

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
                'user_id' => 'required|exists:users,unicode',
                'status'  => 'required|integer|min:0|max:10',
            ]);

            $user = Users::where('unicode', $validated['user_id'])->firstOrFail();

            $user->status = $validated['status'];
            $user->updated = now();
            $user->save();

            $log = UserStatusLog::create([
                'user_id' => $validated['user_id'],
                'status'  => $validated['status'],
                'created' => now(),
            ]);

            return response()->json([
                'data' => [
                    'user_id' => $log->user_id,
                    'status'  => $log->status,
                    'created' => $log->created,
                ],
                'success' => true,
                'status'  => 200
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'status'  => 422,
            ], 422);
        } catch (\Exception $e) {
            Log::error('Exception on status log: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'status'  => 500,
            ], 500);
        }
    }
}
