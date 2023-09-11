<?php

namespace App\Models\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DnsModel extends Model
{
    use HasFactory;
    protected $table="dnsinfo_tbl";
    protected $primaryKey="id";
    public $append=['dns_operator_combin'];
    public function getDnsOperatorCombinAttribute(){
        return $this->dns_operator_en.strtoupper($this->dns_operator_seq);
    }

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}
