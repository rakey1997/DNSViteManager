<?php

namespace App\Models\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageModel extends Model
{
    use HasFactory;
    protected $table="pageinfo_tbl";
    protected $primaryKey="id";

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}
