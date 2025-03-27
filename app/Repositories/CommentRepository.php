<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Repositories\BasicFunctions\BasicFunctions;
use Illuminate\Support\Facades\DB;

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
        try {
            $comment = $this->model->find($id);
            if ($comment) {
                $comment->delete();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
