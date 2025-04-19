<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\UserResource;
use App\Models\Department;
use App\Repositories\DepartmentRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    use AuthorizesRequests;
    protected $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    protected function checkID($id){
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:departments,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid Department ID'
            ], 404);
        }

        return null;
    }



    public function index()
    {

        $departments = Department::all();

        return DepartmentResource::collection($departments);
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
    public function store(StoreDepartmentRequest $request)
    {

        $this->authorize('checkRole',Department::class);

        $department = $this->departmentRepository->create($request->all());


        return response()->json(['message' => 'Department created successfully.', 'department' => new DepartmentResource($department)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $this->authorize('checkRole',Department::class);

        $checkID = $this->checkID($id);

        if($checkID){
            return $checkID;
        }

        $department = $this->departmentRepository->find($id);

        return new DepartmentResource($department);
    }

    public function departmentUsers($id){

        $this->authorize('checkRole',Department::class);


        $checkID = $this->checkID($id);

        if($checkID){
            return $checkID;
        }

        $department = $this->departmentRepository->find($id);

        $users = $department->user;

        return UserResource::collection($users);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, $id)
    {

        $this->authorize('checkRole',Department::class);

        $checkID = $this->checkID($id);

        if($checkID){
            return $checkID;
        }

        $department = $this->departmentRepository->update($id, $request->all());

        return response()->json(['message' => 'Department updated successfully.', 'department' => new DepartmentResource($department)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $this->authorize('checkRole',Department::class);

        $checkID = $this->checkID($id);

        if($checkID){
            return $checkID;
        }

        $checkCanDelete = $this->departmentRepository->find($id);

        $noOfIdeaUsed = $checkCanDelete->user->count();


        if($noOfIdeaUsed){
           return response()->json(['message' => 'Department is used in user. Cannot delete department']);
        }

        $department = $this->departmentRepository->destroy($id);

        return response()->json(['message' => 'Department deleted successfully.']);
    }
}
