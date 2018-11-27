<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefMonths extends Model
{
    protected $table = 'b_refmonths';
    protected $primaryKey = 'month_id';
    public $timestamps = false;
}
