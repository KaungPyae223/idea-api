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


        try {

            DB::beginTransaction();

            $systemSetting = $this->model->create($data);

            $this->addLog([
                "user_id" => 1,
                "type" => "system-setting",
                "action" => "create",
                "activity" => "create system setting " . $data["academic_year"],
            ]);

            DB::commit();

            return $systemSetting;
        } catch (\Throwable $e) {
            DB::rollBack();

            return $e;
        }
    }

    public function update($id, array $data)
    {
        try {
            DB::beginTransaction();

            $systemSetting = $this->find($id);

            if (!$systemSetting ) {
                throw new \Exception('System setting not found.');
            }

            $this->addLog([
                "user_id" => 1,
                "type" => "system_setting",
                "action" => "update",
                "activity" => "update system setting id : " . $id . " / " . $this->compareDiff("idea_closure_date", $systemSetting->idea_closure_date, $data["idea_closure_date"]) . $this->compareDiff("final_closure_date", $systemSetting->final_closure_date, $data["final_closure_date"]) . $this->compareDiff("academic_year", $systemSetting->academic_year, $data["academic_year"]),
            ]);

            $systemSetting->update($data);

            DB::commit();

            return $systemSetting ;
        } catch (\Throwable $e) {
            DB::rollBack();

            return $e;
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $systemSetting = $this->model->find($id);
            if ($systemSetting ) {
                $systemSetting->delete();
                $this->addLog([
                    "user_id" => 1,
                    "type" => "system_setting",
                    "action" => "delete",
                    "activity" => "delete system setting id : " . $systemSetting->id,
                ]);

                DB::commit();
                return true;
            }
            return false ;
        } catch (\Throwable $e) {
            DB::rollBack();

            return $e;
        }
    }
}
