<?php

/*****************************************************
 *Order Model File
 *Created By:Praveen-Singh
 *Created On : 15-Dec-2017
 *Modified On : 29-Feb-2020
 *Package : ITC-ERP-PKL
 ******************************************************/

namespace App;

use Illuminate\Support\Facades\Schema;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;
use DB;

class Order extends Model
{
    protected $table = 'order_master';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_date', 'reference_no', 'sample_description', 'batch_no', 'mfg_date', 'expiry_date', 'batch_size', 'sample_qty', 'supplied_by', 'manufactured_by', 'barcode', 'pi_reference', 'customer_id', 'sale_executive', 'discount_type_id', 'discount_value', 'sample_priority_id', 'remarks', 'product_id', 'product_as_per_customer', 'test_standard', 'created_by'
    ];

    /**
     * get Order Column Detail
     * Date : 17-June-2019
     * Author :Praveen Singh
     */
    public function getColumnList($type = true)
    {
        if ($type) {
            return Schema::getColumnListing($this->table);
        } else {
            $returnColoumList = array();
            $forignIntegrityColumns = array('sample_description');
            $requiredDefaultColumns = array('mfg_lic_no', 'sample_description', 'po_no', 'po_date', 'manufactured_by', 'supplied_by', 'mfg_date', 'expiry_date', 'batch_no', 'batch_size', 'sample_qty', 'pi_reference', 'reference_no', 'letter_no', 'barcode', 'remarks', 'brand_type', 'packing_mode', 'quotation_no', 'advance_details', 'actual_submission_type', 'header_note', 'stability_note');
            $coloumnArrayData = Schema::getColumnListing($this->table);

            //Adding forign Integrity Columns
            if (!empty($forignIntegrityColumns)) {
                foreach ($forignIntegrityColumns as $key => $value) {
                    array_unshift($coloumnArrayData, $value);
                }
            }

            //Creating id/name based Array
            if (!empty($coloumnArrayData)) {
                $counter = '0';
                $coloumnArrayOutData = array_values(array_unique($coloumnArrayData));
                foreach ($coloumnArrayOutData as $key => $value) {
                    if (in_array($value, $requiredDefaultColumns)) {
                        $returnColoumList[$counter]['id'] = $value;
                        $returnColoumList[$counter]['name'] = str_replace('ID', 'NAME', str_replace('_', ' ', strtoupper($value)));
                        $counter++;
                    }
                }
            }
            return $returnColoumList;
        }
    }

    /**
     * get Order Details
     * Date : 17-Oct-2017
     * Author :Praveen Singh
     */
    public function getOrderDetail($order_id)
    {
        return DB::table('order_master')->where('order_id', '=', $order_id)->first();
    }

    /**
     * get Order Details
     * Date : 17-Oct-2017
     * Author :Praveen Singh
     */
    public function getOrderParameterDetail($order_id)
    {
        return DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $order_id)->get()->toArray();
    }


    /**
     * get Order Colum Value
     * Date : 17-Oct-2017
     * Author :Praveen Singh
     */
    public function getOrderColumValue($order_id, $coloumName)
    {
        return DB::table('order_master')->where('order_id', '=', $order_id)->pluck($coloumName)->first();
    }

    /****************************************************
     *Generating Order Number
     *Format : DepartmentName-YYMMDDSERIALNo
     *****************************************************/
    function generateOrderNumber($submittedFormData)
    {
        global $models;

        if (!empty($submittedFormData['product_category_id'])) {

            $currentDay              = date('d');
            $currentMonth            = date('m');
            $currentYear             = date('y');
            $orderDate               = $submittedFormData['order_date'];
            $orderDay                = date('d', strtotime($orderDate));
            $orderMonth              = date('m', strtotime($orderDate));
            $orderYear               = date('Y', strtotime($orderDate));
            $orderDYear              = date('y', strtotime($orderDate));
            $divisionId              = $submittedFormData['division_id'];
            $backDateDepartmentArr   = $models->getBackDateBookingDepartments(); //array('2', '6','308');

            //Getting Section Name
            $divisionData    = DB::table('divisions')->where('divisions.division_id', $divisionId)->first();
            $divisionCode    = !empty($divisionData->division_code) ? trim($divisionData->division_code) : '00';
            $productTestData = DB::table('product_categories')->where('product_categories.p_category_id', $submittedFormData['product_category_id'])->first();
            $sectionName     = !empty($productTestData->p_category_name) ? substr($productTestData->p_category_name, 0, 1) : 'F';

            //In case of Pharma Deparment,order number will be generated according to current month and current day
            if ($models->hasBackDateBookingDepartments($submittedFormData['product_category_id'])) {
                $maxOrderData = DB::table('order_master')->select('order_master.order_id', 'order_master.order_no')->where('order_master.product_category_id', $submittedFormData['product_category_id'])->whereDay('order_master.order_date', $orderDay)->whereMonth('order_master.order_date', $orderMonth)->whereYear('order_master.order_date', $orderYear)->where('order_master.division_id', $divisionId)->orderBy('order_master.order_id', 'DESC')->limit(1)->first();
            } else {
                $maxOrderData = DB::table('order_master')->select('order_master.order_id', 'order_master.order_no')->where('order_master.product_category_id', $submittedFormData['product_category_id'])->whereMonth('order_master.order_date', $orderMonth)->whereYear('order_master.order_date', $orderYear)->where('order_master.division_id', $divisionId)->orderBy('order_master.order_id', 'DESC')->limit(1)->first();
            }

            //getting Max Serial Number					
            $maxSerialNo  = !empty($maxOrderData->order_no) ? substr($maxOrderData->order_no, 10) + 1 : '0001';
            $maxSerialNo  = $maxSerialNo != '9999' ? str_pad($maxSerialNo, 4, '0', STR_PAD_LEFT) : '0001';

            //Combing all to get unique order number
            $orderNumber = $sectionName . $divisionCode . '-' . $orderDYear . $orderMonth . $orderDay . $maxSerialNo;

            //Checking Order No exist in a DB or Not.If Yes,then regenerating the Order Number
            $ifOrderNoExist = DB::table('order_master')->select('order_master.order_id', 'order_master.order_no')->where('order_master.order_no', $orderNumber)->first();
            if (!empty($ifOrderNoExist->order_no)) {
                //getting Max Serial Number					
                $maxSerialNo  = !empty($ifOrderNoExist->order_no) ? substr($ifOrderNoExist->order_no, 10) + 1 : '0001';
                $maxSerialNo  = $maxSerialNo != '9999' ? str_pad($maxSerialNo, 4, '0', STR_PAD_LEFT) : '0001';

                //Combing all to get unique order number
                $orderNumber = $sectionName . $divisionCode . '-' . $orderDYear . $orderMonth . $orderDay . $maxSerialNo;
            }

            return $orderNumber;
        }
        return false;
    }

    /*************************
     *Validate order date
     *Date that needs to be tested goes here
     *************************/
    function isValidDate($date)
    {
        list($dd, $mm, $yyyy) = explode('-', $date);
        if (checkdate($mm, $dd, $yyyy)) {
            return true;
        } else {
            return false;
        }
    }

    /*************************
     *function to get formated date
     *Check date format
     *************************/
    function getFormatedDate($date, $format = 'Y-m-d')
    {
        return date($format, strtotime($date));
    }

    /*************************
     *function to get formated date and time
     *Check date and time format
     *************************/
    function getFormatedDateTime($date, $format = 'Y-m-d')
    {
        return date($format, strtotime($date)) . ' ' . date("H:i:s");
    }

    /*************************
     *function to get order
     *related  all informations
     *************************/
    function getOrder($order_id)
    {

        $orderList = DB::table('order_master')
            ->join('divisions', 'divisions.division_id', 'order_master.division_id')
            ->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
            ->join('customer_contact_persons', 'customer_contact_persons.customer_id', 'customer_master.customer_id')
            ->join('customer_invoicing_types', 'customer_invoicing_types.invoicing_type_id', '=', 'order_master.invoicing_type_id')
            ->join('customer_types', 'customer_types.type_id', 'customer_master.customer_type')
            ->join('customer_discount_types', 'customer_discount_types.discount_type_id', 'order_master.discount_type_id')
            ->join('users as sales', 'sales.id', 'order_master.sale_executive')
            ->join('users as createdBy', 'createdBy.id', 'order_master.created_by')
            ->join('product_master', 'product_master.product_id', 'order_master.product_id')
            ->join('product_categories', 'product_categories.p_category_id', 'product_master.p_category_id')
            ->join('product_test_hdr', 'product_test_hdr.test_id', 'order_master.product_test_id')
            ->join('test_standard', 'test_standard.test_std_id', 'order_master.test_standard')
            ->leftJoin('test_standard as defined_test_standard_db', 'defined_test_standard_db.test_std_id', 'order_master.defined_test_standard')
            ->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
            ->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
            ->join('countries_db', 'countries_db.country_id', 'customer_master.customer_country')
            ->join('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
            ->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
            ->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
            ->join('order_sample_priority', 'order_sample_priority.sample_priority_id', 'order_master.sample_priority_id')
            ->join('samples', 'samples.sample_id', 'order_master.sample_id')
            ->leftJoin('sample_modes', 'sample_modes.sample_mode_id', 'samples.sample_mode_id')
            ->leftJoin('order_report_microbiological_dtl', 'order_report_microbiological_dtl.report_id', 'order_master.order_id')
            ->leftJoin('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
            ->leftJoin('order_report_options as ref_sample_value_data', 'order_report_details.ref_sample_value', 'ref_sample_value_data.report_option_id')
            ->leftJoin('order_report_options as result_drived_value_data', 'order_report_details.result_drived_value', 'result_drived_value_data.report_option_id')
            ->leftJoin('order_report_options as deviation_value_data', 'order_report_details.deviation_value', 'deviation_value_data.report_option_id')
            ->leftJoin('customer_master as reporting_master', 'reporting_master.customer_id', 'order_master.reporting_to')
            ->leftJoin('city_db as reportngToCity', 'reportngToCity.city_id', 'reporting_master.customer_city')
            ->leftJoin('state_db as reportngToState', 'reportngToState.state_id', 'reporting_master.customer_state')
            ->leftJoin('customer_master as invoicing_master', 'invoicing_master.customer_id', 'order_master.invoicing_to')
            ->leftJoin('city_db as invoicingToCity', 'invoicingToCity.city_id', 'invoicing_master.customer_city')
            ->leftJoin('state_db as invoicingToState', 'invoicingToState.state_id', 'invoicing_master.customer_state')
            ->leftJoin('order_dispatch_dtl', function ($join) {
                $join->on('order_dispatch_dtl.order_id', '=', 'order_master.order_id');
                $join->where('order_dispatch_dtl.amend_status', '0');
            })
            ->leftJoin('order_process_log', function ($join) {
                $join->on('order_process_log.opl_order_id', '=', 'order_master.order_id');
                $join->where('order_process_log.opl_current_stage', '1');
                $join->where('order_process_log.opl_amend_status', '0');
            })
            ->leftJoin('invoice_hdr_detail', function ($join) {
                $join->on('invoice_hdr_detail.order_id', '=', 'order_master.order_id');
                $join->where('invoice_hdr_detail.invoice_hdr_status', '1');
            })
            ->leftJoin('order_incharge_dtl', function ($join) {
                $join->on('order_incharge_dtl.order_id', '=', 'order_master.order_id');
                $join->where('order_incharge_dtl.oid_employee_id', defined('USERID') ? USERID : '0');
            })
            ->select('order_master.*', 'divisions.division_name', 'customer_master.customer_type', 'customer_master.customer_name', 'customer_master.customer_address', 'customer_types.customer_type as customerType', 'customer_master.customer_email', 'customer_master.customer_status', 'city_db.city_name', 'state_db.state_name', 'sales.name as sale_executive_name', 'product_master.product_name', 'product_test_hdr.test_code', 'test_standard.test_std_name', 'order_sample_priority.sample_priority_name', 'createdBy.name as createdByName', 'customer_discount_types.discount_type', 'samples.sample_no', 'samples.sample_date', 'sample_modes.sample_mode_name', 'order_report_details.*', 'order_process_log.*', 'product_master_alias.c_product_name as sample_description', 'customer_invoicing_types.*', 'departments.*', 'ref_sample_value_data.report_option_name as ref_sample_value_name', 'result_drived_value_data.report_option_name as result_drived_value_name', 'deviation_value_data.report_option_name as deviation_value_name', 'createdBy.user_signature', 'reportngToCity.city_name as reporting_city', 'reportngToState.state_name as reporting_state', 'invoicingToState.state_name as invoicing_state', 'invoicingToCity.city_name as invoicing_city', 'reporting_master.customer_id as reportingCustomerId', 'reporting_master.customer_name as reportingCustomerName', 'reporting_master.customer_address as altReportingAddress', 'invoicing_master.customer_id as invoicingCustomerId', 'invoicing_master.customer_name as invoicingCustomerName', 'invoicing_master.customer_address as altInvoicingAddress', 'customer_contact_persons.contact_name1', 'customer_contact_persons.contact_mobile1', 'order_process_log.opl_date as order_status_time', 'order_dispatch_dtl.dispatch_id as dispatch_status', 'order_master.billing_type_id', 'invoice_hdr_detail.invoice_dtl_id as invoice_generated_id', 'countries_db.country_name', 'product_categories.p_category_name', 'order_incharge_dtl.oid_confirm_date', 'order_incharge_dtl.oid_id', 'order_report_microbiological_dtl.report_microbiological_name', 'order_report_microbiological_dtl.report_microbiological_sign', 'defined_test_standard_db.test_std_name as defined_test_standard_name')
            ->where('order_master.order_id', '=', $order_id)
            ->first();

        if (!empty($orderList->reporting_to) || !empty($orderList->invoicing_to)) {
            $orderList->customer_name             = !empty($orderList->reporting_to) ? $orderList->reportingCustomerName : $orderList->customer_name;
            $orderList->city_name                 = !empty($orderList->reporting_to) ? $orderList->reporting_city : $orderList->city_name;
            $orderList->state_name                = !empty($orderList->reporting_to) ? $orderList->reporting_state : $orderList->state_name;
            $orderList->customer_address         = !empty($orderList->reporting_to) ? $orderList->altReportingAddress : $orderList->customer_address;
            $orderList->reportingCustomerName     = !empty($orderList->reporting_to) ? $orderList->reportingCustomerName . '/' . $orderList->reporting_city : '';
            $orderList->invoicingCustomerName     = !empty($orderList->invoicing_to) ? $orderList->invoicingCustomerName . '/' . $orderList->invoicing_city : '';
        }
        if (!empty($orderList->customer_id)) {
            list($toEmails, $ccEmails) = $this->getCustomerEmailToCC($orderList->customer_id);
            $orderList->to_emails     = array_values($toEmails);
            $orderList->cc_emails     = array_values($ccEmails);
        }

        if (!empty($orderList->po_no) || !empty($orderList->po_date) || !empty($orderList->hold_reason) || !empty($orderList->order_sample_type) || !empty($orderList->reporting_to) || !empty($orderList->invoicing_to)) {
            $orderList->extraDetail = true;
        }

        //Checking Test report contains Header and Footer.
        if (!empty($orderList) && empty($orderList->header_content) && empty($orderList->footer_content)) {
            list($header_content, $footer_content) = $this->getDynamicHeaderFooterTemplate('1', $orderList->division_id, $orderList->product_category_id);
            $orderList->header_content = $header_content;
            $orderList->footer_content = $footer_content;
        }

        //Getting Prototype No of Stability Order if Order belongs to Stability Module
        if (!empty($orderList->order_id)) {
            $stbOrderPototypeHdrData = !empty($orderList->stb_order_hdr_detail_id) ? $this->getStabilityOrderPrototypeNoDetail($orderList->stb_order_hdr_detail_id) : array();
            $orderList->stb_prototype_no = !empty($stbOrderPototypeHdrData->stb_prototype_no) ? trim($stbOrderPototypeHdrData->stb_prototype_no) : '';
        }

        //Getting Report Microbiological Sign Path
        if (!empty($orderList) && array_key_exists('report_microbiological_sign', $orderList)) {
            $orderList->report_microbiological_sign_path = !empty($orderList->report_microbiological_sign) ? preg_replace('/([^:])(\/{2,})/', '$1/', ROOT_URL . SIGN_PATH . $orderList->report_microbiological_sign) : '';
            $orderList->report_microbiological_sign_dir_path = !empty($orderList->report_microbiological_sign) ? preg_replace('/([^:])(\/{2,})/', '$1/', ROOT_DIR . SIGN_PATH . $orderList->report_microbiological_sign) : '';
        }

        //Getting Customer Defined Fields
        if (!empty($orderList->division_id) && !empty($orderList->product_category_id)) {
            $orderList->customer_defined_fields = $this->getCustomerDefinedTestReportField($ors_type_id = '1', $orderList->division_id, $orderList->product_category_id);
        }

        //Getting Report Due Date and Department Due Date
        if (!empty($orderList->order_id)) {
            $orderList->order_report_due_date = $this->getMaxReportDueDate($orderList->order_id);
            $orderList->order_dept_due_date   = $this->getMaxDepartmentDueDate($orderList->order_id);
        }

        //Getting Group SelectBox Fields
        if (!empty($orderList->division_id) && !empty($orderList->product_category_id)) {
            $orderList->group_dropdown_list = $this->getOrderReportGroupDropdownList($orderList->division_id, $orderList->product_category_id);
        }

        //Getting all Dynamic Field Data Details
        if (!empty($orderList->order_id)) {
            $dynamicAddedFieldData = $this->getDynamicFieldData($orderList->order_id);    //Get order Dynamic Field Data
            if (!empty($dynamicAddedFieldData)) {
                foreach ($dynamicAddedFieldData as $key => $values) {
                    $values->exist_status = '1';
                }
            }
            $orderList->order_dynamic_fields = $dynamicAddedFieldData;
        }

        //Getting Min Date of start of analysis && Max Date of completion of Analysis on 20-May-2022
        if (!empty($orderList->order_id)) {
            $orderList->analysis_start_date      = $this->getMinDateStartOfAnalysis($orderList->order_id);
            $orderList->analysis_completion_date = $this->getMaxDateCompletionOfAnalysis($orderList->order_id);
        }

        return $orderList;
    }

    /*************************
     *Getting Max Report Due Date
     *Created By : Praveen Singh
     *Created On : 12-July-2019
     *************************/
    function getMaxReportDueDate($orderId)
    {
        $reportDueDateObj = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $orderId);
        if (defined('IS_TESTER') && IS_TESTER) {
            $reportDueDateObj->join('schedulings', 'schedulings.order_parameter_id', 'order_parameters_detail.analysis_id');
            $reportDueDateObj->where('schedulings.employee_id', '=', USERID);
        }
        return $reportDueDateObj->max('order_parameters_detail.report_due_date');
    }

    /*************************
     *Getting Max Departmnet Due Date
     *Created By : Praveen Singh
     *Created On : 12-July-2019
     *************************/
    function getMaxDepartmentDueDate($orderId)
    {
        $departmentDueDateObj = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $orderId);
        if (defined('IS_TESTER') && IS_TESTER) {
            $departmentDueDateObj->join('schedulings', 'schedulings.order_parameter_id', 'order_parameters_detail.analysis_id');
            $departmentDueDateObj->where('schedulings.employee_id', '=', USERID);
        }
        return $departmentDueDateObj->max('order_parameters_detail.dept_due_date');
    }

    /*************************
     *Getting Max Departmnet Due Date Order Ids
     *Created By : Praveen Singh
     *Created On : 09-Jan-2020
     *************************/
    function getMaxDepartmentDueDateOrderDetail($searchType, $departmentDueDateFrom, $departmentDueDateTo, $employeeId)
    {
        $getDepartmentDueDateOrderObj = DB::table('order_parameters_detail')->join('schedulings', 'schedulings.order_parameter_id', 'order_parameters_detail.analysis_id');
        if ($searchType == 'between') {
            $getDepartmentDueDateOrderObj->whereDate('order_parameters_detail.dept_due_date', '>=', $departmentDueDateFrom);
            $getDepartmentDueDateOrderObj->whereDate('order_parameters_detail.dept_due_date', '<=', $departmentDueDateTo);
        } else if ($searchType == 'greaterThenEqualTo') {
            $getDepartmentDueDateOrderObj->whereDate('order_parameters_detail.dept_due_date', '>=', $departmentDueDateFrom);
        } else if ($searchType == 'lessThenEqualTo') {
            $getDepartmentDueDateOrderObj->whereDate('order_parameters_detail.dept_due_date', '<=', $departmentDueDateTo);
        }
        $getDepartmentDueDateOrderObj->where('schedulings.employee_id', '=', $employeeId);
        $orderIdArray = $getDepartmentDueDateOrderObj->pluck('order_parameters_detail.order_id', 'order_parameters_detail.order_id')->all();
        return !empty($orderIdArray) ? array_values($orderIdArray) : array(0);
    }

    /*************************
     *Getting Customer Defined Fields
     *Created By : Praveen Singh
     *Created On : 18-June-2019
     *************************/
    function getCustomerDefinedTestReportField($ors_type_id, $division_id, $product_category_id)
    {
        return DB::table('order_report_settings')->where('order_report_settings.ors_type_id', '=', $ors_type_id)->where('order_report_settings.ors_division_id', '=', $division_id)->where('order_report_settings.ors_product_category_id', '=', $product_category_id)->pluck('order_report_settings.ors_column_name')->all();
    }

    /*************************
     *function to get Customer Email To CC
     *
     *************************/
    function getCustomerEmailToCC($customer_id)
    {
        $customerEmailPrimary   = DB::table('customer_email_addresses')->where('customer_email_addresses.customer_id', '=', $customer_id)->where('customer_email_addresses.customer_email_status', '=', '1')->where('customer_email_addresses.customer_email_type', '=', 'P')->pluck('customer_email_addresses.customer_email')->all();
        $customerEmailSecondary = DB::table('customer_email_addresses')->where('customer_email_addresses.customer_id', '=', $customer_id)->where('customer_email_addresses.customer_email_status', '=', '1')->where('customer_email_addresses.customer_email_type', '=', 'S')->pluck('customer_email_addresses.customer_email')->all();
        return array($customerEmailPrimary, $customerEmailSecondary);
    }

    /*************************
     *Getting Prototype No of Stability Order if Order belongs to Stability Module
     *Created By : Praveen Singh
     *Created On : 31-Jan-2019
     *************************/
    function getStabilityOrderPrototypeNoDetail($stb_order_hdr_detail_id)
    {
        return DB::table('stb_order_hdr')->join('stb_order_hdr_dtl_detail', 'stb_order_hdr_dtl_detail.stb_order_hdr_id', 'stb_order_hdr.stb_order_hdr_id')->where('stb_order_hdr_dtl_detail.stb_order_hdr_detail_id', '=', !empty($stb_order_hdr_detail_id) ? $stb_order_hdr_detail_id : '0')->first();
    }

    /*************************
     *function to get order parameters 
     * Modified By : Praveen Singh
     * Modified On : 29-June-2020,01-Aug-2020
     *************************/
    function getOrderParameters($order_id, $arrayOption = array())
    {

        $opd_with_gen_info_parameter_ids = DB::table('order_parameters_detail')
            ->join('test_parameter', 'order_parameters_detail.test_parameter_id', 'test_parameter.test_parameter_id')
            ->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
            ->whereIn('test_parameter_categories.test_para_cat_id', defined('ENV_WATER_REPORT_CATEGORY') ? explode(',', ENV_WATER_REPORT_CATEGORY) : array())
            ->where('order_parameters_detail.order_id', $order_id)
            ->pluck('order_parameters_detail.analysis_id', 'order_parameters_detail.analysis_id')
            ->all();
        $opd_without_gen_info_parameter_ids = DB::table('order_parameters_detail')
            ->join('test_parameter', 'order_parameters_detail.test_parameter_id', 'test_parameter.test_parameter_id')
            ->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
            ->when(!empty($arrayOption['nabl_report_generation_type']) && !empty($arrayOption['reportWithRightLogo']) && $arrayOption['reportWithRightLogo'] == '16', function ($q) {
                return $q->where(function ($query) {
                    $query->where('order_parameters_detail.order_parameter_nabl_scope', '=', '1')->orWhereNull('order_parameters_detail.order_parameter_nabl_scope');
                });
            })
            ->when(!empty($arrayOption['nabl_report_generation_type']) && !empty($arrayOption['reportWithRightLogo']) && $arrayOption['reportWithRightLogo'] == '17', function ($q) {
                return $q->where(function ($query) {
                    $query->where('order_parameters_detail.order_parameter_nabl_scope', '=', '0')->orWhereNull('order_parameters_detail.order_parameter_nabl_scope');
                });
            })
            ->whereNotIn('test_parameter_categories.test_para_cat_id', defined('ENV_WATER_REPORT_CATEGORY') ? explode(',', ENV_WATER_REPORT_CATEGORY) : array())
            ->where('order_parameters_detail.order_id', $order_id)
            ->pluck('order_parameters_detail.analysis_id', 'order_parameters_detail.analysis_id')
            ->all();
        return DB::table('order_parameters_detail')
            ->join('product_test_dtl', 'product_test_dtl.product_test_dtl_id', 'order_parameters_detail.product_test_parameter')
            ->join('product_test_hdr', 'product_test_dtl.test_id', 'product_test_hdr.test_id')
            ->join('product_master', 'product_master.product_id', 'product_test_hdr.product_id')
            ->join('test_parameter', 'order_parameters_detail.test_parameter_id', 'test_parameter.test_parameter_id')
            ->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
            ->leftJoin('equipment_type', 'equipment_type.equipment_id', 'order_parameters_detail.equipment_type_id')
            ->leftJoin('method_master', 'method_master.method_id', 'order_parameters_detail.method_id')
            ->leftJoin('detector_master', 'detector_master.detector_id', 'order_parameters_detail.detector_id')
            ->leftJoin('column_master', 'column_master.column_id', 'order_parameters_detail.column_id')
            ->leftJoin('instance_master', 'instance_master.instance_id', 'order_parameters_detail.instance_id')
            ->leftJoin('customer_invoicing_running_time', 'customer_invoicing_running_time.invoicing_running_time_id', 'order_parameters_detail.running_time_id')
            ->leftJoin('order_discipline_group_dtl', function ($join) {
                $join->on('order_discipline_group_dtl.order_id', '=', 'order_parameters_detail.order_id');
                $join->whereColumn('order_discipline_group_dtl.test_parameter_category_id', 'test_parameter_categories.test_para_cat_id');
            })
            ->leftJoin('order_report_disciplines', 'order_report_disciplines.or_discipline_id', 'order_discipline_group_dtl.discipline_id')
            ->leftJoin('order_report_groups', 'order_report_groups.org_group_id', 'order_discipline_group_dtl.group_id')
            ->select('product_test_dtl.test_id', 'product_test_hdr.test_code', 'product_master.product_id', 'product_master.product_name', 'product_master.p_category_id', 'order_parameters_detail.*', 'test_parameter.test_parameter_code', 'test_parameter.test_parameter_name', 'test_parameter.test_parameter_id as parameter_id', 'equipment_type.equipment_name', 'method_master.method_name', 'test_parameter_categories.test_para_cat_id', 'test_parameter_categories.test_para_cat_name', 'test_parameter_categories.product_category_id', 'product_test_dtl.product_test_dtl_id', 'product_test_dtl.test_id', 'product_test_dtl.claim_dependent', 'product_test_dtl.claim_dependent', 'product_test_dtl.description', 'product_test_dtl.created_by', 'product_test_dtl.standard_value_to as productTestDtlStdValTo', 'product_test_dtl.standard_value_from as productTestDtlStdValFrom', 'test_parameter_categories.category_sort_by', 'detector_master.detector_name', 'customer_invoicing_running_time.invoicing_running_time', 'column_master.column_name', 'instance_master.instance_name', 'order_discipline_group_dtl.discipline_id', 'order_report_disciplines.or_discipline_name as discipline_name', 'order_discipline_group_dtl.group_id', 'order_report_groups.org_group_name as group_name')
            ->where('order_parameters_detail.order_id', $order_id)
            ->whereIn('order_parameters_detail.analysis_id', array_merge($opd_with_gen_info_parameter_ids, $opd_without_gen_info_parameter_ids))
            ->orderBy('test_parameter_categories.category_sort_by', 'ASC')
            ->orderBy('product_test_dtl.parameter_sort_by', 'ASC')
            ->get()
            ->toArray();
    }

    /*************************
     *function to get order parameters 
     * Modified By : Praveen Singh
     * Modified On : 29-June-2020
     *************************/
    function getOrderParameters_vi($order_id, $arrayOption = array())
    {
        return DB::table('order_parameters_detail')
            ->join('product_test_dtl', 'product_test_dtl.product_test_dtl_id', 'order_parameters_detail.product_test_parameter')
            ->join('product_test_hdr', 'product_test_dtl.test_id', 'product_test_hdr.test_id')
            ->join('product_master', 'product_master.product_id', 'product_test_hdr.product_id')
            ->join('test_parameter', 'order_parameters_detail.test_parameter_id', 'test_parameter.test_parameter_id')
            ->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
            ->leftJoin('equipment_type', 'equipment_type.equipment_id', 'order_parameters_detail.equipment_type_id')
            ->leftJoin('method_master', 'method_master.method_id', 'order_parameters_detail.method_id')
            ->leftJoin('detector_master', 'detector_master.detector_id', 'order_parameters_detail.detector_id')
            ->leftJoin('customer_invoicing_running_time', 'customer_invoicing_running_time.invoicing_running_time_id', 'order_parameters_detail.running_time_id')
            ->leftJoin('order_discipline_group_dtl', function ($join) {
                $join->on('order_discipline_group_dtl.order_id', '=', 'order_parameters_detail.order_id');
                $join->whereColumn('order_discipline_group_dtl.test_parameter_category_id', 'test_parameter_categories.test_para_cat_id');
            })
            ->leftJoin('order_report_disciplines', 'order_report_disciplines.or_discipline_id', 'order_discipline_group_dtl.discipline_id')
            ->leftJoin('order_report_groups', 'order_report_groups.org_group_id', 'order_discipline_group_dtl.group_id')
            ->select('product_test_dtl.test_id', 'product_test_hdr.test_code', 'product_master.product_id', 'product_master.product_name', 'product_master.p_category_id', 'order_parameters_detail.*', 'test_parameter.test_parameter_code', 'test_parameter.test_parameter_name', 'test_parameter.test_parameter_id as parameter_id', 'equipment_type.equipment_name', 'method_master.method_name', 'test_parameter_categories.test_para_cat_id', 'test_parameter_categories.test_para_cat_name', 'test_parameter_categories.product_category_id', 'product_test_dtl.product_test_dtl_id', 'product_test_dtl.test_id', 'product_test_dtl.claim_dependent', 'product_test_dtl.claim_dependent', 'product_test_dtl.description', 'product_test_dtl.created_by', 'product_test_dtl.standard_value_to as productTestDtlStdValTo', 'product_test_dtl.standard_value_from as productTestDtlStdValFrom', 'test_parameter_categories.category_sort_by', 'detector_master.detector_name', 'customer_invoicing_running_time.invoicing_running_time', 'order_discipline_group_dtl.discipline_id', 'order_report_disciplines.or_discipline_name as discipline_name', 'order_discipline_group_dtl.group_id', 'order_report_groups.org_group_name as group_name')
            ->when(!empty($arrayOption['reportWithRightLogo']) && $arrayOption['reportWithRightLogo'] == '16', function ($q) {
                return $q->where(function ($query) {
                    $query->where('order_parameters_detail.order_parameter_nabl_scope', '=', '1')->orWhereNull('order_parameters_detail.order_parameter_nabl_scope');
                });
            })
            ->when(!empty($arrayOption['reportWithRightLogo']) && $arrayOption['reportWithRightLogo'] == '17', function ($q) {
                return $q->where(function ($query) {
                    $query->where('order_parameters_detail.order_parameter_nabl_scope', '=', '0')->orWhereNull('order_parameters_detail.order_parameter_nabl_scope');
                });
            })
            ->where('order_parameters_detail.order_id', '=', $order_id)
            ->orderBy('test_parameter_categories.category_sort_by', 'ASC')
            ->orderBy('product_test_dtl.parameter_sort_by', 'ASC')
            ->get()
            ->toArray();
    }

    /*************************
     * Function to get order parameters category
     * Created On : 03-Feb-2020
     * Created By : Praveen Singh
     *************************/
    function getOrderParameterCategory($order_id)
    {
        return DB::table('order_parameters_detail')
            ->join('test_parameter', 'order_parameters_detail.test_parameter_id', 'test_parameter.test_parameter_id')
            ->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
            ->where('order_parameters_detail.order_id', '=', $order_id)
            ->orderBy('test_parameter_categories.category_sort_by', 'ASC')
            ->pluck('test_parameter_categories.test_para_cat_name', 'test_parameter_categories.test_para_cat_id')
            ->all();
    }

    /*************************
     *function to get order parameters
     *
     *************************/
    function getOrderParameterWithNames($order_id)
    {
        return DB::table('order_parameters_detail')
            ->join('product_test_dtl', 'product_test_dtl.product_test_dtl_id', 'order_parameters_detail.product_test_parameter')
            ->join('test_parameter', 'order_parameters_detail.test_parameter_id', 'test_parameter.test_parameter_id')
            ->where('order_parameters_detail.order_id', '=', $order_id)
            ->orderBy('product_test_dtl.parameter_sort_by', 'ASC')
            ->pluck('test_parameter.test_parameter_name', 'order_parameters_detail.test_parameter_id')
            ->all();
    }

    /*************************
     *function to get order parameters
     * assigned to a employee
     *************************/
    function getAsssignedOrderParameters($order_id, $employee_id)
    {
        return DB::table('order_parameters_detail')
            ->join('schedulings', 'schedulings.order_parameter_id', 'order_parameters_detail.analysis_id')
            ->join('product_test_dtl', 'product_test_dtl.product_test_dtl_id', 'order_parameters_detail.product_test_parameter')
            ->join('product_test_hdr', 'product_test_dtl.test_id', 'product_test_hdr.test_id')
            ->join('product_master', 'product_master.product_id', 'product_test_hdr.product_id')
            ->join('test_parameter', 'test_parameter.test_parameter_id', 'order_parameters_detail.test_parameter_id')
            ->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
            ->leftJoin('equipment_type', 'equipment_type.equipment_id', 'order_parameters_detail.equipment_type_id')
            ->leftJoin('method_master', 'method_master.method_id', 'order_parameters_detail.method_id')
            ->leftJoin('detector_master', 'detector_master.detector_id', 'order_parameters_detail.detector_id')
            ->leftJoin('customer_invoicing_running_time', 'customer_invoicing_running_time.invoicing_running_time_id', 'order_parameters_detail.running_time_id')
            ->select('product_test_hdr.test_id', 'product_test_hdr.test_code', 'product_master.product_id', 'product_master.product_name', 'product_master.p_category_id', 'order_parameters_detail.*', 'test_parameter.test_parameter_code', 'test_parameter.test_parameter_name', 'equipment_type.equipment_name', 'method_master.method_name', 'test_parameter_categories.test_para_cat_id', 'test_parameter_categories.test_para_cat_name', 'test_parameter_categories.product_category_id', 'product_test_dtl.description', 'test_parameter_categories.category_sort_by', 'product_test_dtl.standard_value_to as productTestDtlStdValTo', 'product_test_dtl.standard_value_from as productTestDtlStdValFrom', 'detector_master.detector_name', 'customer_invoicing_running_time.invoicing_running_time')
            ->where('schedulings.order_id', '=', $order_id)
            ->where('schedulings.employee_id', '=', $employee_id)
            ->orderBy('product_test_dtl.parameter_sort_by', 'ASC')
            ->get();
    }

    /*************************
     *function to get order parameters
     * assigned to a tester (employee)
     *************************/
    function getAsssignedOrderParameterForTester($order_id, $employee_id)
    {
        return DB::table('order_parameters_detail')
            ->join('schedulings', 'schedulings.order_parameter_id', 'order_parameters_detail.analysis_id')
            ->join('product_test_dtl', 'product_test_dtl.product_test_dtl_id', 'order_parameters_detail.product_test_parameter')
            ->join('product_test_hdr', 'product_test_dtl.test_id', 'product_test_hdr.test_id')
            ->join('test_parameter', 'test_parameter.test_parameter_id', 'order_parameters_detail.test_parameter_id')
            ->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
            ->leftJoin('equipment_type', 'equipment_type.equipment_id', 'order_parameters_detail.equipment_type_id')
            ->leftJoin('method_master', 'method_master.method_id', 'order_parameters_detail.method_id')
            ->leftJoin('detector_master', 'detector_master.detector_id', 'order_parameters_detail.detector_id')
            ->leftJoin('customer_invoicing_running_time', 'customer_invoicing_running_time.invoicing_running_time_id', 'order_parameters_detail.running_time_id')
            ->select('order_parameters_detail.*', 'test_parameter.test_parameter_code', 'test_parameter.test_parameter_name', 'equipment_type.equipment_name', 'method_master.method_name', 'test_parameter_categories.test_para_cat_id', 'test_parameter_categories.test_para_cat_name', 'test_parameter_categories.product_category_id', 'product_test_dtl.description', 'test_parameter_categories.category_sort_by', 'detector_master.detector_name', 'customer_invoicing_running_time.invoicing_running_time')
            ->where('schedulings.order_id', '=', $order_id)
            ->where('schedulings.employee_id', '=', $employee_id)
            ->orderBy('product_test_dtl.parameter_sort_by', 'ASC')
            ->get();
    }

    /*************************
     *function to get aleternative
     * order parameters List
     *************************/
    function getAlternativeOrderParameters($order_id, $product_test_param_altern_method_id)
    {
        return DB::table('product_test_parameter_altern_method')
            ->join('order_parameters_detail', 'order_parameters_detail.test_param_alternative_id', 'product_test_parameter_altern_method.product_test_param_altern_method_id')
            ->join('product_test_hdr', 'product_test_hdr.test_id', 'product_test_parameter_altern_method.test_id')
            ->join('test_parameter', 'test_parameter.test_parameter_id', 'product_test_parameter_altern_method.test_parameter_id')
            ->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
            ->join('equipment_type', 'equipment_type.equipment_id', 'product_test_parameter_altern_method.equipment_type_id')
            ->join('method_master', 'method_master.method_id', 'product_test_parameter_altern_method.method_id')
            ->leftJoin('detector_master', 'detector_master.detector_id', 'product_test_parameter_altern_method.detector_id')
            ->leftJoin('customer_invoicing_running_time', 'customer_invoicing_running_time.invoicing_running_time_id', 'product_test_parameter_altern_method.running_time_id')
            ->select('order_parameters_detail.analysis_id', 'order_parameters_detail.order_id', 'order_parameters_detail.claim_value', 'product_test_parameter_altern_method.*', 'test_parameter.test_parameter_code', 'test_parameter.test_parameter_name', 'equipment_type.equipment_name', 'method_master.method_name', 'test_parameter_categories.test_para_cat_id', 'test_parameter_categories.test_para_cat_name', 'order_parameters_detail.test_performed_by', 'order_parameters_detail.test_result', 'order_parameters_detail.claim_value_unit', 'test_parameter_categories.product_category_id', 'test_parameter_categories.category_sort_by', 'detector_master.detector_name', 'customer_invoicing_running_time.invoicing_running_time')
            ->where('product_test_parameter_altern_method.product_test_param_altern_method_id', $product_test_param_altern_method_id)
            ->where('order_parameters_detail.order_id', $order_id)
            ->first();
    }

    //get Alternative Order Parameters
    function getAlternativeParametersDetail($id)
    {
        return DB::table('product_test_parameter_altern_method')
            ->join('product_test_hdr', 'product_test_hdr.test_id', 'product_test_parameter_altern_method.test_id')
            ->join('test_parameter', 'test_parameter.test_parameter_id', 'product_test_parameter_altern_method.test_parameter_id')
            ->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
            ->join('equipment_type', 'equipment_type.equipment_id', 'product_test_parameter_altern_method.equipment_type_id')
            ->join('method_master', 'method_master.method_id', 'product_test_parameter_altern_method.method_id')
            ->leftJoin('detector_master', 'detector_master.detector_id', 'product_test_parameter_altern_method.detector_id')
            ->leftJoin('customer_invoicing_running_time', 'customer_invoicing_running_time.invoicing_running_time_id', 'product_test_parameter_altern_method.running_time_id')
            ->select('product_test_parameter_altern_method.*', 'test_parameter.test_parameter_code', 'test_parameter.test_parameter_name', 'equipment_type.equipment_name', 'method_master.method_name', 'test_parameter_categories.test_para_cat_id', 'test_parameter_categories.test_para_cat_name', 'detector_master.detector_name', 'customer_invoicing_running_time.invoicing_running_time')
            ->where('product_test_parameter_altern_method.product_test_param_altern_method_id', $id)
            ->first();
    }

    //Check the Reference Number
    function checkReferenceNumber($order_date, $refNumber)
    {
        $data = DB::table('order_master')->where('order_master.reference_no', '=', $refNumber)->count();
        if (strtotime($refNumber) < strtotime($order_date)) {
            return true;
        } else {
            return false;
        }
    }

    /*******************************************
     *Function: generate Order Expected Due Date(EDD)
     *Created By: Praveen Singh
     *Created On : 30-July-2018
     ******************************************/
    function generateUpdateOrderExpectedDueDate($order_id, $date = Null)
    {

        global $order, $models, $mail;

        $dataSave = array();

        $orderDetail =  DB::table('order_master')->select('order_id', 'order_date', 'booking_date', 'tat_in_days')->where('order_master.order_id', '=', $order_id)->first();

        if (!empty($orderDetail->booking_date)) {

            $expectedDueDate           = !empty($date) ? $date : $orderDetail->booking_date;
            $orderDetail->booking_date = !empty($date) ? $date : $orderDetail->booking_date;

            //Getting Number of days
            list($total_time_taken_days, $is_tat_in_day_reupdatable) = $this->__calculateDaysForEDDBySystemOrUserTAT($orderDetail);

            if (!empty($total_time_taken_days)) {

                //if booking date after 2.00 PM
                if (strtotime(date('ha', strtotime($orderDetail->booking_date))) > strtotime("2pm")) {
                    $total_time_taken_days = $total_time_taken_days + 1;
                }

                //Add days to current date to calculate the observed expected due date
                $expectedDueDate = date(MYSQLDATETIMEFORMAT, strtotime('+' . $total_time_taken_days . ' day', strtotime($orderDetail->booking_date)));

                //Checking there any sunday lies on calculated days,then add number of days according to number of sunday in expected due date
                $sundays = $models->getSundays(CURRENTDATE, $expectedDueDate);
                if (!empty($sundays)) {
                    $expectedDueDate = date(MYSQLDATETIMEFORMAT, strtotime('+' . count($sundays) . ' day', strtotime($expectedDueDate)));
                }

                //Checking if any holidays lies between order booking date and Calculated Expected Due Date
                $holidayDayCounts = DB::table('holiday_master')->where('holiday_master.holiday_status', '1')->whereBetween(DB::raw("DATE(holiday_master.holiday_date)"), array(date('Y-m-d', strtotime($orderDetail->booking_date)), date('Y-m-d', strtotime($expectedDueDate))))->count();
                if ($holidayDayCounts) {
                    $expectedDueDate = date(MYSQLDATETIMEFORMAT, strtotime('+' . count($holidayDayCounts) . ' day', strtotime($expectedDueDate)));
                }
            }

            //Validating Sunday and Holidays
            $expectedDueDate = $models->validateSundayHoliday($expectedDueDate);

            //Dept. Due Date and Report Due Date
            list($deptDueDate, $reportDueDate) = $this->generateReportAndDepartmentDueDate($orderDetail->booking_date, $expectedDueDate);

            //Finally Updating the Order Master Table
            if (!empty($is_tat_in_day_reupdatable)) {
                $dataSave = array('order_master.expected_due_date' => $expectedDueDate, 'order_master.order_dept_due_date' => $deptDueDate, 'order_master.order_report_due_date' => $reportDueDate, 'order_master.tat_in_days' => $is_tat_in_day_reupdatable);
            } else {
                $dataSave = array('order_master.expected_due_date' => $expectedDueDate, 'order_master.order_dept_due_date' => $deptDueDate, 'order_master.order_report_due_date' => $reportDueDate);
            }
            return !empty($dataSave) ? DB::table('order_master')->where('order_master.order_id', $order_id)->update($dataSave) : false;
        }
    }

    /*******************************************
     *Function : Getting Number of Days to calculate Order Expected Due Date(EDD)
     *Created By : Praveen Singh
     *Created On : 30-July-2018
     ******************************************/
    function __calculateDaysForEDDBySystemOrUserTAT($orderDetail)
    {

        $total_time_taken_days = $is_tat_in_day_reupdatable = '0';

        $time_taken_days = array();

        //If User Enters the TAT in Days Values and Selected Parameter has not Microbiological Equipment
        $hasEquipmentMicrobiological = DB::table('order_parameters_detail')->where('order_parameters_detail.equipment_type_id', '22')->where('order_parameters_detail.order_id', '=', $orderDetail->order_id)->first();
        if (!empty($orderDetail->tat_in_days) && empty($hasEquipmentMicrobiological)) {
            $total_time_taken_days = !empty($orderDetail->tat_in_days) ? trim($orderDetail->tat_in_days) : '0';
        } else {        //Sysytem Generated Days

            //Getting the default time taken by the parameter
            $orderParametersDetail = DB::table('order_parameters_detail')->select('order_parameters_detail.time_taken_days', 'order_parameters_detail.time_taken_mins')->where('order_parameters_detail.order_id', '=', $orderDetail->order_id)->get();
            foreach ($orderParametersDetail as $key => $orderParameter) {
                $hrMinExplodedData = !empty($orderParameter->time_taken_mins) ? explode(':', $orderParameter->time_taken_mins) : array();
                $time_taken_days[] = !empty($orderParameter->time_taken_days) ? $orderParameter->time_taken_days : '0';
            }

            //Getting Maximum days from all parameter test allocated days
            $total_time_taken_days = !empty($time_taken_days) && array_filter($time_taken_days) ? max($time_taken_days) : '0';

            //Checking If TAT-In-Days is re-updatable in case of Microbilogical Equipment Exist
            $is_tat_in_day_reupdatable = !empty($orderDetail->tat_in_days) && !empty($hasEquipmentMicrobiological) ? $total_time_taken_days : '0';
        }

        return array($total_time_taken_days, $is_tat_in_day_reupdatable);
    }

    /*******************************************
     *Function : generate Report Due and Department Due Date using Expected Due Date
     *Created By : Praveen Singh
     *Created On : 24-July-2018
     ******************************************/
    function generateReportAndDepartmentDueDate($orderDate, $expectedDueDate)
    {

        global $order, $models;

        $deptDueDate =     $deptDueDate = '';

        //Dept. Due Date and Report Due Date
        if (!empty($orderDate) && !empty($expectedDueDate)) {
            $numberOfdays = count($models->date_range($orderDate, $expectedDueDate));
            if ($numberOfdays <= '3') {
                $calDeptDueDate   = $models->sub_days_in_date($expectedDueDate, '1', MYSQLDATETIMEFORMAT);;
                $calReportDueDate = $models->sub_days_in_date($expectedDueDate, '1', MYSQLDATETIMEFORMAT);;
            } else {
                $calDeptDueDate   = $models->sub_days_in_date($expectedDueDate, '2', MYSQLDATETIMEFORMAT);
                $calReportDueDate = $models->sub_days_in_date($expectedDueDate, '1', MYSQLDATETIMEFORMAT);
            }
            $deptDueDate   = $models->checkDateIsSunday($calDeptDueDate) ? $models->sub_days_in_date($calDeptDueDate, '1', MYSQLDATETIMEFORMAT) : $calDeptDueDate;
            $reportDueDate = $models->checkDateIsSunday($calReportDueDate) ? $models->sub_days_in_date($calReportDueDate, '1', MYSQLDATETIMEFORMAT) : $calReportDueDate;
        }

        return array($deptDueDate, $reportDueDate);
    }

    //Adding Order Parameter()Scheduling Jobs in Scheduling Table
    function createOrderSchedulingJobs($order_id)
    {

        $jobsForScheduling = DB::table('order_parameters_detail')
            ->join('order_master', 'order_master.order_id', 'order_parameters_detail.order_id')
            ->select('order_parameters_detail.order_id', 'order_parameters_detail.analysis_id', 'order_parameters_detail.equipment_type_id', 'order_master.product_category_id')
            ->where('order_parameters_detail.order_id', $order_id)
            ->get();

        if (!empty($jobsForScheduling)) {
            $dataSave = array();
            foreach ($jobsForScheduling as $key => $jobs) {
                $dataSave[$key]['order_id']            = $jobs->order_id;
                $dataSave[$key]['order_parameter_id']  = $jobs->analysis_id;
                $dataSave[$key]['product_category_id'] = $jobs->product_category_id;
                $dataSave[$key]['equipment_type_id']   = $jobs->equipment_type_id;
                $dataSave[$key]['status']              = '0';
                $dataSave[$key]['created_by']          = USERID;
            }
            return !empty($dataSave) && DB::table('schedulings')->insert($dataSave) ? true : false;
        }
    }

    //Adding Order Parameter()Scheduling Jobs in Scheduling Table
    function updateOrderSchedulingJobs($analysisId)
    {
        $jobsForScheduling = DB::table('order_parameters_detail')
            ->join('order_master', 'order_master.order_id', 'order_parameters_detail.order_id')
            ->select('order_parameters_detail.order_id', 'order_parameters_detail.analysis_id', 'order_parameters_detail.equipment_type_id', 'order_master.product_category_id')
            ->where('order_parameters_detail.analysis_id', $analysisId)
            ->get();
        if (!empty($jobsForScheduling)) {
            $dataSave = array();
            foreach ($jobsForScheduling as $key => $jobs) {
                $dataSave[$key]['order_id']            = $jobs->order_id;
                $dataSave[$key]['order_parameter_id']  = $jobs->analysis_id;
                $dataSave[$key]['product_category_id'] = $jobs->product_category_id;
                $dataSave[$key]['equipment_type_id']   = $jobs->equipment_type_id;
                $dataSave[$key]['status']              = '0';
                $dataSave[$key]['created_by']          = USERID;
            }
            return !empty($dataSave) && DB::table('schedulings')->insert($dataSave) ? true : false;
        }
    }

    //Updating Sample Status of booked Order in  samples table
    function UpdateSampleStatusOfBookedSample($order_id)
    {
        $orderData = DB::table('order_master')->where('order_master.order_id', $order_id)->first();
        return !empty($orderData->sample_id) ? DB::table('samples')->where('samples.sample_id', $orderData->sample_id)->update(['samples.sample_booked_date' => defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s'), 'samples.sample_status' => '1']) : false;
    }

    //Getting Next Sampe Detail ID from samples table
    function getNextSampleForOrderBooking($sample_id)
    {
        $department_ids = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
        $dataObj = DB::table('samples')
            ->where('samples.sample_id', '<>', $sample_id)
            ->where('samples.customer_id', '<>', null)
            ->where('samples.sample_status', '0');
        if (!empty($department_ids)) {
            $dataObj->whereIn('samples.product_category_id', $department_ids);
        }
        $data = $dataObj->first();
        return !empty($data->sample_id) ? $data->sample_id : '0';
    }

    function getSundays($start, $end)
    {
        $timestamp1 = strtotime($start);
        $timestamp2 = strtotime($end);
        $sundays    = array();
        $oneDay     = 60 * 60 * 24;
        for ($i = $timestamp1; $i <= $timestamp2; $i += $oneDay) {
            $day = date('N', $i);
            if ($day == 7) {
                $sundays[] = date('Y-m-d', $i);
                $i += 6 * $oneDay;
            }
        }
        return array_filter($sundays);
    }

    //manage order log
    function updateOrderLog($orderId, $status, $orderStageDate = NULL)
    {
        $dataSave              = array();
        $dataSave['opl_order_id']      = $orderId;
        $dataSave['opl_order_status_id'] = $status;
        $dataSave['opl_date']          = !empty($orderStageDate) ? $orderStageDate : CURRENTDATETIME;
        $dataSave['opl_user_id']      = USERID;
        return !empty($dataSave) && DB::table('order_process_log')->insert($dataSave) ? true : false;
    }

    //manage order status and Order Log
    function updateOrderStausLog($orderId, $status, $orderStageDate = NULL)
    {
        if (!empty($orderId)) {
            DB::table('order_master')->where('order_id', $orderId)->update(['status' => $status]);
            DB::table('order_process_log')->where('opl_order_id', $orderId)->update(['opl_current_stage' => '0']);
            $dataSave                  = array();
            $dataSave['opl_order_id']          = $orderId;
            $dataSave['opl_order_status_id']     = $status;
            $dataSave['opl_date']         = !empty($orderStageDate) ? $orderStageDate : CURRENTDATETIME;
            $dataSave['opl_user_id']          = USERID;
            $dataSave['opl_current_stage']       = '1';
            return !empty($dataSave) && DB::table('order_process_log')->insert($dataSave) ? true : false;
        }
        return false;
    }

    //manage order status and Order Log
    function updateOrderStatusToNextPhase($orderId, $status, $orderStageDate = NULL)
    {
        if (!empty($orderId)) {
            DB::table('order_master')->where('order_id', $orderId)->update(['status' => $status]);
            DB::table('order_process_log')->where('opl_order_id', $orderId)->update(['opl_current_stage' => '0']);
            $dataSave                  = array();
            $dataSave['opl_order_id']          = $orderId;
            $dataSave['opl_order_status_id']     = $status;
            $dataSave['opl_date']         = !empty($orderStageDate) ? $orderStageDate : CURRENTDATETIME;
            $dataSave['opl_current_stage']       = '1';
            return !empty($dataSave) && DB::table('order_process_log')->insert($dataSave) ? true : false;
        }
        return false;
    }

    //manage order status and Order Log
    function updateOrderStausLogNote($orderId, $note)
    {
        if (!empty($orderId)) {
            $dataSave = DB::table('order_process_log')->where('opl_order_id', $orderId)->where('order_process_log.opl_current_stage', '=', '1')->update(['note' => $note]);
            return !empty($dataSave) ? true : false;
        }
        return false;
    }

    /**
     *
     * update amend status while dispatch order from reports
     *
     ***/
    function updateAmendStatus($orderId, $status)
    {
        $updateAmendStatus = DB::table('order_dispatch_dtl')->where('order_id', '=', $orderId)->update(['amend_status' => $status]);
        return !empty($updateAmendStatus) ? true : false;
    }

    //generate report number in order_report_details table
    function generateReportNumber($orderId)
    {
        if (!empty($orderId)) {
            $dataSave = array();
            $reportData = array();
            $orderNumber = $this->getOrderDetail($orderId);
            if (!empty($orderNumber)) {
                $reportData['report_no']   = REPORT_PREFIX . $orderNumber->order_no;
                $reportData['report_date'] = CURRENTDATETIME;
                $dataSave = DB::table('order_report_details')->where('report_id', $orderId)->update($reportData);
            }
            return !empty($dataSave) ? true : false;
        }
        return false;
    }

    //manage error test report by when reporter need modification
    function updateOrderStausLogErrorTestReport($analysisArr, $order_id)
    {
        if (!empty($analysisArr)) {
            foreach ($analysisArr as $analysis_id) {
                $dataSave[$analysis_id] = DB::table('order_parameters_detail')->where('analysis_id', $analysis_id)->update(['test_result' => null]);
                DB::table('schedulings')->where('schedulings.order_parameter_id', $analysis_id)->update(['schedulings.status' => '1', 'notes' => null, 'completed_at' => null]);
            }
            //Updating Log Detail of Error Analysis Ids
            DB::table('order_process_log')->where('opl_order_id', $order_id)->where('order_process_log.opl_current_stage', '=', '1')->update(['error_parameter_ids' => implode(',', $analysisArr)]);

            //Resetting the test_completion_date in Order Master Table
            DB::table('order_master')->where('order_master.order_id', $order_id)->update(['order_master.test_completion_date' => NULL]);
        }
        return !empty($dataSave) ? true : false;
    }

    //Getting Order Track Stage
    function getOrderTrackRecord($orderId)
    {
        $defaultOrderTrack = DB::table('order_status')->select('order_status_id', 'order_status_name', 'order_status_title')->where('status', '1')->get();
        if (!empty($defaultOrderTrack)) {
            foreach ($defaultOrderTrack as $key => $defaultTrack) {
                $orderProcessLog = DB::table('order_process_log')->select('opl_order_status_id')->where('opl_order_id', $orderId)->where('opl_current_stage', '1')->first();
                $defaultTrack->track = null;
                if (!empty($defaultTrack->order_status_id) && !empty($orderProcessLog->opl_order_status_id) && $defaultTrack->order_status_id <= $orderProcessLog->opl_order_status_id) {
                    $stageTrackRecords = DB::table('order_process_log')->select('opl_id', 'opl_date', 'opl_current_stage')->where('opl_order_id', $orderId)->where('opl_order_status_id', $defaultTrack->order_status_id)->orderBy('opl_id', 'DESC')->first();
                    if (!empty($stageTrackRecords)) {
                        $stageTrackRecords->opl_date = !empty($stageTrackRecords->opl_date) ? date(DATETIMEFORMAT, strtotime($stageTrackRecords->opl_date)) : '';
                        $defaultTrack->track = $stageTrackRecords;
                    }
                }
                $defaultTrack->order_status_name = !empty($defaultTrack->track) && isset($defaultTrack->track->opl_current_stage) && $defaultTrack->track->opl_current_stage == '0' ? $defaultTrack->order_status_title : $defaultTrack->order_status_name;
            }
        }
        return $defaultOrderTrack;
    }

    //Getting Order log
    function getOrderLogRecord($orderId)
    {
        $orderLogList = DB::table('order_process_log')
            ->join('order_master', 'order_master.order_id', 'order_process_log.opl_order_id')
            ->join('order_status', 'order_status.order_status_id', 'order_process_log.opl_order_status_id')
            ->leftJoin('users', 'users.id', 'order_process_log.opl_user_id')
            ->select('order_process_log.*', 'order_master.order_no', 'users.name', 'order_status.order_status_name')
            ->where('order_process_log.opl_order_id', $orderId)
            ->orderBy('order_process_log.opl_id', 'desc')
            ->get();
        return $orderLogList;
    }

    /**
     * get Order Details
     * Date :
     * Author :
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createAliaAndUpdateOrderSampleName($submitedRawData)
    {

        //parsing the raw form data
        parse_str($submitedRawData, $submitedData);

        $dataSave            = array();
        $dataSave['c_product_name']     = trim($submitedData['sample_description']);
        $dataSave['product_id']     = $submitedData['product_id'];
        $dataSave['created_by']     = USERID;
        $dataSave['view_type']         = '1';
        $dataSave['c_product_status']     = '1';

        $data = DB::table('product_master_alias')->where('product_master_alias.c_product_name', '=', trim($submitedData['sample_description']))->where('product_master_alias.product_id', '=', trim($submitedData['product_id']))->first();
        return empty($data) ? DB::table('product_master_alias')->insertGetId($dataSave) : $data->c_product_id;
    }

    /**
     * get Order Details
     * Date :
     * Author :
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSampleDescriptionDetail($sample_description, $allCategotyIds, $invoicing_type_id, $product_category_id)
    {

        $data = DB::table('product_master_alias')
            ->join('product_master', 'product_master.product_id', 'product_master_alias.product_id')
            ->join('product_categories', 'product_categories.p_category_id', 'product_master.p_category_id')
            ->join('customer_invoicing_rates', 'customer_invoicing_rates.cir_c_product_id', 'product_master_alias.c_product_id')
            ->select('product_master_alias.c_product_id', 'product_master_alias.c_product_name')
            ->where('customer_invoicing_rates.invoicing_type_id', $invoicing_type_id)
            ->where('customer_invoicing_rates.cir_product_category_id', $product_category_id)
            ->whereIn('product_master.p_category_id', $allCategotyIds)
            ->where('product_master_alias.c_product_name', '=', trim($sample_description))
            ->first();

        return !empty($data) ? $data->c_product_id : '0';
    }

    /**
     * Get list of companies on page load.
     * Date : 01-03-17
     * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getBookedSamplePrice($order_id)
    {

        global $order, $models;

        $returnData = $parameterWiseRateData = array();

        $invoicingPriceAllocated = '0';

        //getting Order data*****************************************
        $orderData               = DB::table('order_master')->where('order_master.order_id', '=', $order_id)->first();
        $customer_id         = !empty($orderData->customer_id) ? $orderData->customer_id : '0';
        $customerData          = DB::table('customer_master')->where('customer_master.customer_id', '=', $customer_id)->first();
        $invoicingTypeId     = !empty($orderData->invoicing_type_id) ? $orderData->invoicing_type_id : '0';
        $division_id         = !empty($orderData->division_id) ? $orderData->division_id : '0';
        $product_category_id = !empty($orderData->product_category_id) ? $orderData->product_category_id : '0';
        $order_sample_type      = !empty($orderData->order_sample_type) ? $orderData->order_sample_type : '0';
        $customer_city         = !empty($orderData->customer_city) ? $orderData->customer_city : '0';
        $customer_state         = !empty($customerData->customer_state) ? $customerData->customer_state : '0';

        //Checking Order Amount in case of inter-laboratory and Compensatory orders
        if (empty($order_sample_type)) {

            //Conditional Invoicing Type*********************************
            if (!empty($invoicingTypeId) && !empty($product_category_id)) {
                if ($invoicingTypeId == '1') {            //ITC Parameter Wise
                    $invoicingPriceAllocated = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $order_id)->sum('selling_price');
                } else if ($invoicingTypeId == '2') {        //State Wise Product
                    $invoicingData = DB::table('customer_invoicing_rates')
                        ->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicingTypeId)
                        ->where('customer_invoicing_rates.cir_state_id', '=', $customer_state)
                        ->where('customer_invoicing_rates.cir_c_product_id', '=', $orderData->sample_description_id)
                        ->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
                        ->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
                        ->first();
                    $invoicingPriceAllocated = !empty($invoicingData) ? $invoicingData->invoicing_rate : '0';
                } else if ($invoicingTypeId == '3') {        //Customer Wise Product or Fixed rate party

                    //In case of fixed Rate Party
                    $invoicingData = DB::table('customer_invoicing_rates')
                        ->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicingTypeId)
                        ->where('customer_invoicing_rates.cir_customer_id', '=', $customer_id)
                        ->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
                        ->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
                        ->whereNull('customer_invoicing_rates.cir_c_product_id')
                        ->first();

                    //If Product ID is not null,then Customer Wise Product
                    if (empty($invoicingData)) {
                        $invoicingData = DB::table('customer_invoicing_rates')
                            ->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicingTypeId)
                            ->where('customer_invoicing_rates.cir_city_id', '=', $customer_city)
                            ->where('customer_invoicing_rates.cir_customer_id', '=', $customer_id)
                            ->where('customer_invoicing_rates.cir_c_product_id', '=', $orderData->sample_description_id)
                            ->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
                            ->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
                            ->first();
                    }
                    $invoicingPriceAllocated = !empty($invoicingData) ? $invoicingData->invoicing_rate : '0';
                } else if ($invoicingTypeId == '4') {        //Customer Wise Parameters

                    //getting order parameters of a customers
                    $orderParametersDetail = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $order_id)->whereNotNull('equipment_type_id')->get();

                    if ($product_category_id == '2') {
                        $invoicingPriceAllocated = $this->getCustomerWiseAssayParameterRates($invoicingTypeId, $customer_id, $division_id, $product_category_id, $models->convertObjectToArray($orderParametersDetail), $returnType = array());
                    } else {
                        $invoicingPriceAllocated = $this->getCustomerWiseParameterRates($invoicingTypeId, $customer_id, $division_id, $product_category_id, $models->convertObjectToArray($orderParametersDetail), $returnType = array());
                    }
                }
            }
        }
        return $invoicingPriceAllocated;
    }

    /**
     * Get list of companies on page load.
     * Date : 01-03-17
     * Author : Praveen Singh
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkAddCustomerInvoivingRate($sample_description_id, $rawPostedData)
    {

        global $order, $models;

        $postedData = $orderParametersDetail = array();
        $invoicingPriceAllocated = '0';

        //Getting order parameters of a Customer
        parse_str($rawPostedData, $postedData);

        if (!empty($postedData['order_sample_type'])) {
            return true;
        } elseif (!empty($postedData['booked_order_amount'])) {
            return true;
        } else {

            $customer_id         = !empty($postedData['customer_id']) ? $postedData['customer_id'] : '0';
            $division_id         = !empty($postedData['division_id']) ? $postedData['division_id'] : '0';
            $invoicing_type_id      = !empty($postedData['invoicing_type_id']) ? $postedData['invoicing_type_id'] : '0';
            $product_category_id = !empty($postedData['product_category_id']) ? $models->getMainProductCatParentId($postedData['product_category_id']) : '0';

            if (!empty($customer_id) && !empty($invoicing_type_id) && !empty($product_category_id)) {

                //getting customer data**************************************
                $customerData = DB::table('customer_master')->where('customer_master.customer_id', '=', $customer_id)->first();

                if (!empty($customerData)) {

                    //Conditional Invoicing Type*********************************
                    if ($invoicing_type_id == '1') {            //ITC Parameter Wise
                        $invoicingPriceAllocated = !empty($postedData['order_parameters_detail']['test_parameter_id']) ? true : false;
                    } else if ($invoicing_type_id == '2') {        //State Wise Product
                        $invoicingData = DB::table('customer_invoicing_rates')
                            ->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
                            ->where('customer_invoicing_rates.cir_state_id', '=', $customerData->customer_state)
                            ->where('customer_invoicing_rates.cir_c_product_id', '=', $sample_description_id)
                            ->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
                            ->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
                            ->first();
                        $invoicingPriceAllocated = !empty($invoicingData) ? $invoicingData->invoicing_rate : '0';
                    } else if ($invoicing_type_id == '3') {        //Customer Wise Product or Fixed rate party

                        //In case of fixed Rate Party
                        $invoicingData = DB::table('customer_invoicing_rates')
                            ->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
                            ->where('customer_invoicing_rates.cir_customer_id', '=', $customerData->customer_id)
                            ->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
                            ->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
                            ->whereNull('customer_invoicing_rates.cir_c_product_id')
                            ->first();

                        //If Product ID is not null,then Customer Wise Product
                        if (empty($invoicingData)) {
                            $invoicingData = DB::table('customer_invoicing_rates')
                                ->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
                                ->where('customer_invoicing_rates.cir_city_id', '=', $customerData->customer_city)
                                ->where('customer_invoicing_rates.cir_customer_id', '=', $customerData->customer_id)
                                ->where('customer_invoicing_rates.cir_c_product_id', '=', $sample_description_id)
                                ->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
                                ->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
                                ->first();
                        }
                        $invoicingPriceAllocated = !empty($invoicingData) ? $invoicingData->invoicing_rate : '0';
                    } else if ($invoicing_type_id == '4') {        //Customer Wise Parameters
                        if (!empty($postedData['order_parameters_detail'])) {
                            foreach ($postedData['order_parameters_detail'] as $keyParameter => $orderParametersData) {
                                foreach ($orderParametersData as $key => $values) {
                                    $orderParametersDetail[$key][$keyParameter] = empty($values) ? null : $values;
                                }
                            }
                        }
                        if ($product_category_id == '2') {
                            $invoicingPriceAllocated = $this->getCustomerWiseAssayParameterRates($invoicing_type_id, $customerData->customer_id, $division_id, $product_category_id, $orderParametersDetail, $returnType = array());
                        } else {
                            $invoicingPriceAllocated = $this->getCustomerWiseParameterRates($invoicing_type_id, $customerData->customer_id, $division_id, $product_category_id, $orderParametersDetail, $returnType = array());
                        }
                    }
                }
            }
        }
        return $invoicingPriceAllocated;
    }

    /**
     * Get list of companies on page load.
     * Date : 01-03-17
     * Author : Praveen Singh
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkEditCustomerInvoivingRate($sample_description_id, $rawPostedData)
    {

        global $order, $models;

        $parameterWiseRateData   = $postedData = $orderParametersDetailNew = array();
        $invoicingPriceAllocated = '0';

        //Getting Order Parameters of a Customer
        parse_str($rawPostedData, $postedData);

        if (!empty($postedData['order_sample_type'])) {
            return true;
        } else {

            $order_id            = !empty($postedData['order_id']) ? $postedData['order_id'] : '0';
            $customer_id       = !empty($postedData['customer_id']) ? $postedData['customer_id'] : '0';
            $invoicing_type_id = !empty($postedData['invoicing_type_id']) ? $postedData['invoicing_type_id'] : '0';

            if (!empty($order_id) && !empty($customer_id) && !empty($invoicing_type_id)) {

                //getting Order data*****************************************
                $orderData            = DB::table('order_master')->where('order_master.order_id', '=', $order_id)->first();
                $division_id            = !empty($orderData->division_id) ? $orderData->division_id : '0';
                $product_category_id     = !empty($orderData->product_category_id) ? $orderData->product_category_id : '0';

                //getting customer data**************************************
                $customerData = DB::table('customer_master')->where('customer_master.customer_id', '=', $customer_id)->first();

                if (!empty($customerData)) {

                    if ($invoicing_type_id == '1') {            //ITC Parameter Wise
                        $invoicingPriceAllocated = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $order_id)->sum('selling_price');
                    } else if ($invoicing_type_id == '2') {        //State Wise Product
                        $invoicingData = DB::table('customer_invoicing_rates')
                            ->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
                            ->where('customer_invoicing_rates.cir_state_id', '=', $customerData->customer_state)
                            ->where('customer_invoicing_rates.cir_c_product_id', '=', $sample_description_id)
                            ->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
                            ->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
                            ->first();
                        $invoicingPriceAllocated = !empty($invoicingData) ? $invoicingData->invoicing_rate : '0';
                    } else if ($invoicing_type_id == '3') {        //Customer Wise Product or Fixed rate party

                        //In case of fixed Rate Party
                        $invoicingData = DB::table('customer_invoicing_rates')
                            ->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
                            ->where('customer_invoicing_rates.cir_customer_id', '=', $customerData->customer_id)
                            ->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
                            ->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
                            ->whereNull('customer_invoicing_rates.cir_c_product_id')
                            ->first();

                        //If Product ID is not null,then Customer Wise Product
                        if (empty($invoicingData)) {
                            $invoicingData = DB::table('customer_invoicing_rates')
                                ->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
                                ->where('customer_invoicing_rates.cir_c_product_id', '=', $sample_description_id)
                                ->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
                                ->where('customer_invoicing_rates.cir_customer_id', '=', $customerData->customer_id)
                                ->where('customer_invoicing_rates.cir_city_id', '=', $customerData->customer_city)
                                ->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
                                ->first();
                        }
                        $invoicingPriceAllocated = !empty($invoicingData) ? $invoicingData->invoicing_rate : '0';
                    } else if ($invoicing_type_id == '4') {        //Customer Wise Parameters

                        //Existing Added Parameters
                        $orderParametersDetail = $this->getOrderEditParameterDetail($order_id, $postedData);

                        if ($product_category_id == '2') {
                            $invoicingPriceAllocated = $this->getCustomerWiseAssayParameterRates($invoicing_type_id, $customerData->customer_id, $division_id, $product_category_id, $models->convertObjectToArray($orderParametersDetail), $returnType = array());
                        } else {
                            $invoicingPriceAllocated = $this->getCustomerWiseParameterRates($invoicing_type_id, $customerData->customer_id, $division_id, $product_category_id, $models->convertObjectToArray($orderParametersDetail), $returnType = array());
                        }
                    }
                }
            }
        }
        return $invoicingPriceAllocated;
    }

    /**
     * Get order parameter and test_parameter_parent
     * Date : 16-August-2018
     * Author : Ruby
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getOrderEditParameterDetail($order_id, $postedData)
    {

        $orderParametersDetail = $orderParametersDetailNew = array();

        //Newly Added Parameters
        if (!empty($postedData['order_parameters_detail'])) {
            foreach ($postedData['order_parameters_detail'] as $key => $values) {
                $key = str_replace("'", "", $key);
                if ($key == 'new') {
                    $orderParametersDetailNew = $values;
                }
            }
        }

        //Existing Added Parameters
        $orderParametersDetailExisting = DB::table('order_parameters_detail')->join('test_parameter', 'test_parameter.test_parameter_id', '=', 'order_parameters_detail.test_parameter_id')->where('order_parameters_detail.order_id', $order_id)->whereNotNull('equipment_type_id')->get()->toArray();

        //Merging of Parameters
        if (!empty($orderParametersDetailNew) && !empty($orderParametersDetailExisting)) {
            $orderParametersDetail = array_values(array_merge($orderParametersDetailExisting, $orderParametersDetailNew));
        } else {
            $orderParametersDetail = $orderParametersDetailExisting;
        }
        return $orderParametersDetail;
    }

    /**
     * Get order parameter and test_parameter_parent
     * Date : 16-August-2018
     * Author : Ruby
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCustomerWiseParameterRates($invoicing_type_id, $customer_id, $division_id, $product_category_id, $orderParametersDetail, $returnType)
    {

        global $order, $models;

        $parameterWiseRateData = array();
        $invoicingRate            = 0;

        if (!empty($orderParametersDetail)) {
            foreach ($orderParametersDetail as $key => $orderParameters) {
                $isInvoicingNeeded = DB::table('test_parameter')->where('test_parameter.test_parameter_id', $orderParameters['test_parameter_id'])->where('test_parameter.test_parameter_invoicing', '1')->first();
                if (!empty($isInvoicingNeeded) && !empty($orderParameters['test_parameter_id']) && !empty($orderParameters['equipment_type_id'])) {
                    $parameterWiseRate = DB::table('customer_invoicing_rates')
                        ->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
                        ->where('customer_invoicing_rates.cir_customer_id', '=', $customer_id)
                        ->where('customer_invoicing_rates.cir_parameter_id', '=', $orderParameters['test_parameter_id'])
                        ->where('customer_invoicing_rates.cir_equipment_type_id', '=', $orderParameters['equipment_type_id'])
                        ->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
                        ->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
                        ->first();
                    $parameterWiseRateData[$orderParameters['test_parameter_id']] = !empty($parameterWiseRate->invoicing_rate) ? $parameterWiseRate->invoicing_rate : '0';
                }
            }
            $invoicingRate = in_array('0', $parameterWiseRateData) ? '0' : array_sum($parameterWiseRateData);
        }
        return empty($returnType) ? $invoicingRate : $parameterWiseRateData;
    }

    /**
     * Get Customer Wise Assay Parameter Rates
     * Date : 12-April-2018
     * Author : Praveen Singh
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCustomerWiseAssayParameterRates($invoicing_type_id, $customer_id, $division_id, $product_category_id, $orderParametersDetail, $returnType)
    {

        global $order, $models;

        $parameterWiseRateData = $paramterInvoicingWithCount = $withDectorsTestCategory = $withDectorsAssayCategory = $withoutDectorsTestCategory = $withoutDectorsTestParentCategory = $withoutDectorsAssayCategory = $withoutDectorsTestCategory = $noOfInjectionWithDectorsCategory = $withDectorsTestCategoryInfo = $withDectorsAssayCategoryInfo = $withoutDectorsAssayCategoryInfo = $withoutDectorsTestParentCategoryInfo = array();
        $invoicingRate            = '0';

        if (!empty($orderParametersDetail)) {
            foreach ($orderParametersDetail as $key => $values) {
                if (!empty($values['order_id'])) {     //In case of Editing of Order		    
                    $subValues = DB::table('order_parameters_detail')
                        ->select('parentProductCategoryDB.p_category_id as product_category_id', 'productCategoryDB.p_category_id', 'subProductCategoryDB.p_category_id as sub_p_category_id', 'test_parameter_categories.test_para_cat_id as test_parameter_category_id')
                        ->join('order_master', 'order_master.order_id', 'order_parameters_detail.order_id')
                        ->join('product_master', 'product_master.product_id', 'order_master.product_id')
                        ->join('product_categories as subProductCategoryDB', 'subProductCategoryDB.p_category_id', 'product_master.p_category_id')
                        ->join('product_categories as productCategoryDB', 'productCategoryDB.p_category_id', 'subProductCategoryDB.parent_id')
                        ->join('product_categories as parentProductCategoryDB', 'parentProductCategoryDB.p_category_id', 'productCategoryDB.parent_id')
                        ->join('test_parameter', 'test_parameter.test_parameter_id', 'order_parameters_detail.test_parameter_id')
                        ->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
                        ->where('order_parameters_detail.order_id', $values['order_id'])
                        ->where('order_parameters_detail.product_test_parameter', $values['product_test_parameter'])
                        ->where('order_parameters_detail.test_parameter_id', $values['test_parameter_id'])
                        ->first();
                } else {                     //In case of adding of Order
                    $subValues = DB::table('product_test_dtl')
                        ->select('parentProductCategoryDB.p_category_id as product_category_id', 'productCategoryDB.p_category_id', 'subProductCategoryDB.p_category_id as sub_p_category_id', 'test_parameter_categories.test_para_cat_id as test_parameter_category_id')
                        ->join('product_test_hdr', 'product_test_hdr.test_id', 'product_test_dtl.test_id')
                        ->join('product_master', 'product_master.product_id', 'product_test_hdr.product_id')
                        ->join('product_categories as subProductCategoryDB', 'subProductCategoryDB.p_category_id', 'product_master.p_category_id')
                        ->join('product_categories as productCategoryDB', 'productCategoryDB.p_category_id', 'subProductCategoryDB.parent_id')
                        ->join('product_categories as parentProductCategoryDB', 'parentProductCategoryDB.p_category_id', 'productCategoryDB.parent_id')
                        ->join('test_parameter', 'test_parameter.test_parameter_id', 'product_test_dtl.test_parameter_id')
                        ->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
                        ->where('product_test_dtl.product_test_dtl_id', $values['product_test_parameter'])
                        ->where('test_parameter.test_parameter_id', $values['test_parameter_id'])
                        ->first();
                }
                //Merging Values and Sub Vaules
                $orderParameters = !empty($subValues) ? array_merge($values, $models->convertObjectToArray($subValues)) : $values;
                if (!empty($orderParameters)) {
                    //Checking the global Invoicing allowed to the parameters
                    $isInvoicingNeeded = DB::table('test_parameter')->where('test_parameter.test_parameter_id', $orderParameters['test_parameter_id'])->where('test_parameter.test_parameter_invoicing', '1')->first();
                    if (!empty($isInvoicingNeeded) && !empty($orderParameters['test_parameter_id']) && !empty($orderParameters['equipment_type_id'])) {
                        if (!empty($orderParameters['detector_id']) && !empty($orderParameters['running_time_id']) && !empty($orderParameters['no_of_injection'])) {    //checking If Detector,Running Time,no of Injection exist
                            if (!empty($orderParameters['test_parameter_category_id']) && $orderParameters['test_parameter_category_id'] == '1') {
                                if (!empty($orderParameters['test_parameter_invoicing_parent_id'])) {
                                    $groupedColoumName = $orderParameters['test_parameter_category_id'] . '-' . $orderParameters['test_parameter_invoicing_parent_id'] . '-' . $orderParameters['product_category_id'] . '-' . $orderParameters['p_category_id'] . '-' . $orderParameters['sub_p_category_id'] . '-' . $orderParameters['equipment_type_id'] . '-' . $orderParameters['detector_id'] . '-' . $orderParameters['running_time_id'];
                                    $noOfInjectionWithDectorsCategory[$groupedColoumName][]     = $orderParameters['no_of_injection'];
                                    $orderParameters['no_of_per_injection']                   = '1';
                                    $withDectorsTestCategory[$groupedColoumName][]         = $orderParameters;
                                } else {
                                    $withoutDectorsTestCategory[$orderParameters['product_test_parameter']] = $orderParameters;
                                }
                            } else {
                                $groupedColoumName = $orderParameters['test_parameter_category_id'] . '-' . $orderParameters['product_category_id'] . '-' . $orderParameters['p_category_id'] . '-' . $orderParameters['sub_p_category_id'] . '-' . $orderParameters['equipment_type_id'] . '-' . $orderParameters['detector_id'] . '-' . $orderParameters['running_time_id'];
                                $noOfInjectionWithDectorsCategory[$groupedColoumName][]     = $orderParameters['no_of_injection'];
                                $orderParameters['no_of_per_injection']                 = '1';
                                $withDectorsAssayCategory[$groupedColoumName][]         = $orderParameters;
                            }
                        } else {
                            if (!empty($orderParameters['test_parameter_category_id'])) {
                                if (!empty($orderParameters['test_parameter_category_id']) && $orderParameters['test_parameter_category_id'] == '1') {
                                    if (!empty($orderParameters['test_parameter_invoicing_parent_id'])) {
                                        $groupedColoumName = $orderParameters['test_parameter_category_id'] . '-' . $orderParameters['test_parameter_invoicing_parent_id'] . '-' . $orderParameters['product_category_id'] . '-' . $orderParameters['p_category_id'] . '-' . $orderParameters['sub_p_category_id'] . '-' . $orderParameters['equipment_type_id'];
                                        $withoutDectorsTestParentCategory[$groupedColoumName][] = $orderParameters;
                                    } else {
                                        $withoutDectorsTestCategory[$orderParameters['product_test_parameter']] = $orderParameters;
                                    }
                                } else {
                                    $groupedColoumName = $orderParameters['test_parameter_category_id'] . '-' . $orderParameters['product_category_id'] . '-' . $orderParameters['p_category_id'] . '-' . $orderParameters['sub_p_category_id'] . '-' . $orderParameters['equipment_type_id'];
                                    $withoutDectorsAssayCategory[$groupedColoumName][] = $values;
                                }
                            }
                        }
                    }
                }
            }

            //Calculating Rates of Test Parameter Category with Detector,Running Time,no of Injection
            if (!empty($withDectorsTestCategory)) {
                foreach ($withDectorsTestCategory as $nestedkeyWithIds => $values) {
                    $withDectorsTestCategoryInfo[$nestedkeyWithIds]['equipment_count']         = is_array($values) ? count($values) : 0;
                    $withDectorsTestCategoryInfo[$nestedkeyWithIds]['total_injection_count']     = isset($noOfInjectionWithDectorsCategory[$nestedkeyWithIds]) && is_array($noOfInjectionWithDectorsCategory) ? $noOfInjectionWithDectorsCategory[$nestedkeyWithIds] : 0;
                    $withDectorsTestCategoryInfo[$nestedkeyWithIds]['invoicing']         = current($values);
                }
                foreach ($withDectorsTestCategoryInfo as $nestedkeyWithIds => $values) {
                    $keyTestData                 = $models->getExplodedData($nestedkeyWithIds, '-');
                    $test_parameter_category_id         = !empty($keyTestData[0]) ? $keyTestData[0] : '0';
                    $test_parameter_invoicing_parent_id     = !empty($keyTestData[1]) ? $keyTestData[1] : '0';
                    $testParameterInvoicingParentData         = DB::table('test_parameter_invoicing_parents')->where('test_parameter_invoicing_parents.tpip_id', $test_parameter_invoicing_parent_id)->first();
                    $product_category_id             = !empty($keyTestData[2]) ? $keyTestData[2] : '0';
                    $p_category_id                 = !empty($keyTestData[3]) ? $keyTestData[3] : '0';
                    $sub_p_category_id                 = !empty($keyTestData[4]) ? $keyTestData[4] : '0';
                    $equipment_type_id                 = !empty($keyTestData[5]) ? $keyTestData[5] : '0';
                    $detector_id                 = !empty($keyTestData[6]) ? $keyTestData[6] : '0';
                    $running_time_id                 = !empty($keyTestData[7]) ? $keyTestData[7] : '0';
                    $no_of_per_injection             = !empty($values['invoicing']['no_of_per_injection']) ? trim($values['invoicing']['no_of_per_injection']) : 0;
                    if ($test_parameter_invoicing_parent_id == 1) {
                        $cir_equipment_count            = !empty($values['equipment_count']) && $values['equipment_count'] == '1' ? '1' : '2';
                    } else {
                        $cir_equipment_count            = !empty($values['equipment_count']) ? $values['equipment_count'] : '0';
                    }
                    $total_injection_count            = !empty($values['total_injection_count']) ? array_sum($values['total_injection_count']) : '0';
                    $parameterWiseRate = DB::table('customer_invoicing_rates')
                        ->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
                        ->where('customer_invoicing_rates.cir_customer_id', '=', $customer_id)
                        ->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
                        ->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
                        ->where('customer_invoicing_rates.cir_p_category_id', '=', $p_category_id)
                        ->where('customer_invoicing_rates.cir_sub_p_category_id', '=', $sub_p_category_id)
                        ->where('customer_invoicing_rates.cir_test_parameter_category_id', '=', $test_parameter_category_id)
                        ->where('customer_invoicing_rates.cir_parameter_id', '=', $testParameterInvoicingParentData->test_parameter_id)
                        ->where('customer_invoicing_rates.cir_equipment_type_id', '=', $equipment_type_id)
                        ->where('customer_invoicing_rates.cir_equipment_count', '=', $cir_equipment_count)
                        ->where('customer_invoicing_rates.cir_detector_id', '=', $detector_id)
                        ->where('customer_invoicing_rates.cir_running_time_id', '=', $running_time_id)
                        ->where('customer_invoicing_rates.cir_no_of_injection', '=', $no_of_per_injection)
                        ->where('customer_invoicing_rates.cir_is_detector', '=', '1')
                        ->first();
                    $parameterWiseRateData[$nestedkeyWithIds] = !empty($parameterWiseRate->invoicing_rate) ? $parameterWiseRate->invoicing_rate * $total_injection_count : '0';
                }
            }

            //Calculating Rates of Test Parameter Parent Category without Detector,Running Time,no of Injection
            if (!empty($withoutDectorsTestParentCategory)) {
                foreach ($withoutDectorsTestParentCategory as $nestedkeyWithIds => $values) {
                    $withoutDectorsTestParentCategoryInfo[$nestedkeyWithIds]['equipment_count']     = is_array($values) ? count($values) : 0;
                    $withoutDectorsTestParentCategoryInfo[$nestedkeyWithIds]['total_injection_count']     = isset($noOfInjectionWithDectorsCategory[$nestedkeyWithIds]) && is_array($noOfInjectionWithDectorsCategory) ? $noOfInjectionWithDectorsCategory[$nestedkeyWithIds] : 0;
                    $withoutDectorsTestParentCategoryInfo[$nestedkeyWithIds]['invoicing']         = current($values);
                }
                foreach ($withoutDectorsTestParentCategoryInfo as $nestedkeyWithIds => $values) {
                    $keyTestData                 = $models->getExplodedData($nestedkeyWithIds, '-');
                    $test_parameter_category_id         = !empty($keyTestData[0]) ? $keyTestData[0] : '0';
                    $test_parameter_invoicing_parent_id     = !empty($keyTestData[1]) ? $keyTestData[1] : '0';
                    $testParameterInvoicingParentData         = DB::table('test_parameter_invoicing_parents')->where('test_parameter_invoicing_parents.tpip_id', $test_parameter_invoicing_parent_id)->first();
                    $product_category_id             = !empty($keyTestData[2]) ? $keyTestData[2] : '0';
                    $p_category_id                 = !empty($keyTestData[3]) ? $keyTestData[3] : '0';
                    $sub_p_category_id                 = !empty($keyTestData[4]) ? $keyTestData[4] : '0';
                    $equipment_type_id                 = !empty($keyTestData[5]) ? $keyTestData[5] : '0';
                    if ($test_parameter_invoicing_parent_id == 1) {
                        $cir_equipment_count            = !empty($values['equipment_count']) && $values['equipment_count'] == '1' ? '1' : '2';
                    } else {
                        $cir_equipment_count            = !empty($values['equipment_count']) ? $values['equipment_count'] : '0';
                    }
                    $parameterWiseRate = DB::table('customer_invoicing_rates')
                        ->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
                        ->where('customer_invoicing_rates.cir_customer_id', '=', $customer_id)
                        ->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
                        ->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
                        ->where('customer_invoicing_rates.cir_p_category_id', '=', $p_category_id)
                        ->where('customer_invoicing_rates.cir_sub_p_category_id', '=', $sub_p_category_id)
                        ->where('customer_invoicing_rates.cir_test_parameter_category_id', '=', $test_parameter_category_id)
                        ->where('customer_invoicing_rates.cir_parameter_id', '=', $testParameterInvoicingParentData->test_parameter_id)
                        ->where('customer_invoicing_rates.cir_equipment_type_id', '=', $equipment_type_id)
                        ->where('customer_invoicing_rates.cir_equipment_count', '=', $cir_equipment_count)
                        ->where('customer_invoicing_rates.cir_is_detector', '=', '2')
                        ->first();
                    $parameterWiseRateData[$nestedkeyWithIds] = !empty($parameterWiseRate->invoicing_rate) ? $parameterWiseRate->invoicing_rate : '0';
                }
            }

            //Calculating Rates of Test Parameter Category without Detector,Running Time,no of Injection
            if (!empty($withoutDectorsTestCategory)) {
                foreach ($withoutDectorsTestCategory as $key => $values) {
                    $parameterWiseRate = DB::table('customer_invoicing_rates')
                        ->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
                        ->where('customer_invoicing_rates.cir_customer_id', '=', $customer_id)
                        ->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
                        ->where('customer_invoicing_rates.cir_parameter_id', '=', $values['test_parameter_id'])
                        ->where('customer_invoicing_rates.cir_equipment_type_id', '=', $values['equipment_type_id'])
                        ->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
                        ->first();
                    $parameterWiseRateData[$values['test_parameter_id']] = !empty($parameterWiseRate->invoicing_rate) ? $parameterWiseRate->invoicing_rate : '0';
                }
            }

            //Calculating Rates of Assay Parameter Category with Detector,Running Time,no of Injection
            if (!empty($withDectorsAssayCategory)) {
                foreach ($withDectorsAssayCategory as $nestedkeyWithIds => $values) {
                    $withDectorsAssayCategoryInfo[$nestedkeyWithIds]['equipment_count']     = is_array($values) ? count($values) : 0;
                    $withDectorsAssayCategoryInfo[$nestedkeyWithIds]['total_injection_count']     = isset($noOfInjectionWithDectorsCategory[$nestedkeyWithIds]) && is_array($noOfInjectionWithDectorsCategory) ? $noOfInjectionWithDectorsCategory[$nestedkeyWithIds] : 0;
                    $withDectorsAssayCategoryInfo[$nestedkeyWithIds]['invoicing']         = current($values);
                }
                foreach ($withDectorsAssayCategoryInfo as $nestedkeyWithIds => $values) {
                    $keyAssayData                 = $models->getExplodedData($nestedkeyWithIds, '-');
                    $test_parameter_category_id         = !empty($keyAssayData[0]) ? $keyAssayData[0] : '0';
                    $product_category_id             = !empty($keyAssayData[1]) ? $keyAssayData[1] : '0';
                    $p_category_id                 = !empty($keyAssayData[2]) ? $keyAssayData[2] : '0';
                    $sub_p_category_id                 = !empty($keyAssayData[3]) ? $keyAssayData[3] : '0';
                    $equipment_type_id                 = !empty($keyAssayData[4]) ? $keyAssayData[4] : '0';
                    $detector_id                 = !empty($keyAssayData[5]) ? $keyAssayData[5] : '0';
                    $running_time_id                 = !empty($keyAssayData[6]) ? $keyAssayData[6] : '0';
                    $no_of_per_injection             = !empty($values['invoicing']['no_of_per_injection']) ? trim($values['invoicing']['no_of_per_injection']) : 0;
                    $cir_equipment_count            = !empty($values['equipment_count']) ? $values['equipment_count'] : '0';
                    $total_injection_count            = !empty($values['total_injection_count']) ? array_sum($values['total_injection_count']) : '0';
                    $parameterWiseRate = DB::table('customer_invoicing_rates')
                        ->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
                        ->where('customer_invoicing_rates.cir_customer_id', '=', $customer_id)
                        ->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
                        ->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
                        ->where('customer_invoicing_rates.cir_p_category_id', '=', $p_category_id)
                        ->where('customer_invoicing_rates.cir_sub_p_category_id', '=', $sub_p_category_id)
                        ->where('customer_invoicing_rates.cir_test_parameter_category_id', '=', $test_parameter_category_id)
                        ->where('customer_invoicing_rates.cir_equipment_type_id', '=', $equipment_type_id)
                        ->where('customer_invoicing_rates.cir_equipment_count', '=', $cir_equipment_count)
                        ->where('customer_invoicing_rates.cir_detector_id', '=', $detector_id)
                        ->where('customer_invoicing_rates.cir_running_time_id', '=', $running_time_id)
                        ->where('customer_invoicing_rates.cir_no_of_injection', '=', $no_of_per_injection)
                        ->where('customer_invoicing_rates.cir_is_detector', '=', '1')
                        ->first();
                    $parameterWiseRateData[$nestedkeyWithIds] = !empty($parameterWiseRate->invoicing_rate) ? $parameterWiseRate->invoicing_rate * $total_injection_count : '0';
                }
            }

            //Calculating Rates of Assay Parameter Category without Detector,Running Time,no of Injection
            if (!empty($withoutDectorsAssayCategory)) {
                foreach ($withoutDectorsAssayCategory as $nestedkeyWithIds => $values) {
                    $withoutDectorsAssayCategoryInfo[$nestedkeyWithIds]['equipment_count']      = is_array($values) ? count($values) : 0;
                    $withoutDectorsAssayCategoryInfo[$nestedkeyWithIds]['total_injection_count'] = isset($noOfInjectionWithDectorsCategory[$nestedkeyWithIds]) && is_array($noOfInjectionWithDectorsCategory) ? $noOfInjectionWithDectorsCategory[$nestedkeyWithIds] : 0;
                    $withoutDectorsAssayCategoryInfo[$nestedkeyWithIds]['invoicing']          = current($values);
                }
                foreach ($withoutDectorsAssayCategoryInfo as $nestedkeyWithIds => $values) {
                    $keyWDAssayData                 = $models->getExplodedData($nestedkeyWithIds, '-');
                    $test_parameter_category_id         = !empty($keyWDAssayData[0]) ? $keyWDAssayData[0] : '0';
                    $product_category_id             = !empty($keyWDAssayData[1]) ? $keyWDAssayData[1] : '0';
                    $p_category_id                 = !empty($keyWDAssayData[2]) ? $keyWDAssayData[2] : '0';
                    $sub_p_category_id                 = !empty($keyWDAssayData[3]) ? $keyWDAssayData[3] : '0';
                    $equipment_type_id                 = !empty($keyWDAssayData[4]) ? $keyWDAssayData[4] : '0';
                    $no_of_per_injection             = !empty($values['invoicing']['no_of_per_injection']) ? trim($values['invoicing']['no_of_per_injection']) : 0;
                    $cir_equipment_count            = !empty($values['equipment_count']) ? $values['equipment_count'] : '0';
                    $parameterWiseRate = DB::table('customer_invoicing_rates')
                        ->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
                        ->where('customer_invoicing_rates.cir_customer_id', '=', $customer_id)
                        ->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
                        ->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
                        ->where('customer_invoicing_rates.cir_p_category_id', '=', $p_category_id)
                        ->where('customer_invoicing_rates.cir_sub_p_category_id', '=', $sub_p_category_id)
                        ->where('customer_invoicing_rates.cir_test_parameter_category_id', '=', $test_parameter_category_id)
                        ->where('customer_invoicing_rates.cir_equipment_type_id', '=', $equipment_type_id)
                        ->where('customer_invoicing_rates.cir_equipment_count', '=', $cir_equipment_count)
                        ->where('customer_invoicing_rates.cir_is_detector', '=', '2')
                        ->first();
                    $parameterWiseRateData[$nestedkeyWithIds] = !empty($parameterWiseRate->invoicing_rate) ? $parameterWiseRate->invoicing_rate : '0';
                }
            }

            $invoicingRate = in_array('0', $parameterWiseRateData) ? '0' : array_sum($parameterWiseRateData);
        }

        return empty($returnType) ? $invoicingRate : $parameterWiseRateData;
    }

    /**
     * Display the specified resource.
     * Author : Praveen Singh
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewOrderDetail($order_id)
    {

        global $order, $models, $report;

        $returnData = $orderList = $rawTestProductStdParaList = $orderParameterList = $generalParameterList = $orderHasClaimValueOrNot = $orderEquipmentDetail = $orderTatInDayDetail = $nablTestParameterDetail = array();

        if ($order_id) {

            $user_id                                    = defined('USERID') ? USERID : '0';
            $equipment_type_ids                         = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : array();
            $role_ids                                   = defined('ROLE_IDS') ? ROLE_IDS : '0';
            $environmentWaterCategory                   = defined('ENV_WATER_REPORT_CATEGORY') ? explode(',', ENV_WATER_REPORT_CATEGORY) : array();
            $orderList                                  = $order->getOrder($order_id);
            $testProductStdParaList                     = $order->getOrderParameters($order_id);
            $orderPerformerRecord                       = $order->getOrderPerformerRecord($order_id);
            $orderList->order_nabl_scope_symbol         = $report->hasOrderApplicableForNablScopeAsteriskSymbol($order_id);        //Checking Order is applicable for NABL Scope * Symbol
            $orderList->order_nabl_scope                = $report->hasOrderApplicableForNablScope($order_id);                //Checking Order is applicable for NABL Number
            $microbiologistName                         = defined('MICROBIOLOGIST_NAME') &&  !empty(MICROBIOLOGIST_NAME) ? MICROBIOLOGIST_NAME : '-';
            $orderList->testParametersWithSpace         = defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? TEST_PARAMETERS : '';
            $orderList->assayParametersWithSpace        = defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? ASSAY_PARAMETERS : '';
            $orderList->assayParametersWithoutSpace     = defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? strtolower(str_replace(" ", "", ASSAY_PARAMETERS)) : '';
            $orderList->testParametersWithoutSpace      = defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? strtolower(str_replace(" ", "", TEST_PARAMETERS)) : '';
            $orderList->orderAmendStatus                = !empty($order->isBookingOrderAmendedOrNot($order_id)) ? '1' : '0';
            $allowedExceptionParameters                 = array('description', 'description(cl:3.2.1)', 'description(cl:3.2)', 'reference to protocol');

            if (!empty($testProductStdParaList)) {
                foreach ($testProductStdParaList as $key => $values) {
                    if (!empty($values->test_parameter_name) && in_array(strtolower($values->test_parameter_name), $allowedExceptionParameters)) {
                        if (!empty($values->test_result) && strtolower($values->test_result) != 'n/a') {
                            $values->description = $values->test_result;
                        }
                    }
                    //Getting NABL Status of Test Parameter according to category
                    if (!empty($values->equipment_type_id)) {
                        $nablTestParameterDetail[$values->test_para_cat_id][$values->test_parameter_id] = trim($values->order_parameter_nabl_scope);
                    }
                    $rawTestProductStdParaList[$values->analysis_id]  = $values;
                    $orderEquipmentDetail[$values->equipment_type_id] = !empty($values->equipment_name) ? strtolower($values->equipment_name) : '';
                    $orderTatInDayDetail[$values->time_taken_days]       = !empty($values->time_taken_days) ? trim($values->time_taken_days) : '0';
                }
            }
            if (!empty($rawTestProductStdParaList)) {
                foreach ($rawTestProductStdParaList as $key => $values) {
                    $models->getRequirementSTDFromTo($values, $values->standard_value_from, $values->standard_value_to);
                    $report->getNonNablAndOutsourceSymbolTR($values, $nablTestParameterDetail, $orderList->order_nabl_scope_symbol); //Getting Non NABL and Outsource Sample Symbol in Test Report(TR)
                    $orderHasClaimValueOrNot[]                        = !empty($values->claim_value) && $values->test_para_cat_name == $orderList->assayParametersWithSpace ? $values->claim_value : '';
                    $orderParameterList[$values->test_para_cat_id]['categorySortBy']       = $values->category_sort_by;
                    $orderParameterList[$values->test_para_cat_id]['categoryId']           = $values->test_para_cat_id;
                    $orderParameterList[$values->test_para_cat_id]['categoryName']         = $values->test_para_cat_name;
                    $orderParameterList[$values->test_para_cat_id]['categoryNameSymbol'] = $values->non_nabl_category_symbol;
                    $orderParameterList[$values->test_para_cat_id]['categoryParams'][]     = $values;
                }
            }
        }

        $generalParameterListArr = !empty($generalParameterList) ? $models->sortArrayAscOrder(array_values($generalParameterList)) : array();
        $orderParameterListArr   = !empty($orderParameterList) ? $models->sortArrayAscOrder(array_values($orderParameterList)) : array();
        $maxTatInDayNumber       = !empty($orderTatInDayDetail) ? max($orderTatInDayDetail) : '0';
        $totalOrderParameters    = !empty($testProductStdParaList) ? count($testProductStdParaList) : '0';
        
        if (!empty($orderList) && !empty($orderParameterList)) {
            $returnData['order']                        = $orderList;
            $returnData['order']->hasClaimValue         = array_filter($orderHasClaimValueOrNot);
            $returnData['order']->hasMicrobiologicalEquipment     = !empty($orderList->report_microbiological_name) ? $orderList->report_microbiological_name : '0';
            $returnData['totalOrderParameters']             = $totalOrderParameters;
            $returnData['maxTatInDayNumber']             = $maxTatInDayNumber;
            $returnData['generalParameters']              = $generalParameterListArr;
            $returnData['orderParameters']              = $orderParameterListArr;
            $returnData['orderEquipments']              = array_filter($orderEquipmentDetail);
            $returnData['orderTrackRecord']             = $orderPerformerRecord;
            $returnData                                 = json_decode(json_encode($returnData), true);
        }

        return $returnData;
    }

    /**
     * Description  : Checking Order has only one Test Parameter Category
     * Author       : Praveen Singh
     * Created On   : 01-Feb-2020
     * Modified On  : 18-Feb-2020
     */
    public function getNumberOfTestParameterCategoryOfOrder($order_id, $isTestParameterCategoryExist)
    {
        global $models;

        $flag                           = array();
        $orderList                      = $this->getOrderDetail($order_id);
        $apedaDivisionSettingArray      = ['2'];
        $apedaProductCatSettingArray    = ['1', '2'];

        if (!empty($orderList->order_id) && in_array($orderList->division_id, $apedaDivisionSettingArray) && in_array($orderList->product_category_id, $apedaProductCatSettingArray)) {   //For Chenna Food and Pharma
            $testParameterCategoriesArray   = $this->getOrderParameterCategory($orderList->order_id);
            $testParameterCategoriesArray   = $models->unsetFormDataVariableByValues($testParameterCategoriesArray, array('description', 'Description', 'description(cl:3.2.1)', 'description(cl:3.2)'));
            if (!empty($isTestParameterCategoryExist) && !empty($testParameterCategoriesArray) && count($testParameterCategoriesArray) == '1') {
                foreach ($isTestParameterCategoryExist as $key => $value) {
                    if (in_array($value, array_values(array_flip($testParameterCategoriesArray)))) {
                        $flag[] = '1';
                    } else {
                        $flag[] = '0';
                    }
                }
            }
            return !empty($flag) && in_array('1', $flag) ? true : false;
        } else {
            return false;
        }
    }

    /**
     * Display the specified resource.
     * Author : Praveen Singh
     * Created On : 16-Nov-2019
     */
    public function generateReportDetail($order_id, $customizePdfData = array())
    {
        global $models;

        if (!empty($customizePdfData['reportWithDisciplineGroup'])) {
            return $this->viewDisciplineGroupReportDetail($order_id, $customizePdfData);
        } else if (!empty($customizePdfData['reportWithEICFormat'])) {
            return $this->viewEICReportDetail($order_id, $customizePdfData);
        } else if (!empty($this->getNumberOfTestParameterCategoryOfOrder($order_id, explode(',', defined('PESTICIDE_RESIDUE_TEST_PARA_CATEGORY') ? PESTICIDE_RESIDUE_TEST_PARA_CATEGORY : '7')))) {
            return $this->viewPesticideResidueReportDetail($order_id, $customizePdfData);
        } else if (!empty($customizePdfData['withoutPartwiseReport'])) {
            return $this->viewReportDetail($order_id, $customizePdfData);
        } else if (in_array($this->getOrderColumValue($order_id, 'product_category_id'), [1, 3, 6, 8]) && in_array($this->getOrderColumValue($order_id, 'division_id'), [1])) {
            return $this->viewDisciplineGroupReportDetail($order_id, $customizePdfData);
        } else {
            return $this->viewReportDetail($order_id, $customizePdfData);
        }
    }

    /**
     * Display the specified resource.
     * Author : Praveen Singh
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewReportDetail($order_id, $customizePdfData = array())
    {

        global $order, $models, $report;

        $returnData = $orderList = $rawTestProductStdParaList = $orderParameterList = $generalParameterList = $orderHasClaimValueOrNot = $orderEquipmentDetail = $orderTatInDayDetail = $checkReportQuality = $nablTestParameterDetail = $descriptionParameterList = array();

        if ($order_id) {
            $nablCodeActivationDate                     = defined('NABL_CODE_ACTIVATION_DATE') ? trim(NABL_CODE_ACTIVATION_DATE) : '2020-07-15';
            $allowedExceptionParameters                 = array('description', 'description(cl:3.2.1)', 'description(cl:3.2)', 'reference to protocol');
            $user_id                                    = defined('USERID') ? USERID : '0';
            $equipment_type_ids                         = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : array();
            $role_ids                                   = defined('ROLE_IDS') ? ROLE_IDS : '0';
            $environmentWaterCategory                   = defined('ENV_WATER_REPORT_CATEGORY') ? explode(',', ENV_WATER_REPORT_CATEGORY) : array();
            $orderList                                  = $order->getOrder($order_id);
            $testProductStdParaList                     = $order->getOrderParameters($order_id, $customizePdfData);
            $orderPerformerRecord                       = $order->getOrderPerformerRecord($order_id);
            $checkReportQuality                         = $report->getStandardQualityStampOrNot($orderList);
            $microbiologistName                         = defined('MICROBIOLOGIST_NAME') &&  !empty(MICROBIOLOGIST_NAME) ? MICROBIOLOGIST_NAME : '-';
            $orderList->testParametersWithSpace         = defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? TEST_PARAMETERS : '';
            $orderList->assayParametersWithSpace        = defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? ASSAY_PARAMETERS : '';
            $orderList->assayParametersWithoutSpace     = defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? strtolower(str_replace(" ", "", ASSAY_PARAMETERS)) : '';
            $orderList->testParametersWithoutSpace      = defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? strtolower(str_replace(" ", "", TEST_PARAMETERS)) : '';
            $orderList->orderAmendStatus                = !empty($order->isBookingOrderAmendedOrNot($order_id)) ? '1' : '0';
            $orderList->order_nabl_scope_symbol         = $report->hasOrderApplicableForNablScopeAsteriskSymbol($order_id, $customizePdfData);    //Checking Order is applicable for NABL Scope * Symbol
            $orderList->order_nabl_scope                = $report->hasOrderApplicableForNablScope($order_id);                    //Checking Order is applicable for NABL Number
            $orderList->order_nabl_os_remark_scope      = $report->getFullyPartialNablOutsourceSampleScope_v1($order_id, $customizePdfData);    //Getting Fully NABL Scope/Partial NABL Scope/Outsource Sample Scope
            $orderList->order_nabl_remark_scope         = $report->getFullyPartialNablSampleScope($order_id, $customizePdfData);    //Getting Fully NABL Scope/Partial NABL Scope Sample Scope
            $orderList->order_outsource_remark_scope    = $report->getOutsourceSampleScope($order_id, $customizePdfData);    //Getting Outsource Sample Scope
            $orderList->tat_days                        = $models->sub_days_between_two_date($orderList->expected_due_date, $orderList->booking_date);
            $orderList->has_order_fp_nabl_scope         = $report->__getFullyPartialNullNablScopeReport($order_id, $customizePdfData);
            $orderList->nabl_report_generation_type     = !empty($report->__validateNablCodeGenerationAppliciability($order_id, $nablCodeActivationDate)) ? '1' : '0';    //Report Generation Type - Older or Newer
            $orderList->analysis_start_date             = $this->getMinDateStartOfAnalysis($order_id);         //Getting Min Date of start of analysis && Max Date of completion of Analysis on 20-May-2022
            $orderList->analysis_completion_date        = $this->getMaxDateCompletionOfAnalysis($order_id);    //Getting Min Date of start of analysis && Max Date of completion of Analysis on 20-May-2022
            $orderList->order_discipline_group_detail   = $this->get_order_discipline_group_detail($order_id, NULL, true);
            $commencedOnData                            = DB::table('schedulings')->select('schedulings.scheduled_at')->where('schedulings.order_id', $order_id)->orderBy('schedulings.scheduled_at', 'ASC')->first();

            if (!empty($testProductStdParaList)) {
                foreach ($testProductStdParaList as $key => $values) {
                    $values->test_parameter_name = trim($values->test_parameter_name);
                    if (!empty($values->test_parameter_name) && in_array(strtolower($values->test_parameter_name), $allowedExceptionParameters)) {
                        if (!empty($values->test_result) && strtolower($values->test_result) != 'n/a') {
                            $values->description = $values->test_result;
                        }
                    }
                    //Getting NABL Status of Test Parameter according to category
                    if (!empty($values->equipment_type_id)) {
                        $nablTestParameterDetail[$values->test_para_cat_id][$values->test_parameter_id] = trim($values->order_parameter_nabl_scope);
                    }
                    $rawTestProductStdParaList[$values->analysis_id]  = $values;
                    $orderEquipmentDetail[$values->equipment_type_id] = !empty($values->equipment_name) ? strtolower($values->equipment_name) : '';
                    $orderTatInDayDetail[$values->time_taken_days]    = !empty($values->time_taken_days) ? trim($values->time_taken_days) : '0';
                }
            }
            if (!empty($rawTestProductStdParaList)) {
                foreach ($rawTestProductStdParaList as $key => $values) {
                    $models->getRequirementSTDFromTo($values, $values->standard_value_from, $values->standard_value_to);
                    $report->getNonNablAndOutsourceSymbolTR($values, $nablTestParameterDetail, $orderList->order_nabl_scope_symbol); //Getting Non NABL and Outsource Sample Symbol in Test Report(TR)
                    $orderHasClaimValueOrNot[]    = !empty($values->claim_value) && $values->test_para_cat_name == $orderList->assayParametersWithSpace ? $values->claim_value : '';
                    if (!empty($values->test_parameter_name) && in_array(strtolower(strip_tags($values->test_parameter_name)), $allowedExceptionParameters)) {
                        $descriptionParameterList[$values->test_para_cat_id]['categorySortBy']       = $values->category_sort_by;
                        $descriptionParameterList[$values->test_para_cat_id]['categoryId']           = $values->test_para_cat_id;
                        $descriptionParameterList[$values->test_para_cat_id]['categoryName']         = $values->test_para_cat_name;
                        $descriptionParameterList[$values->test_para_cat_id]['categoryNameSymbol']     = $values->non_nabl_category_symbol;
                        $descriptionParameterList[$values->test_para_cat_id]['categoryParams'][]     = $values;
                    } else if (empty($customizePdfData['withoutPartwiseReport']) && !empty($environmentWaterCategory) && in_array($values->test_para_cat_id, $environmentWaterCategory)) {     //In Case of Category(General Information and Observation) In Environment Department
                        $generalParameterList[$values->test_para_cat_id]['categorySortBy']       = $values->category_sort_by;
                        $generalParameterList[$values->test_para_cat_id]['categoryId']           = $values->test_para_cat_id;
                        $generalParameterList[$values->test_para_cat_id]['categoryName']         = $values->test_para_cat_name;
                        $generalParameterList[$values->test_para_cat_id]['categoryNameSymbol']     = $values->non_nabl_category_symbol;
                        $generalParameterList[$values->test_para_cat_id]['categoryParams'][]     = $values;
                    } else {
                        $orderParameterList[$values->test_para_cat_id]['categorySortBy']       = $values->category_sort_by;
                        $orderParameterList[$values->test_para_cat_id]['categoryId']           = $values->test_para_cat_id;
                        $orderParameterList[$values->test_para_cat_id]['categoryName']         = $values->test_para_cat_name;
                        $orderParameterList[$values->test_para_cat_id]['categoryNameSymbol']     = $values->non_nabl_category_symbol;
                        $orderParameterList[$values->test_para_cat_id]['categoryParams'][]     = $values;
                    }
                }
            }
        }

        $generalParameterListArr     = !empty($generalParameterList) ? $models->sortArrayAscOrder(array_values($generalParameterList)) : array();
        $orderParameterListArr       = !empty($orderParameterList) ? $models->sortArrayAscOrder(array_values($orderParameterList)) : array();
        $descriptionParameterListArr = !empty($descriptionParameterList) ? $models->sortArrayAscOrder(array_values($descriptionParameterList)) : array();
        $maxTatInDayNumber           = !empty($orderTatInDayDetail) ? max($orderTatInDayDetail) : '0';
        $totalOrderParameters        = !empty($testProductStdParaList) ? count($testProductStdParaList) : '0';

        if (!empty($orderList) && !empty($orderParameterList)) {
            $returnData['order']                                = $orderList;
            $returnData['order']->hasClaimValue                 = array_filter($orderHasClaimValueOrNot);
            $returnData['order']->hasMicrobiologicalEquipment   = !empty($orderList->report_microbiological_name) ? $orderList->report_microbiological_name : '0';
            $returnData['order']->commenced_on_date             = !empty($commencedOnData->scheduled_at) ? $commencedOnData->scheduled_at : '';
            $returnData['totalOrderParameters']                 = $totalOrderParameters;
            $returnData['maxTatInDayNumber']                    = $maxTatInDayNumber;
            $returnData['descriptionParameters']                = $descriptionParameterListArr;
            $returnData['generalParameters']                    = $generalParameterListArr;
            $returnData['orderParameters']                      = $orderParameterListArr;
            $returnData['orderEquipments']                      = array_filter($orderEquipmentDetail);
            $returnData['orderTrackRecord']                     = $orderPerformerRecord;
            $returnData['quality']                              = $checkReportQuality;
            $returnData                                         = json_decode(json_encode($returnData), true);
        }

        //echo '<pre>';print_r(array_keys($returnData));die;
        return $returnData;
    }

    /**
     * Display the specified resource.
     * Author : Praveen Singh
     * Updated By : Ruby (Change function to view astrick symbol line no 1790)
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewOrderConfirmationDetail($order_id, $customizePdfData = array())
    {

        global $order, $models, $report;

        $returnData = $orderList = $rawTestProductStdParaList = $orderParameterList = $generalParameterList = $orderHasClaimValueOrNot = $orderEquipmentDetail = $orderTatInDayDetail = $checkReportQuality = $nablTestParameterDetail = array();

        if ($order_id) {

            $user_id                                    = defined('USERID') ? USERID : '0';
            $nablCodeActivationDate                     = defined('NABL_CODE_ACTIVATION_DATE') ? trim(NABL_CODE_ACTIVATION_DATE) : '2020-07-15';
            $equipment_type_ids                         = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : array();
            $role_ids                                   = defined('ROLE_IDS') ? ROLE_IDS : '0';
            $environmentWaterCategory                   = defined('ENV_WATER_REPORT_CATEGORY') ? explode(',', ENV_WATER_REPORT_CATEGORY) : array();
            $orderList                                  = $order->getOrder($order_id);
            $testProductStdParaList                     = $order->getOrderParameters($order_id, $customizePdfData);
            $orderPerformerRecord                       = $order->getOrderPerformerRecord($order_id);
            $checkReportQuality                         = $report->getStandardQualityStampOrNot($orderList);
            $microbiologistName                         = defined('MICROBIOLOGIST_NAME') &&  !empty(MICROBIOLOGIST_NAME) ? MICROBIOLOGIST_NAME : '-';
            $orderList->testParametersWithSpace         = defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? TEST_PARAMETERS : '';
            $orderList->assayParametersWithSpace        = defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? ASSAY_PARAMETERS : '';
            $orderList->assayParametersWithoutSpace     = defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? strtolower(str_replace(" ", "", ASSAY_PARAMETERS)) : '';
            $orderList->testParametersWithoutSpace      = defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? strtolower(str_replace(" ", "", TEST_PARAMETERS)) : '';
            $orderList->orderAmendStatus                = !empty($order->isBookingOrderAmendedOrNot($order_id)) ? '1' : '0';
            $orderList->order_nabl_scope                = $report->hasOrderApplicableForNablScope($order_id);                        //Checking Order is applicable for NABL Number
            $orderList->order_nabl_scope_symbol         = $report->hasOrderApplicableForNablScopeAsteriskSymbolInView($order_id, $customizePdfData);    //Checking Order is applicable for NABL Scope * Symbol
            $orderList->order_nabl_os_remark_scope      = $report->getFullyPartialNablOutsourceSampleScope_v1($order_id, $customizePdfData);        //Getting Fully NABL Scope/Partial NABL Scope/Outsource Sample Scope
            $orderList->order_nabl_remark_scope         = $report->getFullyPartialNablSampleScope($order_id, $customizePdfData);    //Getting Fully NABL Scope/Partial NABL Scope Sample Scope
            $orderList->order_outsource_remark_scope    = $report->getOutsourceSampleScope($order_id, $customizePdfData);    //Getting Outsource Sample Scope
            $orderList->tat_days                        = $models->sub_days_between_two_date($orderList->expected_due_date, $orderList->booking_date);
            $orderList->nabl_report_generation_type     = !empty($report->__validateNablCodeGenerationAppliciability($order_id, $nablCodeActivationDate)) ? '1' : '0';    //Report Generation Type - Older or Newer
            $orderList->analysis_start_date             = $this->getMinDateStartOfAnalysis($order_id);         //Getting Min Date of start of analysis && Max Date of completion of Analysis on 20-May-2022
            $orderList->analysis_completion_date        = $this->getMaxDateCompletionOfAnalysis($order_id);    //Getting Min Date of start of analysis && Max Date of completion of Analysis on 20-May-2022
            $commencedOnData                            = DB::table('schedulings')->select('schedulings.scheduled_at')->where('schedulings.order_id', $order_id)->orderBy('schedulings.scheduled_at', 'ASC')->first();
            $allowedExceptionParameters                 = array('description', 'description(cl:3.2.1)', 'description(cl:3.2)', 'reference to protocol');

            //Getting Order Confirmation Templates
            if (!empty($orderList->division_id)) {
                list($header_content, $footer_content) = $this->getDynamicHeaderFooterTemplate('4', $orderList->division_id, $orderList->product_category_id);
                $orderList->header_content = $header_content;
                $orderList->footer_content = $footer_content;
            }
            if (!empty($testProductStdParaList)) {
                foreach ($testProductStdParaList as $key => $values) {
                    if (!empty($values->test_parameter_name) && in_array(strtolower($values->test_parameter_name), $allowedExceptionParameters)) {
                        if (!empty($values->test_result) && strtolower($values->test_result) != 'n/a') {
                            $values->description = $values->test_result;
                        }
                    }
                    //Getting NABL Status of Test Parameter according to category
                    if (!empty($values->equipment_type_id)) {
                        $nablTestParameterDetail[$values->test_para_cat_id][$values->test_parameter_id] = trim($values->order_parameter_nabl_scope);
                    }
                    $rawTestProductStdParaList[$values->analysis_id]  = $values;
                    $orderEquipmentDetail[$values->equipment_type_id] = !empty($values->equipment_name) ? strtolower($values->equipment_name) : '';
                    $orderTatInDayDetail[$values->time_taken_days]    = !empty($values->time_taken_days) ? trim($values->time_taken_days) : '0';
                }
            }
            if (!empty($rawTestProductStdParaList)) {
                foreach ($rawTestProductStdParaList as $key => $values) {
                    $models->getRequirementSTDFromTo($values, $values->standard_value_from, $values->standard_value_to);
                    $report->getNonNablAndOutsourceSymbolTR($values, $nablTestParameterDetail, $orderList->order_nabl_scope_symbol);
                    $orderHasClaimValueOrNot[]    = !empty($values->claim_value) && $values->test_para_cat_name == $orderList->assayParametersWithSpace ? $values->claim_value : '';
                    if (empty($customizePdfData['withoutPartwiseReport']) && !empty($environmentWaterCategory) && in_array($values->test_para_cat_id, $environmentWaterCategory)) {     //In Case of Category(General Information and Observation) In Environment Department
                        $generalParameterList[$values->test_para_cat_id]['categorySortBy']           = $values->category_sort_by;
                        $generalParameterList[$values->test_para_cat_id]['categoryId']               = $values->test_para_cat_id;
                        $generalParameterList[$values->test_para_cat_id]['categoryName']             = $values->test_para_cat_name;
                        $generalParameterList[$values->test_para_cat_id]['categoryParams'][]         = $values;
                    } else {
                        $orderParameterList[$values->test_para_cat_id]['categorySortBy']           = $values->category_sort_by;
                        $orderParameterList[$values->test_para_cat_id]['categoryId']               = $values->test_para_cat_id;
                        $orderParameterList[$values->test_para_cat_id]['categoryName']             = $values->test_para_cat_name;
                        $orderParameterList[$values->test_para_cat_id]['categoryParams'][]           = $values;
                    }
                }
            }
        }

        $generalParameterListArr = !empty($generalParameterList) ? $models->sortArrayAscOrder(array_values($generalParameterList)) : array();
        $orderParameterListArr   = !empty($orderParameterList) ? $models->sortArrayAscOrder(array_values($orderParameterList)) : array();
        $maxTatInDayNumber       = !empty($orderTatInDayDetail) ? max($orderTatInDayDetail) : '0';
        $totalOrderParameters    = !empty($testProductStdParaList) ? count($testProductStdParaList) : '0';

        if (!empty($orderList) && !empty($orderParameterList)) {
            $returnData['order']                        = $orderList;
            $returnData['order']->hasClaimValue         = array_filter($orderHasClaimValueOrNot);
            $returnData['order']->hasMicrobiologicalEquipment     = !empty($orderList->report_microbiological_name) ? $orderList->report_microbiological_name : '0';
            $returnData['order']->commenced_on_date         = !empty($commencedOnData->scheduled_at) ? $commencedOnData->scheduled_at : '';
            $returnData['totalOrderParameters']             = $totalOrderParameters;
            $returnData['maxTatInDayNumber']             = $maxTatInDayNumber;
            $returnData['generalParameters']              = $generalParameterListArr;
            $returnData['orderParameters']              = $orderParameterListArr;
            $returnData['orderEquipments']              = array_filter($orderEquipmentDetail);
            $returnData['orderTrackRecord']             = $orderPerformerRecord;
            $returnData['quality']                          = $checkReportQuality;
            $returnData                                 = json_decode(json_encode($returnData), true);
        }
        return $returnData;
    }

    /**
     * Display the specified resource.
     * Author : Praveen Singh
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewDisciplineGroupReportDetail($order_id, $customizePdfData = array())
    {

        global $order, $models, $report;

        $returnData = $orderList = $rawTestProductStdParaList = $descriptionWiseParameterList = $disciplineWiseParametersList = $categoryWiseParameterList = $orderParameterList = $generalParameterList = $orderHasClaimValueOrNot = $orderEquipmentDetail = $orderTatInDayDetail = $checkReportQuality = $nablTestParameterDetail = $descriptionParameterList = $generalWiseParameterList = array();

        if ($order_id) {
            $nablCodeActivationDate                     = defined('NABL_CODE_ACTIVATION_DATE') ? trim(NABL_CODE_ACTIVATION_DATE) : '2020-07-15';
            $allowedExceptionParameters                 = array('description', 'description(cl:3.2.1)', 'description(cl:3.2)', 'reference to protocol');
            $user_id                                    = defined('USERID') ? USERID : '0';
            $equipment_type_ids                         = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : array();
            $role_ids                                   = defined('ROLE_IDS') ? ROLE_IDS : '0';
            $environmentWaterCategory                   = defined('ENV_WATER_REPORT_CATEGORY') ? explode(',', ENV_WATER_REPORT_CATEGORY) : array();
            $orderList                                  = $order->getOrder($order_id);
            $testProductStdParaList                     = $order->getOrderParameters($order_id, $customizePdfData);
            $orderPerformerRecord                       = $order->getOrderPerformerRecord($order_id);
            $checkReportQuality                         = $report->getStandardQualityStampOrNot($orderList);
            $microbiologistName                         = defined('MICROBIOLOGIST_NAME') &&  !empty(MICROBIOLOGIST_NAME) ? MICROBIOLOGIST_NAME : '-';
            $orderList->testParametersWithSpace         = defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? TEST_PARAMETERS : '';
            $orderList->assayParametersWithSpace        = defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? ASSAY_PARAMETERS : '';
            $orderList->assayParametersWithoutSpace     = defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? strtolower(str_replace(" ", "", ASSAY_PARAMETERS)) : '';
            $orderList->testParametersWithoutSpace      = defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? strtolower(str_replace(" ", "", TEST_PARAMETERS)) : '';
            $orderList->orderAmendStatus                = !empty($order->isBookingOrderAmendedOrNot($order_id)) ? '1' : '0';
            $orderList->order_nabl_scope_symbol         = $report->hasOrderApplicableForNablScopeAsteriskSymbol($order_id, $customizePdfData);        //Checking Order is applicable for NABL Scope * Symbol
            $orderList->order_nabl_scope                = $report->hasOrderApplicableForNablScope($order_id);                        //Checking Order is applicable for NABL Number
            $orderList->order_nabl_os_remark_scope      = $report->getFullyPartialNablOutsourceSampleScope_v1($order_id, $customizePdfData);        //Getting Fully NABL Scope/Partial NABL Scope/Outsource Sample Scope
            $orderList->order_nabl_remark_scope         = $report->getFullyPartialNablSampleScope($order_id, $customizePdfData);    //Getting Fully NABL Scope/Partial NABL Scope Sample Scope
            $orderList->order_outsource_remark_scope    = $report->getOutsourceSampleScope($order_id, $customizePdfData);    //Getting Outsource Sample Scope
            $orderList->tat_days                        = $models->sub_days_between_two_date($orderList->expected_due_date, $orderList->booking_date);
            $orderList->has_order_fp_nabl_scope         = $report->__getFullyPartialNullNablScopeReport($order_id);
            $orderList->nabl_report_generation_type     = !empty($report->__validateNablCodeGenerationAppliciability($order_id, $nablCodeActivationDate)) ? '1' : '0';    //Report Generation Type - Older or Newer
            $orderList->analysis_start_date             = $this->getMinDateStartOfAnalysis($order_id);         //Getting Min Date of start of analysis && Max Date of completion of Analysis on 20-May-2022
            $orderList->analysis_completion_date        = $this->getMaxDateCompletionOfAnalysis($order_id);    //Getting Min Date of start of analysis && Max Date of completion of Analysis on 20-May-2022
            $commencedOnData                            = DB::table('schedulings')->select('schedulings.scheduled_at')->where('schedulings.order_id', $order_id)->orderBy('schedulings.scheduled_at', 'ASC')->first();

            if (!empty($testProductStdParaList)) {
                foreach ($testProductStdParaList as $key => $values) {
                    $values->test_parameter_name = trim($values->test_parameter_name);
                    if (!empty($values->test_parameter_name) && in_array(strtolower($values->test_parameter_name), $allowedExceptionParameters)) {
                        if (!empty($values->test_result) && strtolower($values->test_result) != 'n/a') {
                            $values->description = $values->test_result;
                        }
                    }
                    //Getting NABL Status of Test Parameter according to category
                    if (!empty($values->equipment_type_id)) {
                        $nablTestParameterDetail[$values->test_para_cat_id][$values->test_parameter_id] = trim($values->order_parameter_nabl_scope);
                    }
                    $rawTestProductStdParaList[$values->analysis_id]  = $values;
                    $orderEquipmentDetail[$values->equipment_type_id] = !empty($values->equipment_name) ? strtolower($values->equipment_name) : '';
                    $orderTatInDayDetail[$values->time_taken_days]    = !empty($values->time_taken_days) ? trim($values->time_taken_days) : '0';
                }
            }
            if (!empty($rawTestProductStdParaList)) {
                foreach ($rawTestProductStdParaList as $key => $values) {
                    $models->getRequirementSTDFromTo($values, $values->standard_value_from, $values->standard_value_to);
                    $report->getNonNablAndOutsourceSymbolTR($values, $nablTestParameterDetail, $orderList->order_nabl_scope_symbol); //Getting Non NABL and Outsource Sample Symbol in Test Report(TR)
                    $orderHasClaimValueOrNot[] = !empty($values->claim_value) && $values->test_para_cat_name == $orderList->assayParametersWithSpace ? $values->claim_value : '';
                    if (!empty($values->test_parameter_name) && in_array(strtolower(strip_tags($values->test_parameter_name)), $allowedExceptionParameters)) {
                        $descriptionWiseParameterList[$values->test_para_cat_id]['categorySortBy']       = $values->category_sort_by;
                        $descriptionWiseParameterList[$values->test_para_cat_id]['categoryId']           = $values->test_para_cat_id;
                        $descriptionWiseParameterList[$values->test_para_cat_id]['categoryName']         = $values->test_para_cat_name;
                        $descriptionWiseParameterList[$values->test_para_cat_id]['categoryNameSymbol']   = $values->non_nabl_category_symbol;
                        $descriptionWiseParameterList[$values->test_para_cat_id]['categoryParams'][]     = $values;
                    } else if (!empty($environmentWaterCategory) && in_array($values->test_para_cat_id, $environmentWaterCategory)) {     //In Case of Category(General Information and Observation) In Environment Department
                        $generalWiseParameterList[$values->test_para_cat_id]['categorySortBy']     = $values->category_sort_by;
                        $generalWiseParameterList[$values->test_para_cat_id]['categoryId']         = $values->test_para_cat_id;
                        $generalWiseParameterList[$values->test_para_cat_id]['categoryName']       = $values->test_para_cat_name;
                        $generalWiseParameterList[$values->test_para_cat_id]['categoryNameSymbol'] = $values->non_nabl_category_symbol;
                        $generalWiseParameterList[$values->test_para_cat_id]['categoryParams'][]   = $values;
                    } else if (!empty($values->discipline_id)) {
                        $disciplineWiseParametersList[$values->discipline_id]['disciplineHdr']['discipline_id']   = $values->discipline_id;
                        $disciplineWiseParametersList[$values->discipline_id]['disciplineHdr']['discipline_name'] = $values->discipline_name;
                        $disciplineWiseParametersList[$values->discipline_id]['disciplineHdr']['group_id']        = $values->group_id;
                        $disciplineWiseParametersList[$values->discipline_id]['disciplineHdr']['group_name']      = $values->group_name;
                        $disciplineWiseParametersList[$values->discipline_id]['disciplineDtl'][$values->test_para_cat_id]['categorySortBy']       = $values->category_sort_by;
                        $disciplineWiseParametersList[$values->discipline_id]['disciplineDtl'][$values->test_para_cat_id]['categoryId']           = $values->test_para_cat_id;
                        $disciplineWiseParametersList[$values->discipline_id]['disciplineDtl'][$values->test_para_cat_id]['categoryName']         = $values->test_para_cat_name;
                        $disciplineWiseParametersList[$values->discipline_id]['disciplineDtl'][$values->test_para_cat_id]['productCategoryName']  = str_replace(' ', '', strtolower($values->test_para_cat_name));
                        $disciplineWiseParametersList[$values->discipline_id]['disciplineDtl'][$values->test_para_cat_id]['categoryParams'][]     = $values;
                    } else {
                        $categoryWiseParameterList[$values->test_para_cat_id]['categorySortBy']     = $values->category_sort_by;
                        $categoryWiseParameterList[$values->test_para_cat_id]['categoryId']         = $values->test_para_cat_id;
                        $categoryWiseParameterList[$values->test_para_cat_id]['categoryName']       = $values->test_para_cat_name;
                        $categoryWiseParameterList[$values->test_para_cat_id]['categoryNameSymbol'] = $values->non_nabl_category_symbol;
                        $categoryWiseParameterList[$values->test_para_cat_id]['categoryParams'][]   = $values;
                    }
                }
            }
            if (!empty($descriptionWiseParameterList)) {
                foreach ($descriptionWiseParameterList as $descriptionWiseParameter) {
                    $charNum = 'a';
                    foreach ($descriptionWiseParameter['categoryParams'] as $values) {
                        $values->charNumber = $charNum++;
                    }
                }
            }
            if (!empty($disciplineWiseParametersList)) {
                foreach ($disciplineWiseParametersList as $key => $disciplineWiseParameterListAll) {
                    foreach ($disciplineWiseParameterListAll['disciplineDtl'] as $keyLevelOne => $disciplineWiseParameter) {
                        $charNum = 'a';
                        if (is_array($disciplineWiseParameter) && !empty($disciplineWiseParameter)) {
                            foreach ($disciplineWiseParameter['categoryParams'] as $values) {
                                $values->charNumber = $charNum++;
                            }
                        }
                    }
                }
            }
            if (!empty($categoryWiseParameterList)) {
                foreach ($categoryWiseParameterList as $categoryWiseParameter) {
                    $charNum = 'a';
                    foreach ($categoryWiseParameter['categoryParams'] as $values) {
                        $values->charNumber = $charNum++;
                    }
                }
            }
        }

        $descriptionWiseParameterList = !empty($descriptionWiseParameterList) ? $models->sortArrayAscOrder(array_values($descriptionWiseParameterList)) : array();
        $generalWiseParameterList     = !empty($generalWiseParameterList) ? $models->sortArrayAscOrder(array_values($generalWiseParameterList)) : array();
        $categoryWiseParameterList    = !empty($categoryWiseParameterList) ? $models->sortArrayAscOrder(array_values($categoryWiseParameterList)) : array();
        $disciplineWiseParametersList = !empty($disciplineWiseParametersList) ? $models->sortArrayAscOrder(array_values($disciplineWiseParametersList)) : array();
        $orderParameterList          = ['generalWiseParameterList' => $generalWiseParameterList, 'descriptionWiseParameterList' => $descriptionWiseParameterList, 'categoryWiseParameterList' => $categoryWiseParameterList, 'disciplineWiseParametersList' => $disciplineWiseParametersList];
        $maxTatInDayNumber            = !empty($orderTatInDayDetail) ? max($orderTatInDayDetail) : '0';
        $totalOrderParameters         = !empty($testProductStdParaList) ? count($testProductStdParaList) : '0';

        if (!empty($orderList) && !empty($orderParameterList)) {
            $returnData['order']                        = $orderList;
            $returnData['order']->hasClaimValue         = array_filter($orderHasClaimValueOrNot);
            $returnData['order']->hasMicrobiologicalEquipment     = !empty($orderList->report_microbiological_name) ? $orderList->report_microbiological_name : '0';
            $returnData['order']->commenced_on_date         = !empty($commencedOnData->scheduled_at) ? $commencedOnData->scheduled_at : '';
            $returnData['totalOrderParameters']             = $totalOrderParameters;
            $returnData['maxTatInDayNumber']             = $maxTatInDayNumber;
            $returnData['orderParameterList']              = $orderParameterList;
            $returnData['orderEquipments']              = !empty($orderEquipmentDetail) ? array_filter($orderEquipmentDetail) : array();
            $returnData['orderTrackRecord']             = $orderPerformerRecord;
            $returnData['quality']                          = $checkReportQuality;
            $returnData                                 = json_decode(json_encode($returnData), true);
        }

        //echo '<pre>';print_r($returnData);die;	
        return $returnData;
    }

    /**
     * Display the Pesticide Format Report Detail
     * Author : Praveen Singh
     * Created On : 01-Feb-2020
     * Modified On : 24-Feb-2020
     */
    public function viewPesticideResidueReportDetail($order_id, $customizePdfData = array())
    {

        global $order, $models, $report;

        $returnData = $orderList = $rawTestProductStdParaList = $descriptionWiseParameterList = $disciplineWiseParametersList = $categoryWiseParameterList = $orderParameterList = $generalParameterList = $orderHasClaimValueOrNot = $orderEquipmentDetail = $orderTatInDayDetail = $checkReportQuality = $nablTestParameterDetail = $descriptionParameterList = $generalWiseParameterList = array();

        if ($order_id) {
            $nablCodeActivationDate                         = defined('NABL_CODE_ACTIVATION_DATE') ? trim(NABL_CODE_ACTIVATION_DATE) : '2020-07-15';
            $allowedExceptionParameters                     = array('description', 'description(cl:3.2.1)', 'description(cl:3.2)', 'reference to protocol');
            $user_id                                        = defined('USERID') ? USERID : '0';
            $equipment_type_ids                             = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : array();
            $role_ids                                       = defined('ROLE_IDS') ? ROLE_IDS : '0';
            $environmentWaterCategory                       = defined('ENV_WATER_REPORT_CATEGORY') ? explode(',', ENV_WATER_REPORT_CATEGORY) : array();
            $orderList                                      = $order->getOrder($order_id);
            $testProductStdParaList                         = $order->getOrderParameters($order_id, $customizePdfData);
            $orderPerformerRecord                           = $order->getOrderPerformerRecord($order_id);
            $checkReportQuality                             = $report->getStandardQualityStampOrNot($orderList);
            $microbiologistName                             = defined('MICROBIOLOGIST_NAME') &&  !empty(MICROBIOLOGIST_NAME) ? MICROBIOLOGIST_NAME : '-';
            $orderList->testParametersWithSpace             = defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? TEST_PARAMETERS : '';
            $orderList->assayParametersWithSpace            = defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? ASSAY_PARAMETERS : '';
            $orderList->assayParametersWithoutSpace         = defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? strtolower(str_replace(" ", "", ASSAY_PARAMETERS)) : '';
            $orderList->testParametersWithoutSpace          = defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? strtolower(str_replace(" ", "", TEST_PARAMETERS)) : '';
            $orderList->orderAmendStatus                    = !empty($order->isBookingOrderAmendedOrNot($order_id)) ? '1' : '0';
            $orderList->order_nabl_scope_symbol             = $report->hasOrderApplicableForNablScopeAsteriskSymbol($order_id, $customizePdfData);    //Checking Order is applicable for NABL Scope * Symbol
            $orderList->order_nabl_scope                    = $report->hasOrderApplicableForNablScope($order_id);                    //Checking Order is applicable for NABL Number
            $orderList->order_nabl_os_remark_scope          = $report->getFullyPartialNablOutsourceSampleScope_v1($order_id, $customizePdfData);    //Getting Fully NABL Scope/Partial NABL Scope/Outsource Sample Scope
            $orderList->order_nabl_remark_scope             = $report->getFullyPartialNablSampleScope($order_id, $customizePdfData);    //Getting Fully NABL Scope/Partial NABL Scope Sample Scope
            $orderList->order_outsource_remark_scope        = $report->getOutsourceSampleScope($order_id, $customizePdfData);    //Getting Outsource Sample Scope
            $orderList->tat_days                            = $models->sub_days_between_two_date($orderList->expected_due_date, $orderList->booking_date);
            $orderList->has_order_fp_nabl_scope             = $report->__getFullyPartialNullNablScopeReport($order_id);
            $orderList->has_order_pesticide_category_report = $this->getNumberOfTestParameterCategoryOfOrder($order_id, array(defined('PESTICIDE_RESIDUE_TEST_PARA_CATEGORY') ? PESTICIDE_RESIDUE_TEST_PARA_CATEGORY : '7'));            //Checking Order has only one Test Parameter Category
            $orderList->order_discipline_group_detail       = $this->get_order_discipline_group_detail($order_id, NULL, true);
            $orderList->nabl_report_generation_type         = !empty($report->__validateNablCodeGenerationAppliciability($order_id, $nablCodeActivationDate)) ? '1' : '0';    //Report Generation Type - Older or Newer
            $orderList->analysis_start_date                 = $this->getMinDateStartOfAnalysis($order_id);         //Getting Min Date of start of analysis && Max Date of completion of Analysis on 20-May-2022
            $orderList->analysis_completion_date            = $this->getMaxDateCompletionOfAnalysis($order_id);    //Getting Min Date of start of analysis && Max Date of completion of Analysis on 20-May-2022
            $commencedOnData                                = DB::table('schedulings')->select('schedulings.scheduled_at')->where('schedulings.order_id', $order_id)->orderBy('schedulings.scheduled_at', 'ASC')->first();

            if (!empty($testProductStdParaList)) {
                foreach ($testProductStdParaList as $key => $values) {
                    $values->test_parameter_name = trim($values->test_parameter_name);
                    if (!empty($values->test_parameter_name) && in_array(strtolower($values->test_parameter_name), $allowedExceptionParameters)) {
                        if (!empty($values->test_result) && strtolower($values->test_result) != 'n/a') {
                            $values->description = $values->test_result;
                        }
                    }
                    //Getting NABL Status of Test Parameter according to category
                    if (!empty($values->equipment_type_id)) {
                        $nablTestParameterDetail[$values->test_para_cat_id][$values->test_parameter_id] = trim($values->order_parameter_nabl_scope);
                    }
                    $rawTestProductStdParaList[$values->analysis_id]  = $values;
                    $orderEquipmentDetail[$values->equipment_type_id] = !empty($values->equipment_name) ? strtolower($values->equipment_name) : '';
                    $orderTatInDayDetail[$values->time_taken_days]    = !empty($values->time_taken_days) ? trim($values->time_taken_days) : '0';
                }
            }

            if (!empty($rawTestProductStdParaList)) {
                foreach ($rawTestProductStdParaList as $key => $values) {
                    $models->getRequirementSTDFromTo($values, $values->standard_value_from, $values->standard_value_to);
                    $report->getNonNablAndOutsourceSymbolTR($values, $nablTestParameterDetail, $orderList->order_nabl_scope_symbol); //Getting Non NABL and Outsource Sample Symbol in Test Report(TR)
                    $orderHasClaimValueOrNot[]    = !empty($values->claim_value) && $values->test_para_cat_name == $orderList->assayParametersWithSpace ? $values->claim_value : '';
                    if (!empty($values->test_para_cat_id) && !empty($values->equipment_type_id) && !empty($values->method_id)) {

                        //Column Name
                        $categoryMethodEquipment = $values->test_para_cat_id . '-' . $values->equipment_type_id . '-' . $values->method_id;

                        //Getting Group Name
                        $categoryWiseParameterList['group_name'][$categoryMethodEquipment]['test_parameter_category'] = trim($values->test_para_cat_name);
                        $categoryWiseParameterList['group_name'][$categoryMethodEquipment]['code_no']               = trim($values->equipment_name);
                        $categoryWiseParameterList['group_name'][$categoryMethodEquipment]['test_method']           = trim($values->method_name);

                        //Getting Group Detail
                        if (strpos(strtolower($values->test_result), 'blq') !== false) {
                            $categoryWiseParameterList['group_detail_with_blq'][$categoryMethodEquipment]['categorySortBy']        = trim($values->category_sort_by);
                            $categoryWiseParameterList['group_detail_with_blq'][$categoryMethodEquipment]['categoryId']            = trim($values->test_para_cat_id);
                            $categoryWiseParameterList['group_detail_with_blq'][$categoryMethodEquipment]['categoryName']          = trim($values->test_para_cat_name);
                            $categoryWiseParameterList['group_detail_with_blq'][$categoryMethodEquipment]['categoryNameSymbol']    = trim($values->non_nabl_category_symbol);
                            $categoryWiseParameterList['group_detail_with_blq'][$categoryMethodEquipment]['groupLabelName']        = 'List of molecules found under the code of ' . trim($values->equipment_name) . ' by ' . trim($values->method_name) . '';
                            $categoryWiseParameterList['group_detail_with_blq'][$categoryMethodEquipment]['categoryParams'][]      = $values;
                        } else {
                            $categoryWiseParameterList['group_detail_without_blq'][$categoryMethodEquipment]['categorySortBy']     = trim($values->category_sort_by);
                            $categoryWiseParameterList['group_detail_without_blq'][$categoryMethodEquipment]['categoryId']         = trim($values->test_para_cat_id);
                            $categoryWiseParameterList['group_detail_without_blq'][$categoryMethodEquipment]['categoryName']       = trim($values->test_para_cat_name);
                            $categoryWiseParameterList['group_detail_without_blq'][$categoryMethodEquipment]['categoryNameSymbol'] = trim($values->non_nabl_category_symbol);
                            $categoryWiseParameterList['group_detail_without_blq'][$categoryMethodEquipment]['groupLabelName']     = 'List of molecules detected';
                            $categoryWiseParameterList['group_detail_without_blq'][$categoryMethodEquipment]['categoryParams'][]   = $values;
                        }
                    }
                }
                if (!empty($categoryWiseParameterList)) {
                    foreach ($categoryWiseParameterList as $key => $categoryWiseParameter) {
                        $categoryWiseParameterList[$key] = array_values($categoryWiseParameter);
                    }
                }
            }
        }

        $orderParameterList    = !empty($categoryWiseParameterList) ? $categoryWiseParameterList : array();
        $maxTatInDayNumber     = !empty($orderTatInDayDetail) ? max($orderTatInDayDetail) : '0';
        $totalOrderParameters  = !empty($testProductStdParaList) ? count($testProductStdParaList) : '0';

        if (!empty($orderList) && !empty($orderParameterList)) {
            $returnData['order']                                = $orderList;
            $returnData['order']->hasClaimValue                 = array_filter($orderHasClaimValueOrNot);
            $returnData['order']->hasMicrobiologicalEquipment   = !empty($orderList->report_microbiological_name) ? $orderList->report_microbiological_name : '0';
            $returnData['order']->commenced_on_date             = !empty($commencedOnData->scheduled_at) ? $commencedOnData->scheduled_at : '';
            $returnData['totalOrderParameters']                 = $totalOrderParameters;
            $returnData['maxTatInDayNumber']                    = $maxTatInDayNumber;
            $returnData['orderParameterList']                   = $orderParameterList;
            $returnData['orderEquipments']                      = !empty($orderEquipmentDetail) ? array_filter($orderEquipmentDetail) : array();
            $returnData['orderTrackRecord']                     = $orderPerformerRecord;
            $returnData['quality']                              = $checkReportQuality;
            $returnData                                         = json_decode(json_encode($returnData), true);
        }

        return $returnData;
    }

    /**
     * Display the specified resource.
     * Author : Praveen Singh
     * Created On : 17-Sept-2020
     */
    public function viewEICReportDetail($order_id, $customizePdfData = array())
    {

        global $order, $models, $report;

        $returnData = $orderList = $rawTestProductStdParaList = $orderParameterList = $generalParameterList = $orderHasClaimValueOrNot = $orderEquipmentDetail = $orderTatInDayDetail = $checkReportQuality = $nablTestParameterDetail = $descriptionParameterList = array();

        if ($order_id) {
            $nablCodeActivationDate                     = defined('NABL_CODE_ACTIVATION_DATE') ? trim(NABL_CODE_ACTIVATION_DATE) : '2020-07-15';
            $allowedExceptionParameters                 = array('description', 'description(cl:3.2.1)', 'description(cl:3.2)', 'reference to protocol');
            $user_id                                    = defined('USERID') ? USERID : '0';
            $equipment_type_ids                         = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : array();
            $role_ids                                   = defined('ROLE_IDS') ? ROLE_IDS : '0';
            $environmentWaterCategory                   = defined('ENV_WATER_REPORT_CATEGORY') ? explode(',', ENV_WATER_REPORT_CATEGORY) : array();
            $orderList                                  = $order->getOrder($order_id);
            $testProductStdParaList                     = $order->getOrderParameters($order_id, $customizePdfData);
            $orderPerformerRecord                       = $order->getOrderPerformerRecord($order_id);
            $checkReportQuality                         = $report->getStandardQualityStampOrNot($orderList);
            $microbiologistName                         = defined('MICROBIOLOGIST_NAME') &&  !empty(MICROBIOLOGIST_NAME) ? MICROBIOLOGIST_NAME : '-';
            $orderList->testParametersWithSpace         = defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? TEST_PARAMETERS : '';
            $orderList->assayParametersWithSpace        = defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? ASSAY_PARAMETERS : '';
            $orderList->assayParametersWithoutSpace     = defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? strtolower(str_replace(" ", "", ASSAY_PARAMETERS)) : '';
            $orderList->testParametersWithoutSpace      = defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? strtolower(str_replace(" ", "", TEST_PARAMETERS)) : '';
            $orderList->orderAmendStatus                = !empty($order->isBookingOrderAmendedOrNot($order_id)) ? '1' : '0';
            $orderList->order_nabl_scope_symbol         = $report->hasOrderApplicableForNablScopeAsteriskSymbol($order_id, $customizePdfData);    //Checking Order is applicable for NABL Scope * Symbol
            $orderList->order_nabl_scope                = $report->hasOrderApplicableForNablScope($order_id);                    //Checking Order is applicable for NABL Number
            $orderList->order_nabl_os_remark_scope      = $report->getFullyPartialNablOutsourceSampleScope_v1($order_id, $customizePdfData);    //Getting Fully NABL Scope/Partial NABL Scope/Outsource Sample Scope
            $orderList->order_nabl_remark_scope         = $report->getFullyPartialNablSampleScope($order_id, $customizePdfData);    //Getting Fully NABL Scope/Partial NABL Scope Sample Scope
            $orderList->order_outsource_remark_scope    = $report->getOutsourceSampleScope($order_id, $customizePdfData);    //Getting Outsource Sample Scope
            $orderList->tat_days                        = $models->sub_days_between_two_date($orderList->expected_due_date, $orderList->booking_date);
            $orderList->has_order_fp_nabl_scope         = $report->__getFullyPartialNullNablScopeReport($order_id, $customizePdfData);
            $orderList->nabl_report_generation_type     = !empty($report->__validateNablCodeGenerationAppliciability($order_id, $nablCodeActivationDate)) ? '1' : '0';    //Report Generation Type - Older or Newer
            $orderList->order_discipline_group_detail   = $this->get_eic_order_discipline_group_detail($order_id, NULL, true);
            $orderList->analysis_start_date             = $this->getMinDateStartOfAnalysis($order_id);         //Getting Min Date of start of analysis && Max Date of completion of Analysis on 20-May-2022
            $orderList->analysis_completion_date        = $this->getMaxDateCompletionOfAnalysis($order_id);    //Getting Min Date of start of analysis && Max Date of completion of Analysis on 20-May-2022
            $commencedOnData                            = DB::table('schedulings')->select('schedulings.scheduled_at')->where('schedulings.order_id', $order_id)->orderBy('schedulings.scheduled_at', 'ASC')->first();

            if (!empty($testProductStdParaList)) {
                foreach ($testProductStdParaList as $key => $values) {
                    $values->test_parameter_name = trim($values->test_parameter_name);
                    if (!empty($values->test_parameter_name) && in_array(strtolower($values->test_parameter_name), $allowedExceptionParameters)) {
                        if (!empty($values->test_result) && strtolower($values->test_result) != 'n/a') {
                            $values->description = $values->test_result;
                        }
                    }
                    //Getting NABL Status of Test Parameter according to category
                    if (!empty($values->equipment_type_id)) {
                        $nablTestParameterDetail[$values->test_para_cat_id][$values->test_parameter_id] = trim($values->order_parameter_nabl_scope);
                    }
                    $rawTestProductStdParaList[$values->analysis_id]  = $values;
                    $orderEquipmentDetail[$values->equipment_type_id] = !empty($values->equipment_name) ? strtolower($values->equipment_name) : '';
                    $orderTatInDayDetail[$values->time_taken_days]    = !empty($values->time_taken_days) ? trim($values->time_taken_days) : '0';
                }
            }
            if (!empty($rawTestProductStdParaList)) {
                foreach ($rawTestProductStdParaList as $key => $values) {
                    $models->getLevelOfRecoveryFromTestResult($values);                                    //Getting Level of Recovery
                    $models->getRequirementSTDFromTo($values, $values->standard_value_from, $values->standard_value_to);             //Getting Standard Value from/to
                    $report->getNonNablAndOutsourceSymbolTR($values, $nablTestParameterDetail, $orderList->order_nabl_scope_symbol);     //Getting Non NABL and Outsource Sample Symbol in Test Report(TR)
                    $orderHasClaimValueOrNot[]    = !empty($values->claim_value) && $values->test_para_cat_name == $orderList->assayParametersWithSpace ? $values->claim_value : '';
                    if (!empty($values->test_parameter_name) && in_array(strtolower(strip_tags($values->test_parameter_name)), $allowedExceptionParameters)) {
                        $descriptionParameterList[$values->test_para_cat_id]['categorySortBy']     = $values->category_sort_by;
                        $descriptionParameterList[$values->test_para_cat_id]['categoryId']         = $values->test_para_cat_id;
                        $descriptionParameterList[$values->test_para_cat_id]['categoryName']       = $values->test_para_cat_name;
                        $descriptionParameterList[$values->test_para_cat_id]['categoryNameSymbol'] = $values->non_nabl_category_symbol;
                        $descriptionParameterList[$values->test_para_cat_id]['categoryParams'][]   = $values;
                    } else if (empty($customizePdfData['withoutPartwiseReport']) && !empty($environmentWaterCategory) && in_array($values->test_para_cat_id, $environmentWaterCategory)) {     //In Case of Category(General Information and Observation) In Environment Department
                        $generalParameterList[$values->test_para_cat_id]['categorySortBy']       = $values->category_sort_by;
                        $generalParameterList[$values->test_para_cat_id]['categoryId']           = $values->test_para_cat_id;
                        $generalParameterList[$values->test_para_cat_id]['categoryName']         = $values->test_para_cat_name;
                        $generalParameterList[$values->test_para_cat_id]['categoryNameSymbol']     = $values->non_nabl_category_symbol;
                        $generalParameterList[$values->test_para_cat_id]['categoryParams'][]     = $values;
                    } else {
                        $orderParameterList[$values->test_para_cat_id]['categorySortBy']       = $values->category_sort_by;
                        $orderParameterList[$values->test_para_cat_id]['categoryId']           = $values->test_para_cat_id;
                        $orderParameterList[$values->test_para_cat_id]['categoryName']         = $values->test_para_cat_name;
                        $orderParameterList[$values->test_para_cat_id]['categoryNameSymbol']     = $values->non_nabl_category_symbol;
                        $orderParameterList[$values->test_para_cat_id]['categoryParams'][]     = $values;
                    }
                }
            }
        }

        $generalParameterListArr     = !empty($generalParameterList) ? $models->sortArrayAscOrder(array_values($generalParameterList)) : array();
        $orderParameterListArr       = !empty($orderParameterList) ? $models->sortArrayAscOrder(array_values($orderParameterList)) : array();
        $descriptionParameterListArr = !empty($descriptionParameterList) ? $models->sortArrayAscOrder(array_values($descriptionParameterList)) : array();
        $maxTatInDayNumber           = !empty($orderTatInDayDetail) ? max($orderTatInDayDetail) : '0';
        $totalOrderParameters        = !empty($testProductStdParaList) ? count($testProductStdParaList) : '0';

        if (!empty($orderList) && !empty($orderParameterList)) {
            $returnData['order']                                = $orderList;
            $returnData['order']->hasClaimValue                 = array_filter($orderHasClaimValueOrNot);
            $returnData['order']->hasMicrobiologicalEquipment   = !empty($orderList->report_microbiological_name) ? $orderList->report_microbiological_name : '0';
            $returnData['order']->commenced_on_date             = !empty($commencedOnData->scheduled_at) ? $commencedOnData->scheduled_at : '';
            $returnData['totalOrderParameters']                 = $totalOrderParameters;
            $returnData['maxTatInDayNumber']                    = $maxTatInDayNumber;
            $returnData['descriptionParameters']                = $descriptionParameterListArr;
            $returnData['generalParameters']                    = $generalParameterListArr;
            $returnData['orderParameters']                      = $orderParameterListArr;
            $returnData['orderEquipments']                      = array_filter($orderEquipmentDetail);
            $returnData['orderTrackRecord']                     = $orderPerformerRecord;
            $returnData['quality']                              = $checkReportQuality;
            $returnData                                         = json_decode(json_encode($returnData), true);
        }
        return $returnData;
    }

    /**
     * Create And Update Header Note
     * Author : Praveen Singh
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createAndUpdateHeaderNote($headerNote)
    {

        $dataSave                  = array();
        $dataSave['header_name']   = $headerNote;
        $dataSave['header_status'] = '1';
        $dataSave['created_by']    = USERID;

        $data = DB::table('order_header_notes')->where('order_header_notes.header_name', '=', $headerNote)->first();
        if (!empty($data->header_name)) {
            return $data->header_name;
        } else {
            DB::table('order_header_notes')->insertGetId($dataSave);
            return $headerNote;
        }
    }

    /**
     * Create And Update Stability Note
     * Author : Praveen Singh
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createAndUpdateStabilityNote($stabilityNote)
    {

        $dataSave                     = array();
        $dataSave['stability_name']   = $stabilityNote;
        $dataSave['stability_status'] = '1';
        $dataSave['created_by']       = USERID;

        $data = DB::table('order_stability_notes')->where('order_stability_notes.stability_name', '=', $stabilityNote)->first();
        if (!empty($data->stability_name)) {
            return $data->stability_name;
        } else {
            DB::table('order_stability_notes')->insertGetId($dataSave);
            return $stabilityNote;
        }
    }

    /**
     * check Booking And Sample Receiving Date
     * Author : Praveen Singh
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkBookingAndSampleReceivingDate($orderDate, $sampleId)
    {
        if (!empty($orderDate) && !empty($sampleId)) {
            $sampleData = DB::table('samples')->where('samples.sample_id', $sampleId)->first();
            if (!empty($sampleData->sample_date)) {
                $orderDate  = date('Y-m-d', strtotime($orderDate));
                $sampleDate = date('Y-m-d', strtotime($sampleData->sample_date));
                if (strtotime($orderDate) >= strtotime($sampleDate)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * check Booking And Sample Receiving Date
     * Author : Praveen Singh
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkBookingAndSamplingDate($orderDate, $samplingDate)
    {
        if (strtotime($samplingDate) <= strtotime($orderDate)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * check Booking And Sample Receiving Date
     * Author : Praveen Singh
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getOrderDate($orderId)
    {
        $orderDate = DB::table('order_master')->where('order_master.order_id', $orderId)->first();
        return !empty($orderDate) ? $orderDate->order_date : '0';
    }

    /**
     * Checking Sample Receiving Category and Test product Category
     * Author : Praveen Singh
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    function checkSampleAndTestProductCategory($sampleId, $productCategoryId)
    {
        $sampleData = DB::table('samples')->where('samples.sample_id', $sampleId)->where('samples.product_category_id', $productCategoryId)->first();
        return !empty($sampleData) ? true : false;
    }

    /**
     * Checking Sample Receiving Category and Test product Category
     * Author : Praveen Singh
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    function getOrderPerformerRecord($orderId)
    {
        $returnData = array();

        $orderLogList = DB::table('order_process_log')
            ->join('order_master', 'order_master.order_id', 'order_process_log.opl_order_id')
            ->where('order_process_log.opl_order_id', $orderId)
            ->where('order_process_log.opl_amend_status', '0')
            ->whereNotNull('order_process_log.opl_order_id')
            ->groupBy('order_process_log.opl_order_status_id')
            ->orderBy('order_process_log.opl_order_status_id', 'ASC')
            ->get();
        if (!empty($orderLogList)) {
            foreach ($orderLogList as $key => $values) {
                $orderLog = DB::table('order_process_log')
                    ->join('order_master', 'order_master.order_id', 'order_process_log.opl_order_id')
                    ->join('order_status', 'order_status.order_status_id', 'order_process_log.opl_order_status_id')
                    ->join('users as oplUser', 'oplUser.id', 'order_process_log.opl_user_id')
                    ->join('users as bookingUser', 'bookingUser.id', 'order_master.created_by')
                    ->select('order_process_log.*', 'oplUser.name as oplUsername', 'oplUser.user_signature as opl_user_signature', 'bookingUser.name as bookingUserName', 'bookingUser.user_signature as booking_user_signature', 'order_status.order_status_name', 'oplUser.user_signature', 'order_process_log.opl_date as view_date')
                    ->where('opl_order_id', $values->opl_order_id)
                    ->where('opl_order_status_id', $values->opl_order_status_id)
                    ->orderBy('opl_id', 'DESC')
                    ->first();
                if (!empty($orderLog)) {
                    if ($orderLog->opl_order_status_id == '1') $orderLog->oplUsername                     = $orderLog->bookingUserName;
                    if ($orderLog->opl_order_status_id == '1') $orderLog->opl_user_signature              = $orderLog->booking_user_signature;
                    if ($orderLog->opl_order_status_id == '7') $orderLog->order_status_name               = 'approving';
                    $returnData[strtolower($orderLog->order_status_name)]['user_id']                      = $orderLog->opl_user_id;
                    $returnData[strtolower($orderLog->order_status_name)]['username']                     = $orderLog->oplUsername;
                    $returnData[strtolower($orderLog->order_status_name)]['status_id']                    = $orderLog->opl_order_status_id;
                    $returnData[strtolower($orderLog->order_status_name)]['user_signature_text']          = defined('AUTHORIZED_SIGNATORY_TEXT') && AUTHORIZED_SIGNATORY_TEXT ? AUTHORIZED_SIGNATORY_TEXT  : 'Authorized Signatory';
                    $returnData[strtolower($orderLog->order_status_name)]['status_name']                  = $orderLog->order_status_name;
                    $returnData[strtolower($orderLog->order_status_name)]['user_signature']               = $orderLog->opl_user_signature;
                    $returnData[strtolower($orderLog->order_status_name)]['user_signature_file_path']     = DOC_ROOT . SIGN_PATH . $orderLog->opl_user_signature;
                    $returnData[strtolower($orderLog->order_status_name)]['user_signature_file_url']      = SITE_URL . SIGN_PATH . $orderLog->opl_user_signature;
                    $returnData[strtolower($orderLog->order_status_name)]['report_view_date']             = date('d-m-Y', strtotime($orderLog->view_date));
                    $returnData[strtolower($orderLog->order_status_name)]['report_view_date_time']        = trim($orderLog->view_date);
                }
            }

            //Getting Microbiological Name and Signature as per the assigned equipments by the admin for this report
            $orderReportMicrobiologicalData = DB::table('order_report_microbiological_dtl')->where('order_report_microbiological_dtl.report_id', $orderId)->first();
            if (!empty($orderReportMicrobiologicalData)) {
                $returnData['microbiological_detail']['user_id']                      = $orderReportMicrobiologicalData->user_id;
                $returnData['microbiological_detail']['username']                     = $orderReportMicrobiologicalData->report_microbiological_name;
                $returnData['microbiological_detail']['status_id']                    = '0';
                $returnData['microbiological_detail']['status_name']                  = 'microbiological_detail';
                $returnData['microbiological_detail']['user_signature_text']          = 'Authorized Signatory- Biological';
                $returnData['microbiological_detail']['user_signature']               = $orderReportMicrobiologicalData->report_microbiological_sign;
                $returnData['microbiological_detail']['user_signature_file_path']     = DOC_ROOT . SIGN_PATH . $orderReportMicrobiologicalData->report_microbiological_sign;
                $returnData['microbiological_detail']['user_signature_file_url']      = SITE_URL . SIGN_PATH . $orderReportMicrobiologicalData->report_microbiological_sign;
                $returnData['microbiological_detail']['report_view_date']             = date('d-m-Y', strtotime($orderReportMicrobiologicalData->created_at));
                $returnData['microbiological_detail']['report_view_date_time']        = $orderReportMicrobiologicalData->created_at;
            }

            //Getting Reviewer Name and Signature as per the assigned equipments by the admin for this report
            $reviewerInstrumentWiseData = DB::table('order_report_middle_authorized_signs')->where('order_report_middle_authorized_signs.ormad_order_id', $orderId)->first();
            if (!empty($reviewerInstrumentWiseData)) {
                $returnData['reviewer_equipment_wise']['user_id']                      = $reviewerInstrumentWiseData->ormad_employee_id;
                $returnData['reviewer_equipment_wise']['username']                     = $reviewerInstrumentWiseData->ormad_employee_name;
                $returnData['reviewer_equipment_wise']['status_id']                    = '0';
                $returnData['reviewer_equipment_wise']['status_name']                  = 'reviewer_equipment_wise_detail';
                $returnData['reviewer_equipment_wise']['user_signature_text']          = 'Authorized Signatory-Residue in Food';
                $returnData['reviewer_equipment_wise']['user_signature']               = $reviewerInstrumentWiseData->ormad_employee_sign;
                $returnData['reviewer_equipment_wise']['user_signature_file_path']     = DOC_ROOT . SIGN_PATH . $reviewerInstrumentWiseData->ormad_employee_sign;
                $returnData['reviewer_equipment_wise']['user_signature_file_url']      = SITE_URL . SIGN_PATH . $reviewerInstrumentWiseData->ormad_employee_sign;
                $returnData['reviewer_equipment_wise']['report_view_date']             = date('d-m-Y', strtotime($reviewerInstrumentWiseData->created_at));
                $returnData['reviewer_equipment_wise']['report_view_date_time']        = $reviewerInstrumentWiseData->created_at;
            }
        }
        //echo '<pre>';print_r($returnData);die;
        return $returnData;
    }

    /**
     * Checking Sample Receiving Category and Test product Category
     * Author : Praveen Singh
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    function getOrderPerformerRecordAll($orderId)
    {
        $returnData = array();

        $orderData = $this->getOrderDetail($orderId);

        $orderLogList = DB::table('order_process_log')
            ->join('order_master', 'order_master.order_id', 'order_process_log.opl_order_id')
            ->where('order_process_log.opl_order_id', $orderId)
            ->where('order_process_log.opl_amend_status', '0')
            ->whereNotNull('order_process_log.opl_order_id')
            ->where('order_process_log.opl_order_status_id', '<=', !empty($orderData->status) ? $orderData->status : '1')
            ->groupBy('order_process_log.opl_order_status_id')
            ->orderBy('order_process_log.opl_order_status_id', 'ASC')
            ->get();
        if (!empty($orderLogList)) {
            foreach ($orderLogList as $key => $values) {
                $orderLog = DB::table('order_process_log')
                    ->join('order_master', 'order_master.order_id', 'order_process_log.opl_order_id')
                    ->join('order_status', 'order_status.order_status_id', 'order_process_log.opl_order_status_id')
                    ->leftJoin('users as oplUser', 'oplUser.id', 'order_process_log.opl_user_id')
                    ->leftJoin('users as bookingUser', 'bookingUser.id', 'order_master.created_by')
                    ->select('order_process_log.*', 'oplUser.name as oplUsername', 'oplUser.user_signature as opl_user_signature', 'bookingUser.name as bookingUserName', 'bookingUser.user_signature as booking_user_signature', 'order_status.order_status_name', 'oplUser.user_signature', 'order_process_log.opl_date as view_date')
                    ->where('opl_order_id', $values->opl_order_id)
                    ->where('opl_order_status_id', $values->opl_order_status_id)
                    ->orderBy('opl_id', 'DESC')
                    ->first();

                if (!empty($orderLog)) {
                    if ($orderLog->opl_order_status_id == '1') $orderLog->oplUsername               = $orderLog->bookingUserName;
                    if ($orderLog->opl_order_status_id == '1') $orderLog->opl_user_signature      = $orderLog->booking_user_signature;
                    if ($orderLog->opl_order_status_id == '7') $orderLog->order_status_name       = 'approving';
                    $returnData[strtolower($orderLog->order_status_name)]['user_id']            = $orderLog->opl_user_id;
                    $returnData[strtolower($orderLog->order_status_name)]['username']           = $orderLog->oplUsername;
                    $returnData[strtolower($orderLog->order_status_name)]['status_id']        = $orderLog->opl_order_status_id;
                    $returnData[strtolower($orderLog->order_status_name)]['status_name']        = $orderLog->order_status_name;
                    $returnData[strtolower($orderLog->order_status_name)]['user_signature']     = $orderLog->opl_user_signature;
                    $returnData[strtolower($orderLog->order_status_name)]['report_view_date']     = date('d-m-Y', strtotime($orderLog->view_date));
                    $returnData[strtolower($orderLog->order_status_name)]['report_view_date_time'] = trim($orderLog->view_date);
                }
            }
        }
        return $returnData;
    }

    /**
     * function to update smaple name on editing a order
     * Author : Praveen Singh
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAliasOnUpdateOrderSampleName($orderId, $submitedRawData)
    {

        //parsing the raw form data
        parse_str($submitedRawData, $submitedData);

        if (!empty($submitedData['sample_description'])) {
            $orderData            = DB::table('order_master')->select('order_master.order_id', 'order_master.product_id')->where('order_master.order_id', '=', $orderId)->first();
            $sampleDescriptionData = DB::table('product_master_alias')->where('product_master_alias.c_product_name', '=', trim($submitedData['sample_description']))->where('product_master_alias.product_id', '=', trim($orderData->product_id))->first();
            if (!empty($sampleDescriptionData)) {
                return $sampleDescriptionData->c_product_id;
            } else {
                $dataSave            = array();
                $dataSave['c_product_name']     = trim($submitedData['sample_description']);
                $dataSave['product_id']     = $orderData->product_id;
                $dataSave['created_by']     = USERID;
                $dataSave['view_type']         = '1';
                $dataSave['c_product_status']     = '1';
                return DB::table('product_master_alias')->insertGetId($dataSave);
            }
        }
    }

    /*********************
     * function to get last stage of order before hold
     * @param  int  $id(19-Jan-2018)
     * @return \Illuminate\Http\Response
     ***********************/
    public function getLastOrderStage($order_id)
    {
        $data = DB::table('order_process_log')
            ->where('order_process_log.opl_order_id', $order_id)
            ->where('order_process_log.opl_order_status_id', '<>', '12')
            ->where('order_process_log.opl_amend_status', '=', '0')
            ->orderBy('order_process_log.opl_id', 'DESC')
            ->first();
        return !empty($data->opl_order_status_id) ? $data->opl_order_status_id : '1';
    }

    /*********************
     * function to get last stage of order before hold
     * @param  int  $id(19-Jan-2018)
     * @return \Illuminate\Http\Response
     ***********************/
    public function checkUpdateSampleReceivingCustomer($customerId, $orderId)
    {
        $data = DB::table('order_master')->where('order_master.order_id', '=', $orderId)->where('order_master.customer_id', '=', $customerId)->first();
        if (empty($data)) {
            $orderData = DB::table('order_master')->where('order_master.order_id', '=', $orderId)->first();
            if (!empty($orderData->sample_id)) {
                $hasMultipleBooking = DB::table('order_master')->where('order_master.sample_id', '=', $orderData->sample_id)->count();
                if ($hasMultipleBooking == '1') {
                    DB::table('samples')->where('samples.sample_id', '=', $orderData->sample_id)->update(['samples.customer_id' => $customerId]);
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return true;
        }
    }

    /**
     * check is Booking Order Amended Or Not
     * Author : Praveen Singh
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function isBookingOrderAmendedOrNot($orderId)
    {
        return DB::table('order_process_log')->where('order_process_log.opl_order_id', '=', !empty($orderId) ? $orderId : '0')->where('order_process_log.opl_amend_status', '1')->first();
    }

    /**
     * check is Booking Order Amended Or Not
     * Author : Praveen Singh
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function verifyBookingOrderAmendedOrNot($orderId)
    {
        if (!empty($orderId)) {
            $amendStatus = DB::table('order_process_log')
                ->where('order_process_log.opl_order_id', '=', $orderId)
                ->where('order_process_log.opl_amend_status', '1')
                ->first();

            $amendedWithInvoiceStatus =  DB::table('order_process_log')
                ->where('order_process_log.opl_order_id', '=', $orderId)
                ->where('order_process_log.opl_order_status_id', '8')
                ->whereNotNull('order_process_log.opl_user_id')
                ->first();

            if (empty($amendedWithInvoiceStatus) && !empty($amendStatus)) {
                return $amendStatus;
            }
        } else {
            return false;
        }
    }

    /**
     *02-June-2018
     *function to check invoice generated or not .
     *while editing order(not to add parameters once order invoice is generated)
     */
    public function isOrderInvoiceGenerated($orderId)
    {
        return DB::table('invoice_hdr_detail')->where('invoice_hdr_detail.invoice_hdr_status', '1')->where('invoice_hdr_detail.order_id', '=', !empty($orderId) ? $orderId : '0')->first();
    }

    /***********************************************
     *function to Check the Order is cancelled Or Not
     *Created On :28-May-2018
     *Created By:Praveen-Singh
     **********************************************/
    public function isOrderBookingCancelled($orderId)
    {
        return DB::table('order_master')->where('order_master.order_id', '=', $orderId)->where('order_master.status', '10')->first();
    }

    /***********************************************
     *function to Check the Order is Hold Or Not
     *Created On :28-May-2018
     *Created By:Praveen-Singh
     **********************************************/
    public function isOrderBookingHoldByUser($orderId)
    {
        return DB::table('order_master')->where('order_master.order_id', '=', $orderId)->where('order_master.status', '12')->first();
    }

    /***********************************************
     *function to Check the Order is Hold Or Not
     *Created On :28-May-2018
     *Created By:Praveen-Singh
     **********************************************/
    public function isOrderSchedulingCompleted($orderId)
    {
        return DB::table('schedulings')->where('schedulings.order_id', '=', $orderId)->where('schedulings.status', '<>', '0')->whereNotNull('schedulings.completed_at')->first();
    }

    /***********************************************
     *function to Check the Order is cancelled Or Not
     *Created On :28-May-2018
     *Created By:Praveen-Singh
     **********************************************/
    public function isOrderBackDateBooking($orderId)
    {
        $orderDetail  = $this->getOrderDetail($orderId);
        $bookingDate  = strtotime(date(MYSQLDATFORMAT, strtotime($orderDetail->booking_date)));
        $orderDate    = strtotime(date(MYSQLDATFORMAT, strtotime($orderDetail->order_date)));
        return $bookingDate == $orderDate ? false : true;
    }

    /*************************
     *Getting Prototype No of Stability Order if Order belongs to Stability Module
     *Created By : Praveen Singh
     *Created On : 31-Jan-2019
     *************************/
    function isOrderStabilityOrder($orderId)
    {
        $orderDetail = $this->getOrderDetail($orderId);
        return DB::table('stb_order_hdr')->join('stb_order_hdr_dtl_detail', 'stb_order_hdr_dtl_detail.stb_order_hdr_id', 'stb_order_hdr.stb_order_hdr_id')->where('stb_order_hdr_dtl_detail.stb_order_hdr_detail_id', '=', !empty($orderDetail->stb_order_hdr_detail_id) ? $orderDetail->stb_order_hdr_detail_id : '0')->first();
    }

    /***********************************************
     *function to Check the Order is Inter-laboratory Or Compensatory Or Not
     *Created On :28-May-2018
     *Created By:Praveen-Singh
     **********************************************/
    public function hasOrderInterLabOrCompensatory($orderId)
    {
        return DB::table('order_master')->where('order_master.order_id', '=', $orderId)->whereIn('order_master.order_sample_type', array('1', '2'))->first();
    }

    /*****************************************************
     * Function : Updating Sample Receiving Customer_id ID if User changes the Customer Name of Booked Order
     * Created On :01-June-2018
     * Created By:Praveen-Singh
     ******************************************************/
    public function updateCustomerDetailInSamplesAOrderMaster($submitedData, $orderId)
    {
        $data = DB::table('order_master')->where('order_master.order_id', '=', $orderId)->where('order_master.customer_id', '=', $submitedData['customer_id'])->first();
        if (empty($data)) {
            $orderData = DB::table('order_master')->select('order_master.order_id', 'order_master.sample_id', 'order_master.customer_id')->where('order_master.order_id', '=', $orderId)->first();
            if (!empty($orderData->sample_id)) {
                $allOrderBooking = DB::table('order_master')->select('order_master.order_id', 'order_master.sample_id', 'order_master.customer_id')->where('order_master.sample_id', '=', $orderData->sample_id)->get();
                if (!empty($allOrderBooking)) {

                    //Updating Customer Name in Sample Table
                    DB::table('samples')->where('samples.sample_id', '=', $orderData->sample_id)->update(['samples.customer_id' => $submitedData['customer_id']]);

                    $dataUpdate = array(
                        'order_master.customer_id'     => $submitedData['customer_id'],
                        'order_master.customer_city'    => $submitedData['customer_city'],
                        'order_master.mfg_lic_no'     => $submitedData['mfg_lic_no'],
                        'order_master.sale_executive'    => $submitedData['sale_executive'],
                        'order_master.discount_type_id'    => $submitedData['discount_type_id'],
                        'order_master.discount_value'    => $submitedData['discount_value'],
                        'order_master.invoicing_type_id' => $submitedData['invoicing_type_id'],
                        'order_master.billing_type_id'    => $submitedData['billing_type_id'],
                    );

                    //Updating Customer Detail in Order Master
                    foreach ($allOrderBooking as $OrderBooking) {
                        DB::table('order_master')->where('order_id', '=', $OrderBooking->order_id)->update($dataUpdate);
                    }
                    return true;
                }
            }
        }
        return true;
    }

    /*************************
     *function to get Report header and footer on pdf's
     *Date : 11-06-2018
     *Created By:Praveen Singh
     *************************/
    public function getDynamicHeaderFooterTemplate($template_type_id, $division_id = NULL, $product_category_id = NULL)
    {
        $data = DB::table('template_dtl')
            ->select('template_dtl.header_content', 'template_dtl.footer_content')
            ->where('template_dtl.template_type_id', '=', $template_type_id)
            ->when(!empty($division_id), function ($query) use ($division_id) {
                return $query->where('template_dtl.division_id', '=', $division_id);
            })
            ->when(!empty($product_category_id), function ($query) use ($product_category_id) {
                return $query->where('template_dtl.product_category_id', '=', $product_category_id);
            })
            ->where('template_dtl.template_status_id', '=', '1')
            ->first();
        return !empty($data) ? array($data->header_content, $data->footer_content) : array(0, 0);
    }

    /**
     * Updating Order Scheduled Date and Time of a particular Order
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateOrderScheduledDateTime($orderId, $currentDateTime)
    {
        //Updating Order Scheduled Date and Time
        if ($orderId && $currentDateTime) {
            return DB::table('order_master')->where('order_master.order_id', $orderId)->update(['order_master.order_scheduled_date' => $currentDateTime]);
        }
    }

    /**
     * Updating Test Completion Date and Time of a particular Order
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateTestCompletionDateTime($orderId, $currentDateTime)
    {
        //Updating Test Completion Date
        if ($orderId && $currentDateTime) {
            return DB::table('order_master')->where('order_master.order_id', $orderId)->update(['order_master.test_completion_date' => $currentDateTime]);
        }
    }

    /*************************
     *scope-2
     *function to get order parameters
     * assigned to a tester (employee)
     *************************/
    function getAsssignedOrderParameterForSectionIncharge($order_id, $equipment_ids)
    {
        return DB::table('order_parameters_detail')
            ->join('schedulings', 'schedulings.order_parameter_id', 'order_parameters_detail.analysis_id')
            ->join('product_test_dtl', 'product_test_dtl.product_test_dtl_id', 'order_parameters_detail.product_test_parameter')
            ->join('product_test_hdr', 'product_test_dtl.test_id', 'product_test_hdr.test_id')
            ->join('test_parameter', 'test_parameter.test_parameter_id', 'order_parameters_detail.test_parameter_id')
            ->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
            ->leftJoin('equipment_type', 'equipment_type.equipment_id', 'order_parameters_detail.equipment_type_id')
            ->leftJoin('method_master', 'method_master.method_id', 'order_parameters_detail.method_id')
            ->leftJoin('detector_master', 'detector_master.detector_id', 'order_parameters_detail.detector_id')
            ->leftJoin('customer_invoicing_running_time', 'customer_invoicing_running_time.invoicing_running_time_id', 'order_parameters_detail.running_time_id')
            ->select('order_parameters_detail.*', 'test_parameter.test_parameter_code', 'test_parameter.test_parameter_name', 'equipment_type.equipment_name', 'method_master.method_name', 'test_parameter_categories.test_para_cat_id', 'test_parameter_categories.test_para_cat_name', 'test_parameter_categories.product_category_id', 'product_test_dtl.description', 'test_parameter_categories.category_sort_by', 'detector_master.detector_name', 'customer_invoicing_running_time.invoicing_running_time')
            ->where('order_parameters_detail.order_id', '=', $order_id)
            ->whereIn('order_parameters_detail.equipment_type_id', $equipment_ids)
            ->orderBy('product_test_dtl.parameter_sort_by', 'ASC')
            ->get();
    }

    /**
     * Convert a multi-dimensional array into a single-dimensional array.
     * @author Sean Cannon, LitmusBox.com | seanc@litmusbox.com
     * @param  array $array The multi-dimensional array.
     * @return array
     */
    function assignInvoicingGroupForAssigningRates($values, $sampleData)
    {

        global $order, $models;

        $invoicingGroupName = '';

        if (!empty($sampleData->invoicing_type_id) && $sampleData->invoicing_type_id == '4') {

            $values      = $models->convertObjectToArray($values);
            $subValueObj = DB::table('product_test_dtl')
                ->select('parentProductCategoryDB.p_category_id as product_category_id', 'productCategoryDB.p_category_id', 'subProductCategoryDB.p_category_id as sub_p_category_id', 'test_parameter_categories.test_para_cat_id as test_parameter_category_id')
                ->join('product_test_hdr', 'product_test_hdr.test_id', 'product_test_dtl.test_id')
                ->join('product_master', 'product_master.product_id', 'product_test_hdr.product_id')
                ->join('product_categories as subProductCategoryDB', 'subProductCategoryDB.p_category_id', 'product_master.p_category_id')
                ->join('product_categories as productCategoryDB', 'productCategoryDB.p_category_id', 'subProductCategoryDB.parent_id')
                ->join('product_categories as parentProductCategoryDB', 'parentProductCategoryDB.p_category_id', 'productCategoryDB.parent_id')
                ->join('test_parameter', 'test_parameter.test_parameter_id', 'product_test_dtl.test_parameter_id')
                ->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id');

            if (!empty($values['order_id'])) { //In case of Editing of Order
                $subValueObj->join('order_parameters_detail', 'order_parameters_detail.product_test_parameter', 'product_test_dtl.product_test_dtl_id');
                $subValueObj->where('order_parameters_detail.order_id', $values['order_id']);
                $subValueObj->where('order_parameters_detail.product_test_parameter', $values['product_test_parameter']);
            }
            $subValueObj->where('product_test_dtl.product_test_dtl_id', $values['product_test_parameter']);
            $subValueObj->where('test_parameter.test_parameter_id', $values['test_parameter_id']);
            $subValues = $models->convertObjectToArray($subValueObj->first());

            //Merging Values and Sub Vaules
            $orderParameters = !empty($subValues) ? array_merge($values, $subValues) : $values;
            if (!empty($orderParameters)) {
                if (!empty($orderParameters['product_category_id']) && $orderParameters['product_category_id'] != '2') {
                    $invoicingGroupName = $orderParameters['test_parameter_id'];
                } else {
                    //Checking the global Invoicing allowed to the parameters
                    $isInvoicingNeeded = DB::table('test_parameter')->where('test_parameter.test_parameter_id', $orderParameters['test_parameter_id'])->where('test_parameter.test_parameter_invoicing', '1')->first();
                    if (!empty($isInvoicingNeeded) && !empty($orderParameters['test_parameter_id']) && !empty($orderParameters['equipment_type_id'])) {
                        if (!empty($orderParameters['detector_id']) && !empty($orderParameters['running_time_id']) && !empty($orderParameters['no_of_injection'])) {    //checking If Detector,Running Time,no of Injection exist
                            if (!empty($orderParameters['test_parameter_category_id']) && $orderParameters['test_parameter_category_id'] == '1') {
                                if (!empty($orderParameters['test_parameter_invoicing_parent_id'])) {
                                    $invoicingGroupName = $orderParameters['test_parameter_category_id'] . '-' . $orderParameters['test_parameter_invoicing_parent_id'] . '-' . $orderParameters['product_category_id'] . '-' . $orderParameters['p_category_id'] . '-' . $orderParameters['sub_p_category_id'] . '-' . $orderParameters['equipment_type_id'] . '-' . $orderParameters['detector_id'] . '-' . $orderParameters['running_time_id'];
                                } else {
                                    $invoicingGroupName = $orderParameters['test_parameter_id'];
                                }
                            } else {
                                $invoicingGroupName = $orderParameters['test_parameter_category_id'] . '-' . $orderParameters['product_category_id'] . '-' . $orderParameters['p_category_id'] . '-' . $orderParameters['sub_p_category_id'] . '-' . $orderParameters['equipment_type_id'] . '-' . $orderParameters['detector_id'] . '-' . $orderParameters['running_time_id'];
                            }
                        } else {
                            if (!empty($orderParameters['test_parameter_category_id'])) {
                                if (!empty($orderParameters['test_parameter_category_id']) && $orderParameters['test_parameter_category_id'] == '1') {
                                    if (!empty($orderParameters['test_parameter_invoicing_parent_id'])) {
                                        $invoicingGroupName = $orderParameters['test_parameter_category_id'] . '-' . $orderParameters['test_parameter_invoicing_parent_id'] . '-' . $orderParameters['product_category_id'] . '-' . $orderParameters['p_category_id'] . '-' . $orderParameters['sub_p_category_id'] . '-' . $orderParameters['equipment_type_id'];
                                    } else {
                                        $invoicingGroupName = $orderParameters['test_parameter_id'];
                                    }
                                } else {
                                    $invoicingGroupName = $orderParameters['test_parameter_category_id'] . '-' . $orderParameters['product_category_id'] . '-' . $orderParameters['p_category_id'] . '-' . $orderParameters['sub_p_category_id'] . '-' . $orderParameters['equipment_type_id'];
                                }
                            }
                        }
                    }
                }
            }
        }
        return $invoicingGroupName;
    }

    /**
     * Get list of companies on page load.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getOrderInvoicingRatesInDetail($sampleData, $orderParametersDetail)
    {

        global $order, $models;

        $parameterWiseRateData = array();

        $customer_id          = !empty($sampleData->customer_id) ? $sampleData->customer_id : '0';
        $customer_city          = !empty($sampleData->customer_city) ? $sampleData->customer_city : '0';
        $customer_state         = !empty($sampleData->customer_state) ? $sampleData->customer_state : '0';
        $invoicing_type_id   = !empty($sampleData->invoicing_type_id) ? $sampleData->invoicing_type_id : '0';
        $division_id          = !empty($sampleData->division_id) ? $sampleData->division_id : '0';
        $product_category_id = !empty($sampleData->product_category_id) ? $sampleData->product_category_id : '0';

        //Conditional Invoicing Type*********************************
        if (!empty($invoicing_type_id) && !empty($product_category_id)) {
            if ($invoicing_type_id == '4') {        //Customer Wise Parameters			
                if ($product_category_id == '2') {
                    $parameterWiseRateData = $this->getCustomerWiseAssayParameterRates($invoicing_type_id, $customer_id, $division_id, $product_category_id, $models->convertObjectToArray($orderParametersDetail), $returnType = array(1));
                } else {
                    $parameterWiseRateData = $this->getCustomerWiseParameterRates($invoicing_type_id, $customer_id, $division_id, $product_category_id, $models->convertObjectToArray($orderParametersDetail), $returnType = array(1));
                }
            }
        }
        return $parameterWiseRateData;
    }

    /*************************
     *function to get formated date and time
     *Check date and time format
     *************************/
    function validatePODate($date, $format = 'Y-m-d')
    {
        $dateArray = explode('-', $date);
        if (!empty($dateArray[0]) && !empty($dateArray[1]) && !empty($dateArray[2]) && checkdate($dateArray[1], $dateArray[0], $dateArray[2])) {
            return date($format, strtotime($date)) . ' ' . date("H:i:s");
        } else {
            return NULL;
        }
    }

    /*********************************************************
     *get Orde Equipment Incharge Detail
     *Created on:16-Aug-2018
     *Created By:Praveen Singh
     *********************************************************/
    public function getOrderEquipmentInchargeDetail($orderId)
    {

        $sectionIncharges = array();

        $orderData = DB::table('order_master')->select('order_master.order_id', 'order_master.division_id', 'order_master.product_category_id')->where('order_master.order_id', $orderId)->first();
        if (!empty($orderData->order_id)) {
            $equipmentTypeIds = DB::table('order_parameters_detail')->where('order_id', $orderId)->whereNotNull('order_parameters_detail.equipment_type_id')->groupBy('order_parameters_detail.equipment_type_id')->pluck('order_parameters_detail.equipment_type_id')->all();
            $userEqipDetail   = DB::table('users_equipment_detail')
                ->join('users', 'users.id', 'users_equipment_detail.user_id')
                ->join('users_department_detail', 'users_department_detail.user_id', 'users.id')
                ->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('department_product_categories_link', 'department_product_categories_link.department_id', 'users_department_detail.department_id')
                ->whereIn('users_equipment_detail.equipment_type_id', array_values($equipmentTypeIds))
                ->where('role_user.role_id', '=', '7')
                ->where('users.division_id', '=', $orderData->division_id)
                ->where('users.status', '=', '1')
                ->where('department_product_categories_link.product_category_id', '=', $orderData->product_category_id)
                ->select('users_equipment_detail.user_id', 'users_equipment_detail.equipment_type_id')
                ->groupBy('users_equipment_detail.user_id', 'users_equipment_detail.equipment_type_id')
                ->get()
                ->toArray();
            if (!empty($userEqipDetail)) {
                foreach ($userEqipDetail as $key => $value) {
                    $sectionIncharges[$value->equipment_type_id][$key] = $value->user_id;
                }
            }
        }

        return $sectionIncharges;
    }

    /*********************************************************
     *get Orde Equipment Incharge Detail
     *Created on:16-Aug-2018
     *Created By:Praveen Singh
     *********************************************************/
    public function getSavedOrderEquipmentInchargeDetail($orderId)
    {

        $sectionIncharges = array();

        $orderInchargeData = DB::table('order_incharge_dtl')->select('order_incharge_dtl.oid_employee_id', 'order_incharge_dtl.oid_equipment_type_id')->where('order_incharge_dtl.order_id', $orderId)->get();
        if (!empty($orderInchargeData)) {
            if (!empty($orderInchargeData)) {
                foreach ($orderInchargeData as $key => $value) {
                    $sectionIncharges[$value->oid_equipment_type_id][$key] = $value->oid_employee_id;
                }
            }
        }

        return $sectionIncharges;
    }

    /*********************************************************
     *has Order Confirmed By All Section Incharges
     *Created on:16-Aug-2018
     *Created By:Praveen Singh
     *********************************************************/
    public function hasOrderConfirmedByAllSectionIncharges($orderId)
    {
        //1 if atleast any one Section Incharge doesnot confirm the report
        //2 if all Section Incharge confirm the report
        $checkUpdateOrderStatus = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $orderId)->whereNull('order_parameters_detail.test_result')->first();
        $orderInchargeDtlStatus = DB::table('order_incharge_dtl')->where('order_incharge_dtl.order_id', '=', $orderId)->whereNull('order_incharge_dtl.oid_confirm_by')->where('order_incharge_dtl.oid_status', '=', '0')->count();
        $orderInchargeProcessDtlStatus = DB::table('order_incharge_process_dtl')->where('order_incharge_process_dtl.oipd_order_id', '=', $orderId)->where('order_incharge_process_dtl.oipd_status', '1')->count();
        return empty($checkUpdateOrderStatus) && empty($orderInchargeDtlStatus) && empty($orderInchargeProcessDtlStatus) ? '2' : '1';
    }

    /*********************************************************
     *Has Micro-biological right to all Section Incharges involved in this Order
     *Created on:10-June-2019
     *Created By:Praveen Singh
     *********************************************************/
    public function hasSectionInchargeMicoEquipAssigned($orderId)
    {
        $flag = array();
        $hasMicrobiologicalEquipment = DB::table('order_parameters_detail')->where('order_parameters_detail.equipment_type_id', '22')->where('order_parameters_detail.order_id', '=', $orderId)->first();
        if (!empty($hasMicrobiologicalEquipment)) {
            $orderInchargeEmplDtl = DB::table('order_incharge_dtl')->where('order_incharge_dtl.order_id', '=', $orderId)->where('order_incharge_dtl.oid_equipment_type_id', '22')->where('order_incharge_dtl.oid_status', '<>', '3')->pluck('order_incharge_dtl.oid_employee_id')->all();
            if (!empty($orderInchargeEmplDtl)) {
                foreach ($orderInchargeEmplDtl as $key => $oidEmployeeId) {
                    $dataFound = DB::table('users_equipment_detail')->where('users_equipment_detail.user_id', '=', $oidEmployeeId)->where('users_equipment_detail.equipment_type_id', '22')->count();
                    if ($dataFound) {
                        $flag[$key] = '1';
                    } else {
                        $flag[$key] = '0';
                    }
                }
            }
            return in_array('0', $flag) ? false : true;
        }
        return true;
    }

    /*********************************************************
     *Has Micro-biological right to all Section Incharges involved in this Order
     *Created on:11-June-2019
     *Created By:Praveen Singh
     *********************************************************/
    public function hasUserMicroEquipmentAllocated($postedData)
    {
        $orderId = !empty($postedData['report_id']) ? $postedData['report_id'] : '0';
        $userId = !empty($postedData['si_user_id']) ? $postedData['si_user_id'] : '0';
        $hasMicrobiologicalEquipment = DB::table('order_parameters_detail')->where('order_parameters_detail.equipment_type_id', '22')->where('order_parameters_detail.order_id', '=', $orderId)->first();
        if (!empty($hasMicrobiologicalEquipment) && !empty($this->getOrderEquipmentInchargeDetail($orderId))) {
            return DB::table('users_equipment_detail')->where('users_equipment_detail.user_id', $userId)->where('users_equipment_detail.equipment_type_id', '22')->first();
        } else {
            return false;
        }
    }

    /*********************************************************
     *save/Update Order Incharge Detail On Login
     *Created on:17-Aug-2018
     *Created By:Praveen Singh
     *********************************************************/
    public function saveUpdateOrderInchargeDetailOnLogin($divisionId, $departmentIds, $roleId)
    {

        global $models, $order, $report, $dashboard;

        if (!empty($roleId) && $roleId == '7') {
            $orderDetail = DB::table('order_master')->whereIn('order_master.status', array('4'))->where('order_master.division_id', $divisionId)->whereIn('order_master.product_category_id', $departmentIds)->pluck('order_master.order_id')->all();
            if (!empty($orderDetail)) {
                foreach ($orderDetail as $key => $orderId) {
                    if (!empty($orderId)) $this->updateOrderEquipmentInchargeDetail_v1($orderId);
                }
            }
        }
    }

    /*********************************************************
     *get Orde Equipment Incharge Detail
     *Created on:16-Aug-2018
     *Created By:Praveen Singh
     *********************************************************/
    public function updateOrderEquipmentInchargeDetail($orderId)
    {

        global $models, $order, $report, $dashboard;

        $newSectionInchargeDetail = $removedSectionInchargeDetail = array();

        $currentSectionInchargeDetail  = $this->getOrderEquipmentInchargeDetail($orderId);
        $previousSectionInchargeDetail = $this->getSavedOrderEquipmentInchargeDetail($orderId);
        $combinedSectionInchargeDetail = $currentSectionInchargeDetail + $previousSectionInchargeDetail;

        //Calculation of New Section Incharges
        if (!empty($combinedSectionInchargeDetail) && isset($previousSectionInchargeDetail)) {
            $previousSectionInchargeDetail = array_keys($previousSectionInchargeDetail);
            foreach ($combinedSectionInchargeDetail as $comEquipmentKey => $comEmployeeValue) {
                if (!in_array($comEquipmentKey, $previousSectionInchargeDetail)) {
                    $newSectionInchargeDetail[$comEquipmentKey] = $comEmployeeValue;
                }
            }
        }

        //Calculation of Removed Section Incharges
        if (!empty($combinedSectionInchargeDetail) && !empty($currentSectionInchargeDetail)) {
            $currentSectionInchargeDetail = array_keys($currentSectionInchargeDetail);
            foreach ($combinedSectionInchargeDetail as $comEquipmentKey => $comEmployeeValue) {
                if (!in_array($comEquipmentKey, $currentSectionInchargeDetail)) {
                    $removedSectionInchargeDetail[$comEquipmentKey] = $comEmployeeValue;
                }
            }
        }

        //Removing the unallocated Section Incharge
        if (!empty($removedSectionInchargeDetail)) {
            foreach ($removedSectionInchargeDetail as $equipmentTypeId => $inchargeIds) {
                $dataUpdate = array('order_incharge_dtl.oid_confirm_date' => CURRENTDATETIME, 'order_incharge_dtl.oid_confirm_by' => end($inchargeIds), 'order_incharge_dtl.oid_status' => '3');
                DB::table('order_incharge_dtl')->where('order_incharge_dtl.order_id', $orderId)->whereIn('order_incharge_dtl.oid_employee_id', $inchargeIds)->where('order_incharge_dtl.oid_equipment_type_id', $equipmentTypeId)->whereIn('order_incharge_dtl.oid_status', array('0', '1'))->update($dataUpdate);
            }
        }

        //Saving New Section Incharge Detail
        if (!empty($newSectionInchargeDetail)) {
            $dataSave = array();
            foreach ($newSectionInchargeDetail as $equipmentTypeId => $inchargeIdArray) {
                if (!empty($inchargeIdArray) && is_array($inchargeIdArray)) {
                    foreach ($inchargeIdArray as $key => $inchargeId) {
                        $checkInchargeExistence = DB::table('order_incharge_dtl')->where('order_incharge_dtl.order_id', $orderId)->where('order_incharge_dtl.oid_employee_id', $inchargeId)->where('order_incharge_dtl.oid_equipment_type_id', $equipmentTypeId)->first();
                        if (empty($checkInchargeExistence)) {
                            $dataSave[$key]['order_id']           = $orderId;
                            $dataSave[$key]['oid_employee_id']        = $inchargeId;
                            $dataSave[$key]['oid_equipment_type_id']  = $equipmentTypeId;
                            $dataSave[$key]['oid_assign_date']        = CURRENTDATETIME;
                            $dataSave[$key]['oid_status']             = '0';
                        }
                    }
                }
            }
            if (!empty($dataSave)) DB::table('order_incharge_dtl')->insert($dataSave);
        }

        //If all Section Incharges confirm the Order,then it will moved to the Reviewer.
        if ($order->hasOrderConfirmedByAllSectionIncharges($orderId) == '2') {

            //Updating Log
            $orderInchargeData             = DB::table('order_incharge_dtl')->select('order_incharge_dtl.oid_employee_id')->where('order_incharge_dtl.order_id', $orderId)->first();
            $dataSave                  = array();
            $dataSave['opl_order_id']          = $orderId;
            $dataSave['opl_order_status_id']     = '4';
            $dataSave['opl_date']         = CURRENTDATETIME;
            $dataSave['opl_user_id']          = !empty($orderInchargeData->oid_employee_id) ? $orderInchargeData->oid_employee_id : NULL;
            DB::table('order_process_log')->insert($dataSave);

            $report->updateReportInchargeReviewingDate($orderId);    //Updating Report Incharge Reviewing Date
            $order->updateOrderStatusToNextPhase($orderId, '5');        //Updating Order Status To Next Phase
        }
    }

    /*********************************************************
     *get Orde Equipment Incharge Detail
     *Created on:16-Aug-2018
     *Created By:Praveen Singh
     *********************************************************/
    public function updateOrderEquipmentInchargeDetail_v1($orderId)
    {

        global $models, $order, $report, $dashboard;

        $combinedSectionInchargeDetail = $newFinalSectionInchargeDetail = $newSectionInchargeDetail = $removedSectionInchargeDetail = $removedSectionInchargeEquipWiseDetail = $removedSectionInchargeUserWiseDetail = $currentSectionInchargeUsers = $previousSectionInchargeUsers = $newSectionInchargeUserDetail = $comRemSectionInchargeUsers = $curRemSectionInchargeUsers = array();

        /************************Global Data****************************************/
        $currentSectionInchargeDetail  = $this->getOrderEquipmentInchargeDetail($orderId);
        $previousSectionInchargeDetail = $this->getSavedOrderEquipmentInchargeDetail($orderId);
        if (!empty($currentSectionInchargeDetail)) {
            foreach ($currentSectionInchargeDetail as $keyEquip => $valAll) {
                if (is_array($valAll)) {
                    foreach ($valAll as $keyIn => $value) {
                        $combinedSectionInchargeDetail[$keyEquip][$value] = $value;
                    }
                }
            }
        }
        if (!empty($previousSectionInchargeDetail)) {
            foreach ($previousSectionInchargeDetail as $keyEquip => $valAll) {
                if (is_array($valAll)) {
                    foreach ($valAll as $key => $value) {
                        $combinedSectionInchargeDetail[$keyEquip][$value] = $value;
                    }
                }
            }
        }
        /************************Global Data****************************************/

        /************NEW EQUIPMENT MATCHING BLOCK ACC TO USER & EQUIOMENT********************/
        //Calculation of New Section Incharges by matching the equipments
        if (!empty($combinedSectionInchargeDetail) && isset($previousSectionInchargeDetail)) {
            $previousSectionInchargeKeyDetail = array_keys($previousSectionInchargeDetail);
            foreach ($combinedSectionInchargeDetail as $comEquipmentKey => $comEmployeeValue) {
                if (!in_array($comEquipmentKey, $previousSectionInchargeKeyDetail)) {
                    $newSectionInchargeDetail[$comEquipmentKey] = $comEmployeeValue;
                }
            }
        }
        //Calculation of New Section Incharges by matching the employees
        if (!empty($combinedSectionInchargeDetail) && !empty($previousSectionInchargeDetail)) {
            foreach ($combinedSectionInchargeDetail as $comEquipmentKey => $comEmployeeValueAll) {
                foreach ($comEmployeeValueAll as $innercomEquipmentKey => $innercomEmployeeValue) {
                    $currentSectionInchargeUsers[] = $comEquipmentKey . '-' . $innercomEmployeeValue;
                }
            }
            foreach ($previousSectionInchargeDetail as $prevEquipmentKey => $prevEmployeeValueAll) {
                foreach ($prevEmployeeValueAll as $innerprevEquipmentKey => $innerprevEmployeeValue) {
                    $previousSectionInchargeUsers[] = $prevEquipmentKey . '-' . $innerprevEmployeeValue;
                }
            }
            if (!empty($currentSectionInchargeUsers) && !empty($previousSectionInchargeUsers)) {
                $comEquipEmpValueData = array_diff($currentSectionInchargeUsers, $previousSectionInchargeUsers);
                if (!empty($comEquipEmpValueData)) {
                    foreach ($comEquipEmpValueData as $comEquipEmpValueKey => $comEquipEmpValue) {
                        $comEmployeeArray = $models->getExplodedData($comEquipEmpValue, '-');
                        if (isset($comEmployeeArray[0]) && isset($comEmployeeArray[1])) {
                            $newSectionInchargeUserDetail[$comEmployeeArray[0]][] = $comEmployeeArray[1];
                        }
                    }
                }
            }
        }
        if (!empty($newSectionInchargeDetail)) {
            foreach ($newSectionInchargeDetail as $keyEquip => $valEquipEmpAll) {
                if (is_array($valEquipEmpAll)) {
                    foreach ($valEquipEmpAll as $key => $value) {
                        $newFinalSectionInchargeDetail[$keyEquip][] = $value;
                    }
                }
            }
        }
        if (!empty($newSectionInchargeUserDetail)) {
            foreach ($newSectionInchargeUserDetail as $keyUEquip => $valUserEmpAll) {
                if (is_array($valUserEmpAll)) {
                    foreach ($valUserEmpAll as $key => $value) {
                        $newFinalSectionInchargeDetail[$keyUEquip][] = $value;
                    }
                }
            }
        }

        //Saving New Section Incharge Detail
        if (!empty($newFinalSectionInchargeDetail)) {
            $dataSave = array();
            $counter = '0';
            foreach ($newFinalSectionInchargeDetail as $equipmentTypeId => $inchargeIdArray) {
                if (!empty($inchargeIdArray) && is_array($inchargeIdArray)) {
                    $inchargeIdOutArray = array_unique($inchargeIdArray);
                    foreach ($inchargeIdOutArray as $key => $inchargeId) {
                        $checkInchargeExistence = DB::table('order_incharge_dtl')->where('order_incharge_dtl.order_id', $orderId)->where('order_incharge_dtl.oid_employee_id', $inchargeId)->where('order_incharge_dtl.oid_equipment_type_id', $equipmentTypeId)->first();
                        if (empty($checkInchargeExistence)) {
                            $dataSave[$counter]['order_id']                 = $orderId;
                            $dataSave[$counter]['oid_employee_id']        = $inchargeId;
                            $dataSave[$counter]['oid_equipment_type_id']  = $equipmentTypeId;
                            $dataSave[$counter]['oid_assign_date']        = CURRENTDATETIME;
                            $dataSave[$counter]['oid_status']             = '0';
                            $counter++;
                        }
                    }
                }
            }
            if (!empty($dataSave)) DB::table('order_incharge_dtl')->insert($dataSave);
        }
        /***********NEW EQUIPMENT MATCHING BLOCK ACC TO USER & EQUIPMENT********************/

        /***********REMOVED EQUIPMENT MATCHING BLOCK ACC TO USER & EQUIPMENT********************/
        //Calculation of Removed Section Incharges by matching the Equipment
        if (!empty($combinedSectionInchargeDetail) && !empty($currentSectionInchargeDetail)) {
            $currentSectionInchargeKeyDetail = array_keys($currentSectionInchargeDetail);
            foreach ($combinedSectionInchargeDetail as $comEquipmentKey => $comEmployeeValue) {
                if (!in_array($comEquipmentKey, $currentSectionInchargeKeyDetail)) {
                    $removedSectionInchargeEquipWiseDetail[$comEquipmentKey] = $comEmployeeValue;
                }
            }
        }
        //Calculation of Removed Section Incharges by matching the employees
        if (!empty($combinedSectionInchargeDetail) && !empty($currentSectionInchargeDetail)) {
            foreach ($combinedSectionInchargeDetail as $comEquipmentKey => $comEmployeeValueAll) {
                foreach ($comEmployeeValueAll as $innercomEquipmentKey => $innercomEmployeeValue) {
                    $comRemSectionInchargeUsers[] = $comEquipmentKey . '-' . $innercomEmployeeValue;
                }
            }
            foreach ($currentSectionInchargeDetail as $curSelEquipmentKey => $curSelEmployeeValueAll) {
                foreach ($curSelEmployeeValueAll as $innerCurSelEquipmentKey => $innerCurSelEmployeeValue) {
                    $curRemSectionInchargeUsers[] = $curSelEquipmentKey . '-' . $innerCurSelEmployeeValue;
                }
            }
            if (!empty($comRemSectionInchargeUsers) && !empty($curRemSectionInchargeUsers)) {
                $comRemEquipEmpValueData = array_diff($comRemSectionInchargeUsers, $curRemSectionInchargeUsers);
                if (!empty($comRemEquipEmpValueData)) {
                    foreach ($comRemEquipEmpValueData as $comEquipEmpValueKey => $comEquipEmpValue) {
                        $comEmployeeArray = $models->getExplodedData($comEquipEmpValue, '-');
                        if (isset($comEmployeeArray[0]) && isset($comEmployeeArray[1])) {
                            $removedSectionInchargeUserWiseDetail[$comEmployeeArray[0]][] = $comEmployeeArray[1];
                        }
                    }
                }
            }
        }
        if (!empty($removedSectionInchargeEquipWiseDetail)) {
            foreach ($removedSectionInchargeEquipWiseDetail as $keyEquip => $valEquipAll) {
                foreach ($valEquipAll as $key => $value) {
                    $removedSectionInchargeDetail[$keyEquip][] = $value;
                }
            }
        }
        if (!empty($removedSectionInchargeUserWiseDetail)) {
            foreach ($removedSectionInchargeUserWiseDetail as $keyUrEquip => $valUserAll) {
                foreach ($valUserAll as $key => $value) {
                    $removedSectionInchargeDetail[$keyUrEquip][] = $value;
                }
            }
        }
        //Removing the unallocated Section Incharge
        if (!empty($removedSectionInchargeDetail)) {
            foreach ($removedSectionInchargeDetail as $equipmentTypeId => $inchargeIds) {
                $dataUpdate = array('order_incharge_dtl.oid_confirm_date' => CURRENTDATETIME, 'order_incharge_dtl.oid_confirm_by' => end($inchargeIds), 'order_incharge_dtl.oid_status' => '3');
                DB::table('order_incharge_dtl')->where('order_incharge_dtl.order_id', $orderId)->whereIn('order_incharge_dtl.oid_employee_id', $inchargeIds)->where('order_incharge_dtl.oid_equipment_type_id', $equipmentTypeId)->whereIn('order_incharge_dtl.oid_status', array('0', '1'))->update($dataUpdate);
            }
        }
        /***********REMOVED EQUIPMENT MATCHING BLOCK ACC TO USER & EQUIPMENT********************/

        //If all Section Incharges confirm the Order,then it will moved to the Reviewer.
        if ($order->hasOrderConfirmedByAllSectionIncharges($orderId) == '2') {
            //Updating Log
            $orderInchargeData             = DB::table('order_incharge_dtl')->select('order_incharge_dtl.oid_employee_id')->where('order_incharge_dtl.order_id', $orderId)->first();
            $dataSave                  = array();
            $dataSave['opl_order_id']          = $orderId;
            $dataSave['opl_order_status_id']     = '4';
            $dataSave['opl_date']         = CURRENTDATETIME;
            $dataSave['opl_user_id']          = !empty($orderInchargeData->oid_employee_id) ? $orderInchargeData->oid_employee_id : NULL;
            DB::table('order_process_log')->insert($dataSave);
            $report->updateReportInchargeReviewingDate($orderId);    //Updating Report Incharge Reviewing Date
            $order->updateOrderStatusToNextPhase($orderId, '5');        //Updating Order Status To Next Phase
        }
    }

    /*******************************************
     *Function: generate Order Expected Due Date(EDD)
     *Created By: Praveen Singh
     *Created On : 30-July-2018
     *Modified On : 13-Sept-2018
     ******************************************/
    function generateUpdateOrderExpectedDueDate_v1($order_id, $date = Null)
    {

        global $order, $models;

        $dataSave = array();

        $orderDetail =  DB::table('order_master')->select('order_id', 'division_id', 'order_date', 'booking_date', 'tat_in_days')->where('order_master.order_id', '=', $order_id)->first();

        if (!empty($orderDetail->booking_date)) {

            $expectedDueDate           = !empty($date) ? $date : $orderDetail->booking_date;
            $orderDetail->booking_date = !empty($date) ? $date : $orderDetail->booking_date;

            //Getting Number of days
            list($expectedDueDate, $is_tat_in_day_reupdatable) = $this->__calculateDaysForEDDBySystemOrUserTAT_v1($orderDetail);

            //Dept. Due Date and Report Due Date
            list($deptDueDate, $reportDueDate) = $this->__generateReportAndDepartmentDueDate_v1($orderDetail, $expectedDueDate);

            //Finally Updating the Order Master Table
            if (!empty($is_tat_in_day_reupdatable)) {
                $dataSave = array('order_master.expected_due_date' => $expectedDueDate, 'order_master.order_dept_due_date' => $deptDueDate, 'order_master.order_report_due_date' => $reportDueDate, 'order_master.tat_in_days' => $is_tat_in_day_reupdatable);
            } else {
                $dataSave = array('order_master.expected_due_date' => $expectedDueDate, 'order_master.order_dept_due_date' => $deptDueDate, 'order_master.order_report_due_date' => $reportDueDate);
            }
            return !empty($dataSave) ? DB::table('order_master')->where('order_master.order_id', $order_id)->update($dataSave) : false;
        }
    }

    /*******************************************
     *Function : Getting Number of Days to calculate Order Expected Due Date(EDD)
     *Created By : Praveen Singh
     *Created On : 30-July-2018
     *Modified On : 13-Sept-2018
     ******************************************/
    function __calculateDaysForEDDBySystemOrUserTAT_v1($orderDetail)
    {

        global $order, $models;

        $time_taken_days = array();

        $total_time_taken_days = $is_tat_in_day_reupdatable = '0';

        //If User Enters the TAT in Days Values and Selected Parameter has not Microbiological Equipment
        $hasEquipmentMicrobiological = DB::table('order_parameters_detail')->where('order_parameters_detail.equipment_type_id', '22')->where('order_parameters_detail.order_id', '=', $orderDetail->order_id)->first();
        if (!empty($orderDetail->tat_in_days) && empty($hasEquipmentMicrobiological)) {

            //Getting Days of TAT Calculation
            $total_time_taken_days = !empty($orderDetail->tat_in_days) ? trim($orderDetail->tat_in_days) : '0';

            //Add days to current date to calculate the observed expected due date
            $expectedDueDate = date(MYSQLDATETIMEFORMAT, strtotime('+' . $total_time_taken_days . ' day', strtotime($orderDetail->booking_date)));
        } else if (!empty($orderDetail->tat_in_days) && !empty($hasEquipmentMicrobiological)) {

            //Getting Maximum TAT defined from a parameters detail of an order
            $orderParametersDetail = DB::table('order_parameters_detail')->select('order_parameters_detail.time_taken_days', 'order_parameters_detail.time_taken_mins')->where('order_parameters_detail.order_id', '=', $orderDetail->order_id)->get();
            foreach ($orderParametersDetail as $key => $orderParameter) {
                $time_taken_days[] = !empty($orderParameter->time_taken_days) ? $orderParameter->time_taken_days : '0';
            }
            //Getting Maximum days from all parameter test allocated days
            $total_time_taken_days = !empty($time_taken_days) && array_filter($time_taken_days) ? round(max($time_taken_days)) : '0';
            $total_time_taken_days = !empty($total_time_taken_days) && is_numeric($total_time_taken_days) ? $total_time_taken_days : '0';

            //Checking If TAT-In-Days is re-updatable in case of Microbilogical Equipment Exist
            $is_tat_in_day_reupdatable = !empty($orderDetail->tat_in_days) && !empty($hasEquipmentMicrobiological) ? $total_time_taken_days : '0';

            //Add days to current date to calculate the observed expected due date
            $expectedDueDate = date(MYSQLDATETIMEFORMAT, strtotime('+' . $total_time_taken_days . ' day', strtotime($orderDetail->booking_date)));
        } else {                //Sysytem Generated Expected Due Date	    

            //Getting Maximum TAT defined from a parameters detail of an order
            $orderParametersDetail = DB::table('order_parameters_detail')->select('order_parameters_detail.time_taken_days', 'order_parameters_detail.time_taken_mins')->where('order_parameters_detail.order_id', '=', $orderDetail->order_id)->get();
            foreach ($orderParametersDetail as $key => $orderParameter) {
                $time_taken_days[] = !empty($orderParameter->time_taken_days) ? $orderParameter->time_taken_days : '0';
            }
            //Getting Maximum days from all parameter test allocated days
            $total_time_taken_days = !empty($time_taken_days) && array_filter($time_taken_days) ? round(max($time_taken_days)) : '0';
            $total_time_taken_days = !empty($total_time_taken_days) && is_numeric($total_time_taken_days) ? $total_time_taken_days : '0';

            //if booking date after 2.00 PM
            if (strtotime(date('ha', strtotime($orderDetail->booking_date))) > strtotime("2pm")) {
                $total_time_taken_days = $total_time_taken_days + 1;
            }

            //Add days to current date to calculate the observed expected due date
            $expectedDueDate = date(MYSQLDATETIMEFORMAT, strtotime('+' . $total_time_taken_days . ' day', strtotime($orderDetail->booking_date)));

            //Checking if any holidays lies between order booking date and Calculated Expected Due Date
            $holidayDayCounts = DB::table('holiday_master')->where('holiday_master.holiday_status', '1')->where('holiday_master.division_id', $orderDetail->division_id)->whereBetween(DB::raw("DATE(holiday_master.holiday_date)"), array(date('Y-m-d', strtotime($orderDetail->booking_date)), date('Y-m-d', strtotime($expectedDueDate))))->count();
            if ($holidayDayCounts) {
                $expectedDueDate = date(MYSQLDATETIMEFORMAT, strtotime('+' . $holidayDayCounts . ' day', strtotime($expectedDueDate)));
            }

            //Checking there any sunday lies on calculated days,then add number of days according to number of sunday in expected due date
            $sundays = $models->getSundays($orderDetail->booking_date, $expectedDueDate);
            if (!empty($sundays)) {
                $expectedDueDate = date(MYSQLDATETIMEFORMAT, strtotime('+' . count($sundays) . ' day', strtotime($expectedDueDate)));
            }

            //final Validation of Sunday and Holidays
            $expectedDueDate = $models->validateSundayHoliday_v1($expectedDueDate, '1', '+');
        }
        return array($expectedDueDate, $is_tat_in_day_reupdatable);
    }

    /*******************************************
     *Function : generate Report Due and Department Due Date using Expected Due Date
     *Created By : Praveen Singh
     *Created On : 24-July-2018
     *Modified On : 13-Sept-2018
     ******************************************/
    function __generateReportAndDepartmentDueDate_v1($orderDetail, $expectedDueDate)
    {

        global $order, $models;

        $calDeptDueDate = $calReportDueDate = '';

        //Dept. Due Date and Report Due Date
        if (!empty($orderDetail->order_id) && !empty($orderDetail->booking_date) && !empty($expectedDueDate)) {
            $hasOrderMicrobiologicalEquipment = DB::table('order_parameters_detail')->where('order_parameters_detail.equipment_type_id', '22')->where('order_parameters_detail.order_id', '=', $orderDetail->order_id)->first();
            if (!empty($orderDetail->tat_in_days)) {
                $calDeptDueDate   = $expectedDueDate;
                $calReportDueDate = $expectedDueDate;
            } else if (!empty($hasOrderMicrobiologicalEquipment)) {
                $calDeptDueDate   = $expectedDueDate;
                $calReportDueDate = $expectedDueDate;
            } else {
                $numberOfdays = $models->itc_get_number_of_days($orderDetail->booking_date, $expectedDueDate);
                if ($numberOfdays <= '3') {
                    $calDeptDueDate   = $expectedDueDate;
                    $calReportDueDate = $expectedDueDate;
                } else {
                    $calDeptDueDate   = $models->sub_days_in_date($expectedDueDate, '1', MYSQLDATETIMEFORMAT);
                    $calReportDueDate = $models->sub_days_in_date($expectedDueDate, '1', MYSQLDATETIMEFORMAT);
                }
                //final Validation of Sunday and Holidays
                $calDeptDueDate   = $models->validateSundayHoliday_v1($calDeptDueDate, '1', '-');
                $calReportDueDate = $models->validateSundayHoliday_v1($calReportDueDate, '1', '-');
            }
        }
        return array($calDeptDueDate, $calReportDueDate);
    }

    /**********************************************
     * Running Time validation on add order
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     **********************************************/
    public function validateDecimalValueOnAdd($orderParameterDetailRaw)
    {
        $orderParameterDetail = array();
        if (!empty($orderParameterDetailRaw)) {
            foreach ($orderParameterDetailRaw as $keyParameter => $orderParametersData) {
                foreach ($orderParametersData as $key => $parameters) {
                    $parameters = empty($parameters) ? null : $parameters;
                    $orderParameterDetail[$key][$keyParameter] = $parameters;
                }
            }
            foreach ($orderParameterDetail as $key => $values) {
                if (!empty($values['display_decimal_place']) && strval($values['display_decimal_place']) !== strval(intval($values['display_decimal_place']))) {
                    return false;
                }
            }
        }
        return true;
    }

    /*************************
     *Claim value validation on add order
     * @param  \Illuminate\Http\Request  $request
     * 26-02-2018
     * @return \Illuminate\Http\Response
     ************************/
    public function claimValueValidation($orderParameterDetail)
    {
        if (!empty($orderParameterDetail)) {
            $claimDependent = !empty($orderParameterDetail['claim_dependent']) ? array_filter($orderParameterDetail['claim_dependent']) : '';
            $claimValue     = !empty($orderParameterDetail['claim_value']) ? array_filter($orderParameterDetail['claim_value']) : NULL;
            if (!empty($claimDependent) && count($claimDependent) != count($claimValue)) {
                return true;
            } else {
                return false;
            }
        }
    }

    /*************************
     * Claim value unit validation on add order
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     ************************/
    public function claimUnitValidation($orderParameterDetail)
    {
        if (!empty($orderParameterDetail)) {
            $claimDependent = !empty($orderParameterDetail['claim_dependent']) ? array_filter($orderParameterDetail['claim_dependent']) : '';
            $claimUnit         = !empty($orderParameterDetail['claim_value_unit']) ? array_filter($orderParameterDetail['claim_value_unit']) : NULL;
            if (!empty($claimDependent) && (count($claimDependent) != count($claimUnit))) {
                return true;
            } else {
                return false;
            }
        }
    }

    /*************************
     *Claim value validation on edit order
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     ************************/
    public function claimValueValidationOnEdit($orderParameterDetail)
    {
        $claimValue = array();
        if (!empty($orderParameterDetail)) {
            foreach ($orderParameterDetail as $key => $orderParameterData) {
                $id = str_replace("'", "", $key);
                if (isset($orderParameterData['claim_value'])) {
                    $claimValue[] = !empty($orderParameterData['claim_value']) ? $orderParameterData['claim_value'] : NULL;
                }
                if ($id == 'new') {
                    foreach ($orderParameterData as $key1 => $newData) {
                        $claimValue[] = $newData['claim_value'];
                    }
                }
            }
            if (isset($orderParameterDetail['claim_dependent']) && isset($claimValue) && (count(array_filter($orderParameterDetail['claim_dependent'])) != count(array_filter($claimValue)))) {
                return true;
            } else {
                return false;
            }
        }
    }

    /*************************
     *Claim value unit validation on edit order
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     ************************/
    public function claimUnitValidationOnEdit($orderParameterDetail)
    {
        $claim_value_unit = array();
        if (!empty($orderParameterDetail)) {
            foreach ($orderParameterDetail as $key => $orderParameterData) {
                $id = str_replace("'", "", $key);
                if (isset($orderParameterData['claim_value_unit'])) {
                    $claim_value_unit[] = !empty($orderParameterData['claim_value_unit']) ? $orderParameterData['claim_value_unit'] : NULL;
                }
                if ($id == 'new') {
                    foreach ($orderParameterData as $key1 => $newData) {
                        $claim_value_unit[] = $newData['claim_value_unit'];
                    }
                }
            }
            array_filter($claim_value_unit);
            if (isset($orderParameterDetail['claim_dependent']) && isset($claim_value_unit) && (count(array_filter($orderParameterDetail['claim_dependent'])) != count(array_filter($claim_value_unit)))) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**********************************************
     * Running Time validation on add order
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     **********************************************/
    public function validateDecimalValueOnEdit($orderParameterDetailRaw)
    {

        $orderParameterDetailPrev = $orderParameterDetailNew = $flag = array();

        //Newly Added Parameters
        if (!empty($orderParameterDetailRaw)) {
            if (isset($orderParameterDetailRaw['claim_dependent'])) unset($orderParameterDetailRaw['claim_dependent']);
            foreach ($orderParameterDetailRaw as $key => $values) {
                $key = str_replace("'", "", $key);
                if ($key == 'new') {
                    $orderParameterDetailNew = $values;
                } else {
                    $orderParameterDetailPrev[$key] = $values;
                }
            }
            $orderParameterDetail = $orderParameterDetailPrev + $orderParameterDetailNew;
        }
        if (!empty($orderParameterDetail)) {
            foreach ($orderParameterDetail as $orderParameters) {
                if (!empty($orderParameters['display_decimal_place']) && strval($orderParameters['display_decimal_place']) !== strval(intval($orderParameters['display_decimal_place']))) {
                    return false;
                }
            }
        }
        return true;
    }

    /*************************
     * Running Time validation on add order
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     ************************/
    public function runningTimeEditValidation($orderParameterDetailRaw)
    {

        $orderParameterDetail = $flag = array();

        //Newly Added Parameters
        if (!empty($orderParameterDetailRaw)) {
            foreach ($orderParameterDetailRaw as $key => $values) {
                $key = str_replace("'", "", $key);
                if ($key == 'new') {
                    $orderParameterDetail = array_values($values);
                }
            }
        }
        if (!empty($orderParameterDetail)) {
            foreach ($orderParameterDetail as $orderParameters) {
                if (!empty($orderParameters['cwap_invoicing_required'])) {
                    if (empty($orderParameters['running_time_id'])) {
                        $flag[] = '0';
                    } else {
                        $flag[] = '1';
                    }
                } else {
                    $flag[] = '1';
                }
            }
        } else {
            $flag[] = '1';
        }
        return !empty($flag) && in_array(0, $flag) ? false : true;
    }

    /**********************************************
     * Running Time validation on add order
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     **********************************************/
    public function noOfInjectionEditValidation($orderParameterDetailRaw)
    {

        $orderParameterDetail = $flag = array();

        //Newly Added Parameters
        if (!empty($orderParameterDetailRaw)) {
            foreach ($orderParameterDetailRaw as $key => $values) {
                $key = str_replace("'", "", $key);
                if ($key == 'new') {
                    $orderParameterDetail = $values;
                }
            }
        }
        if (!empty($orderParameterDetail)) {
            foreach ($orderParameterDetail as $orderParameters) {
                if (!empty($orderParameters['cwap_invoicing_required'])) {
                    if (empty($orderParameters['no_of_injection'])) {
                        $flag[] = '0';
                    } else {
                        $flag[] = '1';
                    }
                } else {
                    $flag[] = '1';
                }
            }
        } else {
            $flag[] = '1';
        }
        return !empty($flag) && in_array(0, $flag) ? false : true;
    }

    /**********************************************
     * DT Limit validation on Edit order
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     **********************************************/
    public function checkEditDTLimitValidation($orderParameterDetailRaw)
    {

        $orderParameterDetail = array();

        //Newly Added Parameters
        if (!empty($orderParameterDetailRaw)) {
            foreach ($orderParameterDetailRaw as $key => $values) {
                $key = str_replace("'", "", $key);
                if ($key == 'new') {
                    $orderParameterDetail = $values;
                }
            }
        }
        if (!empty($orderParameterDetail)) {
            foreach ($orderParameterDetail as $key => $values) {
                if ($values['test_parameter_id'] == '25235' && empty($values['standard_value_to'])) {
                    return false;
                }
            }
        }
        return true;
    }

    /*************************
     * Running Time validation on add order
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     ************************/
    public function runningTimeValidation($orderParameterDetail)
    {

        global $models;

        if (!empty($orderParameterDetail['cwap_invoicing_required'])) {
            $cwapInvoicingRequired = !empty($orderParameterDetail['cwap_invoicing_required']) ? array_filter($orderParameterDetail['cwap_invoicing_required']) : array();
            $postedRunningTimeId   = !empty($orderParameterDetail['running_time_id']) ? array_filter($models->changeArrayValues($orderParameterDetail['running_time_id'])) : array();
            if (!empty($cwapInvoicingRequired) && !empty($postedRunningTimeId)) {
                if (count($cwapInvoicingRequired) == count($postedRunningTimeId)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    /**********************************************
     * Running Time validation on add order
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     **********************************************/
    public function noOfInjectionValidation($orderParameterDetail)
    {
        if (!empty($orderParameterDetail['cwap_invoicing_required'])) {
            $cwapInvoicingRequired = !empty($orderParameterDetail['cwap_invoicing_required']) ? array_filter($orderParameterDetail['cwap_invoicing_required']) : array();
            $postedNo0fInjection   = !empty($orderParameterDetail['no_of_injection']) ? array_filter($orderParameterDetail['no_of_injection']) : array();
            if (!empty($cwapInvoicingRequired) && !empty($postedNo0fInjection)) {
                if (count($cwapInvoicingRequired) == count($postedNo0fInjection)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    /**********************************************
     * DT Limit validation on Add order
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     **********************************************/
    public function checkAddDTLimitValidation($orderParameterDetailRaw)
    {

        $orderParameterDetail = array();

        if (!empty($orderParameterDetailRaw)) {
            foreach ($orderParameterDetailRaw as $keyParameter => $orderParametersData) {
                foreach ($orderParametersData as $key => $parameters) {
                    $parameters = empty($parameters) ? null : $parameters;
                    $orderParameterDetail[$key][$keyParameter] = $parameters;
                }
            }
            foreach ($orderParameterDetail as $key => $values) {
                if ($values['test_parameter_id'] == '25235' && empty($values['standard_value_to'])) {
                    return false;
                }
            }
        }
        return true;
    }

    /**********************************************
     *Function    : Validating BackDate Booking for Nabl Scope On Add
     *Created By  : Praveen Singh
     *Created On  : 03-Oct-2018
     *Modified On : 03-Oct-2018
     **********************************************/
    public function validateNablScopeBackDateBookingOnAdd($postedData, $bookingDate)
    {
        if (!empty($postedData['order_parameters_detail']['order_parameter_nabl_scope']) && !empty($postedData['order_date'])) {
            $order_parameter_nabl_scope_array = array_filter($postedData['order_parameters_detail']['order_parameter_nabl_scope']);
            if (!empty($order_parameter_nabl_scope_array) && in_array('1', $order_parameter_nabl_scope_array) && strtotime($postedData['order_date']) != strtotime($bookingDate)) {
                return false;
            }
        }
        return true;
    }

    /****
     *** for order duplicacy check
     **** Get previous order acording to selected customer
     ***
     ****/
    function getPreviousOrderDetail($formData)
    {

        global $order, $sample, $models, $stbOrder, $stbOrderPrototype;

        $previousOrderDetail = array();

        if (!empty($formData['customer_id']) && !empty($formData['sample_id']) && !empty($formData['sample_description_id']) && !empty($formData['batch_no'])) {
            $previousOrderDetail = DB::table('order_master')
                ->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
                ->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
                ->join('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
                ->where('order_master.customer_id', '=', $formData['customer_id'])
                ->where('order_master.batch_no', '=', $formData['batch_no'])
                ->where('order_master.sample_description_id', '=', $formData['sample_description_id'])
                ->select('product_master_alias.c_product_name', DB::raw('CONCAT(customer_master.customer_name,"/",city_db.city_name) AS customer_name'), 'order_master.order_no', 'order_master.batch_no', 'order_master.order_date')
                ->get()
                ->toArray();
            $models->formatTimeStampFromArray($previousOrderDetail, DATETIMEFORMAT);
        }
        return $previousOrderDetail;
    }

    /***********************************************
     *function to Check the Order Confirmation Mail Sent or Not
     *Created On :23-Jan-2019
     *Created By:Praveen-Singh
     **********************************************/
    public function hasOrderConfirmationMailSent($orderId)
    {
        $orderMailSentStatus    = DB::table('order_mail_dtl')->where('order_mail_dtl.order_id', $orderId)->where('order_mail_dtl.mail_content_type', '2')->first();
        $orderMailPendingStatus = DB::table('scheduled_mail_dtl')->where('scheduled_mail_dtl.smd_order_ids', $orderId)->where('scheduled_mail_dtl.smd_content_type', '2')->first();
        return !empty($orderMailSentStatus) || !empty($orderMailPendingStatus) ? '1' : '0';
    }

    /***********************************************
     *function to Check the Order Confirmation Mail Sent or Not
     *Created On : 23-Dec-2021
     *Created By : Praveen-Singh
     **********************************************/
    public function hasTestReportMailSent($orderId)
    {
        $statusArray = ['Pending', 'Sent', 'Failed'];
        $testReportMailSent = DB::table('order_mail_dtl')->where('order_mail_dtl.mail_content_type', '3')->where('order_mail_dtl.mail_status', '1')->where('order_mail_dtl.mail_active_type', '=', '1')->where('order_mail_dtl.order_id', $orderId)->first();
        if (!empty($testReportMailSent)) {
            if ($testReportMailSent->mail_status == '1') {
                return $statusArray[1];
            } else {
                return $statusArray[2];
            }
        } else {
            return $statusArray[0];
        }
    }

    /*******************************************
     *Function: generate Order Expected Due Date(EDD)
     *Created By: Praveen Singh
     *Created On : 08-July-2018
     *Modified On : 08-March-2019
     ******************************************/
    function generateUpdateOrderExpectedDueDate_v2($order_id, $date = NULL, $columnArray = array())
    {

        global $order, $models;

        $dataSave = array();

        $orderDetail =  DB::table('order_master')->select('order_id', 'division_id', 'order_date', 'booking_date', 'tat_in_days')->where('order_master.order_id', '=', $order_id)->first();

        if (!empty($orderDetail->booking_date)) {

            $expectedDueDate           = !empty($date) ? $date : $orderDetail->booking_date;
            $orderDetail->booking_date = !empty($date) ? $date : $orderDetail->booking_date;

            //Getting Number of days
            list($expectedDueDate, $is_tat_in_day_reupdatable) = $this->__calculateDaysForEDDBySystemOrUserTAT_v3($orderDetail, $expectedDueDate, $columnArray);

            //Dept. Due Date and Report Due Date
            list($deptDueDate, $reportDueDate) = $this->__generateReportAndDepartmentDueDate_v2($orderDetail, $expectedDueDate);

            //Finally Updating the Order Master Table
            if (!empty($is_tat_in_day_reupdatable)) {
                $dataSave = array('order_master.expected_due_date' => $expectedDueDate, 'order_master.order_dept_due_date' => $deptDueDate, 'order_master.order_report_due_date' => $reportDueDate, 'order_master.tat_in_days' => $is_tat_in_day_reupdatable);
            } else {
                $dataSave = array('order_master.expected_due_date' => $expectedDueDate, 'order_master.order_dept_due_date' => $deptDueDate, 'order_master.order_report_due_date' => $reportDueDate);
            }
            return !empty($dataSave) ? DB::table('order_master')->where('order_master.order_id', $order_id)->update($dataSave) : false;
        }
    }

    /*******************************************
     *Function : Getting Number of Days to calculate Order Expected Due Date(EDD)
     *Created By : Praveen Singh
     *Created On : 30-July-2018
     *Modified On : 08-March-2019
     ******************************************/
    function __calculateDaysForEDDBySystemOrUserTAT_v2($orderDetail, $expectedDueDate, $columnArray)
    {

        global $order, $models;

        /**************************************************************************************************
	STEP 1 : If Customer has Customer-wise-tat defined and there is no Microbiological Instrument in it,then Expected Due Date will be calculated according to STEP 3.
	STEP 2 : If Customer has Customer-wise-tat defined or there is a Microbiological Instrument in it,then Expected Due Date will be calculated by the maximum TAT defined for a test parameter of a particular order irrespective of weekly off,sunday & +1 of after 2:00 pm conditions.
	STEP 3 : If Customer hasn't Customer-wise-tat defined and there is no Microbiological Instrument in it,then Expected Due Date will be calculated by the System normally.
         ***************************************************************************************************/

        $time_taken_days     = array();
        $total_time_taken_days  = $is_tat_in_day_reupdatable = '0';
        $editActionType     = !empty($columnArray['action']) && $columnArray['action'] == 'edit' ? '1' : '0';

        //If User Enters the TAT in Days Values and Selected Parameter has not Microbiological Equipment
        $hasEquipmentMicrobiological = DB::table('order_parameters_detail')->where('order_parameters_detail.equipment_type_id', '22')->where('order_parameters_detail.order_id', '=', $orderDetail->order_id)->first();

        if (!empty($orderDetail->tat_in_days) && !empty($editActionType)) {    //If Customer Wise TAT will be updated in case of TAT Input is updated in case of Edit Mode

            //Getting Days of TAT Calculation
            $total_time_taken_days = !empty($orderDetail->tat_in_days) && is_numeric($orderDetail->tat_in_days) ? trim($orderDetail->tat_in_days) : '0';
        } else if (!empty($orderDetail->tat_in_days) && empty($hasEquipmentMicrobiological)) {    //STEP 1

            //Getting Days of TAT Calculation
            $total_time_taken_days = !empty($orderDetail->tat_in_days) && is_numeric($orderDetail->tat_in_days) ? trim($orderDetail->tat_in_days) : '0';
        } else if (!empty($orderDetail->tat_in_days) && !empty($hasEquipmentMicrobiological)) { //STEP 2

            //Getting Maximum TAT defined from a parameters detail of an order
            $orderParametersDetail = DB::table('order_parameters_detail')->select('order_parameters_detail.time_taken_days', 'order_parameters_detail.time_taken_mins')->where('order_parameters_detail.order_id', '=', $orderDetail->order_id)->get();
            foreach ($orderParametersDetail as $key => $orderParameter) {
                $time_taken_days[] = !empty($orderParameter->time_taken_days) ? $orderParameter->time_taken_days : '0';
            }
            //Getting Maximum days from all parameter test allocated days
            $total_time_taken_days = !empty($time_taken_days) && array_filter($time_taken_days) ? round(max($time_taken_days)) : '0';
            $total_time_taken_days = !empty($total_time_taken_days) && is_numeric($total_time_taken_days) ? $total_time_taken_days : '0';

            //Checking If TAT-In-Days is re-updatable in case of Microbilogical Equipment Exist
            $is_tat_in_day_reupdatable = !empty($orderDetail->tat_in_days) && !empty($hasEquipmentMicrobiological) ? $total_time_taken_days : '0';
        } else {                //STEP 3	    

            //Getting Maximum TAT defined from a parameters detail of an order
            $orderParametersDetail = DB::table('order_parameters_detail')->select('order_parameters_detail.time_taken_days', 'order_parameters_detail.time_taken_mins')->where('order_parameters_detail.order_id', '=', $orderDetail->order_id)->get();
            foreach ($orderParametersDetail as $key => $orderParameter) {
                $time_taken_days[] = !empty($orderParameter->time_taken_days) ? $orderParameter->time_taken_days : '0';
            }
            //Getting Maximum days from all parameter test allocated days
            $total_time_taken_days = !empty($time_taken_days) && array_filter($time_taken_days) ? round(max($time_taken_days)) : '0';
            $total_time_taken_days = !empty($total_time_taken_days) && is_numeric($total_time_taken_days) ? $total_time_taken_days : '0';
        }

        if (!empty($total_time_taken_days) && !empty($orderDetail->booking_date)) {

            //if booking date after 2.00 PM
            if (strtotime(date('ha', strtotime($orderDetail->booking_date))) > strtotime("2pm")) {
                $total_time_taken_days = $total_time_taken_days + 1;
            }

            //Add days to current date to calculate the observed expected due date
            $expectedDueDate = date(MYSQLDATETIMEFORMAT, strtotime('+' . $total_time_taken_days . ' day', strtotime($orderDetail->booking_date)));

            //Checking if any holidays lies between order booking date and Calculated Expected Due Date
            $holidayDayCounts = DB::table('holiday_master')->where('holiday_master.holiday_status', '1')->where('holiday_master.division_id', $orderDetail->division_id)->whereBetween(DB::raw("DATE(holiday_master.holiday_date)"), array(date('Y-m-d', strtotime($orderDetail->booking_date)), date('Y-m-d', strtotime($expectedDueDate))))->count();
            if ($holidayDayCounts) {
                $expectedDueDate = date(MYSQLDATETIMEFORMAT, strtotime('+' . $holidayDayCounts . ' day', strtotime($expectedDueDate)));
            }

            //Checking there any sunday lies on calculated days,then add number of days according to number of sunday in expected due date
            $sundays = $models->getSundays($orderDetail->booking_date, $expectedDueDate);
            if (!empty($sundays)) {
                $expectedDueDate = date(MYSQLDATETIMEFORMAT, strtotime('+' . count($sundays) . ' day', strtotime($expectedDueDate)));
            }

            //final Validation of Sunday and Holidays
            $expectedDueDate = $models->validateSundayHoliday_v1($expectedDueDate, '1', '+');
        }

        return array($expectedDueDate, $is_tat_in_day_reupdatable);
    }

    /*******************************************
     *Function : generate Report Due and Department Due Date using Expected Due Date
     *Created By : Praveen Singh
     *Created On : 24-July-2018
     *Modified On : 08-March-2019
     ******************************************/
    function __generateReportAndDepartmentDueDate_v2($orderDetail, $expectedDueDate)
    {

        global $order, $models;

        $calDeptDueDate = $calReportDueDate = '';

        //Dept. Due Date and Report Due Date
        if (!empty($orderDetail->order_id) && !empty($orderDetail->booking_date) && !empty($expectedDueDate)) {
            $hasOrderMicrobiologicalEquipment = DB::table('order_parameters_detail')->where('order_parameters_detail.equipment_type_id', '22')->where('order_parameters_detail.order_id', '=', $orderDetail->order_id)->first();
            if (!empty($orderDetail->tat_in_days)) {
                $calDeptDueDate   = $expectedDueDate;
                $calReportDueDate = $expectedDueDate;
            } else if (!empty($hasOrderMicrobiologicalEquipment)) {
                $calDeptDueDate   = $expectedDueDate;
                $calReportDueDate = $expectedDueDate;
            } else {
                $numberOfdays = $models->itc_get_number_of_days($orderDetail->booking_date, $expectedDueDate);
                if ($numberOfdays <= '3') {
                    $calDeptDueDate   = $expectedDueDate;
                    $calReportDueDate = $expectedDueDate;
                } else {
                    $calDeptDueDate   = $models->sub_days_in_date($expectedDueDate, '1', MYSQLDATETIMEFORMAT);
                    $calReportDueDate = $models->sub_days_in_date($expectedDueDate, '1', MYSQLDATETIMEFORMAT);
                }
                //final Validation of Sunday and Holidays
                $calDeptDueDate   = $models->validateSundayHoliday_v1($calDeptDueDate, '1', '-');
                $calReportDueDate = $models->validateSundayHoliday_v1($calReportDueDate, '1', '-');
            }
        }
        return array($calDeptDueDate, $calReportDueDate);
    }

    /***********************************************
     *function to get Invoice Number using Order Detail
     *Created On :09-April-2019
     *Created By:Praveen-Singh
     **********************************************/
    public function gettingInvoiceDetailUsingOrderDetail($orderId)
    {
        return DB::table('order_master')
            ->join('invoice_hdr_detail', 'invoice_hdr_detail.order_id', 'order_master.order_id')
            ->join('invoice_hdr', 'invoice_hdr.invoice_id', 'invoice_hdr_detail.invoice_hdr_id')
            ->where('invoice_hdr.invoice_status', '1')
            ->select('invoice_hdr.*')
            ->where('invoice_hdr_detail.invoice_hdr_status', '1')
            ->where('order_master.order_id', $orderId)
            ->first();
    }

    /**
     * get Order Details
     * Date : 22-May-2019
     * Author :Praveen Singh
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createProductMasterAliaFromTrfSampleName($trfId)
    {

        global $models, $trfHdr;

        $trfData = $trfHdr->getRow($trfId);
        if (!empty($trfData->trf_product_id)) {

            $dataSave            = array();
            $dataSave['c_product_name'] = trim($trfData->trf_sample_name);
            $dataSave['product_id']     = $trfData->trf_product_id;
            $dataSave['created_by']     = USERID;
            $dataSave['view_type']     = '1';
            $dataSave['c_product_status'] = '1';

            $data = DB::table('product_master_alias')->where('product_master_alias.c_product_name', '=', trim($trfData->trf_sample_name))->where('product_master_alias.product_id', '=', trim($trfData->trf_product_id))->first();
            return empty($data) ? DB::table('product_master_alias')->insertGetId($dataSave) : $data->c_product_id;
        }
    }

    /**
     * get Order Details
     * Date : 22-May-2019
     * Author :Praveen Singh
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function connectUpdatePoWithCentralPoLoc($poConnectivityData, $order_id)
    {

        global $models;

        //Extracting array into variable
        extract($poConnectivityData);

        $dataSave = $dataUpdate = array();

        if (!empty($order_id) && !empty($customer_id) && !empty($customer_city) && !empty($po_no) && !empty($po_date)) {

            //Getting Central Location Po Detail basded on Order PO Detail
            $centralPoData = DB::table('central_po_dtls')
                ->where('central_po_dtls.cpo_customer_id', $customer_id)
                ->where('central_po_dtls.cpo_customer_city', $customer_city)
                ->where('central_po_dtls.cpo_no', $po_no)
                ->where(DB::raw("DATE(central_po_dtls.cpo_date)"), date('Y-m-d', strtotime($po_date)))
                ->first();

            //Should Match the record with Central Location PO Detail
            if (!empty($centralPoData)) {

                //Checking Existence
                $orderLinkedPoDtl = DB::table('order_linked_po_dtl')->where('order_linked_po_dtl.olpd_order_id', $order_id)->first();
                if (empty($orderLinkedPoDtl)) {        //Saving Data in order_linked_po_dtl
                    $dataSave = array();
                    $dataSave['olpd_order_id']         = $order_id;
                    $dataSave['olpd_cpo_id']         = $centralPoData->cpo_id;
                    $dataSave['olpd_cpo_no']         = $centralPoData->cpo_no;
                    $dataSave['olpd_cpo_file_name']     = $centralPoData->cpo_file_name;
                    $dataSave['olpd_cpo_sample_name']     = $centralPoData->cpo_sample_name;
                    $dataSave['olpd_cpo_date']         = $centralPoData->cpo_date;
                    $dataSave['olpd_date']         = CURRENTDATETIME;
                    $dataSave['created_by']         = USERID;
                    DB::table('order_linked_po_dtl')->insertGetId($dataSave);
                } else if (!empty($orderLinkedPoDtl->olpd_id)) {    //Updating Data in order_linked_po_dtl
                    $dataUpdate = array();
                    $dataUpdate['olpd_cpo_id']         = $centralPoData->cpo_id;
                    $dataUpdate['olpd_cpo_no']         = $centralPoData->cpo_no;
                    $dataUpdate['olpd_cpo_file_name']     = $centralPoData->cpo_file_name;
                    $dataUpdate['olpd_cpo_sample_name'] = $centralPoData->cpo_sample_name;
                    $dataUpdate['olpd_cpo_date']     = $centralPoData->cpo_date;
                    $dataUpdate['olpd_date']         = CURRENTDATETIME;
                    $dataUpdate['created_by']         = USERID;
                    DB::table('order_linked_po_dtl')->where('order_linked_po_dtl.olpd_id', $orderLinkedPoDtl->olpd_id)->update($dataUpdate);
                }
            }
        }
        return true;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function hasOrderTrfStpDetail($order_id)
    {

        global $models, $report;

        $orderLinkedTrfDtl = DB::table('order_linked_trf_dtl')->where('order_linked_trf_dtl.oltd_order_id', $order_id)->first();
        $orderLinkedStpDtl = $orderLinkedTrfDtl; //DB::table('order_linked_stp_dtl')->where('order_linked_stp_dtl.olsd_order_id', $order_id)->first();
        if (empty($orderLinkedTrfDtl)) {
            return false;
        } else if (empty($orderLinkedStpDtl)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Hold the Order of Paricular Customer
     * Created By : Praveen Singh
     * Created On : 06-June-2019
     */
    function holdAllOrderOfCustomer($formData)
    {

        global $models;

        //Getting All Customer Order Detail
        $customerOrderAll = DB::table('order_master')->where('order_master.customer_id', !empty($formData['chd_customer_id']) ? $formData['chd_customer_id'] : '0')->where('order_master.status', '=', '1')->get()->toArray();
        if (!empty($customerOrderAll)) {
            foreach ($customerOrderAll as $key => $values) {

                //Updating the Hold Reason in order Master
                if (!empty($formData['chd_hold_description'])) {
                    DB::table('order_master')->where('order_master.order_id', $values->order_id)->update(['order_master.hold_reason' => trim($formData['chd_hold_description'])]);
                }
                //Update the Order Status in Hold Stage
                $this->updateOrderStausLog($values->order_id, '12');
                $this->updateOrderStatusToNextPhase($values->order_id, '12');
            }
        }
    }

    /**
     * Unholding all Order of Particular Customer
     * Created By : Praveen Singh
     * Created On : 06-June-2019
     */
    function unHoldAllOrderOfCustomer($customerId, $status)
    {

        global $models;

        //Getting All Customer Order Detail which is in Booked  Stage
        $customerOrderAll = DB::table('order_master')->where('order_master.customer_id', !empty($customerId) ? $customerId : '0')->where('order_master.status', '=', '12')->get()->toArray();
        if (!empty($customerOrderAll)) {
            foreach ($customerOrderAll as $key => $values) {

                //Updating the Hold Reason in order Master
                DB::table('order_master')->where('order_master.order_id', $values->order_id)->update(['order_master.hold_reason' => NULL]);

                //Updating Expected Due Date on Unholding
                $this->generateUpdateOrderExpectedDueDate_v3($values->order_id, CURRENTDATETIME);

                //Updating Report Due Date and Department Due Date in case of order unhold by the User
                $this->updateReportDepartmentDueDate($values->order_id, CURRENTDATETIME);

                //Update the Order Status in Hold Stage
                $this->updateOrderStausLog($values->order_id, $status);

                //Update the Order Status in Hold Stage
                $this->updateOrderStatusToNextPhase($values->order_id, $status);
            }
        }
    }

    /**
     * is New Customer Has No Order In Last Six Months
     * Created By : Praveen Singh
     * Created On : 19-April-2021
     */
    function isNewCustomerHasNoOrderInSixMonth($customer_id)
    {

        global $models;

        //Getting Last Six Month Date Range
        $firstDateOfMonth    = date('Y-m-d', strtotime(date('Y-m-d', strtotime(date('Y-m-01') . ' -5 months'))));
        $lastDateOfMonth     = date('Y-m-d', strtotime('last day of this month', strtotime(date('Y-m-d'))));
        $monthRangeListArray = array_merge($models->month_range($firstDateOfMonth, $lastDateOfMonth, 'Y-m-d'), array($lastDateOfMonth));
        $toDateFromDateList  = array(reset($monthRangeListArray), end($monthRangeListArray));

        //Checking customer has no order yet
        $noOrderCustomer = DB::table('order_master')->where('order_master.customer_id', $customer_id)->whereBetween(DB::raw("DATE(order_master.order_date)"), $toDateFromDateList)->count();

        //Checking no invoice generated for this customer.
        $noInvoiceCustomer = DB::table('invoice_hdr')->where('invoice_hdr.customer_invoicing_id', $customer_id)->whereBetween(DB::raw("DATE(invoice_hdr.invoice_date)"), $toDateFromDateList)->count();

        if (empty($noOrderCustomer) || $noOrderCustomer == '1') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * is New Customer exist in uploaded Acoccunt Hold List
     * Created By : Praveen Singh
     * Created On : 18-June-2021
     */
    function isCustomerExistInAccountuploadedData($customer_id)
    {
        //Checking customer has no order yet
        $custExistInAccountuploaded = DB::table('customer_exist_account_hold_upload_dtl')->where('customer_exist_account_hold_upload_dtl.ceahud_customer_id', $customer_id)->whereNotNull('customer_exist_account_hold_upload_dtl.ceahud_customer_code')->first();
        return !empty($custExistInAccountuploaded->ceahud_ab_outstanding_amt) && !empty(round($custExistInAccountuploaded->ceahud_ab_outstanding_amt)) ? true : false;
    }

    /**
     * Updating Order Status based on Customer Hold Status
     * Created By : Praveen Singh
     * Created On : 07-June-2019
     * Modifed On : 11-June-2021
     */
    function updateOrderStatusOnCustomerHoldStatus($orderId, $formData)
    {

        global $models;

        //Getting All Customer Order Detail which is in Booked  Stage
        $orderData = $this->getOrderDetail($orderId);
        if (!empty($orderData->customer_id)) {

            //Default Flag Status
            $flag = false;
            $defaultReason = $holdStatus = NULL;

            //Checking Customer Hold Status
            $customerData = DB::table('customer_master')->where('customer_master.customer_status', '3')->where('customer_master.customer_id', $orderData->customer_id)->first();

            if (!empty($formData['status']) && !empty($formData['hold_reason']) && $formData['status'] == '12') { //If Order Booker Hold the Customer Order Manually
                $flag = true;
                $defaultReason = config('messages.message.creditCollectionTeamMsg');
                $holdStatus       = '1';
            } else if (!empty($customerData->customer_id)) {                                                 //If Customer Status is in Hold Status
                $flag = true;
                $defaultReason = config('messages.message.creditCollectionTeamMsg');
                $holdStatus       = '2';
                $this->updateOrderStausLog($orderData->order_id, '12');                                        //Update the Order Status in Hold Stage
            } else if ($this->isNewCustomerHasNoOrderInSixMonth($orderData->customer_id)) {                    //Checking if customer is New and has no order or invoice
                $flag = true;
                $defaultReason = config('messages.message.accountHoldTeamMsg');
                $holdStatus       = '3';
                $this->updateOrderStausLog($orderData->order_id, '12');
            } else if ($this->isCustomerExistInAccountuploadedData($orderData->customer_id)) {                //Checking if hold due to Uploaded Account Detail
                $flag  = true;
                $defaultReason = config('messages.message.accountHoldUploadedMsg');
                $holdStatus       = '4';
                $this->updateOrderStausLog($orderData->order_id, '12');
            }
            if ($flag) {

                //Saving Record in customer_hold_dtl table
                $dataSave = array();
                $dataSave['chd_customer_id']       = $orderData->customer_id;
                $dataSave['chd_hold_description'] = !empty($formData['hold_reason']) ? trim($formData['hold_reason']) : $defaultReason;
                $dataSave['chd_hold_date']             = CURRENTDATETIME;
                $dataSave['chd_hold_by']             = defined('USERNAME') ? USERNAME : 'Order Booker';
                $dataSave['chd_hold_status']       = $holdStatus;
                DB::table('customer_hold_dtl')->insertGetId($dataSave);

                //Holding New Customer
                if (!empty($holdStatus) && in_array($holdStatus, ['3', '4'])) {
                    DB::table('customer_master')->where('customer_master.customer_id', $orderData->customer_id)->update(['customer_master.customer_status' => '3']);
                }

                //Updating the Hold Reason in order Master
                if (!empty($dataSave['chd_hold_description'])) {
                    DB::table('order_master')->where('order_master.order_id', $orderData->order_id)->update(['order_master.hold_reason' => trim($dataSave['chd_hold_description'])]);
                }

                //Update the Order Status in Hold Stage
                $this->updateOrderStatusToNextPhase($orderData->order_id, '12');
            }
        }
    }

    /**
     * Getting Customer Hold Status Detail
     * Created By : Praveen Singh
     * Created On : 07-June-2019
     */
    function isCustomerPutOnHold($customerId)
    {
        return DB::table('customer_master')->where('customer_master.customer_status', '3')->where('customer_master.customer_id', $customerId)->count();
    }

    /*******************************************
     *Function: generate Order Expected Due Date(EDD)
     *Created By: Praveen Singh
     *Created On : 08-July-2018
     *Modified On : 08-March-2019,11-July-2019
     ******************************************/
    function generateUpdateOrderExpectedDueDate_v3($order_id, $date = NULL, $columnArray = array())
    {

        global $models;

        $dataSave = array();

        $orderDetail =  DB::table('order_master')->select('order_id', 'division_id', 'order_date', 'booking_date', 'tat_in_days')->where('order_master.order_id', '=', $order_id)->first();

        if (!empty($orderDetail->booking_date)) {

            $expectedDueDate           = !empty($date) ? $date : $orderDetail->booking_date;
            $orderDetail->booking_date = !empty($date) ? $date : $orderDetail->booking_date;

            //Getting Number of days
            list($expectedDueDate, $is_tat_in_day_reupdatable) = $this->__calculateDaysForEDDBySystemOrUserTAT_v3($orderDetail, $expectedDueDate, $columnArray);

            //Finally Updating the Order Master Table
            if (!empty($is_tat_in_day_reupdatable)) {
                $dataSave = array('order_master.expected_due_date' => $expectedDueDate, 'order_master.tat_in_days' => $is_tat_in_day_reupdatable);
            } else {
                $dataSave = array('order_master.expected_due_date' => $expectedDueDate);
            }
            return !empty($dataSave) ? DB::table('order_master')->where('order_master.order_id', $order_id)->update($dataSave) : false;
        }
    }

    /*******************************************
     *Function : Getting Number of Days to calculate Order Expected Due Date(EDD)
     *Created By : Praveen Singh
     *Created On : 30-July-2018
     *Modified On : 08-March-2019,11-July-2019
     ******************************************/
    function __calculateDaysForEDDBySystemOrUserTAT_v3($orderDetail, $expectedDueDate, $columnArray)
    {

        global $models;

        /**************************************************************************************************
		STEP 1 : If Customer has Customer-wise-tat defined and there is no Microbiological Instrument in it,then Expected Due Date will be calculated according to STEP 3.
		STEP 2 : If Customer has Customer-wise-tat defined or there is a Microbiological Instrument in it,then Expected Due Date will be calculated by the maximum TAT defined for a test parameter of a particular order irrespective of weekly off,sunday & +1 of after 2:00 pm conditions.
		STEP 3 : If Customer hasn't Customer-wise-tat defined and there is no Microbiological Instrument in it,then Expected Due Date will be calculated by the System normally.
         ***************************************************************************************************/

        $time_taken_days        = array();
        $total_time_taken_days  = $is_tat_in_day_reupdatable = '0';
        $editActionType         = !empty($columnArray['action']) && $columnArray['action'] == 'edit' ? '1' : '0';

        //If User Enters the TAT in Days Values and Selected Parameter has not Microbiological Equipment
        $hasEquipmentMicrobiological = DB::table('order_parameters_detail')->where('order_parameters_detail.equipment_type_id', '22')->where('order_parameters_detail.order_id', '=', $orderDetail->order_id)->first();

        if (!empty($orderDetail->tat_in_days) && !empty($editActionType)) {            //If Customer Wise TAT will be updated in case of TAT Input is updated in case of Edit Mode

            //Getting Days of TAT Calculation
            $total_time_taken_days = !empty($orderDetail->tat_in_days) && is_numeric($orderDetail->tat_in_days) ? trim($orderDetail->tat_in_days) : '0';
        } else if (!empty($orderDetail->tat_in_days) && empty($hasEquipmentMicrobiological)) {    //STEP 1

            //Getting Days of TAT Calculation
            $total_time_taken_days = !empty($orderDetail->tat_in_days) && is_numeric($orderDetail->tat_in_days) ? trim($orderDetail->tat_in_days) : '0';
        } else if (!empty($orderDetail->tat_in_days) && !empty($hasEquipmentMicrobiological)) {     //STEP 2

            //Getting Maximum TAT defined from a parameters detail of an order
            $total_time_taken_days = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', '=', $orderDetail->order_id)->max('time_taken_days');
            $total_time_taken_days = !empty($total_time_taken_days) && is_numeric($total_time_taken_days) ? $total_time_taken_days : '0';

            //Checking If TAT-In-Days is re-updatable in case of Microbilogical Equipment Exist
            $is_tat_in_day_reupdatable = !empty($orderDetail->tat_in_days) && !empty($hasEquipmentMicrobiological) ? $total_time_taken_days : '0';
        } else {                                                //STEP 3	    

            //Getting Maximum TAT defined from a parameters detail of an order
            $orderParametersDetail = DB::table('order_parameters_detail')->select('order_parameters_detail.time_taken_days', 'order_parameters_detail.time_taken_mins')->where('order_parameters_detail.order_id', '=', $orderDetail->order_id)->get();
            foreach ($orderParametersDetail as $key => $orderParameter) {
                $time_taken_days[] = !empty($orderParameter->time_taken_days) ? $orderParameter->time_taken_days : '0';
            }
            //Getting Maximum days from all parameter test allocated days
            $total_time_taken_days = !empty($time_taken_days) && array_filter($time_taken_days) ? round(max($time_taken_days)) : '0';
            $total_time_taken_days = !empty($total_time_taken_days) && is_numeric($total_time_taken_days) ? $total_time_taken_days : '0';
        }

        if (!empty($total_time_taken_days) && !empty($orderDetail->booking_date)) {

            //if booking date after 2.00 PM.Changed by Client on 19-June-2021
            if (strtotime(date('ha', strtotime($orderDetail->booking_date))) > strtotime("2pm")) {
                $total_time_taken_days = round($total_time_taken_days + '1');
            }

            //Add days to current date to calculate the observed expected due date
            $expectedDueDate = date(MYSQLDATETIMEFORMAT, strtotime('+' . $total_time_taken_days . ' day', strtotime($orderDetail->booking_date)));

            //Checking if any holidays lies between order booking date and Calculated Expected Due Date
            $holidayDayCounts = DB::table('holiday_master')->where('holiday_master.holiday_status', '1')->where('holiday_master.division_id', $orderDetail->division_id)->whereBetween(DB::raw("DATE(holiday_master.holiday_date)"), array(date('Y-m-d', strtotime($orderDetail->booking_date)), date('Y-m-d', strtotime($expectedDueDate))))->count();
            if ($holidayDayCounts) {
                $expectedDueDate = date(MYSQLDATETIMEFORMAT, strtotime('+' . $holidayDayCounts . ' day', strtotime($expectedDueDate)));
            }

            //Checking there any sunday lies on calculated days,then add number of days according to number of sunday in expected due date
            $sundays = $models->getSundays($orderDetail->booking_date, $expectedDueDate);
            if (!empty($sundays)) {
                $expectedDueDate = date(MYSQLDATETIMEFORMAT, strtotime('+' . count($sundays) . ' day', strtotime($expectedDueDate)));
            }

            //Including the Current Day in the Final Expected Due Date
            $expectedDueDate = $models->validateSundayHoliday_v2($orderDetail->division_id, date(MYSQLDATETIMEFORMAT, strtotime('-1 day', strtotime($expectedDueDate))), '1', '-');
        }

        return array($expectedDueDate, $is_tat_in_day_reupdatable);
    }

    /*******************************************
     *Function : Saving/Updating Report Due Date and Departmnet Due date in Order Parameter table
     *Created By : Praveen Singh
     *Created On : 11-July-2019
     *Modified On : 11-July-2019
     ******************************************/
    function updateReportDepartmentDueDate($order_id, $date = NULL)
    {

        global $models;

        $orderDetail                 = $this->getOrderDetail($order_id);
        $hasEquipmentMicrobiological = DB::table('order_parameters_detail')->where('order_parameters_detail.equipment_type_id', '22')->where('order_parameters_detail.order_id', '=', $order_id)->first();
        $orderParametersDetail       = DB::table('order_parameters_detail')->join('order_master', 'order_master.order_id', 'order_parameters_detail.order_id')->select('order_master.order_id', 'order_master.booking_date', 'order_master.division_id', 'order_parameters_detail.analysis_id', 'order_parameters_detail.time_taken_days', 'order_parameters_detail.report_due_date', 'order_parameters_detail.dept_due_date')->where('order_parameters_detail.order_id', $order_id)->whereNotNull('order_master.booking_date')->get()->toArray();

        if (!empty($orderDetail) && !empty($orderParametersDetail)) {
            foreach ($orderParametersDetail as $key => $orderParameter) {

                $orderParameter->booking_date = !empty($date) ? $date : $orderParameter->booking_date;

                if (!empty($orderDetail->tat_in_days) && empty($hasEquipmentMicrobiological)) {
                    //Getting Days of TAT Calculation
                    $orderParameter->time_taken_days = !empty($orderDetail->tat_in_days) && is_numeric($orderDetail->tat_in_days) ? round($orderDetail->tat_in_days) : '0';
                } else {
                    //Filtering Time Taken in days is a Numeric Value and Not NULL
                    $orderParameter->time_taken_days = !empty($orderParameter->time_taken_days) && is_numeric($orderParameter->time_taken_days) ? round($orderParameter->time_taken_days) : '1';
                }

                //if booking date after 2.00 PM
                if (strtotime(date('ha', strtotime($orderParameter->booking_date))) > strtotime("2pm")) {
                    $orderParameter->time_taken_days = round($orderParameter->time_taken_days + '1');
                }

                //Add days to current date to calculate the observed expected due date
                $orderParameter->dept_due_date = date(MYSQLDATETIMEFORMAT, strtotime('+' . $orderParameter->time_taken_days . ' day', strtotime($orderParameter->booking_date)));

                //Checking if any holidays lies between order booking date and Calculated Department/Report Due Date
                $holidayDayCounts = DB::table('holiday_master')->where('holiday_master.holiday_status', '1')->where('holiday_master.division_id', $orderParameter->division_id)->whereBetween(DB::raw("DATE(holiday_master.holiday_date)"), array(date('Y-m-d', strtotime($orderParameter->booking_date)), date('Y-m-d', strtotime($orderParameter->dept_due_date))))->count();
                if ($holidayDayCounts) {
                    $orderParameter->dept_due_date = date(MYSQLDATETIMEFORMAT, strtotime('+' . $holidayDayCounts . ' day', strtotime($orderParameter->dept_due_date)));
                }

                //Checking there any sunday lies on calculated days,then add number of days according to number of sunday in department/report due date
                $sundays = $models->getSundays($orderParameter->booking_date, $orderParameter->dept_due_date);
                if (!empty($sundays)) {
                    $orderParameter->dept_due_date = date(MYSQLDATETIMEFORMAT, strtotime('+' . count($sundays) . ' day', strtotime($orderParameter->dept_due_date)));
                }

                //Including the Current Day in the Final Expected Due Date
                $orderParameter->report_due_date = $orderParameter->dept_due_date = $models->validateSundayHoliday_v2($orderParameter->division_id, date(MYSQLDATETIMEFORMAT, strtotime('-1 day', strtotime($orderParameter->dept_due_date))), '1', '-');

                if (!empty($orderParameter->analysis_id) && !empty($orderParameter->report_due_date) && !empty($orderParameter->dept_due_date)) {
                    DB::table('order_parameters_detail')->where('order_parameters_detail.analysis_id', $orderParameter->analysis_id)->update(['order_parameters_detail.dept_due_date' => $orderParameter->dept_due_date, 'order_parameters_detail.report_due_date' => $orderParameter->report_due_date]);
                }
            }

            //Updating Department Due Date and Report Due Date in Order Master
            $maxOrderDepDueDate = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $order_id)->max('order_parameters_detail.dept_due_date');
            return !empty($maxOrderDepDueDate) ? DB::table('order_master')->where('order_master.order_id', $order_id)->update(['order_master.order_dept_due_date' => $maxOrderDepDueDate, 'order_master.order_report_due_date' => $maxOrderDepDueDate]) : false;
        }
    }

    /**
     * Updating Order Booking Amount
     * Created By : Praveen Singh
     * Created On : 29-Aug-2019
     */
    function updateBookingSampleAmount($orderId, $submittedFormData)
    {

        global $models;

        $orderData = $this->getOrderDetail($orderId);
        if (empty($orderData->order_sample_type)) {
            if (empty($orderData->booked_order_amount)) {
                $bookedOrderAmount = $this->getBookedSamplePrice($orderId);
                if (!empty($orderData->order_id) && !empty($bookedOrderAmount)) {
                    return DB::table('order_master')->where('order_master.order_id', $orderData->order_id)->update(['order_master.booked_order_amount' => trim($bookedOrderAmount)]);
                } else {
                    return false;
                }
            } else {
                return true;
            }
        } else {
            DB::table('order_master')->where('order_master.order_id', $orderData->order_id)->update(['order_master.booked_order_amount' => NULL]);
            return true;
        }
    }

    /**
     * Updating Order Booking Amount
     * Created By : Praveen Singh
     * Created On : 29-Aug-2019
     */
    function insertUpdateDisciplineGroupDetail($orderId)
    {

        global $models;

        $dataSave = $flag = array();

        //Getting All Customer Order Detail which is in Booked  Stage
        $orderData = $this->getOrderDetail($orderId);
        $allowOrderStatus = true; //!empty($orderData->booking_date) && strtotime(date('Y-m-d', strtotime($orderData->booking_date))) >= strtotime(date('Y-m-d')) ? true : false;
        if ($allowOrderStatus) {

            $newParametersDetail = DB::table('order_parameters_detail')
                ->join('order_master', 'order_master.order_id', 'order_parameters_detail.order_id')
                ->join('test_parameter', 'test_parameter.test_parameter_id', 'order_parameters_detail.test_parameter_id')
                ->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
                ->join('order_report_discipline_parameter_dtls', 'order_report_discipline_parameter_dtls.ordp_test_parameter_category_id', 'test_parameter_categories.test_para_cat_id')
                ->join('order_report_disciplines', 'order_report_disciplines.or_discipline_id', 'order_report_discipline_parameter_dtls.ordp_discipline_id')
                ->whereColumn('order_report_discipline_parameter_dtls.ordp_division_id', 'order_master.division_id')
                ->whereColumn('order_report_discipline_parameter_dtls.ordp_product_category_id', 'order_master.product_category_id')
                ->where('order_parameters_detail.order_id', '=', $orderId)
                ->pluck('order_report_disciplines.or_discipline_id', 'order_report_discipline_parameter_dtls.ordp_test_parameter_category_id')
                ->all();

            //Getting Deleted Test parameter Array
            $existingParametersDetail = DB::table('order_discipline_group_dtl')->where('order_discipline_group_dtl.order_id', '=', $orderId)->pluck('order_discipline_group_dtl.odg_id', 'order_discipline_group_dtl.test_parameter_category_id')->all();
            $removedParametersDetail = array_diff(array_keys($existingParametersDetail), array_keys($newParametersDetail));
            if (!empty($removedParametersDetail)) {
                DB::table('order_discipline_group_dtl')->where('order_discipline_group_dtl.order_id', '=', $orderId)->whereIn('order_discipline_group_dtl.test_parameter_category_id', $removedParametersDetail)->delete();
            }

            //Inseting New Test parameter Array
            if (!empty($newParametersDetail)) {
                foreach ($newParametersDetail as $test_parameter_category_id => $discipline_id) {
                    $orderDisciplineGroupData = DB::table('order_discipline_group_dtl')
                        ->where('order_discipline_group_dtl.order_id', '=', $orderId)
                        ->where('order_discipline_group_dtl.test_parameter_category_id', $test_parameter_category_id)
                        ->first();
                    if (empty($orderDisciplineGroupData)) {
                        $dataSave[$test_parameter_category_id]['order_id']              = $orderId;
                        $dataSave[$test_parameter_category_id]['test_parameter_category_id'] = $test_parameter_category_id;
                        $dataSave[$test_parameter_category_id]['discipline_id']              = $discipline_id;
                        $dataSave[$test_parameter_category_id]['created_by']                 = USERID;
                    } else {
                        $flag[] = DB::table('order_discipline_group_dtl')
                            ->where('order_discipline_group_dtl.order_id', '=', $orderId)
                            ->where('order_discipline_group_dtl.test_parameter_category_id', $test_parameter_category_id)
                            ->update(['order_discipline_group_dtl.discipline_id' => $discipline_id]);
                    }
                }
                if (!empty($dataSave)) {
                    $flag[] = DB::table('order_discipline_group_dtl')->insert($dataSave);
                }
            }

            return !empty($flag) ? true : false;
        }
    }

    /*************************
     *Getting Group Dropdown List
     *Created By : Praveen Singh
     *Created On : 19-Nov-2019
     *************************/
    function getOrderReportGroupDropdownList($division_id, $product_category_id)
    {
        return DB::table('order_report_groups')
            ->select('order_report_groups.org_group_id as id', 'order_report_groups.org_group_name as name')
            ->where('order_report_groups.org_group_status', '=', '1')
            ->where('order_report_groups.org_division_id', '=', $division_id)
            ->where('order_report_groups.org_product_category_id', '=', $product_category_id)
            ->orderBy('order_report_groups.org_group_name', 'ASC')
            ->get()
            ->toArray();
    }

    /*****************************
     * Getting Dynamic Field Name/Value Array from order_dynamic_field_dtl tables
     * Created By : Praveen Singh
     * Created On : 30-Jan-2020
     ****************************/
    public function getDynamicFieldData($order_id, $arrayTypes = array())
    {
        if (!empty($arrayTypes['keyValues'])) {
            return DB::table('order_dynamic_field_dtl')->where('order_dynamic_field_dtl.order_id', $order_id)->pluck('order_dynamic_field_dtl.order_field_value', 'order_dynamic_field_dtl.order_field_name')->all();
        } else {
            return DB::table('order_dynamic_field_dtl')->select('order_dynamic_field_dtl.odf_id', 'order_dynamic_field_dtl.order_field_name', 'order_dynamic_field_dtl.order_field_value')->where('order_dynamic_field_dtl.order_id', $order_id)->get()->toArray();
        }
    }

    /*****************************
     * Saving Dynamic Field Name/Value Array in order_dynamic_field_dtl tables
     * Created By : Praveen Singh
     * Created On : 30-Jan-2020
     ****************************/
    function save_order_dynamic_field_detail($dynamicFieldNameValueArray, $orderId)
    {
        if (!empty($dynamicFieldNameValueArray['order_field_name']) && !empty($dynamicFieldNameValueArray['order_field_value']) && !empty($orderId)) {

            $flag = $dataSaveFinal = $dataSave = $dataUpdate = array();

            foreach ($dynamicFieldNameValueArray['order_field_name'] as $key => $value) {
                $dataSaveFinal[$key]['order_id']            = $orderId;
                $dataSaveFinal[$key]['order_field_name']    = trim($value);
                $dataSaveFinal[$key]['order_field_value']   = trim(ucwords(strtolower($dynamicFieldNameValueArray['order_field_value'][$key])));
                $dataSaveFinal[$key]['order_field_date']    = defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');
                $dataSaveFinal[$key]['odf_created_by']      = USERID;
            }
            if (!empty($dataSaveFinal)) {
                foreach ($dataSaveFinal as $key => $values) {
                    $orderDynamicFieldExist = DB::table('order_dynamic_field_dtl')->where('order_dynamic_field_dtl.order_id', $values['order_id'])->where('order_dynamic_field_dtl.order_field_name', $values['order_field_name'])->where('order_dynamic_field_dtl.order_field_value', $values['order_field_value'])->first();
                    if (empty($orderDynamicFieldExist->odf_id)) {
                        $dataSave[] = $values;
                    } elseif (!empty($orderDynamicFieldExist->odf_id)) {
                        $values['odf_id'] = $orderDynamicFieldExist->odf_id;
                        $dataUpdate[]     = $values;
                    }
                }
            }

            //Saving New Array in order_dynamic_field_dtl table
            $flag[] = !empty($dataSave) && DB::table('order_dynamic_field_dtl')->insert($dataSave) ? true : false;

            //Updating existing value in order_dynamic_field_dtl table
            if (!empty($dataUpdate)) {
                foreach ($dataUpdate as $key => $values) {
                    $flag[] = DB::table('order_dynamic_field_dtl')->where('order_dynamic_field_dtl.odf_id', $values['odf_id'])->where('order_dynamic_field_dtl.order_id', $values['order_id'])->update(['order_field_name' => $values['order_field_name'], 'order_field_value' => $values['order_field_value']]);
                }
            }

            return !empty($flag) ? true : false;
        }
    }

    /*****************************
     * Getting Order Discipline and Group Detail Array in order_discipline_group_dtl tables
     * Created By : Praveen Singh
     * Created On : 06-Feb-2020
     ****************************/
    function get_order_discipline_group_detail($order_id, $test_parameter_category_id = NULL, $isPesticideCategory = false)
    {
        $orderDisciplineGroupObj = DB::table('order_discipline_group_dtl')
            ->join('order_report_disciplines', 'order_report_disciplines.or_discipline_id', 'order_discipline_group_dtl.discipline_id')
            ->join('order_report_groups', 'order_report_groups.org_group_id', 'order_discipline_group_dtl.group_id')
            ->select('order_discipline_group_dtl.discipline_id', 'order_report_disciplines.or_discipline_name as discipline_name', 'order_discipline_group_dtl.group_id', 'order_report_groups.org_group_name as group_name')
            ->where('order_discipline_group_dtl.order_id', '=', $order_id);
        if (!empty($test_parameter_category_id)) {
            $orderDisciplineGroupObj->where('order_discipline_group_dtl.test_parameter_category_id', $test_parameter_category_id);
        } else if (!empty($isPesticideCategory) && !empty($this->getNumberOfTestParameterCategoryOfOrder($order_id, array(defined('PESTICIDE_RESIDUE_TEST_PARA_CATEGORY') ? PESTICIDE_RESIDUE_TEST_PARA_CATEGORY : '7')))) {
            $orderDisciplineGroupObj->where('order_discipline_group_dtl.test_parameter_category_id', defined('PESTICIDE_RESIDUE_TEST_PARA_CATEGORY') ? PESTICIDE_RESIDUE_TEST_PARA_CATEGORY : '7');
        }
        return $orderDisciplineGroupObj->orderBy('order_report_disciplines.or_discipline_name', 'ASC')->get()->toArray();
    }

    /*****************************
     * Getting Order Discipline and Group Detail Array in order_discipline_group_dtl tables
     * Created By : Praveen Singh
     * Created On : 06-Feb-2020
     ****************************/
    function get_eic_order_discipline_group_detail($order_id, $test_parameter_category_id = NULL, $isPesticideCategory = false)
    {

        $returnData = array();

        $orderDisciplineGroupObj = DB::table('order_discipline_group_dtl')
            ->join('order_report_disciplines', 'order_report_disciplines.or_discipline_id', 'order_discipline_group_dtl.discipline_id')
            ->join('order_report_groups', 'order_report_groups.org_group_id', 'order_discipline_group_dtl.group_id')
            ->select('order_discipline_group_dtl.discipline_id', 'order_report_disciplines.or_discipline_name as discipline_name', 'order_discipline_group_dtl.group_id', 'order_report_groups.org_group_name as group_name')
            ->where('order_discipline_group_dtl.order_id', '=', $order_id);
        if (!empty($test_parameter_category_id)) {
            $orderDisciplineGroupObj->where('order_discipline_group_dtl.test_parameter_category_id', $test_parameter_category_id);
        } else if (!empty($isPesticideCategory) && !empty($this->getNumberOfTestParameterCategoryOfOrder($order_id, array(defined('PESTICIDE_RESIDUE_TEST_PARA_CATEGORY') ? PESTICIDE_RESIDUE_TEST_PARA_CATEGORY : '7')))) {
            $orderDisciplineGroupObj->where('order_discipline_group_dtl.test_parameter_category_id', defined('PESTICIDE_RESIDUE_TEST_PARA_CATEGORY') ? PESTICIDE_RESIDUE_TEST_PARA_CATEGORY : '7');
        }
        $orderDisciplineGroup = $orderDisciplineGroupObj->orderBy('order_report_disciplines.or_discipline_name', 'ASC')->get()->toArray();
        if (!empty($orderDisciplineGroup)) {
            foreach ($orderDisciplineGroup as $key => $values) {
                $returnData['discipline_name'][$values->discipline_name] = $values->discipline_name;
                $returnData['group_name'][$values->group_name] = $values->group_name;
            }
        }
        return $returnData;
    }

    /****************************************************
     * Description : update Order Client Approval Process Detail
     * created By  : Praveen Singh
     * created On  : 15-May-2021
     ***************************************************/
    public function updateOrderClientApprovalProcessDetail($formFieldArray, $orderId)
    {
        $data = DB::table('order_client_approval_dtl')->where('order_client_approval_dtl.ocad_order_id', '=', $orderId)->first();
        if (!empty($data->ocad_id)) {
            if (defined('IS_ADMIN') && IS_ADMIN) {
                $dataSave = array();
                $dataSave['ocad_approved_by']         = $formFieldArray['ocad_approved_by'];
                $dataSave['ocad_date']                = $this->getFormatedDate($formFieldArray['ocad_date'], $format = 'Y-m-d');
                $dataSave['ocad_credit_period']       = $formFieldArray['ocad_credit_period'];
                $dataSave['ocad_date_upto_amt']       = $this->getFormatedDate($formFieldArray['ocad_date_upto_amt'], $format = 'Y-m-d');
                return DB::table('order_client_approval_dtl')->where('order_client_approval_dtl.ocad_id', '=', $data->ocad_id)->update($dataSave);
            } else {
                return true;
            }
        } else {
            $dataSave = array();
            $dataSave['ocad_order_id']           = $orderId;
            $dataSave['ocad_approved_by']       = $formFieldArray['ocad_approved_by'];
            $dataSave['ocad_date']               = $this->getFormatedDate($formFieldArray['ocad_date'], $format = 'Y-m-d');
            $dataSave['ocad_credit_period']       = $formFieldArray['ocad_credit_period'];
            $dataSave['ocad_date_upto_amt']       = $this->getFormatedDate($formFieldArray['ocad_date_upto_amt'], $format = 'Y-m-d');
            $dataSave['ocad_status']               = '1';
            return DB::table('order_client_approval_dtl')->insertGetId($dataSave);
        }
    }

    /****************************************************
     * Description : Get Header Type Text based On Customer Types
     * created By  : Praveen Singh
     * created On  : 25-June-2021
     ***************************************************/
    public function getHeaderTypeTextbasedOnCustomerTypes($division_id, $product_category_id, $customer_id)
    {
        $headerTypetext =  DB::table('order_report_header_types')
            ->join('report_header_type_default', 'report_header_type_default.rhtd_id', 'order_report_header_types.orht_report_hdr_type')
            ->join('customer_master', 'customer_master.customer_type', 'order_report_header_types.orht_customer_type')
            ->where('order_report_header_types.orht_division_id', '=', $division_id)
            ->where('order_report_header_types.orht_product_category_id', '=', $product_category_id)
            ->where('customer_master.customer_id', '=', $customer_id)
            ->pluck('report_header_type_default.rhtd_name', 'report_header_type_default.rhtd_name')
            ->first();
        return !empty($headerTypetext) ? $headerTypetext : 'Form 39A';
    }

    /**
     * Calculating Price Variation between ITC Standard Price Vs Sample Amount
     * Created By : Praveen Singh
     * Created On : 21-Sept-2021
     */
    function __calculatePriceVariationBetSampleAmtAndItcStdPrice($order_id)
    {

        global $models;

        $orderData = $this->getOrderDetail($order_id);
        if (empty($orderData->order_sample_type) && !empty($orderData->booked_order_amount)) {

            //Booked Sample Amount
            $booked_order_amount   = !empty($orderData->booked_order_amount) ? $orderData->booked_order_amount : $this->getBookedSamplePrice($orderData->order_id);

            //Booked ITC Standard Rate
            $booked_itc_std_amount = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $order_id)->whereNotNull('order_parameters_detail.selling_price')->sum('order_parameters_detail.selling_price');

            //Variation - Sum of ITC Price - Sample anmount
            $variation_amount        = $booked_itc_std_amount - $booked_order_amount;

            //Discount Sample Percentage
            $discount_percentage   = $models->globalPercentageCalculation($variation_amount, $booked_itc_std_amount);

            //Booked Order Parameter Detail
            $orderParameterData    = $this->getOrderParameterDetail($order_id);
            if (!empty($orderParameterData)) {
                foreach ($orderParameterData as $key => $orderParameter) {
                    //Calculation Booked Parameter Wise Variation/Discount Price(Variation/Discount Price)
                    $orderParameter->variation_price = !empty($orderParameter->selling_price) ? $models->roundValues(($discount_percentage *  $orderParameter->selling_price) / 100) : '0.00';
                    if (!empty($orderParameter->variation_price)) {
                        DB::table('order_parameters_detail')->where('order_parameters_detail.analysis_id', $orderParameter->analysis_id)->update(['order_parameters_detail.variation_price' => $orderParameter->variation_price]);
                    }
                }
            }
        }

        return true;
    }

    /**
     * Updating Report Analysat Window Setting in order_analyst_window_settings
     * Created By : Praveen Singh
     * Created On : 08-Oct-2021
     */
    function __updateReportAnalystWindowUISettings($order_id)
    {

        global $models;

        $flag = array();

        $orderData = $this->getOrderDetail($order_id);
        if (!empty($orderData->order_id)) {
            $order_parameters_detail_array = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', '=', $orderData->order_id)->whereNotNull('order_parameters_detail.equipment_type_id')->pluck('order_parameters_detail.equipment_type_id', 'order_parameters_detail.analysis_id')->all();
            if (!empty($order_parameters_detail_array)) {
                foreach ($order_parameters_detail_array as $analysis_id => $equipment_type_id) {
                    $oaws_id = DB::table('order_analyst_window_settings')
                        ->where('order_analyst_window_settings.oaws_division_id', $orderData->division_id)
                        ->where('order_analyst_window_settings.oaws_product_category_id', $orderData->product_category_id)
                        ->where('order_analyst_window_settings.oaws_equipment_type_id', $equipment_type_id)
                        ->orderBy('order_analyst_window_settings.oaws_id', 'DESC')
                        ->pluck('order_analyst_window_settings.oaws_id')
                        ->first();
                    if (!empty($oaws_id)) {
                        $flag[$analysis_id] = DB::table('order_parameters_detail')->where('order_parameters_detail.analysis_id', $analysis_id)->update(['order_parameters_detail.oaws_ui_setting_id' => $oaws_id]);
                    }
                }
            }
        }
        return !empty($flag) ? true : false;
    }

    /*************************
     *Getting Min Date of start of analysis
     *Created By : Praveen Singh
     *Created On : 20-May-2022
     *************************/
    function getMinDateStartOfAnalysis($orderId)
    {
        return DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $orderId)->min('order_parameters_detail.analysis_start_date');
    }

    /*************************
     *Getting Max Date of completion of Analysis
     *Created By : Praveen Singh
     *Created On : 12-July-2019
     *************************/
    function getMaxDateCompletionOfAnalysis($orderId)
    {
        return DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $orderId)->max('order_parameters_detail.analysis_completion_date');
    }
}
