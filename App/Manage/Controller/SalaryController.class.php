<?php
namespace Manage\Controller;
/**
* 
*/
class SalaryController extends CommonController
{
	public function index()
	{
		$hid = session('hid');
		if(!empty($hid)){
			$map['hid']=$hid;
		}else{
			unset($map['hid']);
		}
		//dump($map['hid']);
		$data = I('post.','');
		//$map['start_date'] = $data['start_date'];
		//$map['stop_date'] = $data['stop_date'];
		$map['realname'] = array('like','%'.(trim($data['keyword'])).'%');
		$start_year=((int)substr($data['start_date'],0,4));
		$start_month=((int)substr($data['start_date'],5,2));
		$start_day=((int)substr($data['start_date'],8,2));
		$stop_year=((int)substr($data['stop_date'],0,4));
		$stop_month=((int)substr($data['stop_date'],5,2));
		$stop_day=((int)substr($data['stop_date'],8,2));
		//dump($stop_year);
		if ($start_year==0) {
			$start_date = mktime(0,0,0,1,1,1970);
		}else{
			$start_date = mktime(0,0,0,$start_month,$start_day,$start_year);
		}
		//dump($start_date);
		if($stop_year==0){
			$stop_date = time();
		}else{
		$stop_date = mktime(0,0,0,$stop_month,$stop_day,$stop_year);
		}
		//所有符合条件的医生
		$member = M('memberdetail')->where($map)->select();
		$array1 = array();
		foreach ($member as $key => $value) {
			//dump($value['userid']);
			array_push($array1, $value['userid']);
		}
		$bonus = M('bonus')->select();
		$array2 = array();
		foreach ($bonus as $key => $value) {
			array_push($array2,$value['id']);
		}
		//dump(date('Y-m-d',$start_date));
		//dump(date('Y-m-d',$stop_date));
		foreach($array1 as $val){
			foreach($array2 as $v){
				//$paymet_map['doctor']=$val;
				//$payment_map['bonus_type'] = $v;
				//$payment_map['time'] = array('gt',$start_date);
				//$payment_map['time'] = array('lt',$stop_date);
				//$bonus = M('payment')->where($payment_map)->select();
				$sql = "select * from xyh_payment where doctor=$val and bonus_type= $v and time >= $start_date and time <= $stop_date";
				$bonus = M()->query($sql);
				//dump($bonus);
				$tmp[$val][$v] = $bonus;
			}
		}

		$pay_fee = array();
		foreach ($tmp as $key => $value) {   //$key  医生id
			//dump($key);
			
			foreach ($value as $k => $v) {   //$k    提成办法id
				$pay_fee[$key]['realname'] = M('memberdetail')->where(array('userid'=>$key))->getField('realname');
				$num = count($v);
				for ($i=0; $i < $num; $i++) { 
					//dump($k.'+'.$tmp[$key][$k][$i]['pay_fee']);
					//$pay_fee[$key]['bonus_id'] = $k;
					$pay_fee[$key][$k]['bonus'] = M('bonus')->where(array('id'=>$k))->getField('name');

					if($tmp[$key][$k][$i]['isintro']==1){
						if($tmp[$key][$k][$i]['isinhos']==0){
							$tmp[$key][$k][$i]['isin_fee'] = $tmp[$key][$k][$i]['pay_fee']*0.10;
							//dump(floatval($tmp[$key][$k][$i]['pay_fee']*0.1));
						}elseif($tmp[$key][$k][$i]['isinhos']==1){
							$tmp[$key][$k][$i]['isin_fee'] = ($tmp[$key][$k][$i]['pay_fee']-$tmp[$key][$k][$i]['process_cost']-$tmp[$key][$k][$i]['material_cost'])*0.1;
						}
					}else{
						$tmp[$key][$k][$i]['isin_fee'] = 0;
					}
					//dump($tmp[$key][$k][$i]['isin_fee']);
					$pay_fee[$key][$k]['pay_fee'] +=$tmp[$key][$k][$i]['pay_fee'];//总费用
					$pay_fee[$key][$k]['process_cost'] +=$tmp[$key][$k][$i]['process_cost'];
					$pay_fee[$key][$k]['material_cost'] +=$tmp[$key][$k][$i]['material_cost'];
					$pay_fee[$key][$k]['over'] = $pay_fee[$key][$k]['pay_fee']-$pay_fee[$key][$k]['process_cost']-$pay_fee[$key][$k]['material_cost']-$tmp[$key][$k][$i]['isin_fee'];
				}
				$boundary = M('bonus')->where(array('id'=>$k))->getField('boundary');
				$downper = M('bonus')->where(array('id'=>$k))->getField('downper');
				$upper = M('bonus')->where(array('id'=>$k))->getField('upper');
				if($pay_fee[$key][$k]['over']>$boundary){
					$pay_fee[$key][$k]['sal'] = ($pay_fee[$key][$k]['over']-$boundary)*$upper*0.01+$boundary*$downper*0.01;
				}elseif ($pay_fee[$key][$k]['over']<=$boundary) {
					$pay_fee[$key][$k]['sal'] = $pay_fee[$key][$k]['over']*$downper*0.01;
				}
				$pay_fee[$key]['sal'] += $pay_fee[$key][$k]['sal'];

			}
			$isall = M('allowance')->where(array('userid'=>$key))->order('time desc')->limit(1)->getField('isall');
			if($isall==1){			//全勤奖
				$pay_fee[$key]['isall'] = 100;
			}else{
				$pay_fee[$key]['isall'] = 0;
			}
			//卫生费
			$ishyg = M('memberdetail')->where(array('userid'=>$key))->getField('ishyg');
			if($ishyg==1){
				$pay_fee[$key]['ishyg'] = 60;	
			}else{
				$pay_fee[$key]['ishyg'] = 0;
			}

			//dump(time());die;
			// $pay_fee['tuijian'] = '';
			// //推荐奖
			$pay_fee[$key]['tuijian'] = 0;
			//$tjsql = 'select * from xyh_tuijian where  uid= '.$key.' and time>='.$start_date.' and time <= '.$stop_date;
			$tjsql['time'] = array('egt',$start_date);
			$tjsql['time'] = array('elt',$stop_date);
			$tjsql['uid'] = array('eq',$key);
			$tuijian = M('tuijian')->where($tjsql)->select();
			//dump($tuijian);
			 //$tuijian = M()->query($tjsql);
			 //推荐奖
			foreach ($tuijian as $kk => $vv) {
				$pay_fee[$key]['tuijian'] += $vv['money'];
			}
			//dump($pay_fee[$key]['tuijian']);
			
			//$pay_fee[$key]['tuijian'] = M('tuijian')->where()




			$pay_fee[$key]['allowance'] = M('allowance')->where(array('userid'=>$key))->order('time desc')->limit(1)->getField('num')*5;//餐补
			$pay_fee[$key]['base_sal'] = M('memberdetail')->where(array('userid'=>$key))->getField('base_salary');//基本工资
			$pay_fee[$key]['insurance'] = M('memberdetail')->where(array('userid'=>$key))->getField('insurance');//保险费
			$pay_fee[$key]['zsal'] = $pay_fee[$key]['sal']+$pay_fee[$key]['base_sal']+$pay_fee[$key]['allowance']+$pay_fee[$key]['isall'] + $pay_fee[$key]['insurance'] - $pay_fee[$key]['ishyg'] + $pay_fee[$key]['tuijian'];//总工资  = 提成 + 基本工资 + 餐补 + 全勤奖 +保险费 -卫生费 + 推荐奖
			$pay_fee[$key]['isbd'] = M('memberdetail')->where(array('userid'=>$key))->getField('isbd');//是否保底
			if($pay_fee[$key]['isbd']==1){
				$pay_fee[$key]['bd_salary'] = M('memberdetail')->where(array('userid'=>$key))->getField('bd_salary');
				if($pay_fee[$key]['zsal']>$pay_fee[$key]['bd_salary']){
					$pay_fee[$key]['ssal'] = $pay_fee[$key]['zsal'];
				}else{
					$pay_fee[$key]['ssal'] = $pay_fee[$key]['bd_salary'].'<span style="color:red">(保底)</span>';
				}
			}else{
				$pay_fee[$key]['ssal'] = $pay_fee[$key]['zsal'];
			}
			$pay_fee[$key]['id']=$key;
		}
		//die;
		
		$this->assign('pay_fee',$pay_fee);
		$this->assign('member',$member);
		$this->display();
	}
	public function person(){
		$id = $_GET['id'];
		$map['doctor'] = $id;
		$this->assign('id',$id);
		$bonus_type = M('bonus')->select();

		$data = I('post.','');
		//$map['start_date'] = $data['start_date'];
		//$map['stop_date'] = $data['stop_date'];
		$start_year=((int)substr($data['start_date'],0,4));
		$start_month=((int)substr($data['start_date'],5,2));
		$start_day=((int)substr($data['start_date'],8,2));
		$stop_year=((int)substr($data['stop_date'],0,4));
		$stop_month=((int)substr($data['stop_date'],5,2));
		$stop_day=((int)substr($data['stop_date'],8,2));
		//dump($stop_year);
		$start_date = mktime(0,0,0,$start_month,$start_day,$start_year);
		if($stop_year==0){
			$stop_date= time();
		}else{
		$stop_date = mktime(0,0,0,$stop_month,$stop_day,$stop_year);
		}
		$map['time'] = array(array('egt',$start_date),array('elt',$stop_date),'and'); 
		
		$payment_detail = M('payment')->where($map)->select();
		$pay_fee = array();
		foreach ($payment_detail as $key => $value) {
			$bonus = M('bonus')->where(array('id'=>$value['id']))->select();
		}
		
		$this->assign('payment_detail',$payment_detail);
		$this->assign('bonus_type',$bonus_type);
		$this->display();
	}
	public function fjw(){
		$map['hid'] = 1;
		$data = M('memberdetail')->where($map)->getField('userid', true);
		// dump($data); die;
		$type = M('bonus')->getField('id', true);
		// dump($type); die;
		$tmp = array();
		foreach($data as $key=>$val){
			foreach($type as $k=>$v){
				$sql = "select * from xyh_payment where doctor=$val and bonus_type=$v";
				$bonus = M()->query($sql);
				$tmp[$val][$v] = $bonus;
			}
		}
		$pay_fee = array();
		foreach ($tmp as $key => $value) {
			//dump($key);
			foreach ($value as $k => $v) {
				$num = count($v);
				for ($i=0; $i < $num; $i++) { 
					//dump($k.'+'.$tmp[$key][$k][$i]['pay_fee']);
					$pay_fee[$key][$k]['pay_fee'] +=$tmp[$key][$k][$i]['pay_fee']; 
					$pay_fee[$key][$k]['process_cost'] +=$tmp[$key][$k][$i]['process_cost']; 
					$pay_fee[$key][$k]['material_cost'] +=$tmp[$key][$k][$i]['material_cost']; 
				}
			}
		}
		dump($pay_fee);

	}
public function exp(){
			$data = I('post.','');
		//$map['start_date'] = $data['start_date'];
		//$map['stop_date'] = $data['stop_date'];
		$map['realname'] = array('like','%'.(trim($data['keyword'])).'%');
		$start_year=((int)substr($data['start_date'],0,4));
		$start_month=((int)substr($data['start_date'],5,2));
		$start_day=((int)substr($data['start_date'],8,2));
		$stop_year=((int)substr($data['stop_date'],0,4));
		$stop_month=((int)substr($data['stop_date'],5,2));
		$stop_day=((int)substr($data['stop_date'],8,2));
		//dump($start_year);
		$date_map['start_date'] = mktime(0,0,0,$start_month,$start_day,$start_year);
		$date_map['stop_date'] = mktime(0,0,0,$stop_month,$stop_day,$stop_year);
		//所有符合条件的医生
		$member = M('memberdetail')->where($map)->select();
		$array1 = array();
		foreach ($member as $key => $value) {
			//dump($value['userid']);
			array_push($array1, $value['userid']);
		}
		$bonus = M('bonus')->select();
		$array2 = array();
		foreach ($bonus as $key => $value) {
			array_push($array2,$value['id']);
		}
		foreach($array1 as $val){
			foreach($array2 as $v){
				$sql = "select * from xyh_payment where doctor=$val and bonus_type=$v";
				$bonus = M()->query($sql);
				$tmp[$val][$v] = $bonus;
			}
		}
		$pay_fee = array();
		foreach ($tmp as $key => $value) {   //$key  医生id
			//dump($key);
			foreach ($value as $k => $v) {   //$k    提成办法id
				$num = count($v);
				for ($i=0; $i < $num; $i++) { 
					//dump($k.'+'.$tmp[$key][$k][$i]['pay_fee']);
					$pay_fee[$key]['realname'] = M('memberdetail')->where(array('userid'=>$key))->getField('realname');
					//$pay_fee[$key]['bonus_id'] = $k;
					$pay_fee[$key][$k]['bonus'] = M('bonus')->where(array('id'=>$k))->getField('name');
					$pay_fee[$key][$k]['pay_fee'] +=$tmp[$key][$k][$i]['pay_fee'];
					$pay_fee[$key][$k]['process_cost'] +=$tmp[$key][$k][$i]['process_cost'];
					$pay_fee[$key][$k]['material_cost'] +=$tmp[$key][$k][$i]['material_cost'];
				}
			}
		}
		dump($pay_fee);
		$this->assign('pay_fee',$pay_fee);
}
public function showajax(){
	$id = I('post.id','');
	$payment = M('payment')->where(array('id'=>$id))->find();
	$material = $payment['material'];
	$number = $payment['number']; 
	//dump($payment);
	$material_array = explode(',', $material);
	$number_array = explode(',',$number);
	$count = count($material_array);
	for ($i=0; $i <$count ; $i++) { 
		$material_name = M('material')->where(array('id'=>$material_array[$i]))->getField('title','');
		$text .=$material_name.'使用'.$number_array[$i].'个  ';  
	}
	//dump($material_array);
	$data['info']=$text;
	$data['status'] = $id;
	$this->ajaxReturn($data);
}

}
?>