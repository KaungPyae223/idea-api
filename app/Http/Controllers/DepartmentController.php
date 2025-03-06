<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Repositories\DepartmentRepository;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
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
        $department = $this->departmentRepository->create($request->all());

        // return $department;

        return response()->json(['message' => 'Department created successfully.', 'department' => new DepartmentResource($department)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:departments,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid department ID'
            ], 404);
        }

        $department = $this->departmentRepository->find($id);

        return new DepartmentResource($department);
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

        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:departments,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid department ID'
            ], 404);
        }

        $department = $this->departmentRepository->update($id, $request->all());

        return response()->json(['message' => 'Department updated successfully.', 'department' => new DepartmentResource($department)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:departments,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid department ID'
            ], 404);
        }

        $department = $this->departmentRepository->destroy($id);

        return response()->json(['message' => 'Department deleted successfully.']);
    }
}
