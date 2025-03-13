<?php

namespace App\Http\Controllers;

use App\Http\Resources\LogResource;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:users,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid user ID'
            ], 404);
        }

        $logs = Log::query()->where("user_id",$id)->paginate(20);

        return LogResource::collection($logs);

    }

}
