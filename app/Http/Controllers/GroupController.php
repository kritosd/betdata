<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;

class GroupController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
    * Show the application dashboard.
    *
    * @return \Illuminate\Contracts\Support\Renderable
    */
    public function json()
    {
        $groups = Group::where('xml_type', '=', 'General', 'and')
            ->where('sport', '=', 17)
            ->get()
            ->toJson();
        return response($groups)->withHeaders([
                'Content-Type' => 'application/json',
                'charset' => 'UTF-8'
            ]);
    }

}