<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfferBanner extends Model
{
    protected $table = 'offer_banners';

    protected $fillable = [
        'image',
        'link_url',
        'status',
    ];
}
