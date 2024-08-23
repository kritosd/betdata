<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WinInfo;
use App\Http\Controllers\DB;

class ApiController extends Controller
{
    public function index(Request $request)
    {
        $argument_game_id = $request->query('game_id');

        $cards = WinInfo::select('game_id', 'draw_number', 'msg_type', 'priority', 'msg_gr_text', 'msg_eng_text')
            ->where('game_id', $argument_game_id)
            ->where('draw_number', function ($query) use ($argument_game_id) {
                $query->select(\DB::raw('max(draw_number)'))
                      ->from('opap_win_info')
                      ->where('game_id', $argument_game_id);
            })
            ->orderBy('priority')
            ->orderBy('msg_type')
            ->get();

        // Convert all attributes to string
        $cards = $cards->map(function ($item) {
            return [
                'game_id' => (string) $item->game_id,
                'draw_number' => (string) $item->draw_number,
                'msg_type' => (string) $item->msg_type,
                'priority' => (string) $item->priority,
                'msg_gr_text' => (string) $item->msg_gr_text,
                'msg_eng_text' => (string) $item->msg_eng_text,
            ];
        });

        $response = [
            'win_info' => $cards
        ];
        
        return response()->json($response);
    }
}
