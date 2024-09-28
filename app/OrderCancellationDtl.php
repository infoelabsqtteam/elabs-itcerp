<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class OrderCancellationDtl extends Model
{
    protected $table = 'order_cancellation_dtls';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
}
