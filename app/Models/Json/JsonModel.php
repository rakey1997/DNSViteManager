<?php

namespace App\Models\Json;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JsonModel extends Model
{
    use HasFactory;
    protected $table="view_dns_info";
    protected $primaryKey="id";

    public $appends=['server_v4ip','server_v6ip'];
    public function getServerV4ipAttribute(){
        if ($this->server_ip_anycast_v4 <> "" && $this->server_ip_anycast_v4_Enable=="1" ){
            return $this->server_ip_anycast_v4;
        } elseif ($this->server_ip_public_v4_Enable=="1"){
            return $this->server_ip_public_v4;
        }else{
            return '';
        }
    }
    public function getServerV6ipAttribute(){
        if ($this->server_ip_anycast_v6 <> "" && $this->server_ip_anycast_v6_Enable=="1"){
            return $this->server_ip_anycast_v6;
        }elseif ($this->server_ip_public_v6_Enable=="1"){
            return $this->server_ip_public_v6;
        }
        else{
            return '';
        }
    }
}
