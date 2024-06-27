<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintPrice extends Model
{
    use HasFactory;
    protected $table = 'print_prices';
    protected $guarded = [];

    public function getPrintingTypeAttribute(){
        switch ($this->printing_type_id){
            case 1:
                return __('msg.printing');
            case 2:
                return __('msg.packaging');

        }
    }
}
