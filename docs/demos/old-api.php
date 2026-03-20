<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = DB::select('SELECT id, name, email FROM users');

        return response()->json($users);
    }

    public function show(int $id): JsonResponse
    {
        $user = DB::select('SELECT id, name, email FROM users WHERE id = ?', [$id]);

        if (empty($user)) {
            return response()->json(['error' => 'not found'], 404);
        }

        return response()->json($user[0]);
    }

    public function store(): JsonResponse
    {
        $name = request('name');
        $email = request('email');

        DB::insert('INSERT INTO users (name, email) VALUES (?, ?)', [$name, $email]);

        return response()->json(['message' => 'created'], 201);
    }
}
