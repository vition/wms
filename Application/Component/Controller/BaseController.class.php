<?php
namespace Component\Controller;
// use Think\Cache\Driver\Redis;
/**
 * BaseController 控件基类
 *     公共控制文件
 * 
 * @author vition
 * @date 2017-11-17
 */

class BaseController extends \Common\Controller\BaseController{
    protected $selfDB="";
    /** 
     * @Author: vition 
     * @Date: 2018-01-14 22:16:12 
     * @Desc: 疯狂的入口 
     */    
    function __call($fun,$argu){
        $thisClass=get_class($this);
        $thisClass=explode("\\",$thisClass);
        $className=str_replace("Controller","",$thisClass[count($thisClass)-1]);
        $method=str_replace($className,"",$fun);
        if(method_exists(__CLASS__,$method)){
            return $this->$method($argu[0]);
        }
        return false;
    }
    /** 
     * @Author: vition 
     * @Date: 2018-04-01 12:50:02 
     * @Desc: 统一获取列表 
     */    
    function getList($parameter=[]){
        $res=$this->initRes();
        $where=$parameter['where']?$parameter['where']:true;
        $fields=$parameter['fields']?$parameter['fields']:true;
        $orderStr=$parameter['orderStr']?$parameter['orderStr']:null;
        $page=$parameter['page']?$parameter['page']:0;
        $pageNum=$parameter['pageSize']?$parameter['pageSize']:0;
        $groupBy=$parameter['groupBy']?$parameter['groupBy']:null;
        $classList=$this->selfDB->getList($where , $fields, $orderStr, $page, $pageNum, $groupBy);
        $count=$this->selfDB->countList($where);
        if($classList){
            return ['list'=>$classList,'count'=>$count];
        }
        return false;
    }
    /** 
     * @Author: vition 
     * @Date: 2018-04-01 12:50:15 
     * @Desc: 获取单一行 
     */    
    function getOne($parameter=[]){
        $res=$this->initRes();
        $where=$parameter['where']?$parameter['where']:true;
        $fields=$parameter['fields']?$parameter['fields']:true;
        $orderStr=$parameter['orderStr']?$parameter['orderStr']:null;
        $classList=$this->selfDB->getOne(['where'=>$where]);
        if($classList){
            return ['list'=>$classList];
        }
        return false;
    }
    /** 
     * @Author: vition 
     * @Date: 2018-04-01 12:50:26 
     * @Desc: 插入数据 
     */    
    function insert($parameter){
        $res=$this->initRes();
        $insertResult=$this->selfDB->insert($parameter);
        if($insertResult){
            $res->errCode=0;
            $res->error=getError(0);
            return $res;
        }
        $res->errCode=111;
        $res->error=getError(111);
        return $res;
    }
    /** 
     * @Author: vition 
     * @Date: 2018-04-01 12:50:33 
     * @Desc: 更新数据 
     */    
    function update($parameter){
        $res=$this->initRes();
        $insertResult=$this->selfDB->modify($parameter["where"],$parameter["data"]);
        if($insertResult){
            $res->errCode=0;
            $res->error=getError(0);
            return $res;
        }
        $res->errCode=114;
        $res->error=getError(114);
        return $res;
    }
    /** 
     * @Author: vition 
     * @Date: 2018-05-07 23:02:26 
     * @Desc: M方法 
     */    
    function M(){
        return $this->selfDB;
    }
}
