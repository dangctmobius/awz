<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPtcTask extends Model
{
    use HasFactory;
    protected $table = 'user_ptc_task';


    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
