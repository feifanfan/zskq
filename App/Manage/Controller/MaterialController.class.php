<?php

namespace Manage\Controller;
/**
* 
*/
class MaterialController extends CommonController
{
	
	public function index()
	{
		$material = M('material')->order('id desc')->select();
		$this->assign('material',$material);
		$this->display();
	}
	public function add(){
		$this->display();
	}
	public function addPost(){
		@header("Content-type:text/html;charset=utf-8");
		$data = I('post.','');
		//dump($data);die;
		$data['title'] = trim($data['title']);
		$data['price'] = floatval($data['price']);
		$data['brand'] = trim($data['brand']);
		$data['units'] = trim($data['units']);
		$data['publishtime'] = time();
		//dump($data);
		if(empty($data['title'])||empty($data['price'])||empty($data['brand'])||empty($data['units'])){
			$this->error('所有填写项均不得为空');
		}
		$ret = M('material')->add($data);
		if($ret){
			$this->success('保存成功');
		}else{
			$this->error('保存失败');
		}
	}
	public function edit(){
		$id = $_GET['id'];
		$material= M('material')->where(array('id'=>$id))->find();
		if(!$material){
			$this->error('没有找到该材料');
		}
		$this->assign('material',$material);
		$this->display();
	}
	public function del(){
		$id = $_GET['id'];
		if(!empty($id)){
			$ret = M('material')->where(array('id'=>$id))->delete();
			if($ret){
				$this->success('删除成功');
			}else{
				$this->error('删除失败');
			}
		}
	}
}