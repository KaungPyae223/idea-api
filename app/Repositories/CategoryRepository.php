<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\BasicFunctions\BasicFunctions;
use Illuminate\Support\Facades\DB;

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


    }

    public function update($id, array $data)
    {

    }

    public function destroy($id)
    {

    }
}
