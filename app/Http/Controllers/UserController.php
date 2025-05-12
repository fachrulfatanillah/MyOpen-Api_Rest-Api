<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = Users::all()->map(function ($user) {
                return [
                    'id'    => $user->unicode,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'image' => $user->image_url,
                ];
            });

            return response()->json([
                'data'    => $users,
                'success' => true,
                'status'  => 200
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching user list: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'status'  => 500,
            ], 500);
        }
    }

    public function show($unicode)
    {
        try {
            $user = Users::where('unicode', $unicode)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'status'  => 404
                ], 404);
            }

            return response()->json([
                'data'    => [
                    'id'    => $user->unicode,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'image' => $user->image_url,
                ],
                'success' => true,
                'status'  => 200
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching user: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'status'  => 500
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'google_id'       => 'required|string|max:100',
                'name'            => 'required|string|max:100',
                'email'           => 'required|email|max:100',
                'image_url'       => 'nullable|url',
                'email_verified'  => 'required|boolean',
            ]);

            $user = Users::where('google_id', $validated['google_id'])
                ->orWhere('email', $validated['email'])
                ->first();

            if (!$user) {
                $user = Users::create([
                    'unicode'         => (string) Str::uuid(),
                    'google_id'       => $validated['google_id'],
                    'name'            => $validated['name'],
                    'email'           => $validated['email'],
                    'image_url'       => $validated['image_url'] ?? null,
                    'email_verified'  => $validated['email_verified'],
                    'status'          => 1,
                    'level'           => 0,
                    'created'         => now(),
                    'updated'         => now(),
                ]);
            }

            return response()->json([
                'success' => true,
                'status'  => 200,
                'data'    => [
                    'id'    => $user->unicode,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'image' => $user->image_url,
                ]
            ], 200);
        } catch (QueryException $e) {
            Log::error('QueryException on user store: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'status'  => 500,
            ], 500);
        } catch (\Exception $e) {
            Log::error('Exception on user store: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'status'  => 500,
            ], 500);
        }
    }
}
