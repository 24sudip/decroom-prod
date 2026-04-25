<?php

use App\AppSettings;

function AppSetting($key) {
    return AppSettings::pluck($key)[0];    
}

if (!function_exists('calculateDiscountPercentage')) {
    function calculateDiscountPercentage($price, $comparePrice) {
        if ($comparePrice > $price && $comparePrice > 0) {
            return round((($comparePrice - $price) / $comparePrice) * 100);
        }
        return 0;
    }
}