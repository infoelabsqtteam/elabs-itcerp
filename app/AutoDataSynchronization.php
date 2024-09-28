<?php

/*****************************************************
 *Description : Auto Data Synchronization File for Web Module.
 *Created By  : Praveen-Singh
 *Created On  : 08-April-2019
 *Modified On : 08-April-2019
 *Package     : ITC-ERP-PKL
 ******************************************************/

namespace App;

use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;

use DB;
use File;

class AutoDataSynchronization extends Model
{
    /****************************************
     *function    : Auto Data Synchronization
     *Created By  : Praveen Singh
     *Created On  : 08-April-2019
     *Modified by : Praveen Singh
     *Modified On : 08-April-2019
     *****************************************/
    function funSynActionType($requiredData)
    {

        global $models, $order, $report, $invoice, $numbersToWord;

        /*****************************************************
         *if actionContentType = 1 then Auto Data Synchronization
         ******************************************************/
        if (!empty($requiredData['actionContentType'])) {
            if ($requiredData['actionContentType'] == '1') {
                return $this->copyOrdersWithStatusCommand($requiredData);
            }
        }
        return true;
    }

    /****************************************
     *function    : Copy Orders with Status
     *Created By  : Praveen Singh
     *Created On  : 08-April-2019
     *Modified by : Praveen Singh
     *Modified On : 08-April-2019,14-Feb-2020
     *****************************************/
    function copyOrdersWithStatusCommand($requiredData)
    {

        global $models, $order, $report, $invoice, $numbersToWord;

        $dataSave = $dataUpdate = $dataTrackSave = $dataTrackUpdate = array();

        try {

            //Starting transaction
            DB::beginTransaction();

            list($monthFirstDate, $monthLastDate) = $models->getFirstAndLastDayOfMonth(date('Y-m-d'), $format = 'Y-m-d');
            $customerCodeData = DB::connection('mysql2')->table('users')->whereNotNull('users.code')->where('users.role_id', '3')->pluck('users.code')->all();
            if (empty($customerCodeData)) return;

            //Truncating the report having days more than 10
            $validOrderBookingNos = $this->__trancateOrderStatusReportInvoiceDetail($customerCodeData, $monthFirstDate, $monthLastDate);

            if (empty($validOrderBookingNos)) return;

            $orderDataSource = DB::table('order_master')
                ->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
                ->join('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
                ->join('order_status', 'order_status.order_status_id', 'order_master.status')
                ->join('test_standard', 'test_standard.test_std_id', 'order_master.test_standard')
                ->leftJoin('stb_order_hdr_dtl_detail', function ($join) {
                    $join->on('stb_order_hdr_dtl_detail.stb_order_hdr_detail_id', '=', 'order_master.stb_order_hdr_detail_id');
                    $join->whereNotNull('order_master.stb_order_hdr_detail_id');
                })
                ->leftJoin('stb_order_hdr', 'stb_order_hdr.stb_order_hdr_id', 'stb_order_hdr_dtl_detail.stb_order_hdr_id')
                ->whereIn('customer_master.customer_code', $customerCodeData)
                ->whereIn('order_master.order_no', $validOrderBookingNos)
                ->select('order_master.*', 'stb_order_hdr.stb_prototype_no', 'test_standard.test_std_name', 'product_master_alias.c_product_name as sample_description_name', 'customer_master.customer_code', 'customer_master.customer_email', 'order_status.order_status_name')
                ->get()
                ->toArray();

            if (!empty($orderDataSource)) {
                foreach ($orderDataSource as $key => $values) {

                    $customerDetail = DB::connection('mysql2')->table('users')->where('users.code', !empty($values->customer_code) ? trim($values->customer_code) : '0')->where('users.role_id', '3')->first();
                    $test_description = !empty($values->order_id) ? implode(', ', $order->getOrderParameterWithNames($values->order_id)) : NULL;

                    if (!empty($customerDetail->id) && !empty($test_description)) {

                        //Saving/Updating Order Data in Order Table of Web Module.
                        $ordersExist = DB::connection('mysql2')->table('orders')->where('orders.ref_id', $values->order_no)->first();
                        if (!empty($ordersExist->id)) {
                            DB::connection('mysql2')->table('orders')->where('orders.id', $ordersExist->id)->update(
                                [
                                    'prototype_no'         => trim($values->stb_prototype_no),
                                    'sample_name'           => trim($values->sample_description_name),
                                    'testing_pharmacopoeia' => trim($values->test_std_name),
                                    'test_description'      => trim($test_description),
                                    'exp_delivery_date'     => trim($values->expected_due_date),
                                    'status'                => trim($values->order_status_name),
                                    'batch_number'          => trim($values->batch_no),
                                    'date_of_mfg'           => trim($values->mfg_date),
                                    'date_of_exp'           => trim($values->expiry_date),
                                    'batch_size'            => trim($values->batch_size),
                                    'sample_qty'            => trim($values->sample_qty),
                                    'mfg_lic_no'            => trim($values->mfg_lic_no),
                                    'supplied_by'           => trim($values->supplied_by),
                                    'mfd_by'                => trim($values->manufactured_by),
                                    'brand_type'            => trim($values->brand_type),
                                    'stability_note'        => trim($values->stability_note),
                                    'remarks'               => trim($values->remarks),
                                ]
                            );
                            $orderId = $ordersExist->id;
                        } else {
                            //Order Table Data
                            $dataSave                           = array();
                            $dataSave['ref_id']                 = trim($values->order_no);
                            $dataSave['prototype_no']           = trim($values->stb_prototype_no);
                            $dataSave['user_id']                = '1';
                            $dataSave['ref_date']               = trim($values->booking_date);
                            $dataSave['customer_id']            = trim($customerDetail->id);
                            $dataSave['sample_name']            = trim($values->sample_description_name);
                            $dataSave['testing_pharmacopoeia']  = trim($values->test_std_name);
                            $dataSave['test_description']       = trim($test_description);
                            $dataSave['exp_delivery_date']      = trim($values->expected_due_date);
                            $dataSave['status']                 = trim($values->order_status_name);
                            $dataSave['batch_number']           = trim($values->batch_no);
                            $dataSave['date_of_mfg']            = trim($values->mfg_date);
                            $dataSave['date_of_exp']            = trim($values->expiry_date);
                            $dataSave['batch_size']             = trim($values->batch_size);
                            $dataSave['sample_qty']             = trim($values->sample_qty);
                            $dataSave['mfg_lic_no']             = trim($values->mfg_lic_no);
                            $dataSave['supplied_by']            = trim($values->supplied_by);
                            $dataSave['mfd_by']                 = trim($values->manufactured_by);
                            $dataSave['brand_type']             = trim($values->brand_type);
                            $dataSave['stability_note']         = trim($values->stability_note);
                            $dataSave['remarks']                = trim($values->remarks);
                            $dataSave['created_at']             = trim($values->created_at);
                            $dataSave['updated_at']             = trim($values->updated_at);
                            $orderId = DB::connection('mysql2')->table('orders')->insertGetId($dataSave);
                        }

                        if (!empty($orderId)) {
                            $orderTrackRecord = $order->getOrderPerformerRecordAll($values->order_id);     //Order Status Table Data
                            if (!empty($orderTrackRecord)) {
                                foreach ($orderTrackRecord as $OTkey => $orderTrack) {
                                    //Saving/Updating Order Status                            
                                    if (!empty($orderTrack['status_id']) && !empty($orderTrack['status_name'])) {
                                        $orderStatusExist = DB::connection('mysql2')->table('order_status')->where('order_status.order_id', $orderId)->where('order_status.status', $orderTrack['status_name'])->first();
                                        if (empty($orderStatusExist)) {
                                            $dataTrackSave = array();
                                            $dataTrackSave['order_id']          = $orderId;
                                            $dataTrackSave['exp_delivery_date'] = trim($values->expected_due_date);
                                            $dataTrackSave['status']            = trim($orderTrack['status_name']);
                                            $dataTrackSave['order_status_date'] = trim($orderTrack['report_view_date_time']);
                                            DB::connection('mysql2')->table('order_status')->insertGetId($dataTrackSave);
                                        } else {
                                            DB::connection('mysql2')->table('order_status')->where('order_status.id', $orderStatusExist->id)->update(
                                                [
                                                    'exp_delivery_date' => trim($values->expected_due_date),
                                                    'status'            => trim($orderTrack['status_name']),
                                                    'order_status_date' => trim($orderTrack['report_view_date_time'])
                                                ]
                                            );
                                        }
                                    }
                                }
                            }

                            //Updating the Report Files and Invoice Files
                            if (!empty($values->status) && in_array($values->status, array('8', '9', '11'))) {
                                $this->__copyReportsInvoicesCommand($values, $orderId);
                            }

                            //Updating the dispatched detail
                            if (!empty($values->status) && in_array($values->status, array('11'))) {
                                $this->__copyDispatchedDetailCommand($values, $orderId);
                            }
                        }
                    }
                }

                //Committing the queries
                DB::commit();
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            $message = config('messages.message.OrderInternalErrorMsg');
        } catch (\Throwable $e) {
            DB::rollback();
            $message = config('messages.message.OrderInternalErrorMsg');
        }
    }

    /****************************************
     *function    : Copy Reports and Invoices
     *Created By  : Praveen Singh
     *Created On  : 08-April-2019
     *Modified by : Praveen Singh
     *Modified On : 08-April-2019,14-Feb-2020
     *****************************************/
    function __copyReportsInvoicesCommand($orderData, $webOrderId)
    {

        global $models, $order, $report, $invoice, $numbersToWord;

        //Setting Path
        $rootPath       = defined('ROOT_PATH') ? ROOT_PATH : '/var/www/html/';
        $reportSrcPath  = defined('REPORT_PATH') ? $rootPath . 'itcerp' . REPORT_PATH : $rootPath . 'itcerp/public/images/sales/reports/';
        $invoiceSrcPath = defined('INVOICE_PATH') ? $rootPath . 'itcerp' . INVOICE_PATH : $rootPath . 'itcerp/public/images/sales/invoices/';
        $reportDesPath  = $rootPath . 'itclabs/public/images/reports/';
        $invoiceDesPath = $rootPath . 'itclabs/public/images/invoices/';

        //Creating Directory with full permission
        if (!is_dir($reportDesPath)) {
            //mkdir($reportDesPath, 0777, true);
        }
        //Creating Directory with full permission
        if (!is_dir($invoiceDesPath)) {
            //mkdir($invoiceDesPath, 0777, true);
        }

        //Report Section*****************************************************************
        if (!empty($orderData->order_id) && !empty($orderData->order_no)) {
            $reportFileName = $orderData->order_no . '.pdf';
            $reportSrc = $reportSrcPath . $reportFileName;
            !file_exists($reportSrc) ? $models->downloadSaveDynamicPDF(array('order_id' => $orderData->order_id, 'downloadType' => '1', 'doc_root' => $reportSrcPath), $contentType = 'report') : '';
            //$reportDes = $reportDesPath . $orderData->order_no . '.pdf';
            if (file_exists($reportSrc)) {
                //Coping report file from src to desc folder
                //shell_exec("cp -p $reportSrc $reportDes");
                $report_file_name_content = base64_encode(file_get_contents($reportSrc));
                $reportExist = DB::connection('mysql2')->table('reports')->where('reports.ref_id', $orderData->order_no)->first();
                if (empty($reportExist)) {
                    //Reports Table Data
                    $dataReportSave                   = array();
                    $dataReportSave['order_id']       = $webOrderId;
                    $dataReportSave['ref_id']         = trim($orderData->order_no);
                    $dataReportSave['file_name']      = trim($reportFileName);
                    $dataReportSave['file_name_content'] = trim($report_file_name_content);
                    DB::connection('mysql2')->table('reports')->insertGetId($dataReportSave);
                } else {
                    //Reports Table Data
                    $dataReportUpdate                     = array();
                    $dataReportUpdate['file_name']        = trim($reportFileName);
                    $dataReportUpdate['file_name_content'] = trim($report_file_name_content);
                    DB::connection('mysql2')->table('reports')->where('reports.report_id', $reportExist->report_id)->update($dataReportUpdate);
                }
            }
        }

        //Invoice Section*****************************************************************
        $invoiceDetail = $order->gettingInvoiceDetailUsingOrderDetail($orderData->order_id);
        if (!empty($invoiceDetail->invoice_id) && !empty($invoiceDetail->invoice_no)) {
            $invoiceFileName = $invoiceDetail->invoice_no . '.pdf';
            $invoiceSrc = $invoiceSrcPath . $invoiceFileName;
            !file_exists($invoiceSrc) ? $models->downloadSaveDynamicPDF(array('invoice_id' => $invoiceDetail->invoice_id, 'downloadType' => '1', 'doc_root' => $invoiceSrcPath), $contentType = 'invoice') : '';
            //$invoiceDes = $invoiceDesPath . $invoiceDetail->invoice_no . '.pdf';
            if (file_exists($invoiceSrc)) {
                //Coping Invoice file from src to desc folder
                //shell_exec("cp -p $invoiceSrc $invoiceDes");
                $invoice_file_name_content = base64_encode(file_get_contents($invoiceSrc));
                $invoiceExist = DB::connection('mysql2')->table('order_invoice')->where('order_invoice.ref_id', $orderData->order_no)->first();
                if (empty($invoiceExist)) {
                    //Invoice Table Data
                    $dataInvoiceSave                    = array();
                    $dataInvoiceSave['order_id']        = $webOrderId;
                    $dataInvoiceSave['ref_id']          = trim($orderData->order_no);
                    $dataInvoiceSave['file_name']       = trim($invoiceFileName);
                    $dataInvoiceSave['file_name_content'] = trim($invoice_file_name_content);
                    DB::connection('mysql2')->table('order_invoice')->insertGetId($dataInvoiceSave);
                } else {
                    //Invoice Table Data
                    $dataInvoiceUpdate                      = array();
                    $dataInvoiceUpdate['file_name']         = trim($invoiceFileName);
                    $dataInvoiceUpdate['file_name_content'] = trim($invoice_file_name_content);
                    DB::connection('mysql2')->table('order_invoice')->where('order_invoice.invoice_id', $invoiceExist->invoice_id)->update($dataInvoiceUpdate);
                }
            }
        }
    }

    /****************************************
     *function    : Copy Reports and Invoices
     *Created By  : Praveen Singh
     *Created On  : 13-May-2019
     *Modified by : Praveen Singh
     *Modified On : 13-May-2019
     *****************************************/
    function __copyDispatchedDetailCommand($orderData, $webOrderId)
    {

        global $models, $order, $report, $invoice, $numbersToWord;

        $dispatchData = DB::table('order_dispatch_dtl')->where('order_dispatch_dtl.order_id', $orderData->order_id)->where('order_dispatch_dtl.amend_status', '0')->whereNotNull('order_dispatch_dtl.dispatch_date')->orderBy('order_dispatch_dtl.dispatch_id', 'DESC')->first();
        if (!empty($dispatchData->ar_bill_no)) {
            $displatchExist = DB::connection('mysql2')->table('order_dispatched_dtl')->where('order_dispatched_dtl.odd_order_id', $webOrderId)->first();
            if (empty($displatchExist)) {
                //Dispatched Table Data
                $dataDispatchSave = array();
                $dataDispatchSave['odd_order_id']      = $webOrderId;
                $dataDispatchSave['odd_ar_bill_no']    = trim($dispatchData->ar_bill_no);
                $dataDispatchSave['odd_dispatch_date'] = trim($dispatchData->dispatch_date);
                DB::connection('mysql2')->table('order_dispatched_dtl')->insertGetId($dataDispatchSave);
            } else {
                DB::connection('mysql2')->table('order_dispatched_dtl')->where('order_dispatched_dtl.odd_id', $displatchExist->odd_id)->update(['order_dispatched_dtl.odd_ar_bill_no' => $dispatchData->ar_bill_no, 'order_dispatched_dtl.odd_dispatch_date' => $dispatchData->dispatch_date]);
            }
        }
    }

    /****************************************
     *function    : Trancating the records
     *Created By  : Praveen Singh
     *Created On  : 11-April-2019
     *Modified by : Praveen Singh
     *Modified On : 11-April-2019
     *****************************************/
    function __trancateOrderStatusReportInvoiceDetail($customerCodeData, $monthFirstDate, $monthLastDate)
    {

        global $models, $order, $report, $invoice, $numbersToWord;

        $rootPath = defined('ROOT_PATH') ? ROOT_PATH : '/var/www/html/';
        $returnData = array();

        if (!empty($customerCodeData) && !empty($monthFirstDate) && !empty($monthLastDate)) {
            $allOrderOfMonthData = DB::table('order_master')
                ->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
                ->leftJoin('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
                ->whereIn('customer_master.customer_code', $customerCodeData)
                ->whereNotIn('order_master.status', array('10'))
                ->pluck(DB::raw("DATEDIFF(current_date,order_report_details.approving_date) as approving_date"), 'order_master.order_no')
                ->all();
            if (!empty($allOrderOfMonthData)) {
                foreach ($allOrderOfMonthData as $keyOrderNo => $valueReportDay) {
                    if ($valueReportDay > '10') {

                        $webOrderData = DB::connection('mysql2')->table('orders')->where('orders.ref_id', $keyOrderNo)->first();
                        if (!empty($webOrderData->id)) {

                            //Delete all Invoices
                            $orderInvoiceData = DB::connection('mysql2')->table('order_invoice')->where('order_invoice.order_id', $webOrderData->id)->first();
                            if (!empty($orderInvoiceData->file_name)) {
                                //File::delete($rootPath.'itclabs/public/images/invoices/'.$orderInvoiceData->file_name);
                                DB::connection('mysql2')->table('order_invoice')->where('order_invoice.order_id', $orderInvoiceData->order_id)->delete();
                            }

                            //Delete all reports
                            $orderReportData = DB::connection('mysql2')->table('reports')->where('reports.order_id', $webOrderData->id)->first();
                            if (!empty($orderReportData->file_name)) {
                                //File::delete($rootPath.'itclabs/public/images/reports/'.$orderReportData->file_name);
                                DB::connection('mysql2')->table('reports')->where('reports.order_id', $orderReportData->order_id)->delete();
                            }

                            //Delete all Order dispatched detail
                            DB::connection('mysql2')->table('order_dispatched_dtl')->where('order_dispatched_dtl.odd_order_id', $webOrderData->id)->delete();

                            //Delete all Order Status
                            DB::connection('mysql2')->table('order_status')->where('order_status.order_id', $webOrderData->id)->delete();

                            //Delete all Orders
                            DB::connection('mysql2')->table('orders')->where('orders.id', $webOrderData->id)->delete();
                        }
                    } else {
                        //Getting all Non-insertable Order No
                        $returnData[] = $keyOrderNo;
                    }
                }
            }
        }

        return $returnData;
    }
}
