<?php

namespace Manage\Controller;

class AllowanceController extends CommonController
{

    public function index()
    {

        $keyword = I('keyword', '', 'htmlspecialchars,trim'); //关键字

        $where   = array('userid' => array('gt', 0));


        $hid = (session('hid')>0)?(session('hid')):'';
		//dump($hid);
		if($hid!=''){
			$where['hid'] = $hid;
		}

        $count = M('memberdetail')->where($where)->count();

        $page           = new \Common\Lib\Page($count, 10);
        $page->rollPage = 7;
        $page->setConfig('theme', '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $limit = $page->firstRow . ',' . $page->listRows;
        $list  = M('memberdetail')->where($where)->order('userid desc')->limit($limit)->select();
        foreach ($list as $key => $value) {
            //dump($value['id']);
            $member = M('memberdetail')->where('userid='.$value['userid'])->find();
            $list[$key]['realname'] = $member['realname'];
            $allowance = M('allowance')->where('userid='.$value['userid'])->order('time desc')->limit(1)->select();
            $list[$key]['num'] = $allowance[0]['num'];
            $list[$key]['isall'] = $allowance[0]['isall'];
            $list[$key]['time'] = $allowance[0]['time'];
            //dump($allowance);
        }
        //dump($list);
        $this->assign('page', $page->show());
        $this->assign('vlist', $list);
        $this->assign('keyword', $keyword);
        $this->assign('type', '会员列表');
        $this->display();
    }
    //添加
    public function add()
    {
        //当前控制器名称
    	$batchFlag = I('get.batchFlag', 0, 'intval');
    	$idArr = I('key', 0, 'intval');
    	//dump(I());die;
    	foreach ($idArr as $key => $value) {
    		$data['userid'] = $value;
    		$num = ('num_'.$value);
    		$isall = ('isall_'.$value);
    		//dump(I(''.$num);
    		if(I(''.$isall)!=null){
    			$data['isall'] = 1;
    		}else{
    			$data['isall'] = 0;
    		}
    		$data['num'] = I(''.$num);
    		$data['time'] = time();
    		//dump($data);
    		M('Allowance')->add($data);
    	}
    	$this->success('添加成功');
    }

    public function addhistory(){
         $keyword = I('keyword', '', 'htmlspecialchars,trim');
         $where['realname'] = $keyword;
         $ret = M('memberdetail')->where($where)->getField('userid');
         if($ret){
            $map['userid'] = $ret;
         }else{
            unset($map['userid']);
         }
         //dump($map);
        $list = M('allowance')->where($map)->order('id desc')->select();
        foreach($list as $key=>$value){
            //dump($value['userid']);
            $list[$key]['realname'] = M('memberdetail')->where(array('userid'=>$value['userid']))->getField('realname');
        }
        $this->assign('list',$list);
        $this->display();
    }    

}
