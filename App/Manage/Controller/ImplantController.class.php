<?php
/**
* 
*/
namespace Manage\Controller;
class ImplantController extends CommonController
{
	
	public function index(){

        $hid = (session('hid')>0)?(session('hid')):'';
        //dump($hid);
        if($hid!=''){
        	$where['hid'] = $hid;
        }
        $count = M('Implant')->where($where)->count();
        //dump($count);
        $page           = new \Common\Lib\Page($count, 10);
        $page->rollPage = 7;
        $page->setConfig('theme', '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $limit = $page->firstRow . ',' . $page->listRows;
        $list = M('Implant')->where($where)->select();
        
        foreach ($list as $key => $value) {
            //dump($value['id']);
            $member = M('memberdetail')->where('userid='.$value['userid'])->find();
            $list[$key]['realname'] = $member['realname'];
        }
        //dump($list);die;
        $this->assign('page', $page->show());
        $this->assign('vlist', $list);
        $this->assign('keyword', $keyword);
        $this->assign('type', '会员列表');
        $this->display();
	}
	public function add(){
		$hid = (session('hid')>0)?(session('hid')):'';
		if($hid!=''){
		$doctor = M('memberdetail')->where(array('hid'=>$hid))->select();
		}else{
			$doctor = M('memberdetail')->select();
		}
		$this->assign('doc',$doctor);
		$this->display();
	}
	public function addpost(){
		$data = I('post.');
		
		if($data['price']==''||(trim($data['sickname']))==''){
			$this->error('信息不得为空');
		}else{
		//dump($data);die;
			$db= M('Implant');
			$data['date'] = time();
			$data['hid'] = M('memberdetail')->where(array('userid'=>$data['userid']))->getField('hid');
			$ret = $db->add($data);
			if ($ret) {
				$this->success('添加成功');
			}else{
				$this->error('添加失败');
			}
		}
	}
	public function edit(){
		$id = $_GET['id'];
		$ret = M('Implant')->where(array('id'=>$id))->find();
		$ret['doctor'] = M('memberdetail')->where(array('userid'=>$ret['userid']))->getField('realname');
		$this->assign('list',$ret);
		$this->display();
	}
	public function editpost(){
		$id = $_POST['id'];
		$data = I('post.');
		$data['date'] = time();
		$ret = M('Implant')->where(array('id'=>$id))->save($data);
		//dump($data);die;
		if ($ret) {
				$this->success('修改成功');
			}else{
				$this->error('修改失败');
			}
	}
}
?>