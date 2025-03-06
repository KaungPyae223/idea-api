<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     protected $userRepository;

     public function __construct(UserRepository $userRepository)
     {
        $this->userRepository = $userRepository;
     }

    public function index()
    {
        $users = User::paginate(10);

        return UserResource::collection($users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userRepository->create($request->all());

        return $user;

        return response()->json(['message' => 'User created successfully.', 'user' => new UserResource($user)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:users,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid user ID'
            ], 404);
        }

        $department = $this->userRepository->find($id);

        return new UserResource($department);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {

        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:users,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid user ID'
            ], 404);
        }

        $user = $this->userRepository->update($id, $request->all());


        return response()->json(['message' => 'User updated successfully.', 'user' => new UserResource($user)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
