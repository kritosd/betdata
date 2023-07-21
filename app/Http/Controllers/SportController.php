<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sport;
use App\Event;

class SportController extends Controller
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
        $sports_from_events = Event::distinct()->get(['sports_id']);
        
        $sport = Sport::orderBy('name_gr')
            ->whereIn('id', $sports_from_events)
            ->orderBy('name_en')
            ->get()
            ->toJson();
        return response($sport)->withHeaders([
                'Content-Type' => 'application/json',
                'charset' => 'UTF-8'
            ]);
    }

}