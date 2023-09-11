<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Json\JsonModel;
use App\Models\Json\ParaModel;
use App\Models\Json\DevelopModel;

class JsonController extends Controller
{
    public function jsonShow(){
        $jsonArr=array();
        $tempJsonArr=array();
        $filterName='根服务器';
        $filterName2='权威服务器';

        #JSON参数信息
        $paraModel=new ParaModel();
        $paraData=$paraModel->select('kind','key','value')->get()->toArray(); 

        foreach ($paraData as $items){
            $kind=$items['kind'];
            $key=$items['key'];
            $value=$items['value'];
            if ($key=="version"){
                $value=date('Ymd').$value;
            }

            switch ($kind){
                case "General":
                    $jsonArr[$key]=$value;
                    break;
                default:
                    $jsonArr[$kind][$key]=$value;
            }
        }

        #JSON本域根信息
        $serverModel=new JsonModel();
        $Data=$serverModel->select('dns_authname','dns_authname_seq','server_ip_public_v4','server_ip_public_v4_Enable','server_ip_public_v6','server_ip_public_v6_Enable','server_ip_anycast_v4','server_ip_anycast_v4_Enable','server_ip_anycast_v6','server_ip_anycast_v6_Enable','server_ip_anycast2_v4','server_ip_anycast2_v4_Enable','server_ip_anycast2_v6','server_ip_anycast2_v6_Enable','dns_role','dns_type')
                          ->where('dns_type', '<>','测试根')
                          ->where(function($query) use($filterName,$filterName2){
                            $query->where('dns_role',$filterName)
                                ->orWhere(function($query) use($filterName2){
                            $query->where('dns_role', $filterName2);});})
                        ->where('server_status','正常')->orderBy('dns_authname','asc')->orderBy('dns_authname_seq','asc')->get()->toArray(); 
        
        $rootv4ipArr=array();
        $rootv6ipArr=array();
        $authv4ipArr=array();
        $authv6ipArr=array();
        foreach ($Data as &$items){
            $v4ip=$items['server_v4ip'];
            $v6ip=$items['server_v6ip'];

            $dnsAuthName=$items['dns_authname'];
            $dnsAuthNameSeq=$items['dns_authname_seq'];
            $dnsRole=$items['dns_role'];
            $dnsType=$items['dns_type'];

            $tempJsonArr[$dnsAuthName]['root_id']=$dnsAuthName.'.';
            $tempJsonArr[$dnsAuthName]['email']='admin.'.$dnsAuthName.'.';

            if (!array_key_exists($dnsAuthName,$rootv4ipArr)){
                $rootv4ipArr[$dnsAuthName]=[];
            }
            if (!array_key_exists($dnsAuthName,$authv4ipArr)){
                $authv4ipArr[$dnsAuthName]=[];
            }

            if ($dnsRole == '根服务器'){
                $rootServer=array();
                if (!in_array($v4ip,$rootv4ipArr[$dnsAuthName]) && $v4ip <> '' ){
                    $rootServer['server_label']=$dnsAuthNameSeq;
                    $rootServer['ipv4']=$v4ip;
                    array_push($rootv4ipArr[$dnsAuthName],$v4ip);

                    if ($v6ip <> ''){
                        $rootServer['ipv6']=$v6ip;
                        array_push($rootv6ipArr,$v6ip);
                    }
                    if ($dnsType == '独立根'){
                        $rootServer['cluster']='False';
                    }
                    $arrKey=array_key_exists('root_servers',$tempJsonArr[$dnsAuthName]);
                    if (!$arrKey){
                        $tempJsonArr[$dnsAuthName]['root_servers'][0]=$rootServer;
                    }else{
                        array_push($tempJsonArr[$dnsAuthName]['root_servers'],$rootServer);
                    }
            }
            }elseif($dnsRole == '权威服务器'){
                $tldServer=array();
                if (!in_array($v4ip,$authv4ipArr[$dnsAuthName]) && $v4ip <> ''){
                    $tldServer['server_label']=$dnsAuthNameSeq;
                    $tldServer['ipv4']=$v4ip;
                    array_push($authv4ipArr[$dnsAuthName],$v4ip);

                    if ($v6ip <> ''){
                        $tldServer['ipv6']=$v6ip;
                        array_push($authv6ipArr,$v6ip);
                    }

                    $arrKey=array_key_exists('tld_servers',$tempJsonArr[$dnsAuthName]);
                    if (!$arrKey){
                        $tempJsonArr[$dnsAuthName]['tld_servers'][0]=$tldServer;
                    }else{
                        array_push($tempJsonArr[$dnsAuthName]['tld_servers'],$tldServer);
                    }
                }
            }
        }

        
        $jsonArr['root_list']=array_values($tempJsonArr);
        foreach ($jsonArr['root_list'] as &$detail){
            uksort($detail,function ($one,$two) {
                // var_dump($one.'='.$two);
                if ($one=="root_id"){
                    return -1;
                }elseif($one=="email" && $two!="root_id"){
                    return -1;
                }elseif($one>$two && $one!="root_id" && $one!="email"){
                    return 1;
                }else{
                    return -1;
                }
              });
        }

        #JSON参数信息
        $paraModel=new DevelopModel();
        $paraData=$paraModel->select('kind','auth_name','server_ipv4','server_port')->orderBy('kind','asc')->orderBy('auth_name','asc')->get()->toArray(); 

        foreach ($paraData as &$items){
            $kind=$items['kind'];
            $authName=$items['auth_name'];
            $serverIpv4=$items['server_ipv4'];
            $serverPort=$items['server_port'];

            switch ($kind){
                case "rs-config":
                    $jsonArr[$kind][$authName]=$serverIpv4;
                    break;
                default:
                    $jsonArr[$kind][$authName]=[$serverIpv4,$serverPort];
            }
        }


        $jsonResult['opCode']=true;
        $jsonResult['msg']='query success';
        $jsonResult['data']=$jsonArr;

        return $jsonResult;
    }

    public function genJSONFile(Request $request){
        $fileNameList=$request->input('fileNameList');
        try {
            $jsonResult=$this->jsonShow();
            $jsonArr_new = preg_replace_callback ('/^ +/m',function ($m) {
                return str_repeat (' ',strlen ($m[0]) / 2);
              },json_encode ($jsonResult['data'],JSON_PRETTY_PRINT));

            file_put_contents($fileNameList['fileName'],$jsonArr_new);

            $resultArr['opCode']=true;
            $resultArr['msg']='Generate JSON File Success';
        }catch (Exception $e) {
            $resultArr['opCode']=false;
            $resultArr['msg']='Generate JSON File Fail';
            $resultArr['data']=$e->getMessage();
        }     

        return $resultArr;
    }

}