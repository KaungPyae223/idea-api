<?php

namespace App\Repositories;

use App\Models\SystemSetting;
use App\Repositories\BasicFunctions\BasicFunctions;
use Illuminate\Support\Facades\DB;

class SystemSettingRepository extends BasicFunctions
{
    protected $model;

    public function __construct()
    {
        $this->model = new SystemSetting();
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
