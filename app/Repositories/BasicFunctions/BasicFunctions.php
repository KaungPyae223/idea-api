<?php

namespace App\Repositories\BasicFunctions;

use App\Models\Log;

class BasicFunctions
{

    public function compareDiff ($column,$originalData,$updateData){
        if($originalData != $updateData){
            return "column ".$column."'s data changed from ".$originalData." to ".$updateData." / ";
        }
        return;
    }

    public function addLog (array $data){
        $log = Log::class;

        $log::create([
            "user_id" => $data["user_id"],
            "type" => $data["type"],
            "action" => $data["action"],
            "activity" => $data["activity"],
        ]);

    }

}
