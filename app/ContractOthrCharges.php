<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractOthrCharges extends Model
{
    protected $table = 'b_contract_othr_charges';
    protected $primaryKey = 'contract_misc_id';
    public $timestamps = false;
}
