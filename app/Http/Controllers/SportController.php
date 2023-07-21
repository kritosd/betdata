<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sport;

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
        $sport = Sport::orderBy('name_gr')
            ->orderBy('name_en')
            ->get()
            ->toJson();
        return response($sport)->withHeaders([
                'Content-Type' => 'application/json',
                'charset' => 'UTF-8'
            ]);
    }

}