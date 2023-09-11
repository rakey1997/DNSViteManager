<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Model\DebugInfoModel;

class DebugController extends Controller
{
    public function getDebugInfo(){
        $jsonArr=array();

        #Debug信息
        $debugInfoModel=new DebugInfoModel();
        $debugInfo=$debugInfoModel->get()->toArray(); 

        $jsonArr['opCode']=true;
        $jsonArr['msg']='query success';
        $jsonArr['data']=array_values($debugInfo);

    return $jsonArr;
    }
}
