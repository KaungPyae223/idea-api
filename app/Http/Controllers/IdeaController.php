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
use App\Repositories\IdeaRepository;
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

    public function index(Request $request)
    {

        $departmentQuery = $request->input("department");
        $popularQuery = $request->input("popular");
        $categoryQuery = $request->input("category");
        $popularQuery = $request->input("popular");
        $latestQuery = $request->input("latest");
        $titleQuery = $request->input("title");


        $ideas = Idea::query();

        if($titleQuery){
            $ideas->where('title',"like","%".$titleQuery."%");
        }

        if ($departmentQuery) {

            $ideas->where("is_anonymous",false);

            $ideas->whereHas("user", function ($q) use ($departmentQuery) {
                $q->where('department_id',$departmentQuery);
            });

        }

        if ($popularQuery) {
            $ideas->withSum('votes', 'vote_value')->orderBy('votes_sum_vote_value', $popularQuery);
        }

        if ($categoryQuery) {

            $ideas->whereHas('categories',function($q) use($categoryQuery){
                return $q->where('name',$categoryQuery);
            });
        }

        $ideas->where("is_enabled",true);

        if($latestQuery){
            $ideas->orderBy("id","desc");
        }

        $ideas = $ideas->paginate(5);

        return IdeaResource::collection($ideas);
    }



    public function ideasToSubmit()
    {

        $ideaToSubmit = Idea::query();

        $ideaToSubmit->where('is_enabled',false);

        $ideas = $ideaToSubmit->paginate(5);

        return IdeaResource::collection($ideas);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIdeaRequest $request)
    {
        $idea = $this->ideaRepository->create([...$request->all(),'is_enabled'=>false,'user_id' => 1]);

        // Mail::to("kaungpyaeaung8123@gmail.com")->send(new PostIdeaMail($idea));

        return response()->json(['message' => 'Idea created successfully.', 'idea' => new IdeaResource($idea)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:ideas,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid idea ID'
            ], 404);
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

        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:ideas,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid idea ID'
            ], 404);
        }

        $idea = $this->ideaRepository->update($id, [...$request->all(),'is_enabled'=>false]);

        return response()->json(['message' => 'Idea updated successfully.', 'idea' => new IdeaResource($idea)]);

    }

    public function updateIdeaCategory(UpdateIdeaCategoryRequest $request, $id){

        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:ideas,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid idea ID'
            ], 404);
        }

        $idea = $this->ideaRepository->updateIdeaCategory($id,$request->all());

        return response()->json(['message' => "Idea's Category updated successfully.", 'idea' => new IdeaResource($idea)]);

    }

    public function submitIdea(SubmitIdeaRequest $request, $id){

        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:ideas,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid idea ID'
            ], 404);
        }

        $idea = $this->ideaRepository->submitIdea($id,$request->all());

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
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:ideas,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid idea ID'
            ], 404);
        }

        $idea = $this->ideaRepository->destroy($id);

        return response()->json(['message' => 'Idea deleted successfully.']);

    }
}
