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
            ->with('events')
            ->get()
            ->toJson();
            
        return response($groups)->withHeaders([
                'Content-Type' => 'application/json',
                'charset' => 'UTF-8'
            ]);
    }

    public function add($groupId, Request $request)
    {
        $sportId = $request->sportId;
        $eventId = $request->eventId;
        $group = Group::find($groupId);
        $group->events()->attach($eventId);
        
        return $this->json($sportId);
    }

    public function delete($groupId, Request $request)
    {
        $sportId = $request->sportId;
        $eventId = $request->eventId;
        $group = Group::find($groupId);
        $group->events()->detach($eventId);
        
        return $this->json($sportId);
    }

}