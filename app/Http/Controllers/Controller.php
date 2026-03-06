<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Gate;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public $activeTheme;

    function __construct() {
        $this->activeTheme = activeTheme();
    }

    protected function authorizeForAdmin($ability, $arguments = [])
    {
        return Gate::forUser(auth('admin')->user())->authorize($ability, $arguments);
    }
}
