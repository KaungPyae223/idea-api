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
            $category = $this->model->find($id);
            if ($category) {
                $category->update($data);
                return $category;
            }
            return null;
        } catch (\Exception $e) {
            Log::error('Error updating category: ' . $e->getMessage());
            return null;
        }
    }

    public function destroy($id)
    {
        try {
            $category = $this->model->find($id);
            if ($category) {
                $category->delete();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage());
            return false;
        }
    }
}
