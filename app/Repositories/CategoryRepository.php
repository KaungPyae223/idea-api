<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\BasicFunctions\BasicFunctions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryRepository extends BasicFunctions
{
    protected $model;

    public function __construct()
    {
        $this->model = new Category();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {

        try {
        DB::beginTransaction();
        $category = $this->model->create($data);
        $this->addLog([
            "user_id" => 1,
            "type" => "category",
            "action" => "create",
            "activity" => "create category ".$data["name"],
        ]);

        DB::commit();

        return $category;

        } catch (\Throwable $e) {
            DB::rollBack();
            return $e;
        }
    }

    public function update($id, array $data)
    {
        try {
            DB::beginTransaction();
            $category = $this->find($id);
            $this->addLog([
                "user_id" => 1,
                "type" => "category",
                "action" => "update",
                "activity" => "update category id : " . $id . " / " . $this->compareDiff("name", $category->name, $data["name"]),
            ]);

            $category->update($data);
            DB::commit();
            return $category;
        } catch (\Throwable $e) {
            DB::rollBack();
            return $e;
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $category = $this->model->find($id);
            if ($category) {
                $category->delete();

                $this->addLog([
                    "user_id" => 1,
                    "type" => "category",
                    "action" => "delete",
                    "activity" => "delete category id : " . $category->id,
                ]);
                DB::commit();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }
}
