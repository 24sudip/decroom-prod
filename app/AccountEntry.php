<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountEntry extends Model {
    use HasFactory;

    protected $fillable = [
        'account_head_id', 'amount', 'entry_date', 'note',
    ];

    public function accountHead() {
        return $this->belongsTo(AccountHead::class);
    }
}
