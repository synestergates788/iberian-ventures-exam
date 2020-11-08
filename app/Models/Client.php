<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    public $incrementing = true;

    public $timestamps = true;

    /**
     * lets you specify which fields is mass-assignable in your model
     *
     * @var array
     */
    protected $fillable = [
        "company_sector",
        "contact_email",
        "revenue",
        "years_of_growth",
        "avg_ebitda_last_three_yrs",
        "avg_net_last_three_yrs",
        "yrs_with_positive_net_result",
        "net_debt",
        "fixed_assets",
        "biggest_shareholder",
        "revenue_from_biggest_client",
        "is_the_company_audited",
        "m_and_a_in_last_5_yrs",
        "selling_90_percent",
        "ebitda_rev",
        "net_margin",
        "deuda_ebitda",
        "asset_to_revenue_ratio",
        "total_score",
        "decision",
        "created_at",
        "updated_at"
    ];
}
