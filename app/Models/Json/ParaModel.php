<?php

namespace App\Models\Json;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParaModel extends Model
{
    use HasFactory;
    protected $table="jsoninfo_tbl";
    protected $primaryKey="id";
}
