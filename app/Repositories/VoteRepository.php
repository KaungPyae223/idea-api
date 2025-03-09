<?php

namespace App\Repositories;


use App\Models\Vote;
use App\Repositories\BasicFunctions\BasicFunctions;
use Illuminate\Support\Facades\DB;

class VoteRepository extends BasicFunctions
{
    protected $model;

    public function __construct()
    {
        $this->model = new Vote();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        try {

            DB::beginTransaction();

            $vote = $this->model->create($data);

            DB::commit();

            return $vote;

        } catch (\Throwable $e) {
            DB::rollBack();

            return $e;
        }

    }

    public function update($id, array $data)
    {
        try {
            DB::beginTransaction();

            $vote = $this->find($id);

            $vote->update(
               $data
            );


            DB::commit();

            return $vote;

        } catch (\Throwable $e) {
            DB::rollBack();

            return $e;
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $vote = $this->find($id);

            $vote->delete();

            DB::commit();

            return $vote;

        } catch (\Throwable $e) {
            DB::rollBack();

            return $e;
        }
    }
}
