<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;

class EventController extends Controller
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
        $events = Event::where('sports_id', '=', $sportId)
            ->orderBy('start_date')
            ->orderBy('league_id')
            ->orderBy('name')
            ->get()
            ->toJson();
        return response($events)->withHeaders([
                'Content-Type' => 'application/json',
                'charset' => 'UTF-8'
            ]);
    }

    public function datatables()
    {
        return view('events/datatable');
    }
}