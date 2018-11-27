<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefBillingPeriod extends Model
{
    protected $table = 'b_refbillingperiod';
    protected $primaryKey = 'period_id';
    public $timestamps = false;
}
