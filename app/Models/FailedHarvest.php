<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedHarvest extends Model
{
    protected $guarded = [];
    public $timestamps = false; // Karena tabel ini tidak punya updated_at secara default, hanya failed_at
}
