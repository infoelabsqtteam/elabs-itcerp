<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceHdrDetail extends Model
{
    protected $table = 'invoice_hdr_detail';
    protected $primaryKey = 'invoice_dtl_id';
}
