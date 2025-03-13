<?php

namespace App\Http\Controllers;

use App\Http\Resources\SystemSettingResouce;
use App\Http\Requests\StoreSystemSettingRequest;
use App\Http\Requests\UpdateSystemSettingRequest;
use App\Models\SystemSetting;
use App\Repositories\SystemSettingRepository;
use Illuminate\Support\Facades\Validator;

class SystemSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     protected $systemSettingRepository;

    public function __construct(SystemSettingRepository $systemSettingRepository)
    {
        $this->systemSettingRepository = $systemSettingRepository;
    }

    protected function checkID($id){
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:system_settings,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid system setting ID'
            ], 404);
        }

        return null;
    }

    public function index()
    {
        $systemSettings = SystemSetting::all();
        return SystemSettingResouce::collection($systemSettings);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSystemSettingRequest $request)
    {

        $activeSystemSetting = SystemSetting::query()->where("status",true)->exists();

        if($activeSystemSetting){
            return response()->json([
                'message' => 'Already have an active system setting'
            ], 409);
        }

        $systemSetting = $this->systemSettingRepository->create([...$request->all(),"status"=>true]);

        return $systemSetting;

        return response()->json(['message' => 'System setting created successfully.', 'system_setting' => new SystemSettingResouce($systemSetting)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $checkID = $this->checkID($id);

        if($checkID){
            return $checkID;
        }

        $systemSetting = $this->systemSettingRepository->find($id);
        return response()->json(['system_settings' => $systemSetting]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SystemSetting $systemSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSystemSettingRequest $request, $id)
    {
        $checkID = $this->checkID($id);

        if($checkID){
            return $checkID;
        }

        $updateSystemSetting = $this->systemSettingRepository->find($id);

        if($updateSystemSetting->status !== 1){
            return response()->json([
                'message' => 'You can only edit active system setting'
            ], 409);
        }

        $systemSetting = $this->systemSettingRepository->update($id, $request->all());

        return response()->json(['message' => 'System setting updated successfully.', 'system_setting' => $systemSetting]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $checkID = $this->checkID($id);

        if($checkID){
            return $checkID;
        }

        $checkCanDelete = $this->systemSettingRepository->find($id);

        $noOfIdeaUsed = $checkCanDelete->ideas->count();


        if($noOfIdeaUsed){
           return response()->json(['message' => 'System setting is used in Idea. Cannot delete system setting']);
        }

        $systemSetting = $this->systemSettingRepository->destroy($id);
        return response()->json(['message' => 'System setting deleted successfully.']);
    }
}
