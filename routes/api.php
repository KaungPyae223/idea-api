<?php

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


        Route::apiResource('roles', RoleController::class);
        Route::apiResource('users', UserController::class)->except(["destroy"]);
        Route::apiResource('departments', DepartmentController::class);
        Route::apiResource('categories',CategoryController::class);
        Route::apiResource('comments',CommentController::class);
        Route::apiResource('votes',VoteController::class);
        Route::apiResource('system-setting',SystemSettingController::class);

        Route::put('update-idea-category/{id}',[IdeaController::class,"updateIdeaCategory"]);
        Route::get('idea/to-submit',[IdeaController::class,"ideasToSubmit"]);
        Route::put('idea/submit/{id}',[IdeaController::class,"submitIdea"]);
        Route::apiResource('idea',IdeaController::class);

        Route::get('logs',[logController::class,'viewLog']);
        Route::get('user-log/{id}',[logController::class,'userLog']);

    });
