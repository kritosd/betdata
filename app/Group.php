<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xml_lists';

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
        'name', 'xml_type', 'description', 'sport', 'league_id', 'visible_events', 'next_days', 'modified',
    ];

    /**
     * The events that belong to the group.
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'selections_table', 'xml_id', 'event_id')->orderBy('start_date')->orderBy('league_id')->orderBy('name');
    }
}
