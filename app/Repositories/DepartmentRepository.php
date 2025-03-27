<?php

namespace App\Repositories;

use App\Models\Department;
use App\Repositories\BasicFunctions\BasicFunctions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DepartmentRepository extends BasicFunctions
{
    protected $model;

    public function __construct()
    {
        $this->model = new Department();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {

        try {

            DB::beginTransaction();

            $department = $this->model->create($data);

            $this->addLog([
                "user_id" => Auth::id(),
                "type" => "department",
                "action" => "create",
                "activity" => "create department ".$data["department_name"],
            ]);

            DB::commit();

            return $department;

        } catch (\Throwable $e) {
            DB::rollBack();

            return $e;
        }
    }

    public function update($id, array $data)
    {

        try {

            DB::beginTransaction();

            $department = $this->find($id);


            $this->addLog([
               "user_id" => Auth::id(),

                "type" => "department",
                "action" => "update",
                "activity" => "update department id : " . $id . " / " . $this->compareDiff("department_name", $department->department_name, $data["department_name"]) . $this->compareDiff("QACoordinatorID", $department->QACoordinatorID, $data["QACoordinatorID"]),
            ]);

            $department->update($data);

            return $department;

            DB::commit();
        } catch (\Throwable $e) {

            DB::rollBack();

            return $e;
        }

    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $department = $this->find($id);

            $this->addLog([
               "user_id" => Auth::id(),

                "type" => "department",
                "action" => "delete",
                "activity" => "delete department name : " . $department->department_name,
            ]);

            $department->delete();

            DB::commit();

            return true;
        } catch (\Throwable $e) {

            DB::rollBack();

            return $e;
        }
    }
}
