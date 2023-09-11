<?php

namespace App\Models\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewDNSModel extends Model
{
    use HasFactory;
    protected $table="view_dns_info";
    protected $primaryKey="id";

    public $appends=['dns_operator_combine','dns_operator_combine_en','tld_server_name','tld_manager_email'];
    public function getDnsOperatorCombineAttribute(){
        return $this->dns_operator.$this->dns_operator_seq;
    }
    public function getDnsOperatorCombineEnAttribute(){
        return $this->dns_operator_en.strtoupper($this->dns_operator_seq);
    }
    public function getTldServerNameAttribute(){
        return 'a.tld-servers.'.$this->dns_authname;
    }
    public function getTldManagerEmailAttribute(){
        return 'admin.'.$this->dns_authname.'.';
    }
}
