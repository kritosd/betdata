<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WinInfo extends Model
{
    protected $connection = 'second_db';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'opap_win_info';
}
