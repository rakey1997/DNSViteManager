<?php

namespace App\Models\Json;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevelopModel extends Model
{
    use HasFactory;
    protected $table="developinfo_tbl";
    protected $primaryKey="id";
}
