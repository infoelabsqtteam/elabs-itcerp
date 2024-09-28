<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	
	<title>Editable Invoice</title>
	<style>
	/*
	 CSS-Tricks Example
	 by Chris Coyier
	 http://css-tricks.com
*/

* { margin: 0; padding: 0; }
body { font: 14px/1.4 Georgia, serif; }
#page-wrap { width: 800px; margin: 0 auto; }

textarea { border: 0; font: 14px Georgia, Serif; overflow: hidden; resize: none; }
table { border-collapse: collapse; }
table td, table th { border: 1px solid black; padding: 5px; }

#header { height: 15px; width: 100%; margin: 20px 0; background: #222; text-align: center; color: white; font: bold 15px Helvetica, Sans-Serif; text-decoration: uppercase; letter-spacing: 20px; padding: 8px 0px; }

#address { width: 250px; height: 150px; float: left; }
#customer { overflow: hidden; }

#logo { text-align: right; float: right; position: relative; margin-top: 25px; border: 1px solid #fff; max-width: 540px; max-height: 100px; overflow: hidden; }
#logo:hover, #logo.edit { border: 1px solid #000; margin-top: 0px; max-height: 125px; }
#logoctr { display: none; }
#logo:hover #logoctr, #logo.edit #logoctr { display: block; text-align: right; line-height: 25px; background: #eee; padding: 0 5px; }
#logohelp { text-align: left; display: none; font-style: italic; padding: 10px 5px;}
#logohelp input { margin-bottom: 5px; }
.edit #logohelp { display: block; }
.edit #save-logo, .edit #cancel-logo { display: inline; }
.edit #image, #save-logo, #cancel-logo, .edit #change-logo, .edit #delete-logo { display: none; }
#customer-title { font-size: 20px; font-weight: bold; float: left; }

#meta { margin-top: 1px; width: 300px; float: right; }
#meta td { text-align: right;  }
#meta td.meta-head { text-align: left; background: #eee; }
#meta td textarea { width: 100%; height: 20px; text-align: right; }

#items { clear: both; width: 100%; margin: 30px 0 0 0; border: 1px solid black; }
#items th { background: #eee; }
#items textarea { width: 80px; height: 50px; }
#items tr.item-row td { border: 0; vertical-align: top; }
#items td.description { width: 300px; }
#items td.item-name { width: 175px; }
#items td.description textarea, #items td.item-name textarea { width: 100%; }
#items td.total-line { border-right: 0; text-align: right; }
#items td.total-value { border-left: 0; padding: 10px; }
#items td.total-value textarea { height: 20px; background: none; }
#items td.balance { background: #eee; }
#items td.blank { border: 0; }

#terms { text-align: center; margin: 20px 0 0 0; }
#terms h5 { text-transform: uppercase; font: 13px Helvetica, Sans-Serif; letter-spacing: 10px; border-bottom: 1px solid black; padding: 0 0 8px 0; margin: 0 0 8px 0; }
#terms textarea { width: 100%; text-align: center;}

textarea:hover, textarea:focus, #items td.total-value textarea:hover, #items td.total-value textarea:focus, .delete:hover { background-color:#EEFF88; }

.delete-wpr { position: relative; }
.delete { display: block; color: #000; text-decoration: none; position: absolute; background: #EEEEEE; font-weight: bold; padding: 0px 3px; border: 1px solid; top: -6px; left: -22px; font-family: Verdana; font-size: 12px; }

tr.hiderow {
    text-align: center;
}
.main td {
    width: 50%;
}
#items td b {
    width: 50%;
    float: left;
}
#items span {
    float: left;
    margin-right: 12px;
}
#items p {
    float: left;
}
#mylogo img {
    float: left;width:100%;
}
.cpi-titulo3 {
    font-size: 28px;    color: #276da9;display:block;text-align:center;
}
.cpi-titulo2 {
    font-size: 14px;    color: #276da9;display:block;text-align:center;
}
div#identity {
    display: flex;
    align-items: center;
}
div#mylogo {
    float: left;
    width: 18%;
}
#addrs{float: left;
    width: 70%;}
	#hiderow,
.delete {
  display: none;
}
</style>
</head>

<body>

	<div id="page-wrap">
		<div id="identity">
		

            <div id="mylogo">
              <img src="http://itclab.drishinfo.com/itcerp/public/images/template_logo.png" alt="logo" />
            </div>
			
            <div id="addrs">
				 <b class="cpi-titulo3">Interstellar Testing Centre Pvt. Ltd.</b>
				  <b class="cpi-titulo2">86, Industrial Area, Phase-1, Panchkula-134109 ( Haryana )</b>
				  <b class="cpi-titulo2">Phone : ( O ) 0172-2561543, 2565825</b>
				   <b class="cpi-titulo2">Visit us : www.itclabs.com  Email : customersupport@itclabs.com</b>
			</div>
		</div>
		
		<div style="clear:both"></div>
		
		<div id="customer">

            

           <table id="items">
		
		  <tr>
		      <td><b>Booking Date & Time</b><span>:</span><p>15-jul-2017 10:49:25</p></td>
		      <td><b>Booking Date & Time</b><span>:</span><p>15-jul-2017 10:49:25</p></td>
		  </tr>
		  <tr>
		      <td><b>Expected Due Date & Time</b><span>:</span><p>15-jul-2017 10:49:25</p></td>
		      <td><b>Booking Date & Time</b><span>:</span><p>15-jul-2017 10:49:25</p></td>
		  </tr>
		  <tr>
		      <td><b>Quotation No.</b><span>:</span><p></p></td>
		      <td><b>Booking </b><span>:</span><p>15-jul-2017</p></td>
		  </tr>
		  
		  <tr class="top_div">
		      <td>Parameter</td>
		      <td>0</td>
		  </tr>
		
		</div>
		
		<table id="items" style="margin-top:0">
		
		  <tr>
		      <th>S.No.</th>
		      <th>Parameter</th>
		      <th>Test Tat</th>
		  </tr>
		  
		  <tr class="hiderow">
		       <td>1</td>
		      <td>Parameter</td>
		      <td>0</td>
		  </tr>
		  
		  <tr class="hiderow">
		       <td>1</td>
		      <td>Parameter</td>
		      <td>0</td>
		  </tr>
		  
		  <tr class="hiderow">
		    <td>1</td>
		      <td>Parameter</td>
		      <td>0</td>
		  </tr>
		 <tr class="hiderow">
		       <td>1</td>
		      <td>Parameter</td>
		      <td>0</td>
		  </tr>
		  
		  <tr class="hiderow">
		       <td>1</td>
		      <td>Parameter</td>
		      <td>0</td>
		  </tr>
		  
		  <tr class="hiderow">
		    <td>1</td>
		      <td>Parameter</td>
		      <td>0</td>
		  </tr><tr class="hiderow">
		       <td>1</td>
		      <td>Parameter</td>
		      <td>0</td>
		  </tr>
		  
		  <tr class="hiderow">
		       <td>1</td>
		      <td>Parameter</td>
		      <td>0</td>
		  </tr>
		  
		  <tr class="hiderow">
		    <td>1</td>
		      <td>Parameter</td>
		      <td>0</td>
		  </tr>
		  <tr class="hiderow">
		       <td>1</td>
		      <td>Parameter</td>
		      <td>0</td>
		  </tr>
		  
		  <tr class="hiderow">
		       <td>1</td>
		      <td>Parameter</td>
		      <td>0</td>
		  </tr>
		  
		  <tr class="hiderow">
		    <td>1</td>
		      <td>Parameter</td>
		      <td>0</td>
		  </tr><tr class="hiderow">
		       <td>1</td>
		      <td>Parameter</td>
		      <td>0</td>
		  </tr>
		  
		  <tr class="hiderow">
		       <td>1</td>
		      <td>Parameter</td>
		      <td>0</td>
		  </tr>
		  
		  <tr class="hiderow">
		    <td>1</td>
		      <td>Parameter</td>
		      <td>0</td>
		  </tr>
		
		</table>
		
		<div id="terms">
		  <h5>Terms</h5>
		  <textarea>NET 30 Days. Finance Charge of 1.5% will be made on unpaid balances after 30 days.</textarea>
		</div>
	
	</div>
	
</body>

</html>
<?php //die;?>