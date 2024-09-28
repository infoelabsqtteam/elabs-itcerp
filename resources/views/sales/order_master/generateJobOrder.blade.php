<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
    *{padding: 0;font-family: sans-serif;font-size: 13px;}
    @page { margin: 56px 20px 20px;}
    .page-break {page-break-before: always;}
    #header { position: fixed; left: 0px; top: -56px; right: 0px; width:100%; height: auto; text-align: center; }
    #footer { position:  fixed;  left: 0px; bottom: 0px; right: 0px; width:100%; height:50px;}
    #footer .page:after { content: counter(page, upper-roman); }
    #content{width:100%; left: 0px; bottom: 0px; right: 0px;}
    .cntr_hdng h5,.cntr_hdng p {  color: #4d64a1;font-size: 13px;font-weight: 700;margin: -3px!important;padding: 0;}
    .side_cntnt {width: 20%;border: 0px!important;font-size: 12px!important;color: #4d64a1;}
    .side_cntnt p {margin: 0;}
    .top_1 {border: 2px solid #4d64a1;color: #4d64a1;padding: 3px;width: auto!important;font-weight: 700;display: inline-block;}
    td table td {border-bottom: 0 none !important;padding:2px!important;}
    p{padding:1!important;margin:0!important;}
    .imgCls{padding:0px 0px 5px 0px;vertical-align: middle; text-align: left; min-height: 50px; min-width: 100px;}
    </style>
</head>
<body>
    <div id="header">
        <table width="100%" style="margin:0 auto;border-collapse:collapse;">
            <tr>
                <td width="10%" style="vertical-align:top;border:0px!important"><img width="65px" src="{{url('/public/images/template_logo.png')}}" /></td>
                <td width="80%" style="border:0px!important;text-align:center!important;">
                    <h3 style="font-family: times of romen; color: rgb(77, 100, 161); padding: 0px; font-size: 20px!important; font-weight: 600; margin-top: 5px!important;">Interstellar Testing Centre Private Limited</h3>
                    <h4 style="font-family: times of romen; color: rgb(77, 100, 161); padding: 0px; font-size: 14px!important; font-weight: 600; padding-top: -20px!important;">{{ !empty($viewData['order']['department_name']) ? strtoupper($viewData['order']['department_name']) :  ''}} : JOB ORDER</h4>
                </td>
                <td width="100%" class="side_cntnt" style="border:0!important"></td>
            </tr>
        </table>
    </div>
    <div id="footer">
        <table valign="bottom" width="100%" style="margin:0 auto;border-collapse:collapse;">
            <tr>
                <td colspan="3">
                    <table width="100%" style="margin:10px 0;">
                        <tr>
                            <td width="33.3%" align="left" valign="top">
                                @if(!empty($viewData['order']['user_signature']) && file_exists(DOC_ROOT.SIGN_PATH.$viewData['order']['user_signature']))
                                <p style="width:100%;"><img height="30px" width="80px" style="margin:-30px 0 0;" alt="{{$viewData['order']['user_signature']}}" src="{{DOC_ROOT.SIGN_PATH.$viewData['order']['user_signature']}}"/></p>
                                @endif
                                @if(!empty($viewData['order']['createdByName']))
                                <p style="font-size:10px;width:100%;">{{$viewData['order']['createdByName']}}</p>
                                @endif
                                <p><b>Booking Person</b></p>
                            </td>
                            <td width="33.3%" align="center" valign="top">
                                <script type='text/php'>
                                if(isset($pdf)){ 
                                    $font = $fontMetrics->get_font('serif','bold');
                                    $size = 11;
                                    $y    = $pdf->get_height() - 28;
                                    $x    = $pdf->get_width() - 305 - $fontMetrics->get_text_width('1/1', $font, $size);
                                    $pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
                                }
                                </script>
                            </td>
                            <td align="right" valign="top"><p style="padding-right:11px!important">{{ !empty($viewData['order']['order_date']) ? date(DATEFORMAT,strtotime($viewData['order']['order_date'])) :  ''}}</p><b>Booking Date</b></td>
                        </tr>
                    </table>
                </td>
            </tr>                         
        </table>
    </div> 
    <div id="content">
      <table width="100%" style="margin:0 auto;border-collapse:collapse;font-size:12px!important;">
            <tr>
               <td colspan="3">
                 <table width="100%" border="1" style="border-bottom:0px!important;border-collapse:collapse">
                     <tr>
                         <td width="50%"><b style="display: inline-block;vertical-align: middle; width: 155px;">Booking Date & Time</b><b>:</b>{{ !empty($viewData['order']['order_date']) ? date(DATETIMEFORMAT,strtotime($viewData['order']['order_date'])) :  ''}}</td>
                         <td><b style="display: inline-block;vertical-align: middle; width: 85px;">Security Code</b><b>:</b><span style="display: inline-block;vertical-align: middle; width: 100%;padding-top:3px;"><img style="height: 16px;" src="{{!empty($viewData['order']['barcode']) ? $viewData['order']['barcode'] : ''}}"></span></td>
                     </tr>
                 </table>
               </td>
            </tr>
             <tr>
               <td colspan="3">
                 <table width="100%" border="1" style="border-bottom:0px!important;border-collapse:collapse">
                     <tr>
                         <td width="50%"><b style="display: inline-block;vertical-align: middle; width: 155px;">Expected Due Date & Time</b><b>:</b>{{!empty($viewData['order']['expected_due_date']) ? date(DATETIMEFORMAT,strtotime($viewData['order']['expected_due_date'])) : '' }}</td>
                         <td><b style="display: inline-block;vertical-align: middle; width: 85px;">Booking Code</b><b>:</b>{{!empty($viewData['order']['order_no']) ? $viewData['order']['order_no'] : '' }}</td>
                     </tr>
                 </table>
               </td>
            </tr>
             <tr>
               <td colspan="3">
                 <table width="100%" border="1" style="border-bottom:0px!important;border-collapse:collapse">
                     <tr>
                         <td width="50%"><b style="display: inline-block;vertical-align: middle; width: 155px;">Quotation No.</b><b>:</b>{{!empty($viewData['order']['quotation_no']) ? $viewData['order']['quotation_no'] : '' }}</td>
                         <td><b style="display: inline-block;vertical-align: middle; width: 85px;">PO No.</b><b>:</b>{{!empty($viewData['order']['po_no']) ? $viewData['order']['po_no'] : '' }}</td>
                     </tr>
                 </table>
               </td>
            </tr>
             <tr>
               <td colspan="3">
                 <table width="100%" border="1" style="border-bottom:0px!important;border-collapse:collapse">
                     <tr>
                         <td width="50%"><b style="display: inline-block;vertical-align: middle; width: 155px;">Sampling Date & Time</b><b>:</b>{{!empty($viewData['order']['sampling_date']) &&($viewData['order']['sampling_date']!=NULL) ? date(DATETIMEFORMAT,strtotime($viewData['order']['sampling_date'])) : '' }}</b></td>
                         <td><b style="display: inline-block;vertical-align: middle; width: 85px;">Priority</b><b>:</b>{{!empty($viewData['order']['sample_priority_name']) ? $viewData['order']['sample_priority_name'] : '' }}</td>
                     </tr>
                 </table>
               </td>
            </tr>
            <tr>
               <td colspan="3">
                 <table width="100%" border="1" style="border-bottom:0px!important;border-collapse:collapse">
                        <tr>
                            <td style="padding:4px 0 4px 0px!important;" colspan="2"><b style="display: inline-block;vertical-align: middle; width: 84px;">Sample Name</b><b>:</b>{{!empty($viewData['order']['sample_description']) ? $viewData['order']['sample_description'] : '' }}</td>
                        </tr>
                 </table>
               </td>
            </tr>
            <tr>
               <td colspan="3">
                 <table width="100%" border="1" style="border-bottom:0px!important;border-collapse:collapse">
                     <tr>
                         <td width="33.3%"><b style="display: inline-block;vertical-align: middle; width: 84px;">Sampling Loc.</b><b>:</b>{{!empty($viewData['order']['division_name']) ? trim($viewData['order']['division_name']) : '' }}</td>
                         <td width="33.3%"><b style="display: inline-block;vertical-align: middle; width: 65px;">Batch Size</b><b>:</b>{{!empty($viewData['order']['batch_size']) ? $viewData['order']['batch_size'] : '' }}</td>
                         <td width="33.3%"><b style="display: inline-block;vertical-align: middle; width: 100px;">Sealed/Unsealed</b><b>:</b>@if($viewData['order']['is_sealed']==0) {{'Unsealed'}}@elseif($viewData['order']['is_sealed']==1){{'Sealed'}}@elseif($viewData['order']['is_sealed']==2){{'Intact'}}@elseif($viewData['order']['is_sealed']==3){{''}}@endif
                         </td>
                     </tr>
                 </table>
               </td>
            </tr>
            <tr>
               <td colspan="3">
                 <table width="100%" border="1" style="border-bottom:0px!important;border-collapse:collapse">
                     <tr>
                         <td width="33.3%"><b style="display: inline-block;vertical-align: middle; width: 84px;">Batch No.</b><b>:</b>{{!empty($viewData['order']['batch_no']) ? $viewData['order']['batch_no'] : '' }}</td>
                         <td width="33.3%"><b style="display: inline-block;vertical-align: middle; width: 65px;">Mfg Date</b><b>:</b>{{!empty($viewData['order']['mfg_date']) ? $viewData['order']['mfg_date'] : '' }}</td>
                         <td width="33.3%"><b style="display: inline-block;vertical-align: middle; width: 100px;">Sign/Unsign</b><b>:</b>@if($viewData['order']['is_signed']==0){{ 'Unsigned'}}@elseif($viewData['order']['is_signed']==1){{ 'Signed'}}@endif
                        </td>
                     </tr>
                 </table>
               </td>
            </tr>
            <tr>
               <td colspan="3">
                 <table width="100%" border="1" style="border-bottom:0px!important;border-collapse:collapse">
                     <tr>
                         <td width="33.3%"><b style="display: inline-block;vertical-align: middle; width: 84px;">Sample Qty</b><b>:</b>{{!empty($viewData['order']['sample_qty']) ? $viewData['order']['sample_qty'] : '' }}</td>
                         <td width="33.3%"><b style="display: inline-block;vertical-align: middle; width: 65px;">Exp. Date</b><b>:</b>{{!empty($viewData['order']['expiry_date']) ? $viewData['order']['expiry_date'] : '' }}</td>
                         <td width="33.3%"><b style="display: inline-block;vertical-align: middle; width: 100px;">Party Code if any</b><b>:</b></td>
                     </tr>
                 </table>
               </td>
            </tr>
            <tr>
               <td colspan="3">
                 <table width="100%" border="1" style="border-bottom:0px!important;border-collapse:collapse">
                     <tr>
                         <td width="33.3%"><b style="display: inline-block;vertical-align: middle; width: 107px;">Submission Type</b><b>:</b>@if($viewData['order']['submission_type']==1){{'Direct'}}@elseif($viewData['order']['submission_type']==2){{'Courier'}}@elseif($viewData['order']['submission_type']==3){{'Marketing Executive'}}@else {{ ''}}@endif
                        </td>
                         <td width="33.3%"><b style="display: inline-block;vertical-align: middle; width: 155px;">Actual Submission Type</b><b>:</b>{{!empty($viewData['order']['actual_submission_type']) ? $viewData['order']['actual_submission_type'] : '' }}</td>
                         <td width="33.3%"><b style="display: inline-block;vertical-align: middle; width: 100px;">Type of Client</b><b>:</b>{{!empty($viewData['order']['customerType']) ? $viewData['order']['customerType'] : '' }}</td>
                     </tr>
                 </table>
               </td>
            </tr>
            <tr>
               <td colspan="3">
                 <table width="100%" border="1" style="border-bottom:0px!important;border-collapse:collapse">
                     <tr>
                         <td width="50%"><b style="display: inline-block;vertical-align: middle; width: 85px;">Packing Mode</b><b>:</b>{{!empty($viewData['order']['packing_mode']) ? $viewData['order']['packing_mode'] : '' }}</td>
                         <td><b style="display: inline-block;vertical-align: middle; width: 60px;">Remarks</b><b>:</b>{{!empty($viewData['order']['remarks']) ? $viewData['order']['remarks'] : '' }}</td>
                     </tr>
                 </table>
               </td>
            </tr>
            <tr>
               <td colspan="3">
                 <table width="100%" border="1" style="border-bottom:0px!important;border-collapse:collapse">
                     <tr>
                        <td colspan="2"><b style="display: inline-block;vertical-align: middle; width: 60px;">Test Head</b><b>:</b>{{!empty($viewData['order']['product_name']) ? $viewData['order']['product_name'] : '' }}</td>
                     </tr>
                 </table>
               </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table width="100%" border="1" style="border-collapse:collapse">
                        <tr>
                            <th width="5%" align="center" height="30px">S.No.</th>
                            <th width="95%" valign="middle"><span>Parameter</span><span style="float:right;margin-top:5px!important;padding-right:5px!important;">Test TAT (in days) : {{ !empty($viewData['maxTatInDayNumber']) ? $viewData['maxTatInDayNumber'] : '0' }}</span></th>
                        </tr>
                        @if(!empty($viewData['orderParameters']))
                            <?php $break_counter = 1; $counter = 1; $page=1;?>
                            @foreach($viewData['orderParameters'] as  $categoryParameters)
                                @foreach($categoryParameters['categoryParams']  as $key=> $subCategoryParameters)
                                    <tr>	
                                        <td width="5%" align="center">{{$counter++}}</td>
                                        <td width="95%" align="left">
                                            <?php echo trim($subCategoryParameters['test_parameter_name']);?>
                                            @if($page == 1)			
                                                @if($break_counter == 34)
                                                    <div class="page-break"><?php $page++; $break_counter = 1; ?></div>
                                                @endif
                                            @else 
                                                @if($break_counter % 47 == 0)
                                                    <?php $break_counter = 1; ?>
                                                    <div class="page-break"></div>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    <?php $break_counter++;?>
                                @endforeach
                            @endforeach
                        @endif
                    </table>
                </td>
             </tr>
      </table>
  </div>
</body>
</html>
<?php //die;?>