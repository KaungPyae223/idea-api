<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Repositories\BasicFunctions\BasicFunctions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommentRepository extends BasicFunctions
{
    protected $model;

    public function __construct()
    {
        $this->model = new Comment();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        try {

            DB::beginTransaction();

            $comment = $this->model->create(
                $data
            );

            DB::commit();

            return $comment;

        } catch (\Throwable $e) {
            DB::rollBack();

            return $e;
        }

    }

    public function update($id, array $data)
    {
        try {
            DB::beginTransaction();

            $comment = $this->find($id);

            $comment->update(
               $data
            );


            DB::commit();

            return $comment;

        } catch (\Throwable $e) {
            DB::rollBack();

            return $e;
        }
    }

    public function destroy($id)
    {

    }
}
