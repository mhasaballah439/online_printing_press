<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Settings extends Model
{
    use HasFactory;
    protected $table = 'settings';
    protected $guarded = [];

    function about_us_api($lang){
        if ($lang == 'ar')
            return $this->about_us_ar;
        else
            return $this->about_us_en;
    }

    function terms_conditions_api($lang){
        if ($lang == 'ar')
            return $this->terms_conditions_ar;
        else
            return $this->terms_conditions_en;
    }

    function policy_api($lang){
        if ($lang == 'ar')
            return $this->policy_ar;
        else
            return $this->policy_en;
    }

}
