<?php

namespace App\Models\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewPageModel extends Model
{
    use HasFactory;
    protected $table="view_page_info";
    protected $primaryKey="id";

    public $appends=['dns_operator_combine'];
    public function getDnsOperatorCombineAttribute(){
        return $this->dns_operator_en.strtoupper($this->dns_operator_seq);
    }
}