<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $users = User::query()
            ->when(request('role'), fn ($query, $role) => $query->where('role', $role))
            ->paginate(25);

        return UserResource::collection($users);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user->load('teams'));
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = User::create([
            ...$request->validated(),
            'role' => $request->input('role', 'member'),
        ]);

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }
}
