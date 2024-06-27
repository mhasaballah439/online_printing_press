<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Branch extends Model
{
    use HasFactory;
    protected $table = 'branches';
    protected $guarded = [];
    public function getNameAttribute(){
        if (App::isLocale('ar'))
            return $this->name_ar;
        else
            return $this->name_en;
    }
    public function name_api($lang)
    {
        if ($lang == 'ar')
            return $this->name_ar;
        else
            return $this->name_en;
    }
    public function address_api($lang)
    {
        if ($lang == 'ar')
            return $this->address_ar;
        else
            return $this->address_en;
    }
    public function scopeActive($q){
        return $q->where('active',1);
    }
}
