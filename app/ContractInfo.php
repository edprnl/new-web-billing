<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractInfo extends Model
{
    protected $table = 'b_contract_info';
    protected $primaryKey = 'contract_id';
    public $timestamps = false;
}
