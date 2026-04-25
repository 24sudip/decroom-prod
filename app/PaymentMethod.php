<?php


namespace App;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PaymentMethod extends Model
{
use HasFactory;


protected $table = 'payment_methods';


/**
* The attributes that are mass assignable.
*
* @var array<int, string>
*/
protected $fillable = [
                    'title',
                    'logo',
                    'description',
                ];


/**
* Casts
*/
protected $casts = [
// add casts if needed, e.g. 'meta' => 'array'
];
}