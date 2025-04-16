<?php

namespace App\Http\Controllers;

use App\Http\Resources\VoteResource;
use App\Http\Requests\StoreVoteRequest;
use App\Http\Requests\UpdateVoteRequest;
use App\Models\Vote;
use App\Repositories\VoteRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use AuthorizesRequests;
    protected $voteRepository;

    public function __construct(VoteRepository $voteRepository)
    {
        $this->voteRepository = $voteRepository;
    }

    public function index()
    {

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
    public function store(StoreVoteRequest $request)
    {

        $already_vote  = Vote::query()->where("idea_id",$request->idea_id)->where("user_id",$request->user()->id)->first();


        if($already_vote){
            $vote = $this->voteRepository->update($already_vote->id, [...$request->all(),"user_id" => $request->user()->id]);
        }else{
            $vote = $this->voteRepository->create([...$request->all(),"user_id" => 1]);
        }

        return response()->json(['message' => 'Successfully Voted', 'vote' => new VoteResource($vote)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vote $vote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVoteRequest $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:votes,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid Vote ID'
            ], 404);
        }

        $vote = $this->voteRepository->find($id);

        $this->authorize("delete",$vote);

        $this->voteRepository->destroy($id);
        return response()->json(['message' => 'Vote deleted successfully.']);
    }
}
