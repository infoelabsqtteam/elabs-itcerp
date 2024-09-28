@if(!empty($viewData))

    @if(array_key_exists('order', $viewData)) 
        @if(!empty($viewData['order']['product_category_id']))
            @if(!empty($reportWithEICFormat) && $reportWithEICFormat == '18')
                @include('sales.reports.generateReports.generateReportEIC')
            @elseif($viewData['order']['product_category_id'] == '1')
                @if(!empty($viewData['order']['has_order_pesticide_category_report']) && $viewData['order']['has_order_pesticide_category_report'] == '1')
                    @include('sales.reports.generateReports.generateReportFoodPesticidesWithoutPartWiseDisciplineWise')
                @elseif(!empty($withOutPartWiseReport) && $withOutPartWiseReport == '12')
                    @include('sales.reports.generateReports.generateReportFoodWithoutPartWise')
                @elseif(!empty($reportWithDisciplineGroup) && $reportWithDisciplineGroup == '15')
                    @include('sales.reports.generateReports.generateReportFoodWithoutPartWiseDisciplineWise')
                @else
                    @include('sales.reports.generateReports.generateReportFood')
                @endif
            @elseif($viewData['order']['product_category_id'] == '2')
                @if(!empty($viewData['order']['has_order_pesticide_category_report']) && $viewData['order']['has_order_pesticide_category_report'] == '1')
                    @include('sales.reports.generateReports.generateReportPharmaPesticidesWithoutPartWiseDisciplineWise')
                @elseif(!empty($reportWithoutForm39Format) && $reportWithoutForm39Format == '20')
                    @include('sales.reports.generateReports.generateReportPharmaWithoutForm39')
                @else
                    @include('sales.reports.generateReports.generateReportPharma')
                @endif
            @elseif($viewData['order']['product_category_id'] == '3')                                               
                @include('sales.reports.generateReports.generateReportWater')
            @elseif($viewData['order']['product_category_id'] == '4')
               @include('sales.reports.generateReports.generateReportHelmet')
            @elseif($viewData['order']['product_category_id'] == '5')
               @include('sales.reports.generateReports.generateReportAyurvedic')
            @elseif($viewData['order']['product_category_id'] == '6')
                @if(!empty($reportWithDisciplineGroup) && $reportWithDisciplineGroup == '15')
                    @include('sales.reports.generateReports.generateReportBuildingWithoutPartWiseDisciplineWise')
                @else
                    @include('sales.reports.generateReports.generateReportBuilding')
                @endif
            @elseif($viewData['order']['product_category_id'] == '7')
                @include('sales.reports.generateReports.generateReportTextile')
            @elseif($viewData['order']['product_category_id'] == '8')
                @if(!empty($withOutPartWiseReport) && $withOutPartWiseReport == '12')
                    @include('sales.reports.generateReports.generateReportEnvironmentWithoutPartWise')
                @elseif(!empty($reportWithDisciplineGroup) && $reportWithDisciplineGroup == '15')
                    @include('sales.reports.generateReports.generateReportEnvironmentWithoutPartWiseDisciplineWise')
                @elseif(!empty($viewData['orderParameterList']['generalWiseParameterList']))
                    @include('sales.reports.generateReports.generateReportEnvironmentWater')
                @else
                    @include('sales.reports.generateReports.generateReportEnvironment')
                @endif
            @elseif($viewData['order']['product_category_id'] == '405')
                @if(!empty($withOutPartWiseReport) && $withOutPartWiseReport == '12')
                    @include('sales.reports.generateReports.generateReportCosmeticWithForm50Format')
                @elseif(!empty($reportWithOutForm50Format) && $reportWithOutForm50Format == '14')
                    @include('sales.reports.generateReports.generateReportCosmetic')
                @elseif(!empty($reportCosmeticWithForm39Format) && $reportCosmeticWithForm39Format == '19')
                    @include('sales.reports.generateReports.generateReportCosmeticWithForm39Format')
                @elseif(!empty($reportCosmeticWithForm39Format) && $reportCosmeticWithForm39Format == '21')
                    @include('sales.reports.generateReports.generateReportCosmeticForm39NewFormat')
                @else
                    @include('sales.reports.generateReports.generateReportCosmeticForm39NewFormat')
                @endif
            @else
                @if(!empty($withOutPartWiseReport) && $withOutPartWiseReport == '12')
                    @include('sales.reports.generateReports.generateReportDefaultWithoutPartwise')
                @elseif(!empty($reportWithDisciplineGroup) && $reportWithDisciplineGroup == '15')
                    @include('sales.reports.generateReports.generateReportDefaultWithoutPartWiseDisciplineWise')
                @else
                    @include('sales.reports.generateReports.generateReportDefault')
                @endif
            @endif
        @endif
    @else
        <?php $totalRows = count($viewData);?>
        @foreach($viewData as $key => $viewData)
            @if(!empty($viewData['order']['product_category_id']))
                @if(!empty($reportWithEICFormat) && $reportWithEICFormat == '18')
                    @include('sales.reports.generateReports.generateReportEIC')
                @elseif($viewData['order']['product_category_id'] == '1')
                    @if(!empty($viewData['order']['has_order_pesticide_category_report']) && $viewData['order']['has_order_pesticide_category_report'] == '1')
                        @include('sales.reports.generateReports.generateReportFoodPesticidesWithoutPartWiseDisciplineWise')
                    @elseif(!empty($withOutPartWiseReport) && $withOutPartWiseReport == '12')
                        @include('sales.reports.generateReports.generateReportFoodWithoutPartWise')
                    @elseif(!empty($reportWithDisciplineGroup) && $reportWithDisciplineGroup == '15')
                        @include('sales.reports.generateReports.generateReportFoodWithoutPartWiseDisciplineWise')
                    @else
                        @include('sales.reports.generateReports.generateReportFood')
                    @endif
                @elseif($viewData['order']['product_category_id'] == '2')
                   @include('sales.reports.generateReports.generateReportPharma')
                @elseif($viewData['order']['product_category_id'] == '3')
                    @include('sales.reports.generateReports.generateReportWater')
                @elseif($viewData['order']['product_category_id'] == '4')
                   @include('sales.reports.generateReports.generateReportHelmet')
                @elseif($viewData['order']['product_category_id'] == '5')
                   @include('sales.reports.generateReports.generateReportAyurvedic')
                @elseif($viewData['order']['product_category_id'] == '6')
                    @if(!empty($reportWithDisciplineGroup) && $reportWithDisciplineGroup == '15')
                        @include('sales.reports.generateReports.generateReportBuildingWithoutPartWiseDisciplineWise')
                    @else
                        @include('sales.reports.generateReports.generateReportBuilding')
                    @endif
                @elseif($viewData['order']['product_category_id'] == '7')
                   @include('sales.reports.generateReports.generateReportTextile')
                @elseif($viewData['order']['product_category_id'] == '8')
                    @if(!empty($withOutPartWiseReport) && $withOutPartWiseReport == '12')
                        @include('sales.reports.generateReports.generateReportEnvironmentWithoutPartWise')
                    @elseif(!empty($reportWithDisciplineGroup) && $reportWithDisciplineGroup == '15')
                        @include('sales.reports.generateReports.generateReportEnvironmentWithoutPartWiseDisciplineWise')
                    @elseif(!empty($viewData['orderParameterList']['generalWiseParameterList']))
                        @include('sales.reports.generateReports.generateReportEnvironmentWater')
                    @else
                        @include('sales.reports.generateReports.generateReportEnvironment')
                    @endif
                @elseif($viewData['order']['product_category_id'] == '405')
                    @if(!empty($withOutPartWiseReport) && $withOutPartWiseReport == '12')
                        @include('sales.reports.generateReports.generateReportCosmeticWithForm50Format')
                    @elseif(!empty($reportWithOutForm50Format) && $reportWithOutForm50Format == '14')
                        @include('sales.reports.generateReports.generateReportCosmetic')
                    @elseif(!empty($reportCosmeticWithForm39Format) && $reportCosmeticWithForm39Format == '19')
                        @include('sales.reports.generateReports.generateReportCosmeticWithForm39Format')
                    @elseif(!empty($reportCosmeticWithForm39Format) && $reportCosmeticWithForm39Format == '21')
                        @include('sales.reports.generateReports.generateReportCosmeticForm39NewFormat')
                    @else
                        @include('sales.reports.generateReports.generateReportCosmeticForm39NewFormat')
                    @endif
                @else
                    @if(!empty($withOutPartWiseReport) && $withOutPartWiseReport == '12')
                        @include('sales.reports.generateReports.generateReportDefaultWithoutPartwise')
                    @elseif(!empty($reportWithDisciplineGroup) && $reportWithDisciplineGroup == '15')
                        @include('sales.reports.generateReports.generateReportDefaultWithoutPartWiseDisciplineWise')
                    @else
                        @include('sales.reports.generateReports.generateReportDefault')
                    @endif
                @endif
            @endif            
            @if($key+1 < $totalRows)
                <div style="page-break-after: always;"></div>
            @endif            
        @endforeach
    @endif
@endif