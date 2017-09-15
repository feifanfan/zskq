<?php
namespace Manage\Controller;

class SetbonusController extends CommonController
{

    public function index()
    {	
    	$cate = M('bonus')->order('sort desc')->select();
    	foreach ($cate as $key => $value) {
    		//dump($value['oid']);
    		$officename = M('office')->where(array('id'=>$value['oid']))->find();
    		//dump($officename['name']);
    		$cate[$key]['officename'] = $officename['name'];
    	}
    	//dump($cate);
    	$this->assign('cate',$cate);
    	$this->display();
	}
	public function add(){
		$office = M('office')->order('sort')->select();
		$this->assign('office',$office);
		$this->display();
	}
	public function addPost(){
		$data = I('post.', '');
		//$data['name'] = 'DDD';
		$data['level'] = intval($data['level']);
		$data['oid'] = intval($data['oid']);
		$data['boundary'] = intval($data['boundary']);
		$data['downper'] = floatval($data['downper']);
		$data['upper'] = floatval($data['upper']);
		 //dump($data);
		if(empty($data['level'])){
			$this->error('请选择等级');
		}
		//dump($data['oid']);
		if(!isset($data['oid'])){
			$this->error('请选择科室');
		}
		$a = M('bonus')->where(array('level'=>$data['level'],'oid'=>$data['oid']))->find();
		$b = M('bonus')->where(array('name'=>$data['name']))->find();
		if($b){
			$this->error('该提成办法名称已被使用');
		}
		if($a){
			$this->error('该等级下的科室提成办法已经填写，请勿重复');
		}
		if($data['downper']>100||$data['downper']<0||$data['upper']>100||$data['upper']<1){
			$this->error('请正确填写比率');
		}
		$ret = M('bonus')->add($data);
		if(!$ret){
			$this->error('添加失败');
		}
		$this->redirect('Setbonus/index');
	}
	public function edit(){
		$data = I('get.', '');
        $id   = $data['id']   = intval($data['id']);
        $res = M('bonus')->where(array('id'=>$id))->find();
        //dump($res);
        if(!$res){
        	$this->error('医院信息不存在');
        }
        $office = M('office')->order('sort')->select();
		$this->assign('office',$office);
        $this->assign('data',$res);
        $this->display();
	}
	 public function editPost(){
    	$data = I('post.', '');
        $id   = $data['id']   = intval($data['id']);
        $data['name']      = trim($data['name']);
        $res = M('bonus')->where(array('id'=>$id))->save($data);
        $this->success('更新成功');
    }

    public function sort()
    {
        $sortlist = I('sortlist', array(), 'intval');

        foreach ($sortlist as $k => $v) {
            $data = array(
                'id'   => $k,
                'sort' => $v,
            );
            M('bonus')->save($data);
        }
       $this->redirect('Setbonus/index');
    }
    public function delbonus(){
    	$id = $_GET['id'];
    	$res = M('bonus')->where(array('id' =>$id))->find();
    	if(!$res){
    		$this->error('该信息不存在');
    	}
    	$ret = M('bonus')->where(array('id'=>$id))->delete();
    	if($ret){
    		$this->success('删除成功');
    	}else{
    		$this->error('删除失败');
    	}
    }
}
?>