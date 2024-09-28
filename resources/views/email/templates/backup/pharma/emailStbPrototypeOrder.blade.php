<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta name="viewport" content="width=device-width" />
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <link href="{!! asset('public/css/email.css') !!}" rel="stylesheet" type="text/css"/>
		<style>
		.content{font-size: 13px !important;}
		 .width40{ width: 40% !important;}
		 .width20{ width: 20% !important;}
		 .content table >tbody >tr> td {
			padding: 2px !important;
		 }
		</style>
   </head>
   <body bgcolor="#FFFFFF">
      <!-- BODY -->
      <div class="row content" style="max-width:100% !important;">
         <table class="col-md-12 table table-striped body-wrap">
            <tr>
               <td>
                  <h4> Dear {{!empty($user['name']) ? ucfirst($user['name']): ''}},</h4>
                  <p class="lead">Thanks a lot for the Sample Submission with us.We acknowledge it.Details are given following:</p>
                  <!--<p class="lead">The Report will be delivered tentatively by <b>{{!empty($user['expected_due_date']) ? date('d/m/Y',strtotime($user['expected_due_date'])): '-'}}</b></p>-->
               </td>
            </tr>
            <tr>
               <td  bgcolor="#FFFFFF">
                  <table class="table table-striped" border="1px solid black" style="border-collapse:collapse;">
                     <tr>
                        <td colspan="4" style="background: #e4dede;">Customer Detail</td>
                     </tr>
                     <tr>
                        <td><b>Sample Received No.:</b><span>{{!empty($stbOrderList->sample_recieved) ? $stbOrderList->sample_recieved : '-' }}</span>	</td>
                        <td><b> Customer:	</b><span>{{!empty($stbOrderList->customer_name) ? $stbOrderList->customer_name : '-' }}</span>	</td>
                        <td><b>Customer Location:</b><span>{{!empty($stbOrderList->state_name) ? $stbOrderList->state_name : '-' }}</span>	</td>
                        <td><b>Customer Mfg. Lic No.:</b><span>{{!empty($stbOrderList->stb_mfg_lic_no) ? $stbOrderList->stb_mfg_lic_no : '-' }}</span></td>
                     </tr>
                     <tr>
                        <td><b>Sales Executive:	</b><span>{{!empty($stbOrderList->sale_executive_name) ? $stbOrderList->sale_executive_name : '-' }}</span></td>
                        <td><b> Discount Type:	</b><span>{{!empty($stbOrderList->discount_type) ? $stbOrderList->discount_type : '-' }}</span></td>
                        <td><b> Discount value:	</b><span>{{!empty($stbOrderList->discount_value) ? $stbOrderList->discount_value : '-' }}</span></td>
                        <td><b>Billing Type:	</b><span>{{!empty($stbOrderList->billing_type) ? $stbOrderList->billing_type : '-' }}</span></td>
                     </tr>
                     <tr>
                        <td><b>Invoicing Type:	</b><span>{{!empty($stbOrderList->invoicing_type) ? $stbOrderList->invoicing_type : '-' }}</span></td>
                        <td><b> Reporting To:	</b><span>{{!empty($stbOrderList->reportingCustomerName) ? $stbOrderList->reportingCustomerName : '-' }}</span></td>
                        <td colspan="2"><b>Invoicing To:</b><span>{{!empty($stbOrderList->invoicingCustomerName) ? $stbOrderList->invoicingCustomerName : '-' }}</span></td>
                     </tr>
                     <tr>
                        <td colspan="4" style="background: #e4dede;">Sample Detail</td>
                     </tr>
                     <tr>
                        <td><b>Branch:	</b>
                           <span>{{!empty($stbOrderList->division_name) ? $stbOrderList->division_name : '-' }}</span>
                        </td>
                        <td><b> Incubation Date:	</b>
                           <span>{{!empty($stbOrderList->stb_prototype_date) ? $stbOrderList->stb_prototype_date : '-' }}</span>
                        </td>
                        <td> <b>Letter Reference No.:	</b><span>{{!empty($stbOrderList->stb_reference_no) ? $stbOrderList->stb_reference_no : '-' }}</span></td>
                        <td><b>Letter Reference Date:</b><span>{{!empty($stbOrderList->stb_letter_no) ? $stbOrderList->stb_letter_no : '-' }}</span></td>
                     </tr>
                     <tr>
                        <td><b>Sample Name:</b><span>{{!empty($stbOrderList->stb_sample_description_name) ? $stbOrderList->stb_sample_description_name : '-' }}</span>	</td>
                        <td><b>Batch No.:</b><span>{{!empty($stbOrderList->stb_batch_no) ? $stbOrderList->stb_batch_no : '-' }}</span>	</td>
                        <td><b>Sample Qty.:</b><span>{{!empty($stbOrderList->stb_sample_qty) ? $stbOrderList->stb_sample_qty : '-' }}</span>	</td>
                        <td><b>Date of Mfg.:</b><span>{{!empty($stbOrderList->stb_mfg_date) ? $stbOrderList->stb_mfg_date : '-' }}</span>	</td>
                     </tr>
                     <tr>
                        <td><b>Date of Expiry:</b><span>{{!empty($stbOrderList->stb_expiry_date) ? $stbOrderList->stb_expiry_date : '-' }}</span>	</td>
                        <td><b> Batch Size:</b>	<span>{{!empty($stbOrderList->stb_batch_size) ? $stbOrderList->stb_batch_size : '-' }}</span></td>
                        <td><b> Supplied By:</b>	<span>{{!empty($stbOrderList->stb_supplied_by) ? $stbOrderList->stb_supplied_by : '-' }}</span></td>
                        <td> <b>Manufactured By :</b>	<span>{{!empty($stbOrderList->stb_manufactured_by) ? $stbOrderList->stb_manufactured_by : '-' }}</span></td>
                     </tr>
                     <tr>
                        <td><b>Brand:</b>	<span>{{!empty($stbOrderList->stb_brand_type) ? $stbOrderList->stb_brand_type : '-' }}</span></td>
                        <td> <b>Sample Priority:</b>	<span>{{!empty($stbOrderList->sample_priority_name) ? $stbOrderList->sample_priority_name : '-' }}</span></td>
                        <td> <b>Surcharge (Rs):</b><span>{{!empty($stbOrderList->stb_surcharge_value) ? $stbOrderList->stb_surcharge_value : '-' }}</span></td>
                        <td><b>Remark:</b><span>{{!empty($stbOrderList->stb_remarks) ? $stbOrderList->stb_remarks : '-' }}</span></td>
                     </tr>
                     <tr>
                        <td><b>PI Reference(if any):</b>	<span>{{!empty($stbOrderList->stb_pi_reference) ? $stbOrderList->stb_pi_reference : '-' }}</span></td>
                        <td><b> Sealed/Unsealed:</b>
                           <span>
                           @if(isset($stbOrderList->stb_is_sealed) && $stbOrderList->stb_is_sealed =='0')
                           {{ 'Unsealed' }}
                           @elseif(isset($stbOrderList->stb_is_sealed) && $stbOrderList->stb_is_sealed =='1')
                           {{ 'Sealed' }}
                           @elseif(isset($stbOrderList->stb_is_sealed) && $stbOrderList->stb_is_sealed =='2')
                           {{ 'Intact' }}
                           @elseif(isset($stbOrderList->stb_is_sealed) && $stbOrderList->stb_is_sealed =='3')
                           {{ 'NA' }}
                           @elseif(isset($stbOrderList->stb_is_sealed) && $stbOrderList->stb_is_sealed =='4')
                           {{ 'Sealed by Officer Incharge' }}
                           @else
                           {{ 'Sealed by Department' }}
                           @endif
                           </span>
                        </td>
                        <td> <b>Signed/Unsigned:</b>
                           <span>
                           @if(isset($stbOrderList->stb_is_signed) && $stbOrderList->stb_is_signed =='0')
                           {{ 'Unsigned' }}
                           @elseif(isset($stbOrderList->stb_is_signed) && $stbOrderList->stb_is_signed =='1')
                           {{ 'Signed' }}
                           @elseif(isset($stbOrderList->stb_is_signed) && $stbOrderList->stb_is_signed =='2')
                           {{ 'NA' }}
                           @elseif(isset($stbOrderList->stb_is_signed) && $stbOrderList->stb_is_signed =='3')
                           {{ 'Signed by Officer Incharge' }}
                           @else
                           {{ 'Sealed by Department' }}
                           @endif
                           </span>
                        </td>
                        <td><b>Packing Mode:</b><span>{{!empty($stbOrderList->stb_packing_mode) ? $stbOrderList->stb_packing_mode : '-' }}</span></td>
                     </tr>
                     <tr>
                        <td><b>Sampling Date:</b><span>{{!empty($stbOrderList->stb_sampling_date) ? $stbOrderList->stb_sampling_date : '-' }}</span></td>
                        <td><b> Submission Type :</b><span>{{!empty($stbOrderList->sample_mode_name) ? $stbOrderList->sample_mode_name : '-' }}</span></td>
                        <td><b> Quotation No.:</b><span>{{!empty($stbOrderList->stb_quotation_no) ? $stbOrderList->stb_quotation_no : '-' }}</span></td>
                        <td><b> Actual Submission Type :</b><span>{{!empty($stbOrderList->stb_actual_submission_type) ? $stbOrderList->stb_actual_submission_type : '-' }}</span></td>
                     </tr>
                     <tr>
                        <td colspan="4"><b>Extra Amount:	</b><span>{{!empty($stbOrderList->stb_extra_amount) ? $stbOrderList->stb_extra_amount : '-' }}</span></td>
                     </tr>
                     <tr>
                        <td colspan="4" style="background: #e4dede;">Prototype Detail</td>
                     </tr>
                     <tr>
                        <td colspan="4">
                           <table class="table table-striped">
                              <tr>
                                 <th width="1%">S.No</th>
											<th width="20%">MONTH NAME</th>
											<th width="10%"> INCUBATION DATE</th>
											<th width="10%">END DATE</th>
											<th width="30%">PRODUCT NAME</th>
											<th width="10%">TEST STD NAME</th>
											<th width="30%">PRODUCT TESTS</th>
                              </tr>
                              @if(!empty($returnData))
                              @foreach($returnData as $key=> $prototypeDetail)
                               <tr  style="background: #e4dede !important;">
										  <td class="" width="1%"><b>{{$key+1}}.</b></td>
										  <td class="" width="20%">{{!empty($prototypeDetail->stb_label_name) ? $prototypeDetail->stb_label_name : '-'}}</td>
										  <td class="" width="10%">{{!empty($prototypeDetail->stb_start_date) ? $prototypeDetail->stb_start_date : '-'}}</td>
										  <td class="" width="10%">{{!empty($prototypeDetail->stb_end_date) ? $prototypeDetail->stb_end_date : '-'}}</td>
										  <td class="" width="30%">{{!empty($prototypeDetail->product_name) ? $prototypeDetail->product_name : '-'}}</td>
										  <td class="" width="10%">{{!empty($prototypeDetail->test_std_name) ? $prototypeDetail->test_std_name : '-'}}</td>
										  <td class="" width="30%">{{!empty($prototypeDetail->test_code) ? $prototypeDetail->test_code : '-'}}</td>
										</tr>
                              @foreach($prototypeDetail->prototypeParams as $stabiltyCondition=> $prototypeParams)
                              <tr>
                                 <td colspan ="7" style="padding:5px !important;"><span class="text-left"><b>STORAGE CONDITION:</b></span>{{$stabiltyCondition}} </td>
                              </tr>
                              <tr>
                                 <td colspan ="7"></td>
                              </tr>
                              <tr>
                                 <td colspan="7">
                                    <table class="table table-striped" border="1px solid black" style="border-collapse:collapse;">
                                       <tr>
                                          <th class="width40">PARAMETERS</th>
                                          <th class="width20">EQUIPMENT</th>
                                          <th class="width20">METHOD</th>
                                          <th class="width20" colspan="2">REQUIREMENT</th>
                                       </tr>
                                       @foreach($prototypeParams as $parameterCategory=>$prototypeParamDetail)
                                       <tr>
                                          <td colspan="9" class=""><b>{{!empty($parameterCategory) ? $parameterCategory : '' }}</b></td>
                                       </tr>
                                       @foreach($prototypeParamDetail as $paramDetail)
                                       @if(!empty($paramDetail->parameters) && strtolower($paramDetail->parameters) == 'description' || (strtolower($paramDetail->parameters) == 'reference to protocol'))
                                       <tr>
                                          <td colspan="9" class="">{{!empty($paramDetail->parameters) ? $paramDetail->parameters : '-'}}</td>
                                       </tr>
                                       @else
                                       <tr>
                                          <td class=" width40">{{!empty($paramDetail->parameters) ?  strip_tags($paramDetail->parameters) : '-'}}</td>
                                          <td class=" width20">{{!empty($paramDetail->equipment) ? $paramDetail->equipment : '-'}}</td>
                                          <td class=" width20">{{!empty($paramDetail->method) ? $paramDetail->method : '-'}}</td>
                                          <td class=" width20" colspan="2">{{!empty($paramDetail->requirement_from_to) ? $paramDetail->requirement_from_to : '-'}}</td>
                                       </tr>
                                       @endif
                                       @endforeach	
                                       @endforeach	
                                    </table>
                                 </td>
                              </tr>
                              @endforeach	  
                              @endforeach
                              @endif
                           </table>
                        </td>
                     </tr>
                  </table>
               </td>
            </tr>
         </table>
         <!-- FOOTER -->
         <table class="footer-wrap">
            <tr>
               <td></td>
               <td class="" >
                  <!-- content -->
                  <div class="content" style="padding:5px;margin-left: -1px;">
                     <table>
                        <tr>
                           <td align="left">
                              <p><strong>Thanks & Regards,</strong></p>
                              <p>Alka Sharma(alka.sharma@itclabs.com) / Mob. No. : +91-6283052092</p>
                              <p>Prachi Mishra(prachi.mishra@itclabs.com) / Mob. No. : +91-8360389901</p>
                              <p>Phone No.-0172-2561543</p>
                              <p>For Sales related queries,contact sales@itclabs.com</p>
                           </td>
                        </tr>
                     </table>
                  </div>
                  <!-- /content -->
               </td>
               <td></td>
            </tr>
         </table>
         <!-- /FOOTER -->
      </div>
      <!-- /BODY -->
   </body>
</html>
<?php //die; ?>