<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use App\User;

class Exports implements FromCollection
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */

    // public function __construct(int $location_id, int $app_year, int $app_month, int $charge_type)
    // {
    //     $this->location_id = $location_id;
    //     $this->app_year = $app_year;
    //     $this->app_month = $app_month;
    //     $this->charge_type = $charge_type;
    // }

    public function collection()
    {
        // return collect(DB::select('CALL rental_and_charges("'.$this->location_id.'","'.$this->app_year.'","'.$this->app_month.'","'.$this->charge_type.'")'));
        return User::all();
    }
}
