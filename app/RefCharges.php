<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefCharges extends Model
{
    protected $table = 'b_refcharges';
    protected $primaryKey = 'charge_id';
    public $timestamps = false;
}
