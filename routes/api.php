<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\logController;
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
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('comments', CommentController::class)->except(["show"]);
        Route::apiResource('votes', VoteController::class)->only(["create", "destroy"]);

        Route::get('system-setting/getCSV/{id}', [SystemSettingController::class, "exportCSV"]);
        Route::apiResource('system-setting', SystemSettingController::class);

        Route::put('update-idea-category/{id}', [IdeaController::class, "updateIdeaCategory"]);

        Route::get('idea/get-comment/{id}', [IdeaController::class, 'ideaComments']);
        Route::get('idea/to-submit', [IdeaController::class, "ideasToSubmit"]);
        Route::put('idea/submit/{id}', [IdeaController::class, "submitIdea"]);
        Route::apiResource('idea', IdeaController::class);

        Route::get('logs', [logController::class, 'viewLog']);
        Route::get('user-log/{id}', [logController::class, 'userLog']);
    });
});
