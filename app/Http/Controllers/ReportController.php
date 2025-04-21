<?php

namespace App\Http\Controllers;

use App\Http\Requests\HideIdeaRequest;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Http\Resources\ReportedUserResource;
use App\Http\Resources\ReportIdeaResource;
use App\Http\Resources\UserResource;
use App\Models\Idea;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $model;
    use AuthorizesRequests;


    protected function checkID($id)
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:ideas,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid Idea ID'
            ], 404);
        }

        return null;
    }

    protected function checkUserID($id)
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:users,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid Idea ID'
            ], 404);
        }

        return null;
    }

    public function __construct()
    {
        $this->model = new Report();
    }

    public function index() {}

    public function reportIdea()
    {

        $this->authorize("checkRole", Report::class);

        $idea = Idea::has("reports")->paginate(6);

        return ReportIdeaResource::collection($idea);
    }

    public function reportIdeaDetails($id)
    {

        $this->authorize("checkRole", Report::class);


        $checkID = $this->checkID($id);

        if ($checkID) {
            return $checkID;
        }

        $report = $this->model::query()->where('idea_id', $id)->get()->map(function ($data) {
            return [
                "reason" => $data->reason,
                "user_name" => $data->user->name,
                "user_photo" => $data->user->photo,
                "user_department" => $data->user->department->department_name,
            ];
        });

        return response()->json($report);
    }

    public function hideIdea(HideIdeaRequest $request, $id)
    {

        $this->authorize("isHideUser", Report::class);


        $checkID = $this->checkID($id);

        if ($checkID) {
            return $checkID;
        }

        $idea = Idea::find($id);

        $idea->hidden = $request->hide;

        $idea->update();

        if ($request->hide) {
            return response()->json([
                "message" => "successfully hide idea"
            ]);
        }
        return response()->json([
            "message" => "successfully unhide idea"
        ]);
    }

    public function getAllHideIdeas()
    {

        $this->authorize("isHideUser", Report::class);


        $idea = Idea::query()->where("hidden", true)->paginate(6);

        return response()->json(
            ReportIdeaResource::collection($idea)
        );
    }

    public function hideAllUserPosts(HideIdeaRequest $request, $id)
    {

        $this->authorize("isHideUser", Report::class);

        $checkUserId = $this->checkUserID($id);

        if ($checkUserId) {
            return $checkUserId;
        }

        $hide = $request->hide;

        $user = User::find($id);

        $user->ideas()->update(['hidden' => $hide]);
        $user->comments()->update(['hidden' => $hide]);


        $user->update(['hidden' => $hide]);

        if ($request->hide) {
            return response()->json([
                "message" => "successfully hide user idea"
            ]);
        }
        return response()->json([
            "message" => "successfully unhide user idea"
        ]);
    }

    public function getHideIdeaUser()
    {
        $this->authorize("isHideUser", Report::class);


        $user = User::query()->where("hidden", true)->paginate(10);

        return response()->json(UserResource::collection($user));
    }

    public function removePostCommentPermission($id)
    {

        $this->authorize("bannedUser", Report::class);


        $checkUserId = $this->checkUserID($id);

        if ($checkUserId) {
            return $checkUserId;
        }

        $user = User::find($id);

        $user->permissions()->detach([12, 13]);

        return response()->json([
            "message" => "successfully remove user's post idea and comment permission"
        ]);
    }

    public function givePostCommentPermission($id)
    {

        $this->authorize("bannedUser", Report::class);


        $checkUserId = $this->checkUserID($id);

        if ($checkUserId) {
            return $checkUserId;
        }

        $user = User::find($id);

        $targetPermissions = [12, 13];

        $existingPermissions = $user->permissions()->pluck('permission_id')->toArray();

        $permissionsToAttach = array_diff($targetPermissions, $existingPermissions);

        if (!empty($permissionsToAttach)) {
            $user->permissions()->attach($permissionsToAttach);
        }

        return response()->json([
            "message" => "successfully add user's post idea and comment permission"
        ]);
    }

    public function getBanUser()
    {

        $this->authorize("bannedUser", Report::class);


        $usersWithoutPermissions = User::whereDoesntHave('permissions', function ($query) {
            $query->whereIn('permission_id', [12, 13]);
        })->paginate(10);

        return response()->json($usersWithoutPermissions);
    }

    public function reportedUser()
    {

        $users = User::whereHas('ideas.reports')
            ->withCount(['ideas as reports_count' => function ($query) {
                $query->join('reports', 'ideas.id', '=', 'reports.idea_id');
            }])
            ->orderByDesc('reports_count')
            ->get();

        return response()->json(ReportedUserResource::collection($users));
    }

    public function reportedUserDetails($id)
    {

        $checkUserId = $this->checkUserID($id);

        if ($checkUserId) {

            return $checkUserId;
        }

        $idea = Idea::has("reports")->where("user_id", $id)->get();

        return ReportIdeaResource::collection($idea);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportRequest $request)
    {

        $userReported = Report::query()
            ->where("user_id", $request->user()->id)
            ->where("idea_id", $request->idea_id)
            ->exists();

        if ($userReported) {
            return response()->json(["message" => "User has already reported this idea"], 409);
        }

        $report = $this->model->create([...$request->all(), "user_id" => $request->user()->id]);

        return response()->json(["message" => "successfully reported", "data" => $report]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReportRequest $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
