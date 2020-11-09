<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client as ClientModel;
use App\Mail\DecisionEmail;
use phpDocumentor\Reflection\Types\String_;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Client extends Controller
{

    public function __construct()
    {
        //init function
    }

    public function addRecord(Request $request){

        $company_sector = (isset($request['company_sector']) && $request['company_sector']) ? $request['company_sector'] : '';
        $contact_email = (isset($request['contact_email']) && $request['contact_email']) ? $request['contact_email'] : '';
        $revenue = (isset($request['revenue']) && $request['revenue']) ? $this->cleanAmount($request['revenue']) : '';
        $yrs_of_growth = (isset($request['yrs_of_growth']) && $request['yrs_of_growth']) ? $request['yrs_of_growth'] : '';
        $avg_ebitda_last_three_yrs = (isset($request['avg_ebitda_last_three_yrs']) && $request['avg_ebitda_last_three_yrs']) ? $this->cleanAmount($request['avg_ebitda_last_three_yrs']) : '';
        $avg_net_last_three_yrs = (isset($request['avg_net_last_three_yrs']) && $request['avg_net_last_three_yrs']) ? $this->cleanAmount($request['avg_net_last_three_yrs']) : '';
        $yrs_with_positive_net_result = (isset($request['yrs_with_positive_net_result']) && $request['yrs_with_positive_net_result']) ? $request['yrs_with_positive_net_result'] : '';
        $net_debt = (isset($request['net_debt']) && $request['net_debt']) ? $this->cleanAmount($request['net_debt']) : '';
        $fixed_assets = (isset($request['fixed_assets']) && $request['fixed_assets']) ? $this->cleanAmount($request['fixed_assets']) : '';
        $biggest_shareholder = (isset($request['biggest_shareholder']) && $request['biggest_shareholder']) ? $this->cleanAmount($request['biggest_shareholder']) : '';
        $revenue_from_biggest_client = (isset($request['revenue_from_biggest_client']) && $request['revenue_from_biggest_client']) ? $this->cleanAmount($request['revenue_from_biggest_client']) : '';
        $is_the_company_audited = (isset($request['is_the_company_audited']) && $request['is_the_company_audited']) ? $request['is_the_company_audited'] : '';
        $m_and_a_in_last_5_yrs = (isset($request['m_and_a_in_last_5_yrs']) && $request['m_and_a_in_last_5_yrs']) ? $request['m_and_a_in_last_5_yrs'] : '';
        $selling_90_percent = (isset($request['selling_90_percent']) && $request['selling_90_percent']) ? $request['selling_90_percent'] : '';

        $revenue_score = 0;
        $yrs_of_growth_score = 0;
        $avg_ebitda_last_three_yrs_score = 0;
        $avg_net_last_three_yrs_score = 0;
        $yrs_with_positive_net_result_score = 0;
        $deuda_ebitda_score = 0;
        $assset_to_revenue_score = 0;
        $biggest_shareholder_score = 0;
        $revenue_from_biggest_client_score = 0;
        $is_the_company_audited_score = 0;
        $m_and_a_in_last_5_yrs_score = 0;
        $selling_90_percent_score = 0;
        $ebitda_rev_score = 0;
        $net_margin_score = 0;

        if($revenue >= 1500000 && $revenue <= 10000000){
            $revenue_score = $revenue_score + 1;
        }

        if($yrs_of_growth >= 3){
            $yrs_of_growth_score = $yrs_of_growth_score + 1;
        }

        if($avg_ebitda_last_three_yrs >= 150000){
            $avg_ebitda_last_three_yrs_score = $avg_ebitda_last_three_yrs_score + 1;
        }

        if($avg_net_last_three_yrs >= 70000){
            $avg_net_last_three_yrs_score = $avg_net_last_three_yrs_score + 1;
        }

        if($yrs_with_positive_net_result >= 3){
            $yrs_with_positive_net_result_score = $yrs_with_positive_net_result_score + 1;
        }

        $deuda_ebitda = ($net_debt / $avg_ebitda_last_three_yrs);
        $deuda_ebitda_score = 0;
        if($deuda_ebitda <= 2){
            $deuda_ebitda_score = $deuda_ebitda_score + 1;

        }else{
            if($deuda_ebitda > 3){
                $deuda_ebitda_score = -100;
            }
        }

        $assset_to_revenue = ($fixed_assets / $revenue);
        if($assset_to_revenue <= 1.5){
            $assset_to_revenue_score = $assset_to_revenue_score + 1;
        }

        if($biggest_shareholder >= 65){
            $biggest_shareholder_score = $biggest_shareholder_score + 1;
        }

        if($revenue_from_biggest_client <= 40){
            $revenue_from_biggest_client_score = $revenue_from_biggest_client_score + 1;
        }

        if($is_the_company_audited == "Yes"){
            $is_the_company_audited_score = $is_the_company_audited_score + 1;
        }

        if($m_and_a_in_last_5_yrs == "No"){
            $m_and_a_in_last_5_yrs_score = $m_and_a_in_last_5_yrs_score + 1;
        }

        if($selling_90_percent == "Yes"){
            $selling_90_percent_score = $selling_90_percent_score + 1;
        }

        $ebitda_rev = ($revenue / $avg_ebitda_last_three_yrs);
        if($ebitda_rev >= 7){
            $ebitda_rev_score = $ebitda_rev_score + 1;
        }

        $net_margin = ($avg_net_last_three_yrs / $revenue);
        $net_margin = round((float)$net_margin * 100 );
        if($net_margin >= 5){
            $net_margin_score = $net_margin_score + 1;
        }

        $deuda_ebitda = ($net_debt / $avg_ebitda_last_three_yrs);
        $asset_to_revenue_ratio = ($fixed_assets / $revenue);

        $score = $revenue_score + $yrs_of_growth_score + $avg_ebitda_last_three_yrs_score + $avg_net_last_three_yrs_score + $yrs_with_positive_net_result_score +
            $deuda_ebitda_score + $assset_to_revenue_score + $biggest_shareholder_score + $revenue_from_biggest_client_score + $is_the_company_audited_score +
            $m_and_a_in_last_5_yrs_score + $selling_90_percent_score + $ebitda_rev_score + $net_margin_score;

        $SQLData = [
            'company_sector'                  => $company_sector,
            'contact_email'                   => $contact_email,
            'revenue'                         => $revenue,
            'years_of_growth'                 => $yrs_of_growth,
            'avg_ebitda_last_three_yrs'       => $avg_ebitda_last_three_yrs,
            'avg_net_last_three_yrs'          => $avg_net_last_three_yrs,
            'yrs_with_positive_net_result'    => $yrs_with_positive_net_result,
            'net_debt'                        => $net_debt,
            'fixed_assets'                    => $fixed_assets,
            'biggest_shareholder'             => $biggest_shareholder,
            'revenue_from_biggest_client'     => $revenue_from_biggest_client,
            'is_the_company_audited'          => $is_the_company_audited,
            'm_and_a_in_last_5_yrs'           => $m_and_a_in_last_5_yrs,
            'selling_90_percent'              => $selling_90_percent,
            'ebitda_rev'                      => $ebitda_rev,
            'net_margin'                      => $net_margin,
            'deuda_ebitda'                    => $deuda_ebitda,
            'asset_to_revenue_ratio'          => $asset_to_revenue_ratio,
            'total_score'                     => $score
        ];

        if($score >= 10){
            $SQLData['decision'] = 'Go';
        }else{
            $SQLData['decision'] = 'No-Go';
        }

        /*save attachment*/
        $this->attachment($SQLData);

        /*send email*/
        $this->sendEmail('luis@ibventur.es','','');

        $result = ClientModel::insert($SQLData);
        if($result){

            $returnData = [
                "error"             =>  false,
                "decision_status"   =>  $SQLData['decision'],
                "message"           =>  'success'
            ];

            return json_encode($returnData);

        }else{

            $returnData = [
                "error"             =>  true,
                "message"           =>  'Error saving data to database'
            ];

            return json_encode($returnData);
        }
    }

    public function cleanAmount(string $data=null, array $filterData=['$',',','%']){

        if($filterData){
            foreach ($filterData as $row){
                $data = str_replace($row, "", $data);
            }
        }

        return (int) $data;
    }

    public function sendEmail($to=null, $title=null, $body=null){
        $details = [
            'title' => $title,
            'body' => $body
        ];

        \Mail::to($to)->send(new DecisionEmail($details));
        return true;
    }

    public function attachment($data){
        File::delete(public_path() . '/Typeform-validation-exercise.xlsx');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Field as shown on the website');
        $sheet->setCellValue('B1', 'Field');
        $sheet->setCellValue('C1', 'Value');
        $sheet->setCellValue('D1', 'Result');
        $sheet->setCellValue('F2', 'Total score'); //score
        $sheet->setCellValue('G2', '=SUM(D2:D1009)'); //total score
        $sheet->setCellValue('F4', 'Decision');
        $sheet->setCellValue('G4', '=IF(G2>=10,"GO","NO-GO")'); //decision

        $sheet->setCellValue('A2', 'Facturación media de los últimos 3 año (en €):');
        $sheet->setCellValue('B2', 'Revenue');
        $sheet->setCellValue('C2', $data['revenue']); //Revenue value
        $sheet->setCellValue('D2', '=IF(AND(C2>=1500000,C2<=10000000),1,0)');

        $sheet->setCellValue('A3', 'Años consecutivos creciendo ingreso:');
        $sheet->setCellValue('B3', 'Years of growth');
        $sheet->setCellValue('C3', $data['years_of_growth']); //value
        $sheet->setCellValue('D3', '=IF(C3>=3,1,0)');

        $sheet->setCellValue('A4', 'EBITDA media de los últimos 3 años (en €):');
        $sheet->setCellValue('B4', 'Avg. EBITDA last 3 years');
        $sheet->setCellValue('C4', $data['avg_ebitda_last_three_yrs']); //value
        $sheet->setCellValue('D4', '=IF(C4>=150000,1,-100)');

        $sheet->setCellValue('A5', 'Resultado neto medio de los últimos 3 años (en €):');
        $sheet->setCellValue('B5', 'Avg. net result last 3 years');
        $sheet->setCellValue('C5', $data['avg_net_last_three_yrs']); //value
        $sheet->setCellValue('D5', '=IF(C5>=70000,1,0)');

        $sheet->setCellValue('A6', 'Años consecutivos con resultado positivo:');
        $sheet->setCellValue('B6', 'Years with positive net results');
        $sheet->setCellValue('C6', $data['yrs_with_positive_net_result']); //value
        $sheet->setCellValue('D6', '=IF(C6>=3,1,0)');

        $sheet->setCellValue('A7', 'Deuda financiera neta total (en €):');
        $sheet->setCellValue('B7', 'Net debt');
        $sheet->setCellValue('C7', $data['net_debt']); //value
        $sheet->setCellValue('D7', '=IF(C17<=2,1,IF(C17>3,-100,0))');

        $sheet->setCellValue('A8', 'Total activo inmovilizado (en €):');
        $sheet->setCellValue('B8', 'Fixed assets');
        $sheet->setCellValue('C8', $data['fixed_assets']); //value
        $sheet->setCellValue('D8', '=IF(C18<=1.5,1,0)');

        $sheet->setCellValue('A9', '¿Porcentaje de la empresa del mayor accionista?:');
        $sheet->setCellValue('B9', '% biggest shareholder');
        $sheet->setCellValue('C9', $data['biggest_shareholder']); //value
        $sheet->setCellValue('D9', '=IF(C9>=65%,1,0)');

        $sheet->setCellValue('A10', 'Porcentaje de facturación que viene del mayor cliente:	');
        $sheet->setCellValue('B10', '% revenue from biggest client');
        $sheet->setCellValue('C10', $data['revenue_from_biggest_client']); //value
        $sheet->setCellValue('D10', '=IF(C10<=40%,1,0)');

        $sheet->setCellValue('A11', '¿Ha sido auditada la compañía alguna vez?:');
        $sheet->setCellValue('B11', 'Is the company audited? (yes/ no)');
        $sheet->setCellValue('C11', strtolower($data['is_the_company_audited'])); //value
        $sheet->setCellValue('D11', '=IF(C11="yes",1,0)');

        $sheet->setCellValue('A12', '¿Operaciones de compra o fusiones en los últimos 5 años?');
        $sheet->setCellValue('B12', 'm&a in the last 5 years? (yes/ no)');
        $sheet->setCellValue('C12', strtolower($data['m_and_a_in_last_5_yrs'])); //value
        $sheet->setCellValue('D12', '=IF(C12="no",1,0)');

        $sheet->setCellValue('A13', '¿Se quiere vender más del 90% de la compañía?');
        $sheet->setCellValue('B13', 'Selling 90%? (yes/ no)');
        $sheet->setCellValue('C13', strtolower($data['selling_90_percent'])); //value
        $sheet->setCellValue('D13', '=IF(C13="yes",1,-100)');

        $sheet->setCellValue('B15', 'EBITDA/Rev');
        $sheet->setCellValue('C15', '=IF(C2=0,C2,C4/C2)');
        $sheet->setCellValue('D15', '=IF(C15>=7%,1,0)');

        $sheet->setCellValue('B16', 'Net margin');
        $sheet->setCellValue('C16', '=IF(C2=0,C2,C5/C2)');
        $sheet->setCellValue('D16', '=IF(C16>=5%,1,0)');

        $sheet->setCellValue('B17', 'Deuda/EBITDA');
        $sheet->setCellValue('C17', '=IF(C4=0,C4,C7/C4)');

        $sheet->setCellValue('B18', 'Asset to revenue ratio');
        $sheet->setCellValue('C18', '=IF(C2=0,C2,C8/C2)');

        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path() . '/Typeform-validation-exercise.xlsx');
    }
}
