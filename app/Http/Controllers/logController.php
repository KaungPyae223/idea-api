<?php

namespace App\Http\Controllers;

use App\Http\Resources\LogResource;
use App\Models\Log;
use Illuminate\Http\Request;

class logController extends Controller
{

    public function viewLog (Request $request) {


        $searchUser = $request->input("user");
        $searchType = $request->input("type");
        $searchAction = $request->input("action");

        $logs = Log::query();

        if($searchUser){
            $logs->whereHas('user',function($q) use ($searchUser){
                return $q->where("name","Like","%".$searchUser."%")->orWhere("email","Like","%".$searchUser."%");
            });
        }

        if($searchType){
            $logs->where("type",$searchType);
        }

        if($searchAction){
            $logs->where("action",$searchAction);
        }


        $logs = $logs->paginate(20);

        return LogResource::collection($logs);
    }

    public function userLog ($id){

        $logs = Log::query()->where("user_id",$id)->paginate(20);

        return LogResource::collection($logs);

    }

}
