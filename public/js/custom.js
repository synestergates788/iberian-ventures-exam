function save() {
    //set csrf token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //submit data on click
    $("#save").click(function() {

        var company_sector = $("#company_sector").val();
        var contact_email = $("#contact_email").val();
        var revenue = $("#revenue").val();
        var yrs_of_growth = $("#yrs_of_growth").val();
        var avg_ebitda_last_three_yrs = $("#avg_ebitda_last_three_yrs").val();
        var avg_net_last_three_yrs = $("#avg_net_last_three_yrs").val();
        var yrs_with_positive_net_result = $("#yrs_with_positive_net_result").val();
        var net_debt = $("#net_debt").val();
        var fixed_assets = $("#fixed_assets").val();
        var biggest_shareholder = $("#biggest_shareholder").val();
        var revenue_from_biggest_client = $("#revenue_from_biggest_client").val();
        var is_the_company_audited = $("#is_the_company_audited").val();
        var m_and_a_in_last_5_yrs = $("#m_and_a_in_last_5_yrs").val();
        var selling_90_percent = $("#selling_90_percent").val();

        var fields = {
            "company_sector"                :   company_sector,
            "contact_email"                 :   contact_email,
            "revenue"                       :   revenue,
            "yrs_of_growth"                 :   yrs_of_growth,
            "avg_ebitda_last_three_yrs"     :   avg_ebitda_last_three_yrs,
            "avg_net_last_three_yrs"        :   avg_net_last_three_yrs,
            "yrs_with_positive_net_result"  :   yrs_with_positive_net_result,
            "net_debt"                      :   net_debt,
            "fixed_assets"                  :   fixed_assets,
            "biggest_shareholder"           :   biggest_shareholder,
            "revenue_from_biggest_client"   :   revenue_from_biggest_client,
            "is_the_company_audited"        :   is_the_company_audited,
            "m_and_a_in_last_5_yrs"         :   m_and_a_in_last_5_yrs,
            "selling_90_percent"            :   selling_90_percent,
        };

        $.ajax({
            url         :   'client-actions',
            type        :   'POST',
            data        :   fields,
            dataType    :   'JSON',
            success     :   function (data){

                if(data.error == true){

                    $('.decision').html('');
                    $('.decision').html('<div class="alert alert-danger" role="alert">'+data.message+'</div>');

                }else{
                    if(data.decision_status == "Go"){
                        $('.decision').html('');
                        $('.decision').html('<div class="alert alert-success" role="alert">Thanks for sending information about your company. It seems to fit “Iberian Ventures” investment criteria –an associate in the team will reach out to you for next steps</div>');
                    }else{
                        $('.decision').html('');
                        $('.decision').html('<div class="alert alert-danger" role="alert">Thanks for sending information about your company. Unfortunately, it seems that this company does not meet “Iberian Ventures” investment criteria. Regardless, we will take a second look in detail and send you an email!</div>');
                    }
                }
            }
        });
    });
}

(function() {
    'use strict';

    $("form").submit(function(e){
        e.preventDefault();
    });

    $('.force_to_int').keypress(function(event){
        if(event.which != 8 && isNaN(String.fromCharCode(event.which))){
            event.preventDefault(); //stop character from entering charac input
        }
    });

    save();
})();