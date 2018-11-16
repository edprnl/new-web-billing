<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefCategory extends Model
{
    protected $table = 'b_refcategory';
    protected $primaryKey = 'category_id';
    public $timestamps = false;
    protected  $fillable = [
        'category_code',
        'category_desc'
    ];
}
