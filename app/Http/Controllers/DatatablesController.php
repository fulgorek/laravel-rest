<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Name;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Yajra\Datatables\Datatables;

class DatatablesController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => []]);
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function filter()
    {
        return Datatables::of(Name::query())->make(true);
    }
}
