<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIdeaRequest;
use App\Http\Requests\SubmitIdeaRequest;
use App\Http\Requests\UpdateIdeaCategoryRequest;
use App\Http\Requests\UpdateIdeaRequest;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\IdeaResource;
use App\Mail\ApproveMail;
use App\Mail\PostIdeaMail;
use App\Models\Idea;
use App\Models\SystemSetting;
use App\Repositories\IdeaRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $ideaRepository;

    public function __construct(IdeaRepository $ideaRepository)
    {
        $this->ideaRepository = $ideaRepository;
    }

    protected function checkCategory($categories)
    {

        $categoryArr = explode(',', $categories);

        foreach ($categoryArr as $id) {
            $validated = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:categories,id',
            ]);

            if ($validated->fails()) {
                return response()->json([
                    'message' => "Category ID $id is Invalid Category ID"
                ], 404);
            }
        }

        return null;
    }

    protected function checkID($id)
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:ideas,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid idea ID'
            ], 404);
        }

        return null;
    }

    public function index(Request $request)
    {

        $departmentQuery = $request->input("department");
        $popularQuery = $request->input("popular");
        $categoryQuery = $request->input("category");
        $popularQuery = $request->input("popular");
        $latestQuery = $request->input("latest");
        $titleQuery = $request->input("title");


        $ideas = Idea::query();

        if ($titleQuery) {
            $ideas->where('title', "like", "%" . $titleQuery . "%");
        }

        if ($departmentQuery) {

            $ideas->where("is_anonymous", false);

            $ideas->whereHas("user", function ($q) use ($departmentQuery) {
                $q->where('department_id', $departmentQuery);
            });
        }

        if ($popularQuery) {
            $ideas->withSum('votes', 'vote_value')->orderBy('votes_sum_vote_value', $popularQuery);
        }

        if ($categoryQuery) {

            $ideas->whereHas('categories', function ($q) use ($categoryQuery) {
                return $q->where('name', $categoryQuery);
            });
        }

        $ideas->where("is_enabled", true);

        if ($latestQuery) {
            $ideas->orderBy("id", "desc");
        }

        $ideas = $ideas->paginate(5);

        return IdeaResource::collection($ideas);
    }



    public function ideasToSubmit()
    {

        $ideaToSubmit = Idea::query();

        $ideaToSubmit->where('is_enabled', false);

        $ideas = $ideaToSubmit->paginate(5);

        return IdeaResource::collection($ideas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIdeaRequest $request)
    {

        $activeSystemSetting = SystemSetting::query()->where("status", true)->first();
        $currentDate = now();

        if (!$activeSystemSetting) {
            return response()->json([
                'message' => 'You cannot create idea without active system setting'
            ], 409);
        }

        $ideaClosureDate = Carbon::parse($activeSystemSetting->idea_closure_date);

        if ($ideaClosureDate->lessThan($currentDate)) {
            return response()->json([
                'message' => 'You cannot create idea after the idea closure date'
            ], 409);
        }

        $checkCategory = $this->checkCategory($request->category);

        if ($checkCategory) {
            return $checkCategory;
        }

        $idea = $this->ideaRepository->create([...$request->all(), 'is_enabled' => false, 'user_id' => 1, 'system_setting_id' => $activeSystemSetting->id]);

        // Mail::to("kaungpyaeaung8123@gmail.com")->send(new PostIdeaMail($idea));

        return response()->json(['message' => 'Idea created successfully.', 'idea' => new IdeaResource($idea)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $checkID = $this->checkID($id);

        if ($checkID) {
            return $checkID;
        }

        $department = $this->ideaRepository->find($id);


        return new DepartmentResource($department);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Idea $idea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIdeaRequest $request, $id)
    {

        $checkID = $this->checkID($id);

        if ($checkID) {
            return $checkID;
        }

        // check idea is over is over final closure date or not.

        $checkIdeaClosureDate = $this->ideaRepository->find($id);
        $ideaClosureDate = Carbon::parse($checkIdeaClosureDate);
        $currentDate = now();

        if ($ideaClosureDate->lessThan($currentDate)) {
            return response()->json([
                'message' => 'You cannot create idea after the idea closure date'
            ], 409);
        }

        $checkCategory = $this->checkCategory($request->category);

        if ($checkCategory) {
            return $checkCategory;
        }

        $idea = $this->ideaRepository->update($id, [...$request->all(), 'is_enabled' => false]);

        return response()->json(['message' => 'Idea updated successfully.', 'idea' => new IdeaResource($idea)]);
    }

    public function updateIdeaCategory(UpdateIdeaCategoryRequest $request, $id)
    {

        // check the valid id or not
        $checkID = $this->checkID($id);

        if ($checkID) {
            return $checkID;
        }


        //check after closure date or not
        $checkIdeaFinalClosureDate = $this->ideaRepository->find($id);


        if ($checkIdeaFinalClosureDate->status) {
            return response()->json([
                'message' => 'Cannot update idea after the idea closure date'
            ], 409);
        }

        $checkCategory = $this->checkCategory($request->category);

        if ($checkCategory) {
            return $checkCategory;
        }

        $idea = $this->ideaRepository->updateIdeaCategory($id, $request->all());

        return response()->json(['message' => "Idea's Category updated successfully.", 'idea' => new IdeaResource($idea)]);
    }

    public function submitIdea(SubmitIdeaRequest $request, $id)
    {

        $checkID = $this->checkID($id);

        if ($checkID) {
            return $checkID;
        }

        $idea = $this->ideaRepository->submitIdea($id, $request->all());

        // if($idea->is_enabled){

        //     Mail::to("kaungpyaeaung8123@gmail.com")->send(new ApproveMail($idea));

        // }

        return response()->json(['message' => "Idea's submitted successfully.", 'idea' => new IdeaResource($idea)]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $checkID = $this->checkID($id);

        if ($checkID) {
            return $checkID;
        }

        $idea = $this->ideaRepository->destroy($id);

        return response()->json(['message' => 'Idea deleted successfully.']);
    }
}
