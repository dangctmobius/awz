<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $table = 'ptc_tasks';
    protected $primaryKey = 'id';


    public function user()
    {
        return $this->hasMany(UserPtcTask::class);
    }
}
