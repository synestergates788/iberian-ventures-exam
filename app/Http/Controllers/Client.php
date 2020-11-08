<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client as ClientModel;
use App\Mail\DecisionEmail;
use phpDocumentor\Reflection\Types\String_;
use Illuminate\Support\Facades\File;

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

        $attachmentData = [
            'company_sector'                  => $company_sector,
            'contact_email'                   => $contact_email,
            'revenue'                         => $revenue,
            'revenue_score'                         => $revenue_score,
            'years_of_growth'                 => $yrs_of_growth,
            'yrs_of_growth_score'                 => $yrs_of_growth_score ,
            'avg_ebitda_last_three_yrs'       => $avg_ebitda_last_three_yrs,
            'avg_ebitda_last_three_yrs_score'       => $avg_ebitda_last_three_yrs_score ,
            'avg_net_last_three_yrs'          => $avg_net_last_three_yrs,
            'avg_net_last_three_yrs_score'          => $avg_net_last_three_yrs_score ,
            'yrs_with_positive_net_result'    => $yrs_with_positive_net_result,
            'yrs_with_positive_net_result_score'    => $yrs_with_positive_net_result_score ,
            'net_debt'                        => $net_debt,
            'deuda_ebitda_score'                        => $deuda_ebitda_score ,
            'fixed_assets'                    => $fixed_assets,
            'assset_to_revenue_score'                    => $assset_to_revenue_score ,
            'biggest_shareholder'             => $biggest_shareholder,
            'biggest_shareholder_score'             => $biggest_shareholder_score ,
            'revenue_from_biggest_client'     => $revenue_from_biggest_client,
            'revenue_from_biggest_client_score'     => $revenue_from_biggest_client_score ,
            'is_the_company_audited'          => $is_the_company_audited,
            'is_the_company_audited_score'          => $is_the_company_audited_score ,
            'm_and_a_in_last_5_yrs'           => $m_and_a_in_last_5_yrs,
            'm_and_a_in_last_5_yrs_score'           => $m_and_a_in_last_5_yrs_score ,
            'selling_90_percent'              => $selling_90_percent,
            'selling_90_percent_score'              => $selling_90_percent_score ,
            'ebitda_rev'                      => $ebitda_rev,
            'ebitda_rev_score'                      => $ebitda_rev_score ,
            'net_margin'                      => $net_margin,
            'net_margin_score'                      => $net_margin_score ,
            'deuda_ebitda'                    => $deuda_ebitda,
            'asset_to_revenue_ratio'          => $asset_to_revenue_ratio,
            'total_score'                     => $score
        ];

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
            $attachmentData['decision'] = 'Go';
            $SQLData['decision'] = 'Go';
        }else{
            $attachmentData['decision'] = 'No-Go';
            $SQLData['decision'] = 'No-Go';
        }

        /*save attachment*/
        $this->attachment($attachmentData);

        /*send email*/
        $this->sendEmail('melquecedec.catangcatang@outlook.com','','');

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
        File::delete(public_path() . '/Typeform-validation-exercise.csv');

        $content = 'Field as shown on the website,Field,Value,Result,,,,';
        $content .= "\n";
        $content .= 'Facturación media de los últimos 3 año (en €):	,Revenue,'.$data['revenue'].','.$data['revenue_score'].',,Total score,'.$data['total_score'].',';
        $content .= "\n";
        $content .= 'Años consecutivos creciendo ingreso: ,Years of growth,'.$data['years_of_growth'].','.$data['yrs_of_growth_score'].',,,,';
        $content .= "\n";
        $content .= 'EBITDA media de los últimos 3 años (en €): ,Avg. EBITDA last 3 years,'.$data['avg_ebitda_last_three_yrs'].','.$data['avg_ebitda_last_three_yrs_score'].',,Decision,'.$data['decision'].',';
        $content .= "\n";
        $content .= 'Resultado neto medio de los últimos 3 años (en €): ,Avg. net result last 3 years,'.$data['avg_net_last_three_yrs'].','.$data['avg_net_last_three_yrs_score'].'';
        $content .= "\n";

        $content .= 'Años consecutivos con resultado positivo: ,Years with positive net results,'.$data['yrs_with_positive_net_result'].','.$data['yrs_with_positive_net_result_score'].'';
        $content .= "\n";
        $content .= 'Deuda financiera neta total (en €): ,Net debt,'.$data['net_debt'].','.$data['deuda_ebitda_score'].'';
        $content .= "\n";
        $content .= 'Total activo inmovilizado (en €): ,Fixed assets,'.$data['fixed_assets'].','.$data['assset_to_revenue_score'].'';
        $content .= "\n";
        $content .= '¿Porcentaje de la empresa del mayor accionista?: ,% biggest shareholder,'.$data['biggest_shareholder'].','.$data['biggest_shareholder_score'].'';
        $content .= "\n";
        $content .= 'Porcentaje de facturación que viene del mayor cliente: ,% revenue from biggest client,'.$data['revenue_from_biggest_client'].','.$data['revenue_from_biggest_client_score'].'';
        $content .= "\n";
        $content .= '¿Ha sido auditada la compañía alguna vez?: ,Is the company audited? (yes/ no),'.$data['is_the_company_audited'].','.$data['is_the_company_audited_score'].'';
        $content .= "\n";
        $content .= '¿Operaciones de compra o fusiones en los últimos 5 años? ,m&a in the last 5 years? (yes/ no),'.$data['m_and_a_in_last_5_yrs'].','.$data['m_and_a_in_last_5_yrs_score'].'';
        $content .= "\n";
        $content .= '¿Se quiere vender más del 90% de la compañía? ,Selling 90%? (yes/ no),'.$data['selling_90_percent'].','.$data['selling_90_percent_score'].'';

        $content .= "\n";
        $content .= "\n";
        $content .= ',EBITDA/Rev,'.$data['ebitda_rev'].','.$data['ebitda_rev_score'].'';
        $content .= "\n";
        $content .= ',Net margin,'.$data['net_margin'].','.$data['net_margin_score'].'';
        $content .= "\n";
        $content .= ',Deuda/EBITDA,'.$data['deuda_ebitda'].',';
        $content .= "\n";
        $content .= ',Asset to revenue ratio,'.$data['asset_to_revenue_ratio'].',';

        $fp = fopen(public_path() . '/Typeform-validation-exercise.csv', 'wb');
        fwrite($fp, $content);
        fclose($fp);

        return true;
    }
}
