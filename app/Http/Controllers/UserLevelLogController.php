<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\UserLevelLog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class UserLevelLogController extends Controller
{
    public function index()
    {
        try {
            $logs = UserLevelLog::select('user_id', 'level', 'created')->get();

            return response()->json([
                'data'    => $logs,
                'success' => true,
                'status'  => 200
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching list: ' . $e->getMessage());

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
                'level'   => 'required|integer|min:0|max:10',
            ]);

            $user = Users::where('unicode', $validated['user_id'])->firstOrFail();

            $user->level = $validated['level'];
            $user->updated = now();
            $user->save();

            $log = UserLevelLog::create([
                'user_id' => $validated['id'],
                'level'   => $validated['level'],
                'created' => now(),
            ]);

            return response()->json([
                'data' => [
                    'user_id' => $log->user_id,
                    'level'   => $log->level,
                    'created' => $log->created,
                ],
                'success' => true,
                'status'  => 200
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'status'  => 422,
            ], 422);
        } catch (\Exception $e) {
            Log::error('Exception on user :  ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'status'  => 500,
            ], 500);
        }
    }
}
