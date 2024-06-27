<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'carts';
    protected $guarded = [];

    public function file()
    {
        return $this->morphOne(Media::class, 'mediable')->orderByDesc('id');
    }

    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id');
    }
    public function getPaperTypeNameAttribute(){
        switch ($this->paper_type){
            case 1:
                return __('msg.normal');
                case 2:
                return __('msg.adhesive');
                case 3:
                return __('msg.reinforced');
        }
    }
    public function getPageLayoutNameAttribute(){
        switch ($this->page_layout){
            case 1:
                return __('msg.one_face');
                case 2:
                return __('msg.2_faces');
                case 3:
                return __('msg.4_faces');
        }
    }
    public function getAspectsPrintingNameAttribute(){
        switch ($this->aspects_printing){
            case 1:
                return __('msg.face');
                case 2:
                return __('msg.two_face');
        }
    }
    public function getPackagingNameAttribute(){
        switch ($this->packaging_id){
            case 1:
                return __('msg.without_packaging');
                case 2:
                return __('msg.stapling_packaging_with_a_tape');
                case 3:
                return __('msg.wire');
                case 4:
                return __('msg.one_perforated_paper');
                case 5:
                return __('msg.plastic_snail_packaging');
        }
    }
    public function aspects_printing_name($lang){
        switch ($this->aspects_printing){
            case 1:
                return __('msg.face',[],$lang);
            case 2:
                return __('msg.two_face',[],$lang);
        }
    }
    public function paper_type_name($lang){
        switch ($this->paper_type){
            case 1:
                return __('msg.normal',[],$lang);
                case 2:
                return __('msg.adhesive',[],$lang);
                case 3:
                return __('msg.reinforced',[],$lang);
        }
    }
    public function page_layout_name($lang){
        switch ($this->page_layout){
            case 1:
                return __('msg.one_face',[],$lang);
                case 2:
                return __('msg.2_faces',[],$lang);
                case 3:
                return __('msg.4_faces',[],$lang);
        }
    }
    public function packaging_name($lang){
        switch ($this->packaging_id){
            case 1:
                return __('msg.without_packaging',[],$lang);
                case 2:
                return __('msg.stapling_packaging_with_a_tape',[],$lang);
                case 3:
                return __('msg.wire',[],$lang);
                case 4:
                return __('msg.one_perforated_paper',[],$lang);
                case 5:
                return __('msg.plastic_snail_packaging',[],$lang);
        }
    }

    public function getSubTotalAttribute(){
        $total = $this->price * $this->qty;
        if ($this->printing_color == 1)
            $total = $total  * 2;

        $color_cover_price_total = $this->color_cover_price * $this->qty;
        $packaging_price_total = $this->packaging_price * $this->qty;
        return $total + $packaging_price_total + $color_cover_price_total;
    }

    public function getTaxPriceAttribute(){
        $setting = Settings::first();
        return $this->sub_total * $setting->tax/100;
    }

    public function getTotalAttribute(){
            $total = $this->sub_total + $this->tax_price;
        return $total;
    }
}
