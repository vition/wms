<?php
namespace Component\Controller;
// use Common\Controller\BaseController;
/** 
 * @Author: vition 
 * @Date: 2018-05-20 22:17:37 
 * @Desc: 基础数据组件 
 */
class BasicController extends BaseController{
    public function _initialize(){
        parent::_initialize();
        $this->selfDB = D('Component/Basic');
    }
}