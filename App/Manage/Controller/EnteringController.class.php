<?php
namespace Manage\Controller;
class EnteringController extends CommonController{
	public function enhospital(){
		$hospital = M('hospital')->order('sort desc')->select();
		$this->assign('cate',$hospital);
		$this->display();
	}
	public function add(){
		$this->display();
	}
	public function addPost(){
		$data = I('post.', '');
		$data['name']      = trim($data['name']);
		$data['sort'] = intval($data['sort']);
		$data['id']='';
		//var_dump($data);die;
		if(empty($data['name'])){
			$this->error('医院名称不能为空');
		}
		$id = M('hospital')->add($data);
		if($id){
			$this->success('添加成功',U('Entering/add'));
		}else{
			$this->error('添加失败');
		}
	}
	    //批量更新排序
    public function sort()
    {
        $sortlist = I('sortlist', array(), 'intval');

        foreach ($sortlist as $k => $v) {
            $data = array(
                'id'   => $k,
                'sort' => $v,
            );
            M('hospital')->save($data);
        }
       $this->redirect('Entering/enhospital');
    }
    public function edit(){
        $data = I('get.', '');
        $id   = $data['id']   = intval($data['id']);
        $res = M('hospital')->where(array('id'=>$id))->find();
        //dump($res);
        if(!$res){
        	$this->error('医院信息不存在');
        }
        $this->assign('data',$res);
        $this->display();
    }
    public function editPost(){
    	$data = I('post.', '');
        $id   = $data['id']   = intval($data['id']);
        $data['name']      = trim($data['name']);
        $res = M('hospital')->where(array('id'=>$id))->save($data);
        $this->success('更新成功');
    }
    public function delhos(){
    	$data = I('get.','');
    	$id   = $data['id']   = intval($data['id']);
    	// dump($data);
    	$res = M('hospital')->where(array('id'=>$id))->delete();
    	if(!res){
    		$this->error('删除失败');
    	}
    	$this->redirect('Entering/enhospital');
    }

   public function enoffice(){
   		$office = M('office')->order('sort desc')->select();
		$this->assign('cate',$office);
		$this->display();
   }
   public function addoffice(){
   	$this->display();
   }
   	public function addofficePost(){
		$data = I('post.', '');
		$data['name']      = trim($data['name']);
		$data['sort'] = intval($data['sort']);
		//$data['id']='';
		//var_dump($data);die;
		if(empty($data['name'])){
			$this->error('科室名称不能为空');
		}
		$res = M('office')->where(array('name'=>$data['name']))->find();
       // dump($res);die;
		if($res!=null){
			$this->error('该科室已经添加');
		}
        //dump($data);die;
		$id = M('office')->add($data);
        // dump(M('office')->_sql());die;
		if($id){
			$this->success('添加成功',U('Entering/enoffice'));
		}else{
			$this->error('添加失败');
		}
	}
	public function sortoffice(){
		$sortlist = I('sortlist', array(), 'intval');

        foreach ($sortlist as $k => $v) {
            $data = array(
                'id'   => $k,
                'sort' => $v,
            );
            M('office')->save($data);
        }
       $this->redirect('Entering/enoffice');
	}
	public function deloffice(){
		$data = I('get.','');
    	$id   = $data['id']   = intval($data['id']);
    	// dump($data);
    	$res = M('office')->where(array('id'=>$id))->delete();
    	if(!res){
    		$this->error('删除失败');
    	}
    	$this->redirect('Entering/enoffice');
	}
	public function editoffice(){
		$data = I('get.', '');
        $id   = $data['id']   = intval($data['id']);
        $res = M('office')->where(array('id'=>$id))->find();
        //dump($res);
        if(!$res){
        	$this->error('医院信息不存在');
        }
        $this->assign('data',$res);
        $this->display();
	}
	public function editofficePost(){
    	$data = I('post.', '');
        $id   = $data['id']   = intval($data['id']);
        $data['name']      = trim($data['name']);
        $res = M('office')->where(array('id'=>$id))->save($data);
        $this->success('更新成功');
    }
}
?>