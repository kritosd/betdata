<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    const UPDATED_AT = 'modified';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sports_id', 'name', 'start_date', 'league_id', 'league_name', 'country_id', 'country_name', 'team_home', 'is_live', 'comments', 'modified',
    ];
}
