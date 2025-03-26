<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    protected function checkPermissions($permission){


        return Auth::user()->permissions->contains('permission', $permission);
    }
}
