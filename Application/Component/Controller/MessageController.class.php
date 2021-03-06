<?php
namespace Component\Controller;
class MessageController extends BaseController{
    public function _initialize(){
        parent::_initialize();
        $this->selfDB = D('Component/Message');
    }
    function messageAdd($datas){
        $datas['add_time'] = time();
        $datas['from_user'] = session("userId");
        
        $datas['content'] = stripslashes(htmlspecialchars_decode(urldecode($datas['content'])));
        $relationArray = $datas['to_user'];
        $allNum = count($relationArray);
        $current = 0;
        $datas['group_id'] = $datas['from_user'].$datas['add_time'];
        
        $relationRes = A("Component/User")->getOne(["where"=>["userId"=>["IN",$relationArray]],"fields"=>"GROUP_CONCAT(userName) relation_uname,GROUP_CONCAT(qiye_id) qiye_ids"]);
        if($allNum>1){
            $datas['relation_user'] = implode(",",$datas['to_user']);
            $datas['relation_uname'] = $relationRes["list"]["relation_uname"];
        }


        $weinPush = [];
        
        // print_r($datas);exit;
        // echo stripslashes(htmlspecialchars_decode($datas['content']));exit;
        $this->startTrans();
        foreach ($relationArray as $to_user) {
            $datas['to_user'] = $to_user;
            // print_r($datas);
            $insertResult=$this->insert($datas);
            if(isset($insertResult->errCode) && $insertResult->errCode==0){
                $touser = A("Component/User")->getQiyeId($to_user);
                if(!empty($touser)){
                    $weinPush[$touser] = $insertResult->data;
                }
                $current++;
            }
        }
        
        // print_r($msgResult );
        if($allNum > 0 && $current==$allNum){
            if(!empty($weinPush)){
                foreach ($weinPush as $tUser => $id) {
                    $desc = "<div class=\"gray\">".date("Y年m月d日",$datas['add_time'])."</div> <div class=\"normal\">".utf8_substr($datas['content'],30)."</div>";
                    $url = C('qiye_url')."/Admin/Index/Main.html?action=Public/messageControl&id=".$id;
                    $msgResult = $this->QiyeCom-> textcard($tUser,$datas['title'],$desc,$url);
                }
            }
            
            $this->commit();
            return ['errCode'=>0,'error'=>getError(0)];
        }else{
            $this->rollback();
            return ['errCode'=>100,'error'=>getError(100)];
        }
        
    }
    /** 
     * @Author: vition 
     * @Date: 2018-09-06 20:07:33 
     * @Desc: 获取未读取邮件 
     */    
    function noRead($userId=NULL){
        $userId = $userId ? $userId : session("userId") ;
        return $this->M()->where(["to_user"=>$userId,"status"=>0])->count();
    }
    function newMesg($userId=NULL){
        $userId = $userId ? $userId : session("userId") ;
        $param = [
            'where' => ["to_user"=>$userId,"status"=>0],
            'orderStr'=>'add_time DESC',
            'pageSize'=>5,
            'joins' => [
                "LEFT JOIN (SELECT userId,userName user_name,avatar FROM v_user) u ON u.userId = from_user",
            ],
        ];
        return $this->getList($param)['list'];
    }
    /** 
     * @Author: vition 
     * @Date: 2018-09-06 20:08:09 
     * @Desc: 修改状态 
     */    
    function updateState($id,$status){
        return $this->update(["where"=>["id"=>$id],"data"=>["status"=>$status]]);
    }
}