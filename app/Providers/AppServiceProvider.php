<?php

namespace App\Providers;


use App\Models\DeliveryDetail;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('not_yesterday', function($attribute, $value){
            
            if (is_yesterday($value)) {
                return false;
            }
            return true;
        }, 'Date must not be less than today.');

        Validator::extend('unique_barcode', function($attribute, $value){
            $request = request();
            $detail = DeliveryDetail::where('barcode',  $request->barcode)->first();

            if ($detail && $detail->product_id != $request->product) {
                return false;
            }

            return true;
        }, 'Barcode has already been taken.');
    }
}
