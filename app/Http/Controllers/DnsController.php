<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Model\ViewDNSModel;
use App\Models\Model\ViewPageModel;
use Spatie\Dns\Dns;
use Spatie\Dns\Support\Types;

class DnsController extends Controller
{
    public function mergeAnycast($AnyData,$AnyData_other,$str){
        $newArr=[];
        $kname = array('dns_role','dns_type','server_location',$str,'dns_operator','dns_operator_en','is_monitor','dns_operator_combine','dns_operator_combine_en','tld_server_name','tld_manager_email');
        for ($i=0;$i<count($AnyData_other);$i++){
            $newArr=['999'.$i=>array_combine($kname,$AnyData_other[$i])];
        }        
        $AnyData=array_merge($AnyData,$newArr);
        return $AnyData;
    }

    public function diffAnycast($AnyData,$flag){
        $opArr=array();
        foreach ($AnyData as &$items){
            $op=$items[$flag];
            $oper=$items['dns_operator_en']; 
            if (array_key_exists($oper,$opArr)) {
                if (!array_search($op,$opArr[$oper],true)){
                    array_push($opArr[$oper],$op);
                }
            } else{
                $opArr[$oper][]=$op;
            }       
        }
        foreach ($AnyData as &$items){
            $op=$items[$flag];
            $oper=$items['dns_operator_en']; 
            sort($opArr[$oper]);
            $pos=(int)array_search($op,$opArr[$oper],true)+1;
            $items['dns_operator_combine']=$items['dns_operator'].'X0'.$pos;
            $items['dns_operator_combine_en']=$items['dns_operator_en'].'X0'.$pos;
        }
        unset($items);
        return $AnyData;
    }

    public function merge3wAnycast($AnyData,$AnyData_other,$str){
        $newArr=[];
        $kname = array('dns_operator_en',$str,'page_name_port','page_name','page_name_ip','dns_operator_combine');
        for ($i=0;$i<count($AnyData_other);$i++){
            $newArr=['999'.$i=>array_combine($kname,$AnyData_other[$i])];
        }        
        $AnyData=array_merge($AnyData,$newArr);
        return $AnyData;
    }

    public function diff3wAnycast($AnyData,$flag){
        $opArr=array();
        foreach ($AnyData as &$items){
            $op=$items[$flag];
            $oper=$items['dns_operator_en']; 
            if (array_key_exists($oper,$opArr)) {
                if (!array_search($op,$opArr[$oper],true)){
                    array_push($opArr[$oper],$op);
                }
            }else{
                $opArr[$oper][]=$op;
            }
        }
        foreach ($AnyData as &$items){
            $op=$items[$flag];
            $oper=$items['dns_operator_en']; 
            sort($opArr[$oper]);
            $pos=(int)array_search($op,$opArr[$oper],true)+1;
            $items['dns_operator_combine']=$items['dns_operator_en'].'X0'.$pos;
        }
        unset($items);
        return $AnyData;
    }

    public function saveTo3wFile($v4fileName,$v6fileName,$mode,$Data_v4,$Data_v6){
        $v4handle=fopen($v4fileName,$mode) or die('打开<b>'.$v4fileName.'</b>文件失败!!');
        $v6handle=fopen($v6fileName,$mode) or die('打开<b>'.$v6fileName.'</b>文件失败!!');

        foreach($Data_v4 as $item){
            if ($item['server_ip_public_v4']!="") {
                $v4str=$item['dns_operator_combine'].":".$item['server_ip_public_v4'].":".$item['page_name_port'].":".$item['page_name'].":".$item['page_name_ip']."\n";
                fwrite($v4handle,$v4str);
            }
        }
        foreach($Data_v6 as $item){
            if ($item['server_ip_public_v6']!="") {
                $v6str=$item['dns_operator_combine']."|".$item['server_ip_public_v6']."|".$item['page_name_port']."|".$item['page_name']."|".$item['page_name_ip']."\n";
                fwrite($v6handle,$v6str);
            } 
        }
        fclose($v4handle);
        fclose($v6handle);
    }
    
    public function saveAnycastTo3wFile($v4fileName,$v6fileName,$mode,$Data_v4,$Data_v6){
        $v4handle=fopen($v4fileName,$mode) or die('打开<b>'.$v4fileName.'</b>文件失败!!');
        $v6handle=fopen($v6fileName,$mode) or die('打开<b>'.$v6fileName.'</b>文件失败!!');
        
        foreach($Data_v4 as $item){
            if ($item['server_ip_anycast_v4']!="") {
                $v4str=$item['dns_operator_combine'].":".$item['server_ip_anycast_v4'].":".$item['page_name_port'].":".$item['page_name'].":".$item['page_name_ip']."\n";
                fwrite($v4handle,$v4str);
            }
        }
        foreach($Data_v6 as $item){
            if ($item['server_ip_anycast_v6']!="") {
                $v6str=$item['dns_operator_combine']."|".$item['server_ip_anycast_v6']."|".$item['page_name_port']."|".$item['page_name'].":".$item['page_name_ip']."\n";
                fwrite($v6handle,$v6str);
            } 
        }
        fclose($v4handle);
        fclose($v6handle);
    }

    public function saveToFile($v4fileName,$v6fileName,$mode,$Data_v4,$Data_v6){
        $v4handle=fopen($v4fileName,$mode) or die('打开<b>'.$v4fileName.'</b>文件失败!!');
        $v6handle=fopen($v6fileName,$mode) or die('打开<b>'.$v6fileName.'</b>文件失败!!');

        foreach($Data_v4 as $item){
            if ($item['server_ip_public_v4']!="") {
                $v4str=$item['server_ip_public_v4']." ".$item['dns_operator_combine']." ".$item['dns_operator_combine_en']."\n";
                fwrite($v4handle,$v4str);
            }
        }
        foreach($Data_v6 as $item){
            if ($item['server_ip_public_v6']!="") {
                $v6str=$item['server_ip_public_v6']." ".$item['dns_operator_combine']." ".$item['dns_operator_combine_en']."\n";
                fwrite($v6handle,$v6str);
            } 
        }
        fclose($v4handle);
        fclose($v6handle);
    }
    
    public function saveAnycastToFile($v4fileName,$v6fileName,$mode,$Data_v4,$Data_v6){
        $v4handle=fopen($v4fileName,$mode) or die('打开<b>'.$v4fileName.'</b>文件失败!!');
        $v6handle=fopen($v6fileName,$mode) or die('打开<b>'.$v6fileName.'</b>文件失败!!');
        
        foreach($Data_v4 as $item){
            if ($item['server_ip_anycast_v4']!="") {
                $v4str=$item['server_ip_anycast_v4']." ".$item['dns_operator_combine']." ".$item['dns_operator_combine_en']."\n";
                fwrite($v4handle,$v4str);
            }
        }
        foreach($Data_v6 as $item){
            if ($item['server_ip_anycast_v6']!="") {
                $v6str=$item['server_ip_anycast_v6']." ".$item['dns_operator_combine']." ".$item['dns_operator_combine_en']."\n";
                fwrite($v6handle,$v6str);
            } 
        }
        fclose($v4handle);
        fclose($v6handle);
    }

    public function saveToSoaFile($fileName,$mode,$Data){
        $handle=fopen($fileName,$mode) or die('打开<b>'.$fileName.'</b>文件失败!!');
        foreach($Data as $item){
            $str=$item['dns_authname'].",".$item['tld_server_name'].". ".$item['tld_manager_email']."\n";
            fwrite($handle,$str);
        }
        fclose($handle);
    }

    public function PublishGit($type){
        $jsonArr=array();
        $command = sprintf('ssh root@172.17.0.1 "bash /home/dns/publishFile.sh %s"',escapeshellcmd($type));
        $res = shell_exec($command);
        $jsonArr['opCode']=true;
        $jsonArr['msg']='publishFile success';
        $jsonArr['data']=$command;
        return $jsonArr;
    }

    public function returnDnsData(Request $request){
        $filterNameList=$request->input('filterNameList');

        return $this->queryDnsData($filterNameList);
    }

    public function queryDomainGroup(){
        $domainGroup = ViewDNSModel::where('dns_role', '根服务器')
            ->where('server_status', '正常')
            ->where('dns_type', '本域根')
            ->distinct('dns_operator_en')
            ->pluck('dns_operator_en')
            ->toArray();
        return $domainGroup;
    }

    public function queryDnsData($filterNameList){
        $jsonArr=array();

        $view_DNS=new ViewDNSModel();

        $baseQuery = $view_DNS->select(
            'dns_role', 'dns_type', 'server_location', 'server_ip_public_v4', 'server_ip_public_v6', 
            'server_ip_anycast_v4', 'server_ip_anycast_v6', 'server_ip_public_v4_Enable', 'server_ip_public_v6_Enable', 
            'server_ip_anycast_v4_Enable', 'server_ip_anycast_v6_Enable', 'server_ip_anycast2_v4', 'server_ip_anycast2_v6', 
            'server_ip_anycast2_v4_Enable', 'server_ip_anycast2_v6_Enable', 'is_monitor','is_special_monitor', 'dns_operator_seq', 'dns_operator', 
            'dns_operator_en'
            )
            ->whereIn('dns_role', $filterNameList)
            ->where('server_status', '正常')
            ->orderBy('dns_type', 'asc')
            ->orderBy('server_location', 'asc')
            ->orderBy('dns_operator', 'asc')
            ->orderBy('dns_operator_seq', 'asc');

        $Data_v4 = clone $baseQuery;
        $Data_v4 = $Data_v4->where('server_ip_public_v4', '<>', '')
            ->where('server_ip_public_v4_Enable', 1)
            ->select('dns_role', 'dns_type', 'server_location', 'server_ip_public_v4', 'dns_operator_seq', 'dns_operator', 'dns_operator_en','is_monitor')
            ->get()
            ->toArray();

        $Data_v6 = clone $baseQuery;
        $Data_v6 = $Data_v6->where('server_ip_public_v6', '<>', '')
            ->where('server_ip_public_v6_Enable', 1)
            ->select('dns_role', 'dns_type', 'server_location', 'server_ip_public_v6', 'dns_operator_seq', 'dns_operator', 'dns_operator_en','is_monitor')
            ->get()
            ->toArray();

        $AnyData_v4 = clone $baseQuery;
        $AnyData_v4 = $AnyData_v4->where('server_ip_anycast_v4', '<>', '')
            ->where('server_ip_anycast_v4_Enable', 1)
            ->distinct()
            ->select('dns_role', 'dns_type', 'server_location', 'server_ip_anycast_v4', 'dns_operator', 'dns_operator_en','is_monitor')
            ->get()
            ->toArray();

        $AnyData_v6 = clone $baseQuery;
        $AnyData_v6 = $AnyData_v6->where('server_ip_anycast_v6', '<>', '')
            ->where('server_ip_anycast_v6_Enable', 1)
            ->distinct()
            ->select('dns_role', 'dns_type', 'server_location', 'server_ip_anycast_v6', 'dns_operator', 'dns_operator_en','is_monitor')
            ->get()
            ->toArray();

        $AnyData2_v4 = clone $baseQuery;
        $AnyData2_v4 = $AnyData2_v4->where('server_ip_anycast2_v4', '<>', '')
            ->where('server_ip_anycast2_v4_Enable', 1)
            ->distinct()
            ->select('dns_role', 'dns_type', 'server_location', 'server_ip_anycast2_v4', 'dns_operator', 'dns_operator_en','is_monitor')
            ->get()
            ->toArray();

        $AnyData2_v6 = clone $baseQuery;
        $AnyData2_v6 = $AnyData2_v6->where('server_ip_anycast2_v6', '<>', '')
            ->where('server_ip_anycast2_v6_Enable', 1)
            ->distinct()
            ->select('dns_role', 'dns_type', 'server_location', 'server_ip_anycast2_v6', 'dns_operator', 'dns_operator_en','is_monitor')
            ->get()
            ->toArray();

        $WhiteData_v4 = clone $baseQuery;
        $WhiteData_v4 = $WhiteData_v4->where('server_ip_public_v4', '<>', '')
        ->where('server_ip_public_v4_Enable', 1)
        ->where('is_monitor', 0)
        ->select('dns_role', 'dns_type', 'server_location', 'server_ip_public_v4', 'dns_operator_seq', 'dns_operator', 'dns_operator_en','is_monitor')
        ->get()
        ->toArray();

        $WhiteData_v6 = clone $baseQuery;
        $WhiteData_v6 = $WhiteData_v6->where('server_ip_public_v6', '<>', '')
            ->where('server_ip_public_v6_Enable', 1)
            ->where('is_monitor', 0)
            ->select('dns_role', 'dns_type', 'server_location', 'server_ip_public_v6', 'dns_operator_seq', 'dns_operator', 'dns_operator_en','is_monitor')
            ->get()
            ->toArray();

        $WhiteAnyData_v4 = clone $baseQuery;
        $WhiteAnyData_v4 = $WhiteAnyData_v4->where('server_ip_anycast_v4', '<>', '')
            ->where('server_ip_anycast_v4_Enable', 1)
            ->where('is_monitor', 0)
            ->distinct()
            ->select('dns_role', 'dns_type', 'server_location', 'server_ip_anycast_v4', 'dns_operator', 'dns_operator_en','is_monitor')
            ->get()
            ->toArray();

        $WhiteAnyData_v6 = clone $baseQuery;
        $WhiteAnyData_v6 = $WhiteAnyData_v6->where('server_ip_anycast_v6', '<>', '')
            ->where('server_ip_anycast_v6_Enable', 1)
            ->where('is_monitor', 0)
            ->distinct()
            ->select('dns_role', 'dns_type', 'server_location', 'server_ip_anycast_v6', 'dns_operator', 'dns_operator_en','is_monitor')
            ->get()
            ->toArray();

        $WhiteAnyData2_v4 = clone $baseQuery;
        $WhiteAnyData2_v4 = $WhiteAnyData2_v4->where('server_ip_anycast2_v4', '<>', '')
            ->where('server_ip_anycast2_v4_Enable', 1)
            ->where('is_monitor', 0)
            ->distinct()
            ->select('dns_role', 'dns_type', 'server_location', 'server_ip_anycast2_v4', 'dns_operator', 'dns_operator_en','is_monitor')
            ->get()
            ->toArray();

        $WhiteAnyData2_v6 = clone $baseQuery;
        $WhiteAnyData2_v6 = $WhiteAnyData2_v6->where('server_ip_anycast2_v6', '<>', '')
            ->where('server_ip_anycast2_v6_Enable', 1)
            ->where('is_monitor', 0)
            ->distinct()
            ->select('dns_role', 'dns_type', 'server_location', 'server_ip_anycast2_v6', 'dns_operator', 'dns_operator_en','is_monitor')
            ->get()
            ->toArray();
        
        if (!empty($AnyData2_v4)){
            $AnyData_v4=$this->mergeAnycast($AnyData_v4,$AnyData2_v4,'server_ip_anycast_v4');
        }
        if (!empty($WhiteAnyData2_v4)){
            $WhiteAnyData_v4=$this->mergeAnycast($WhiteAnyData_v4,$WhiteAnyData2_v4,'server_ip_anycast_v4');
        }
        if (!empty($AnyData2_v6)){
            $AnyData_v6=$this->mergeAnycast($AnyData_v6,$AnyData2_v6,'server_ip_anycast_v6');
        }
        if (!empty($WhiteAnyData2_v6)){
            $WhiteAnyData_v6=$this->mergeAnycast($WhiteAnyData_v6,$WhiteAnyData2_v6,'server_ip_anycast_v6');
        }

        $AnyData_v4=$this->diffAnycast($AnyData_v4,'server_ip_anycast_v4');
        $AnyData_v6=$this->diffAnycast($AnyData_v6,'server_ip_anycast_v6');
        $WhiteAnyData_v4=$this->diffAnycast($WhiteAnyData_v4,'server_ip_anycast_v4');
        $WhiteAnyData_v6=$this->diffAnycast($WhiteAnyData_v6,'server_ip_anycast_v6');

        $SpecialListData_v4 = clone $baseQuery;
        $SpecialListData_v4 = $SpecialListData_v4->where('server_ip_public_v4', '<>', '')
            ->where('server_ip_public_v4_Enable', 1)
            ->where('is_special_monitor',1)
            ->select('dns_role', 'dns_type', 'server_location', 'server_ip_public_v4', 'dns_operator_seq', 'dns_operator', 'dns_operator_en','is_monitor')
            ->get()
            ->toArray();

        $SpecialListData_v6 = clone $baseQuery;
        $SpecialListData_v6 = $SpecialListData_v6->where('server_ip_public_v6', '<>', '')
            ->where('server_ip_public_v6_Enable', 1)
            ->where('is_special_monitor',1)
            ->select('dns_role', 'dns_type', 'server_location', 'server_ip_public_v6', 'dns_operator_seq', 'dns_operator', 'dns_operator_en','is_monitor')
            ->get()
            ->toArray();

        $SpecialListAnyData_v4 = clone $baseQuery;
        $SpecialListAnyData_v4 = $SpecialListAnyData_v4->where('server_ip_anycast_v4', '<>', '')
            ->where('server_ip_anycast_v4_Enable', 1)
            ->where('is_special_monitor',1)
            ->distinct()
            ->select('dns_role', 'dns_type', 'server_location', 'server_ip_anycast_v4', 'dns_operator_seq', 'dns_operator', 'dns_operator_en','is_monitor')
            ->get()
            ->toArray();

        $SpecialListAnyData_v6 = clone $baseQuery;
        $SpecialListAnyData_v6 = $SpecialListAnyData_v6->where('server_ip_anycast_v6', '<>', '')
            ->where('server_ip_anycast_v6_Enable', 1)
            ->where('is_special_monitor',1)
            ->distinct()
            ->select('dns_role', 'dns_type', 'server_location', 'server_ip_anycast_v6', 'dns_operator_seq', 'dns_operator', 'dns_operator_en','is_monitor')
            ->get()
            ->toArray();

        $SpecialListAnyData2_v4 = clone $baseQuery;
        $SpecialListAnyData2_v4 = $SpecialListAnyData2_v4->where('server_ip_anycast2_v4', '<>', '')
            ->where('server_ip_anycast2_v4_Enable', 1)
            ->where('is_special_monitor',1)
            ->distinct()
            ->select('dns_role', 'dns_type', 'server_location', 'server_ip_anycast2_v4', 'dns_operator_seq', 'dns_operator', 'dns_operator_en','is_monitor')
            ->get()
            ->toArray();

        $SpecialListAnyData2_v6 = clone $baseQuery;
        $SpecialListAnyData2_v6 = $SpecialListAnyData2_v6->where('server_ip_anycast2_v6', '<>', '')
            ->where('server_ip_anycast2_v6_Enable', 1)
            ->where('is_special_monitor',1)
            ->distinct()
            ->select('dns_role', 'dns_type', 'server_location', 'server_ip_anycast2_v6', 'dns_operator_seq', 'dns_operator', 'dns_operator_en','is_monitor')
            ->get()
            ->toArray();

        if (!empty($SpecialListAnyData2_v4)){
            $SpecialListAnyData_v4=$this->mergeAnycast($SpecialListAnyData_v4,$SpecialListAnyData2_v4,'server_ip_anycast_v4');
        }

        if (!empty($SpecialListAnyData2_v6)){
            $SpecialListAnyData_v6=$this->mergeAnycast($SpecialListAnyData_v6,$SpecialListAnyData2_v6,'server_ip_anycast_v6');
        }

        $SpecialListAnyData_v4=$this->diffAnycast($SpecialListAnyData_v4,'server_ip_anycast_v4');
        $SpecialListAnyData_v6=$this->diffAnycast($SpecialListAnyData_v6,'server_ip_anycast_v6');

        $jsonArr['opCode']=true;
        $jsonArr['msg']='query success';
        $jsonArr['data']['Data_v4']=$Data_v4;
        $jsonArr['data']['AnyData_v4']=$AnyData_v4;
        $jsonArr['data']['Data_v6']=$Data_v6;
        $jsonArr['data']['AnyData_v6']=$AnyData_v6;
        $jsonArr['data']['SpecialListData_v4']=$SpecialListData_v4;
        $jsonArr['data']['SpecialListAnyData_v4']=$SpecialListAnyData_v4;
        $jsonArr['data']['SpecialListData_v6']=$SpecialListData_v6;
        $jsonArr['data']['SpecialListAnyData_v6']=$SpecialListAnyData_v6;
        $jsonArr['data']['WhiteData_v4']=$WhiteData_v4;
        $jsonArr['data']['WhiteAnyData_v4']=$WhiteAnyData_v4;
        $jsonArr['data']['WhiteData_v6']=$WhiteData_v6;
        $jsonArr['data']['WhiteAnyData_v6']=$WhiteAnyData_v6;

        return $jsonArr;    
    }

    public function returnWWWData(){
        $jsonArr=array();
        $view_Page=new ViewPageModel();

        $Data_v4=$view_Page->select('server_location','dns_operator','dns_operator_seq','dns_operator_en','server_ip_public_v4','page_name_port','page_name','page_name_ip')->where('server_status','正常')->where('server_ip_public_v4','<>','')->where('server_ip_public_v4_Enable',1)->orderBy('server_location','asc')->orderBy('dns_operator','asc')->orderBy('dns_operator','asc')->orderBy('dns_operator_seq','asc')->get()->toArray(); 
        $Data_v6=$view_Page->select('server_location','dns_operator','dns_operator_seq','dns_operator_en','server_ip_public_v6','page_name_port','page_name','page_name_ip')->where('server_status','正常')->where('server_ip_public_v6','<>','')->where('server_ip_public_v6_Enable',1)->orderBy('server_location','asc')->orderBy('dns_operator','asc')->orderBy('dns_operator','asc')->orderBy('dns_operator_seq','asc')->get()->toArray(); 
        
        $AnyData_v4=$view_Page->select('server_location','dns_operator','dns_operator_en','server_ip_anycast_v4','page_name_port','page_name','page_name_ip')->where('server_status','正常')->where('server_ip_anycast_v4','<>','')->where('server_ip_anycast_v4_Enable',1)->distinct()->orderBy('server_location','asc')->orderBy('dns_operator','asc')->orderBy('dns_operator','asc')->get()->toArray();
        $AnyData_v6=$view_Page->select('server_location','dns_operator','dns_operator_en','server_ip_anycast_v6','page_name_port','page_name','page_name_ip')->where('server_status','正常')->where('server_ip_anycast_v6','<>','')->where('server_ip_anycast_v6_Enable',1)->distinct()->orderBy('server_location','asc')->orderBy('dns_operator','asc')->orderBy('dns_operator','asc')->get()->toArray();
        
        $AnyData2_v4=$view_Page->select('server_location','dns_operator','dns_operator_en','server_ip_anycast2_v4','page_name_port','page_name','page_name_ip')->where('server_status','正常')->where('server_ip_anycast2_v4','<>','')->where('server_ip_anycast2_v4_Enable',1)->distinct()->orderBy('server_location','asc')->orderBy('dns_operator','asc')->orderBy('dns_operator','asc')->get()->toArray();
        $AnyData2_v6=$view_Page->select('server_location','dns_operator','dns_operator_en','server_ip_anycast2_v6','page_name_port','page_name','page_name_ip')->where('server_status','正常')->where('server_ip_anycast2_v6','<>','')->where('server_ip_anycast2_v6_Enable',1)->distinct()->orderBy('server_location','asc')->orderBy('dns_operator','asc')->orderBy('dns_operator','asc')->get()->toArray();
    
        $WhiteData_v4=$view_Page->select('server_location','dns_operator','dns_operator_seq','dns_operator_en','server_ip_public_v4','page_name_port','page_name','page_name_ip')->where('server_status','正常')->where('server_ip_public_v4','<>','')->where('is_monitor',0)->where('server_ip_public_v4_Enable',1)->orderBy('server_location','asc')->orderBy('dns_operator','asc')->orderBy('dns_operator','asc')->orderBy('dns_operator_seq','asc')->get()->toArray();
        $WhiteData_v6=$view_Page->select('server_location','dns_operator','dns_operator_seq','dns_operator_en','server_ip_public_v6','page_name_port','page_name','page_name_ip')->where('server_status','正常')->where('server_ip_public_v6','<>','')->where('is_monitor',0)->where('server_ip_public_v6_Enable',1)->orderBy('server_location','asc')->orderBy('dns_operator','asc')->orderBy('dns_operator','asc')->orderBy('dns_operator_seq','asc')->get()->toArray();
            
        $WhiteAnyData_v4=$view_Page->select('server_location','dns_operator','dns_operator_en','server_ip_anycast_v4','page_name_port','page_name','page_name_ip')->where('server_status','正常')->where('server_ip_anycast_v4','<>','')->where('server_ip_anycast_v4_Enable',1)->where('is_monitor',0)->distinct()->orderBy('server_location','asc')->orderBy('dns_operator','asc')->orderBy('dns_operator','asc')->get()->toArray();
        $WhiteAnyData_v6=$view_Page->select('server_location','dns_operator','dns_operator_en','server_ip_anycast_v6','page_name_port','page_name','page_name_ip')->where('server_status','正常')->where('server_ip_anycast_v6','<>','')->where('server_ip_anycast_v6_Enable',1)->where('is_monitor',0)->distinct()->orderBy('server_location','asc')->orderBy('dns_operator','asc')->orderBy('dns_operator','asc')->get()->toArray();
    
        $WhiteAnyData2_v4=$view_Page->select('server_location','dns_operator','dns_operator_en','server_ip_anycast2_v4','page_name_port','page_name','page_name_ip')->where('server_status','正常')->where('server_ip_anycast2_v4','<>','')->where('server_ip_anycast2_v4_Enable',1)->where('is_monitor',0)->distinct()->orderBy('server_location','asc')->orderBy('dns_operator','asc')->orderBy('dns_operator','asc')->get()->toArray();
        $WhiteAnyData2_v6=$view_Page->select('server_location','dns_operator','dns_operator_en','server_ip_anycast2_v6','page_name_port','page_name','page_name_ip')->where('server_status','正常')->where('server_ip_anycast2_v6','<>','')->where('server_ip_anycast2_v6_Enable',1)->where('is_monitor',0)->distinct()->orderBy('server_location','asc')->orderBy('dns_operator','asc')->orderBy('dns_operator','asc')->get()->toArray();

        if (!empty($AnyData2_v4)){
            $AnyData_v4=$this->merge3wAnycast($AnyData_v4,$AnyData2_v4,'server_ip_anycast_v4');
        }
        if (!empty($WhiteAnyData2_v4)){
            $WhiteAnyData_v4=$this->merge3wAnycast($WhiteAnyData_v4,$WhiteAnyData2_v4,'server_ip_anycast_v4');
        }
        if (!empty($AnyData2_v6)){
            $AnyData_v6=$this->merge3wAnycast($AnyData_v6,$AnyData2_v6,'server_ip_anycast_v6');
        }
        if (!empty($WhiteAnyData2_v6)){
            $WhiteAnyData_v6=$this->merge3wAnycast($WhiteAnyData_v6,$WhiteAnyData2_v6,'server_ip_anycast_v6');
        }

        $AnyData_v4=$this->diff3wAnycast($AnyData_v4,'server_ip_anycast_v4');
        $AnyData_v6=$this->diff3wAnycast($AnyData_v6,'server_ip_anycast_v6');
        $WhiteAnyData_v4=$this->diff3wAnycast($WhiteAnyData_v4,'server_ip_anycast_v4');
        $WhiteAnyData_v6=$this->diff3wAnycast($WhiteAnyData_v6,'server_ip_anycast_v6');

        
        $jsonArr['opCode']=true;
        $jsonArr['msg']='query success';
        $jsonArr['data']['Data_v4']=$Data_v4;
        $jsonArr['data']['AnyData_v4']=$AnyData_v4;
        $jsonArr['data']['Data_v6']=$Data_v6;
        $jsonArr['data']['AnyData_v6']=$AnyData_v6;
        $jsonArr['data']['WhiteData_v4']=$WhiteData_v4;
        $jsonArr['data']['WhiteAnyData_v4']=$WhiteAnyData_v4;
        $jsonArr['data']['WhiteData_v6']=$WhiteData_v6;
        $jsonArr['data']['WhiteAnyData_v6']=$WhiteAnyData_v6;
        return $jsonArr;    
    }

    public function returnSOAData(){
        $jsonArr=array();

        $view_DNS=new ViewDNSModel();
        $SoaData=$view_DNS->select('dns_authname')->where('dns_role','权威服务器')->where('server_status','正常')->distinct()->orderBy('dns_authname','asc')->get()->toArray();
        $WhiteSoaData=$view_DNS->select('dns_authname')->where('dns_role','权威服务器')->where('server_status','正常')->where('is_monitor',0)->distinct()->orderBy('dns_authname','asc')->get()->toArray();

        $jsonArr['opCode']=true;
        $jsonArr['msg']='query success';
        $jsonArr['data']['SoaData']=$SoaData;
        $jsonArr['data']['WhiteSoaData']=$WhiteSoaData;

        return $jsonArr;
    }

    public function makeFiles(Request $request){
        $resultArr=[];
        $filterNameList=$request->input('filterNameList');
        $fileNameList=array_values($request->input('fileNameList'));
        $type=$request->input('type');

        if($type=='server'){
            try {
                list($fileName,$fileV6Name,$whiteFileName,$whiteV6FileName,$specialFileName,$specialV6FileName)=array_slice($fileNameList,0,6);
                $jsonArr=$this->queryDnsData($filterNameList);

                $this->saveToFile($fileName,$fileV6Name,'w+',$jsonArr['data']['Data_v4'],$jsonArr['data']['Data_v6']);
                $this->saveToFile($whiteFileName,$whiteV6FileName,'w+',$jsonArr['data']['WhiteData_v4'],$jsonArr['data']['WhiteData_v6']);

                $this->saveAnycastToFile($fileName,$fileV6Name,'a+',$jsonArr['data']['AnyData_v4'],$jsonArr['data']['AnyData_v6']);
                $this->saveAnycastToFile($whiteFileName,$whiteV6FileName,'a+',$jsonArr['data']['WhiteAnyData_v4'],$jsonArr['data']['WhiteAnyData_v6']);

                $this->saveToFile($specialFileName,$specialV6FileName,'w+',$jsonArr['data']['SpecialListData_v4'],$jsonArr['data']['SpecialListData_v6']);
                $this->saveAnycastToFile($specialFileName,$specialV6FileName,'a+',$jsonArr['data']['SpecialListAnyData_v4'],$jsonArr['data']['SpecialListAnyData_v6']);

                $resultArr['opCode']=true;
                $resultArr['msg']='Generate File Success';
            }catch (Exception $e) {
                $resultArr['opCode']=false;
                $resultArr['msg']='Generate File Fail';
                $resultArr['data']=$e->getMessage();
            }           
        }else if($type=='www'){
            try{
                list($fileName,$fileV6Name,$whiteFileName,$whiteV6FileName)=array_slice($fileNameList,0,4);
                $jsonArr=$this->returnWWWData();

                $this->saveTo3wFile($fileName,$fileV6Name,'w+',$jsonArr['data']['Data_v4'],$jsonArr['data']['Data_v6']);
                $this->saveTo3wFile($whiteFileName,$whiteV6FileName,'w+',$jsonArr['data']['WhiteData_v4'],$jsonArr['data']['WhiteData_v6']);

                $this->saveAnycastTo3wFile($fileName,$fileV6Name,'a+',$jsonArr['data']['AnyData_v4'],$jsonArr['data']['AnyData_v6']);
                $this->saveAnycastTo3wFile($whiteFileName,$whiteV6FileName,'a+',$jsonArr['data']['WhiteAnyData_v4'],$jsonArr['data']['WhiteAnyData_v6']);

                $resultArr['opCode']=true;
                $resultArr['msg']='Generate File Success';
            }catch (Exception $e) {
                $resultArr['opCode']=false;
                $resultArr['msg']='Generate File Fail';
                $resultArr['data']=$e->getMessage();
            }  

            return $resultArr;
        }else if($type=='soa'){
            try{
                list($fileName,$whiteFileName)=array_slice($fileNameList,0,2);
                $jsonArr=$this->returnSOAData();

                $this->saveToSoaFile($fileName,'w+',$jsonArr['data']['SoaData']);
                $this->saveToSoaFile($whiteFileName,'w+',$jsonArr['data']['WhiteSoaData']);

                $resultArr['opCode']=true;
                $resultArr['msg']='Generate File Success';
            }catch (Exception $e) {
                $resultArr['opCode']=false;
                $resultArr['msg']='Generate File Fail';
                $resultArr['data']=$e->getMessage();
            }  
        }else{
            $resultArr['opCode']=false;
            $resultArr['msg']='Unknow File to generate';
        }
        return $resultArr;
    }

    public function checkPage($data){
        $testResult="0";
        $ip_addr = $data['page_name_ip'];
        $port = $data['page_name_port'];
        $command = sprintf("curl %s:%s --retry 0 --connect-timeout 2 -m 1 -I --compressed",escapeshellcmd($ip_addr),escapeshellcmd($port));
        $res = shell_exec($command);
        if($res===null){
            $testResult="2";
        }else{
            $testResult="1";
        }
        return $testResult;
    }

    public function testPage(Request $request){
        $jsonArr=$this->returnWWWData();
        
        for ($i=0;$i<count($jsonArr['data']['Data_v4']);$i++){
            $jsonArr['data']['Data_v4'][$i]['status']=$this->checkPage($jsonArr['data']['Data_v4'][$i]);
        }

        for ($i=0;$i<count($jsonArr['data']['AnyData_v4']);$i++){
            $jsonArr['data']['AnyData_v4'][$i]['status']=$this->checkPage($jsonArr['data']['AnyData_v4'][$i]);
        }

        $jsonArr['opCode']=true;
        $jsonArr['msg']='Test WWW Pages Success';

        return $jsonArr;
    }

    public function getDnsResult($records, $property, $errorMessage){
        $result=[];
        if (empty($records)) {
            $testResult = $errorMessage;
        } else {
            foreach ($records as $record) {
                $result[] = $record->$property();
            }
            $testResult = $result;
        }
        return $testResult;
    }

    public function checkDNS($dns,$dns_role,$dns_domain,$ip_addr, $testType,$prefix,$testDomain,$domainGroup){
        $testResult = "";
        
        $dnsTypes = [
            'A' => DNS_A,
            'AAAA' => DNS_AAAA,
            'CNAME' => DNS_CNAME,
            'NS' => DNS_NS,
            'PTR' => DNS_PTR,
            'SOA' => DNS_SOA,
            'MX' => DNS_MX,
            'SRV' => DNS_SRV,
            'TXT' => DNS_TXT,
        ];
        
        $dnsType = $dnsTypes[$testType] ?? null;
        if($dnsType) {
            if (in_array($dns_role, ['根服务器', '权威服务器']) &&     //当根服务器、权威服务器角色时
            !(in_array($dnsType, [DNS_SOA, DNS_NS]) && in_array($testDomain,['.',$dns_domain])) &&   //当不是探测自己所管理的域名时
            !(in_array($testDomain,$domainGroup) && in_array($dns_domain,$domainGroup))  //或者不是同根群中的域名时
            ){
                $testResult = 'OK, Root server did not return'.$testType.' record';
            }else{
                try{
                    $testFullDomain = $prefix.$testDomain;
                    // var_dump($testFullDomain);
                    $records = $dns->useNameserver($ip_addr)->getRecords($testFullDomain, $dnsType);
                    // 根据不同的 $dnsType 做相应的处理
                    switch ($dnsType) {
                        case DNS_A:
                            // A 记录的处理逻辑
                            $testResult = $this->getDnsResult($records, 'ip', 'Error, no DNS_A record found');
                            break;
                        case DNS_AAAA:
                            // AAAA 记录的处理逻辑
                            $testResult = $this->getDnsResult($records, 'ipv6', 'Error, no DNS_AAAA record found');
                            break;
                        case DNS_CNAME:
                            // CNAME 记录的处理逻辑
                            $testResult = $this->getDnsResult($records, 'target', 'Error, no DNS_CNAME record found');
                            break;
                        case DNS_NS:
                            // DNS_NS 记录的处理逻辑
                            $testResult = $this->getDnsResult($records, 'target', 'Error, no DNS_NS record found');
                            break;
                        case DNS_SOA:
                            // DNS_SOA 记录的处理逻辑
                            $testResult = $this->getDnsResult($records, '__toString', 'Error, no DNS_SOA record found');
                            break;
                        case DNS_MX:
                            // DNS_MX 记录的处理逻辑
                            $testResult = $this->getDnsResult($records, '__toString', 'Error, no DNS_MX record found');
                            break;
                        case DNS_SRV:
                            // DNS_SRV 记录的处理逻辑
                            $testResult = $this->getDnsResult($records, '__toString', 'Error, no DNS_SRV record found');
                            break;
                        case DNS_TXT:
                            // DNS_TXT 记录的处理逻辑
                            $testResult = $this->getDnsResult($records, '__toString', 'Error, no DNS_TXT record found');
                            break;
                        case DNS_PTR:
                            // DNS_PTR 记录的处理逻辑
                            $testResult = $this->getDnsResult($records, '__toString', 'Error, no DNS_PTR record found');
                            break;
                        // 其他类型的处理逻辑
                        default:
                            // 默认处理逻辑
                            $testResult="Unkown DNS Type Given";
                            // break;
                        }
                }catch(\Spatie\Dns\Exceptions\CouldNotFetchDns $e){
                    $testResult='connection timed out; no servers could be reached';
                } 
            }
        }
        return $testResult;
    }

    private function processDNSData(&$data, $dns, $testType,$prefix, $testDomain, $start, $end, &$allTestResult,$domainGroup) {
        for ($i = $start; $i < $end; $i++) {
            $eachRecord = &$data[$i];
            $dns_role = $eachRecord['dns_role'];
            $is_monitor = $eachRecord['is_monitor'];
            $dns_domain = $eachRecord['dns_operator_en'];
            $ip_addr = $eachRecord['server_ip_public_v4'] ?? $eachRecord['server_ip_anycast_v4'];
    
            if ($is_monitor == "0") {
                $eachRecord['dns_result'] = "OK, In whitelist No need Test";
                continue; 
            }
    
            if (isset($allTestResult[$ip_addr])) {
                $eachRecord['dns_result'] = $allTestResult[$ip_addr];
            } else {
                $DNSResult = $this->checkDNS($dns, $dns_role, $dns_domain, $ip_addr, $testType,$prefix, $testDomain,$domainGroup);
                $eachRecord['dns_result'] = $DNSResult;
                $allTestResult[$ip_addr] = $DNSResult;
            }
        }
    }

    private function getEndIndex(&$array, $endIndex){
        return ($endIndex !== null) ? min($endIndex, count($array)) : count($array);
    }
    
    private function getDNSData(&$nodes, $dns, $testType, $prefix, $testDomain, $start, $endIndex, &$allTestResult, $domainGroup){
        $dataEnd = $this->getEndIndex($nodes, $endIndex);
        $this->processDNSData($nodes, $dns, $testType, $prefix, $testDomain, $start, $dataEnd, $allTestResult, $domainGroup);
        // return $nodes;
    }

    public function testDNS(Request $request, $endIndex = null){
        $dns = new Dns();
        $allTestResult = [];
        $start = 0; // 遍历的起始索引
        // $endIndex=4;
        $testDomain = $request->input('testDomain');
        $prefix = $request->input('prefix');
        $testType = $request->input('testType');
        $IPV4Nodes = $request->input('IPV4Nodes');
        $IPV4AnyNodes = $request->input('IPV4AnyNodes');

        $IPV4Flag=empty($IPV4Nodes);
        $IPV4AnyFlag=empty($IPV4AnyNodes);


        $domainGroup=$this->queryDomainGroup();

        if($IPV4Flag && $IPV4AnyFlag){
            $jsonArr = $this->queryDnsData($request->input('filterNameList'));
            $this->getDNSData($jsonArr['data']['Data_v4'], $dns, $testType, $prefix, $testDomain, $start, $endIndex, $allTestResult,$domainGroup);
            $this->getDNSData($jsonArr['data']['AnyData_v4'], $dns, $testType, $prefix, $testDomain, $start, $endIndex, $allTestResult,$domainGroup);
            // var_dump('测试全部');
        }else{
            $jsonArr['IPV4']=[];
            $jsonArr['IPV4Any']=[];
            if(!$IPV4Flag){
                $this->getDNSData($IPV4Nodes, $dns, $testType, $prefix, $testDomain, $start, $endIndex, $allTestResult,$domainGroup);
                // var_dump('测试部分');
                $jsonArr['IPV4'] =$IPV4Nodes;
            }

            if(!$IPV4AnyFlag){
                $this->getDNSData($IPV4AnyNodes, $dns, $testType, $prefix, $testDomain, $start, $endIndex, $allTestResult,$domainGroup);
                // var_dump('测试部分');
                $jsonArr['IPV4Any'] =$IPV4AnyNodes;
            }
        }
    
        $jsonArr['opCode'] = true;
        $jsonArr['msg'] = 'Test DNS Success';
    
        return $jsonArr;
    }
}