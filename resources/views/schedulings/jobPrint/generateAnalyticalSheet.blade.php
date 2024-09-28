@if(!empty($containAnalyticalOrCalculation))
    @if($containAnalyticalOrCalculation == '1')
        @include('schedulings.jobPrint.generateJobSheetPrint')
    @elseif(!empty($viewData['orderEquipments']))
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <style>
            *{margin:0;padding:0;font-family:sans-serif;font-size:14px;}
            @page{padding:0px 20px;}
            .page-break {page-break-before: always;}
            .page:after { content: counter(page); }
            .cntr_hdng h5,.cntr_hdng p{font-size: 13px;margin: 3px;font-weight: 700;color: #4d64a1;}
            .side_cntnt{width:35%;border:0px!important;font-size:12px!important;color:#4d64a1;}
            .side_cntnt p{margin:0;}
            </style>
        </head>
        <body>
            @if(!empty($viewData['orderEquipments']) && in_array('chemically',$viewData['orderEquipments']))
                @include('schedulings.jobPrint.analyticalCalculationSheet.chemically.index')
            @endif
            
            @if(!empty($viewData['orderEquipments']) && in_array('hplc',$viewData['orderEquipments']))
                
                @if(in_array('chemically',$viewData['orderEquipments']))
                <div class="page-break"></div>
                @endif
                
                @include('schedulings.jobPrint.analyticalCalculationSheet.hplc.index')
            @endif
            
            @if(!empty($viewData['orderEquipments']) && (in_array('chromatography',$viewData['orderEquipments']) || in_array('ion chromatography',$viewData['orderEquipments'])))
                
                @if(in_array('chemically',$viewData['orderEquipments']) || in_array('hplc',$viewData['orderEquipments']))
                <div class="page-break"></div>
                @endif
                
                @include('schedulings.jobPrint.analyticalCalculationSheet.chromatography.index')
            @endif
            
            @if(!empty($viewData['orderEquipments']) && in_array('microbiological',$viewData['orderEquipments']))
                
                @if(in_array('chemically',$viewData['orderEquipments']) || in_array('hplc',$viewData['orderEquipments']) || in_array('chromatography',$viewData['orderEquipments']) || in_array('ion chromatography',$viewData['orderEquipments']))
                <div class="page-break"></div>
                @endif
                
                @include('schedulings.jobPrint.analyticalCalculationSheet.microbiological.index')
            @endif
            
        </body>
        </html>
    @endif
@endif

<?php //die;?>