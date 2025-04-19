<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\logController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix("v1")->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');


    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'LogIn');
    });



    Route::middleware('auth:sanctum')->group(function () {

        Route::get('roles', [RoleController::class, "index"]);

        Route::get('user/getIdeasByAdmin/{id}', [UserController::class, "userIdeasByAdmin"]);
        Route::get('users/getIdeas/{id}', [UserController::class, "userIdeas"]);
        Route::post('users/reset-password/{id}', [UserController::class, "restartPassword"]);
        Route::apiResource('users', UserController::class)->except(["destroy"]);
        Route::get('departments/users/{id}', [DepartmentController::class, "departmentUsers"]);
        Route::apiResource('departments', DepartmentController::class);

        Route::put('change-password',[AuthController::class,'changePassword']);

        Route::apiResource('categories',CategoryController::class);
        Route::apiResource('comments',CommentController::class)->except(["show"]);

        Route::apiResource('votes',VoteController::class)->only(["store","destroy"]);

        Route::get('system-setting/getCSV/{id}', [SystemSettingController::class, "exportCSV"]);
        Route::apiResource('system-setting', SystemSettingController::class);

        Route::put('update-idea-category/{id}', [IdeaController::class, "updateIdeaCategory"]);

        Route::get('idea/get-comment/{id}', [IdeaController::class, 'ideaComments']);
        Route::get('idea/to-submit', [IdeaController::class, "ideasToSubmit"]);
        Route::put('idea/submit/{id}', [IdeaController::class, "submitIdea"]);
        Route::apiResource('idea', IdeaController::class);

        Route::get('logs', [logController::class, 'viewLog']);
        Route::get('user-log/{id}', [logController::class, 'userLog']);

        Route::get('/log-in-activities/{id}',[UserController::class,'getLogInData']);

        Route::put('user/hide/{id}',[ReportController::class,"hideAllUserPosts"]);
        Route::put('hide/{id}',[ReportController::class,"hideIdea"]);
        Route::get('get-hide-ideas',[ReportController::class,"getAllHideIdeas"]);
        Route::get('get-hide-ideas-user',[ReportController::class,"getHideIdeaUser"]);

        Route::put('remove-idea-permissions/{id}',[ReportController::class,'removePostCommentPermission']);
        Route::put('give-idea-permissions/{id}',[ReportController::class,'givePostCommentPermission']);
        Route::get('banUser',[ReportController::class,'getBanUser']);

        Route::get('/report/user/{id}',[ReportController::class,'reportedUserDetails']);
        Route::get('/report/user',[ReportController::class,'reportedUser']);
        Route::get('/report/ideas/{id}',[ReportController::class,'reportIdeaDetails']);
        Route::get('/report/ideas',[ReportController::class,'reportIdea']);
        Route::resource('/report',ReportController::class)->only(["store"]);

    });
});
