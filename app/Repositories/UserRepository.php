<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BasicFunctions\BasicFunctions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BasicFunctions
{
    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {

        try {

            DB::beginTransaction();

            $roleIDs = explode(',', $data["role_id"]);
            $permissionIDs = explode(',', $data["permissions_id"]);


            $user = $this->model->create([
                "name" => $data["name"],
                "email" => $data["email"],
                "department_id" => $data["department_id"],
                "password" => Hash::make("idea")
            ]);

            $user->roles()->attach($roleIDs);
            $user->permissions()->attach($permissionIDs);

            $this->addLog([
                "user_id" => 1,
                "type" => "user",
                "action" => "create",
                "activity" => "create user " . $data['name'],
            ]);

            DB::commit();

            return $user;
        } catch (\Throwable $e) {
            DB::rollBack();

            return $e;
        }
    }

    public function update($id, array $data)
    {
        try {

            DB::beginTransaction();

            $user = $this->model::find($id);

            $userRoles = $user->roles->pluck('id')->toArray();
            $userPermissions = $user->permissions->pluck('id')->toArray();

            $originalRoles = implode(',', $userRoles);
            $originalPermissions = implode(',', $userPermissions);

            $roleIDs = explode(',', $data["role_id"]);
            $permissionIDs = explode(',', $data["permissions_id"]);

            $rolesToDelete = array_diff($userRoles, $roleIDs);
            $rolesToAdd = array_diff($roleIDs, $userRoles);

            $permissionsToDelete = array_diff($userPermissions, $permissionIDs);
            $permissionsToAdd = array_diff($permissionIDs, $userPermissions);

            $this->addLog([
                "user_id" => 1,
                "type" => "user",
                "action" => "create",
                "activity" => "Update user " . $id . " / " . $this->compareDiff("name", $user->name, $data["name"]) . $this->compareDiff("email", $user->email, $data["email"]) . $this->compareDiff("department_id", $user->department_id, $data["department_id"]) . $this->compareDiff("role_ids", $originalRoles, $data["role_id"]) . $this->compareDiff("permissions_ids", $originalPermissions, $data["permissions_id"])

            ]);

            if ($rolesToAdd) {
                $user->roles()->attach($rolesToAdd);
            }
            if ($permissionsToAdd) {
                $user->permissions()->attach($permissionsToAdd);
            }

            if ($rolesToDelete) {
                $user->roles()->detach($rolesToDelete);
            }
            if ($permissionsToDelete) {
                $user->permissions()->detach($permissionsToDelete);
            }

            $user->update([
                "name" => $data["name"],
                "email" => $data["email"],
                "department_id" => $data["department_id"],
            ]);

            DB::commit();

            return $user;

        } catch (\Throwable $e) {
            DB::rollBack();

            return $e;
        }
    }

    public function destroy($id) {}
}
