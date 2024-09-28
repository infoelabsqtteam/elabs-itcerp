<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
    .page-break-always{ page-break-after: always;}
    .page-break-auto{ page-break-after:auto;}
    @page { margin: 60px 20px 90px 20px;font-size:13px;}	
    #header {left: 0;position: fixed;right: 0;text-align: center;top: -55px;width: 100%;height:auto;}
    #footer {left: 0;position: fixed;right: 0;bottom:0px;width: 100%;height:auto;}
    #content {bottom: 0;height:auto;left: 0;right: 0;width: 100%;}
    td table td {padding:2px!important;border-bottom: 0px!important;}
    p{padding:0!important;margin:0!important;}
    .sno{width:3%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .category{font-size:13px!important;padding:0 5px!important;font-weight:bold;overflow-wrap: break-word;word-wrap: break-word;}
    .parameter{width:34%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .methodName{width:15%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .equipmentName{width:15%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .requirementName{width:15%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .testResult{width:15%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .pdftable{table-layout:fixed;border-collapse: collapse;background: #fff;}
    .pdftable tr td {text-overflow:ellipsis;overflow:hidden;white-space:wrap;padding:2px 2px!important;vertical-align: middle;}
    .header-content table,footer-content table{border-collapse:collapse;}
    .left-content{border: 0px none ! important; vertical-align: top;}
    .middle-content{color:#4d64a1;border:0px !important;text-align:center!important;}
    .middle-content h3{font-family: times of romen; font-weight: 600; color: rgb(77, 100, 161); margin: 0px; font-size: 30px !important;}
    .right-content{border: 0px none ! important; color: #d7563a; vertical-align: top; padding: 5px; text-align: center ! important; font-size: 12px ! important;}
    .footer-content h3 {font-family: times of romen; font-weight: 600; color:#4d64a1;margin: 0px; font-size: 15px !important;padding: 0 !important;}
    .footer-content p,h5 {font-size:11px !important;margin:0 !important;padding: 0 !important;}
    .footer-content ul {margin: 0 !important;padding: 0 !important;}
    .footer-content ul li {font-size: 11px !important;margin-top: 0px !important;padding: 0 !important;}
</style>
<!--header-->
<div id="header">
    <table width="100%" style="margin:0 auto;border-collapse:collapse;">
	<tr>
	    <td width="10%" style="border: 0px none ! important; vertical-align: top;"><img width="65px" src="{{$rootDir.'/public/images/template_logo.png'}}"/></td>
	    <td width="80%" style="border:0px!important;text-align:center!important;">
		<h3 style="font-family: times of romen; color: rgb(77, 100, 161); padding: 0px; font-size: 20px!important; font-weight: 600; margin-top: 5px!important;">Interstellar Testing Centre Private Limited</h3>
		<h2 style="font-family: times of romen; color: rgb(77, 100, 161); padding: 0px; font-size: 14px!important; font-weight: 600; padding-top: -20px!important;">{{ !empty($viewData['trfHdr']['trf_product_category_name']) ? strtoupper($viewData['trfHdr']['trf_product_category_name']) :  ''}} : TRF SHEET</h2>
	    </td>
	</tr>
    </table>
</div>
<!--/header-->

<!--footer-->
<div id="footer">
    <table valign="bottom" width="100%" style="margin:0 auto;border-collapse:collapse;">
	<tr>
	    <td colspan="3">
		<table width="100%">
		    <tr>
			<td width="33.33%" align="left" valign="bottom"></td>
			<td width="33.33%" valign="bottom" align="center">
			    <script type='text/php'>
                            if(isset($pdf)){ 
                                $font = $fontMetrics->get_font('serif','bold');
                                $size = 11;
                                $y    = $pdf->get_height() - 21;
                                $x    = $pdf->get_width() - 305 - $fontMetrics->get_text_width('1/1', $font, $size);
                                $pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
                            }
                            </script>
			</td>
			<td width="33.33%" valign="bottom" align="right"></td>
		    </tr>
		</table>
	    </td>
	</tr>
    </table>
</div>
<!--/footer-->
    
<body>
    <!--content-->
    <div id="content">
	<div class="page-break-auto">
	
	    <table class="pdftable" width="100%" style="border-collapse:collapse;">
                <tr>
                    <td>
                        <table class="pdftable" border="1" width="100%" style="border-collapse:collapse;">	 
                            
                            <tr>
                                <td align="left" colspan="2" width="100%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Sample Name</b><b>:</b>{{!empty($viewData['trfHdr']['trf_sample_name']) ? trim($viewData['trfHdr']['trf_sample_name']) : ''}}</td>
                            </tr>
			    <tr>				
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">TRF No.</b><b>:</b>{{!empty($viewData['trfHdr']['trf_no']) ? trim($viewData['trfHdr']['trf_no']) : ''}}</td>
				<td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">TRF Date & Time</b><b>:</b>{{!empty($viewData['trfHdr']['trf_date']) ? date(DATETIMEFORMAT,strtotime($viewData['trfHdr']['trf_date'])) : ''}}</td>
			    </tr>
                            <tr>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Division</b><b>:</b>{{!empty($viewData['trfHdr']['trf_division_name']) ? trim($viewData['trfHdr']['trf_division_name']) : ''}}</td>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Department</b><b>:</b>{{!empty($viewData['trfHdr']['trf_product_category_name']) ? trim($viewData['trfHdr']['trf_product_category_name']) : ''}}</td>
                            </tr>
			    <tr>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Customer Name</b><b>:</b>{{!empty($viewData['trfHdr']['trf_customer_name']) ? trim($viewData['trfHdr']['trf_customer_name']) : ''}}</td>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Mfg. Lic. No.</b><b>:</b>{{!empty($viewData['trfHdr']['trf_mfg_lic_no']) ? trim($viewData['trfHdr']['trf_mfg_lic_no']) : ''}}</td>
                            </tr>
			    <tr>
                                
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Sample Qty.</b><b>:</b>{{!empty($viewData['trfHdr']['trf_batch_no']) ? trim($viewData['trfHdr']['trf_sample_qty']) : ''}}</td>
				<td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">TRF Status</b><b>:</b>{{!empty($viewData['trfHdr']['trf_status']) && $viewData['trfHdr']['trf_status'] == '0' ? 'Booked' : 'Pending'}}</td>
                            </tr>
			    <tr>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Batch No.</b><b>:</b>{{!empty($viewData['trfHdr']['trf_batch_no']) ? trim($viewData['trfHdr']['trf_batch_no']) : ''}}</td>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Batch Size</b><b>:</b>{{!empty($viewData['trfHdr']['trf_batch_size']) ? trim($viewData['trfHdr']['trf_batch_size']) : ''}}</td>
                            </tr>
			    <tr>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Manufactured Date</b><b>:</b>{{!empty($viewData['trfHdr']['trf_mfg_date']) ? trim($viewData['trfHdr']['trf_mfg_date']) : ''}}</td>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Expiry Date</b><b>:</b>{{!empty($viewData['trfHdr']['trf_expiry_date']) ? trim($viewData['trfHdr']['trf_expiry_date']) : ''}}</td>
                            </tr>
			    <tr>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Testing Standard</b><b>:</b>{{!empty($viewData['trfHdr']['trf_test_standard_name']) ? trim($viewData['trfHdr']['trf_test_standard_name']) : ''}}</td>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Product Category</b><b>:</b>{{!empty($viewData['trfHdr']['trf_p_category_name']) ? trim($viewData['trfHdr']['trf_p_category_name']) : ''}}</td>
                            </tr>
			    <tr>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Sub Product Category</b><b>:</b>{{!empty($viewData['trfHdr']['trf_sub_p_category_name']) ? trim($viewData['trfHdr']['trf_sub_p_category_name']) : ''}}</td>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Product Name</b><b>:</b>{{!empty($viewData['trfHdr']['trf_product_name']) ? trim($viewData['trfHdr']['trf_product_name']) : ''}}</td>
                            </tr>
			    <tr>
                                <td align="left" colspan="2" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Storage Condition</b><b>:</b>{{!empty($viewData['trfHdr']['trf_storage_condition_name']) ? trim($viewData['trfHdr']['trf_storage_condition_name']) : ''}}</td>
                            </tr>                            
                        </table>
                    </td>
                </tr>
            </table>
	    
	    <table class="pdftable" width="100%" border="1" style="border-collapse:collapse;margin-top:10px!important;">	    
                <tr>
                    <th class="sno" align="left" valign="middle" style="padding-top:3px!important;padding-bottom:3px!important;"><b style="font-size:14px!important;">S.No.</b></th>
		    <th class="parameter" align="left" valign="middle" style="padding-top:3px!important;padding-bottom:3px!important;"><b style="font-size:14px!important;">Test Parameters</b></th>
                </tr>
                @if(!empty($viewData['trfHdrDtl']))
                    @foreach($viewData['trfHdrDtl'] as $key => $values)
			<tr>
			    <td class="sno" align="left" valign="middle">{{$key + 1}}</td>
			    <td class="methodName" align="left" valign="middle"><?php echo trim($values['trf_test_parameter_name']);?></td>
			</tr>
                    @endforeach
                @endif                    
            </table>
	    
	</div>
    </div>
    <!--/content-->	
</body>
</html>