<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractMiscCharges extends Model
{
    protected $table = 'b_contract_misc_charges';
    protected $primaryKey = 'contract_misc_id';
    public $timestamps = false;
}
