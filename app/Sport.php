<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sports_list';

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
        'name_en', 'name_gr', 'regexp_str', 'enabled_to_parsed', 'db_table', 'modified',
    ];
}
