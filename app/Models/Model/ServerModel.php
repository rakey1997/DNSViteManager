<?php

namespace App\Models\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerModel extends Model
{
    use HasFactory;
    protected $table="serverinfo_tbl";
    protected $primaryKey="id";

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}
