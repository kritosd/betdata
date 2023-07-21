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
    public function json($sportId)
    {
        $groups = Group::where('xml_type', '=', 'General', 'and')
            ->where('sport', '=', $sportId)
            ->get()
            ->toJson();
        return response($groups)->withHeaders([
                'Content-Type' => 'application/json',
                'charset' => 'UTF-8'
            ]);
    }

    public function update($sportId, Request $request)
    {
        $group = Group::find($sportId);
        $group->events_list = $request->events_list;
        $group->save();

        $this->json($sportId);
    }

}