<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $guarded = [];

    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function details(){
        return $this->hasMany(OrderDetail::class,'order_id');
    }

    public function getOrderSubTotalAttribute(){
        $sum = 0;
        if (isset($this->details) && count($this->details) > 0)
        {
            foreach ($this->details as $item)
                $sum+=$item->sub_total;
        }
        return $sum;
    }
    public function getSumColorCoverPriceAttribute(){
        $sum = 0;
        if (isset($this->details) && count($this->details) > 0)
        {
            foreach ($this->details as $item)
                $sum+=$item->color_cover_price;
        }
        return $sum;
    }

    public function getTotalTaxAttribute(){
        return $this->order_sub_total * $this->tax/100;
    }
    public function getTotalAttribute(){

        return $this->order_sub_total + $this->total_tax;
    }

    public function getStatusNameAttribute(){
        switch ($this->status_id){
            case 1:
                return __('msg.pending');
            case 2:
                return __('msg.approve');
            case 3:
                return __('msg.cancel');
        }
    }

    public function status_name_api($lang){
        switch ($this->status_id){
            case 1:
                return __('msg.pending',[],$lang);
            case 2:
                return __('msg.approve',[],$lang);
            case 3:
                return __('msg.cancel',[],$lang);
        }
    }
}
