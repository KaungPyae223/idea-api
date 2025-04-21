<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\IdeaResource;
use App\Models\Comment;
use App\Models\Department;
use App\Models\Idea;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class ReportingController extends Controller
{

    use AuthorizesRequests;

    public function activeUser()
    {

        $this->authorize("viewReport", Report::class);


        $topUsers = User::withCount(['ideas', 'comments'])
            ->get()
            ->map(function ($user) {
                $user->total_activity = $user->ideas_count + $user->comments_count;
                return $user;
            })
            ->sortByDesc('total_activity')
            ->take(10)
            ->values();

        return response()->json($topUsers);
    }

    public function departmentReport()
    {

        $this->authorize("viewReport", Report::class);

        $departments = Department::with(['user.ideas', 'user.comments'])->get();

        $totalIdeas = Idea::count();

        $departments = $departments->map(function ($department) use($totalIdeas) {
            $userTotalIdeas = 0;
            $userTotalComments = 0;

            foreach ($department->user as $user) {
                $userTotalIdeas += $user->ideas->count();
                $userTotalComments += $user->comments->count();
            }

            $contributors = $department->user->filter(function ($user) {
                return $user->ideas->count() > 0;
            })->count();

            $department->total_activity = $userTotalIdeas + $userTotalComments;
            $department->total_ideas = $userTotalIdeas;
            $department->total_comments = $userTotalComments;
            $department->contributors = $contributors;
            $department->ideas_percentage = $totalIdeas > 0 ? round(($userTotalIdeas / $totalIdeas) * 100, 2) : 0;


            return $department;
        })->sortByDesc('total_activity')->values()->map(function($data){
            return [
                "id" => $data->id,
                "department_name" => $data->department_name,
                "qa_coordinator" => $data->qaCoordinator->name,
                "total_user" => $data->user->count(),
                "total_activity" => $data->total_activity,
                "total_ideas" => $data->total_ideas,
                "total_comments" => $data->total_comments,
                "contributors" => $data->contributors,
                "ideas_percentage" => $data->ideas_percentage
            ];
        });

        return response()->json($departments);
    }

    public function anonymousIdeas(){

        $this->authorize("viewReport", Report::class);

        $anonymousIdeas = Idea::query()->where('is_anonymous',true)->paginate(6);

        return IdeaResource::collection($anonymousIdeas);
    }

    public function anonymousComments(){

        $this->authorize("viewReport", Report::class);

        $anonymousIdeas = Comment::query()->where('is_anonymous',true)->paginate(10);

        return CommentResource::collection($anonymousIdeas);
    }

}
