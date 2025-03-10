<?php

namespace App\Http\Controllers;

use App\Http\Resources\VoteResource;
use App\Http\Requests\StoreVoteRequest;
use App\Http\Requests\UpdateVoteRequest;
use App\Models\Vote;
use App\Repositories\VoteRepository;
use Illuminate\Support\Facades\Validator;

class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $voteRepository;

    public function __construct(VoteRepository $voteRepository)
    {
        $this->voteRepository = $voteRepository;
    }

    public function index()
    {
        $votes = Vote::all();
        return VoteResource::collection($votes);
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
        $vote = $this->voteRepository->create([...$request->all(),"user_id" => 1]);
        return response()->json(['message' => 'Vote created successfully.', 'vote' => new VoteResource($vote)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:votes,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid vote ID'
            ], 404);
        }

        $vote = $this->voteRepository->find($id);
        return new VoteResource($vote);
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
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:votes,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid vote ID'
            ], 404);
        }

        $vote = $this->voteRepository->update($id, [...$request->all(),"user_id" => 1]);
        return response()->json(['message' => 'Vote updated successfully.', 'vote' => $vote]);
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
                'message' => 'Invalid vote ID'
            ], 404);
        }

        $vote = $this->voteRepository->destroy($id);
        return response()->json(['message' => 'Vote deleted successfully.']);
    }
}
