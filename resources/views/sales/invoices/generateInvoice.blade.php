<?php
/****************************************
* Invoice Generation Blade
* CreatedOn : 03-Dec-2017
* ModifiedOn : 30-June-2018
* Author : Praveen Singh
*******************************************/
?>

@if(!empty($invoiceTemplateType) && $invoiceTemplateType == '4')
    @include('sales.invoices.generateInvoiceWithStateWise')
@else
    @include('sales.invoices.generateInvoiceWithoutStateWise')
@endif