<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirdropSubmit extends Model
{
    use HasFactory;
    protected $table = 'airdrop_submits';


    public function submits() {
        return $this->belongTo(Airdrop::class);
    }
}
