<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     protected $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function index()
    {
        $comments = Comment::all();
        return CommentResource::collection($comments);
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
    public function store(StoreCommentRequest $request)
    {
        $comment = $this->commentRepository->create([...$request->all(),"user_id" => 1]);
        return response()->json(['message' => 'Comment created successfully.', 'comment' => new CommentResource($comment)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:comments,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid comment ID'
            ], 404);
        }

        $comment = $this->commentRepository->find($id);
        return new CommentResource($comment);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, $id)
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:comments,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid comment ID'
            ], 404);
        }

        $comment = $this->commentRepository->update($id, [...$request->all(),"user_id" => 1]);
        return response()->json(['message' => 'Comment updated successfully.', 'comments' => $comment]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:comments,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid comment ID'
            ], 404);
        }

        $comment = $this->commentRepository->destroy($id);
        return response()->json(['message' => 'Comment deleted successfully.']);
    }
}
