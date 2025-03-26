<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\IdeaResource;
use App\Http\Resources\UserResource;
use App\Models\SystemSetting;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    protected function checkID($id)
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:users,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid User ID'
            ], 404);
        }

        return null;
    }
    protected function checkPermissions($permissions)
    {

        $permissionArr = explode(',', $permissions);

        foreach ($permissionArr as $id) {
            $validated = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:permissions,id',
            ]);

            if ($validated->fails()) {
                return response()->json([
                    'message' => "Permission ID $id is Invalid Permission ID"
                ], 404);
            }
        }

        return null;
    }
    protected function checkRole($role)
    {

        $rolesArr = explode(',', $role);

        foreach ($rolesArr as $id) {
            $validated = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:roles,id',
            ]);

            if ($validated->fails()) {
                return response()->json([
                    'message' => "Role ID $id is Invalid Role ID"
                ], 404);
            }
        }

        return null;
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


        $checkRole = $this->checkRole($request->role_id);

        if ($checkRole) {
            return $checkRole;
        }

        $checkPermission = $this->checkPermissions($request->permissions_id);

        if ($checkPermission) {
            return $checkPermission;
        }

        $user = $this->userRepository->create($request->all());

        return $user;

        return response()->json(['message' => 'User created successfully.', 'user' => new UserResource($user)], 201);
    }

    public function userIdeas(Request $request, $id)
    {
        $checkID = $this->checkID($id);

        if ($checkID) {
            return $checkID;
        }

        $user = $this->userRepository->find($id);

        $systemSettingID = SystemSetting::query()->orderBy("id", "desc")->first()->id;

        if ($request->input("systemSettingID")) {

            $systemSettingID = $request->input("systemSettingID");

            $validated = Validator::make(['id' => $systemSettingID], [
                'id' => 'required|integer|exists:system_settings,id',
            ]);

            if ($validated->fails()) {
                return response()->json([
                    'message' => 'Invalid system setting ID'
                ], 404);
            }
        }

        $ideas = $user->ideas()
            ->where("system_setting_id", $systemSettingID)
            ->paginate(5);

        return IdeaResource::collection($ideas);
    }

    public function userIdeasByAdmin(Request $request, $id)
    {
        $checkID = $this->checkID($id);

        if ($checkID) {
            return $checkID;
        }

        $user = $this->userRepository->find($id);

        $systemSettingID = SystemSetting::query()->orderBy("id", "desc")->first()->id;

        if ($request->input("systemSettingID")) {

            $systemSettingID = $request->input("systemSettingID");

            $validated = Validator::make(['id' => $systemSettingID], [
                'id' => 'required|integer|exists:system_settings,id',
            ]);

            if ($validated->fails()) {
                return response()->json([
                    'message' => 'Invalid system setting ID'
                ], 404);
            }
        }

        $ideas = $user->ideas()
            ->where("system_setting_id", $systemSettingID)
            ->where("is_anonymous",false)
            ->paginate(5);

        return IdeaResource::collection($ideas);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $checkID = $this->checkID($id);

        if ($checkID) {
            return $checkID;
        }

        $department = $this->userRepository->find($id);

        return new UserResource($department);
    }

    public function restartPassword($id)
    {

        $checkID = $this->checkID($id);

        if ($checkID) {
            return $checkID;
        }

        $user = $this->userRepository->find($id);

        $user->update([
            "password" => Hash::make("idea")
        ]);

        $user->tokens()->delete();

        return response()->json([
            "message" => "Successfully Reset the Password"
        ]);
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

        $checkID = $this->checkID($id);

        if ($checkID) {
            return $checkID;
        }

        $checkRole = $this->checkRole($request->role_id);

        if ($checkRole) {
            return $checkRole;
        }

        $checkPermission = $this->checkPermissions($request->permissions_id);

        if ($checkPermission) {
            return $checkPermission;
        }

        $user = $this->userRepository->update($id, $request->all());

        $user->tokens()->delete();

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
