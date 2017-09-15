<?php
namespace Manage\Controller;
/**
* 
*/
class PaymentController extends CommonController
{
	
	public function index()
	{	
		$keyword = $_POST['keyword'];
		$hid = (session('hid')>0)?(session('hid')):'';
		//dump($hid);
		$whe['realname'] = array('like','%'.$keyword.'%');
		$nums = M('memberdetail')->where($whe)->select();
		$arr = array();
		foreach ($nums as $key => $value) {
			//dump($value['userid']);
			array_push($arr,$value['userid']);
		}
		
		if($hid==''){
			$map['sick_name'] = array('like','%'.$keyword.'%');
			$map['doctor'] = array('in',$arr);
			$map['_logic'] = 'OR';
			$payment = M('payment')->where($map)->select();
		}else{
			$map['sick_name'] = array('like','%'.$keyword.'%');
			$map['doctor'] = array('in',$arr);
			$map['_logic'] = 'OR';
			$mmap['_complex'] = $map;
			$mmap['hid'] = array('eq',$hid);
			$mmap['_logic'] = 'AND';
			$payment = M('payment')->where($mmap)->order('time desc')->select();
			}
		$payment['time'] = date('Y-m-d',$payment['time']);

		$count = count($payment)-1;
		$page           = new \Common\Lib\Page($count, 10);
        $page->rollPage = 7;
        $page->setConfig('theme', '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $limit = $page->firstRow . ',' . $page->listRows;
        //dump($hid);
        if($hid==''){
        $list  = M('payment')->order('time desc')->limit($limit)->select();
	    }else{
	    	$list  = M('payment')->where(array('hid'=>$hid))->order('time desc')->limit($limit)->select();
	    }
        foreach ($list as $key => $value) {
  			$id = $value['doctor'];
  			//dump($id);
  			$doctor = M('memberdetail')->where(array('userid'=>$id))->limit($limit)->find();
			
  			  $list[$key]['doctor'] = $doctor['realname'];
  			 // dump($list);
        }
		//dump($payment);
		$this->assign('page', $page->show());
		$this->assign('vlist',$list);
		$this->display();
	}
	public function add(){
		$hid = (session('hid')>0)?(session('hid')):-1;
		$material = M('material')->order('id desc')->select();
		$bonus = M('bonus')->order('sort desc')->select();//提成办法
		//选择医生
		if($hid>0){
			$doctor = M('memberdetail')->where(array('hid'=>$hid))->select();
		}else{
			$doctor = M('memberdetail')->select();
		}
		$paytype = M('paytype')->select();//付款方式
		//dump($doctor);die;
		$this->assign('material',$material);
		$this->assign('bonus',$bonus);
		$this->assign('doctor',$doctor);
		$this->assign('paytype',$paytype);
		$this->display();
	}
	public function addPost(){
		$data = I('post.');
		//dump($data);die;
		
		$count = (count($data)-13)/2;
		if($data['isdebt']==''||!array_key_exists('isdebt',$data)||empty($data['isdebt'])){
			$data['isdebt'] = 0;
		}
		//dump($count);//die;
		$number = array();
		$material =array();
		$paytype = array();
		$money = array();
		for ($i=0; $i <= $count; $i++) { 
			//$number = 'number'.$i;
			if(array_key_exists("pay_method".$i,$data)){
				array_push($paytype,$data['pay_method'.$i]);
				array_push($money,$data['money'.$i]);
		}
	}
		for ($i=0; $i <= $count; $i++) { 
			//$number = 'number'.$i;
			if(array_key_exists("number".$i,$data)){
				array_push($number,$data['number'.$i]);
				array_push($material,$data['material'.$i]);
		}
	}
		for($j=0;$j<count($number);$j++){
			$price = M('material')->where(array('id'=>$material[$j]))->getField('price');
			$id = M('material')->where(array('id'=>$material[$j]))->find();
			//dump($id['num']);
			$daa['num'] = $id['num'] - $number[$j];
			M('material')->where(array('id'=>$material[$j]))->save($daa);
			//dump($daa['num']);
			$data['material_cost'] += $number[$j]*$price;
		}
		//die;
		if($data['isintro']==0||$data['isintro']==-1){
			$data['isinhos'] = -1;
		}
		if($data['tuijianren']!=-1){
			$tuijian['uid'] = $data['tuijianren'];
			$tuijian['money'] = ($data['pay_fee']-$data['process_cost']-$data['material_cost'])*0.10;//推荐人所得奖金
			$tuijian['time'] = strtotime(I('time', '0000-00-00'));
			M('tuijian')->add($tuijian);
		}
		$data['number'] = implode(',', $number);
		$data['material'] = implode(',',$material);
		$data['pay_method'] = implode(',',$paytype);
		$data['money'] = implode(',',$money);
		//dump($data['money']);die;
		// dump($number);
		// dump($material);
		//dump($data);
		//die;
		if(strlen($data['sickphone'])!=11){
			$this->error('手机号填写错误');
		}
		if($data['bonus_type']==-1){
			$this->error('请选择提成办法');
		}
		$data['sick_phone'] = $data['sickphone'];
		$data['sick_name'] = trim($data['sickname']);
		//$data['time'] = ;
		$data['time'] = strtotime(I('time', '0000-00-00'));
		$data['remark'] = trim($data['remark']);
		$data['hid'] = session('hid');
		//var_dump($data['time']);die;
		//$data['pay_method'] = 
		$ret = M('payment')->add($data);
		if($ret){
			// for($j=0;$j<count($number);$j++){
			// $material = M('material')->where(array('id'=>$material[$j]))->find();
			// $id = $material['id'];
			// $da['num']=$material['num']-$number[$j];
			// //dump($id.'+'.$da['num']);die;
			// $ress = M('material')->where(array('id'=>$id))->save($da);
			// }
			//if($ress){
				$this->success('已添加');
			//}else{
			//	$this->error('库存更新失败，请手动更新');
			//}
		}else{
			$this->error('添加失败');
		}
	}
	public function edit(){
		$id = $_GET['id'];
		$material = M('material')->order('id desc')->select();
		$bonus = M('bonus')->order('sort desc')->select();
		$list=M('payment')->where(array('id'=>$id))->find();
		$list['time'] = date('Y-m-d',$list['time']);
		$bonus = M('bonus')->order('sort desc')->select();//提成办法
		$paytype = M('paytype')->select();//付款方式
		if($hid>0){
			$doctor = M('memberdetail')->where(array('hid'=>$hid))->select();
		}else{
			$doctor = M('memberdetail')->select();
		}
		if(strpos($list['pay_method'],',')){
			$pay_method = explode(',',$list['pay_method']);
			$money = explode(',',$list['money']);
		}else{
			$pay_method[0] = $list['pay_method'];
			$money[0] = $lis['money'];
		}
		if(strpos($list['material'],',')){
			$materialsel = explode(',',$list['material']);
			$materialnum = explode(',',$list['number']);
		}else{
			$materialsel[0] = $list['material'];
			$materialnum[0] = $list['number'];
		}
		
		foreach ($materialsel as $key => $value) {
			//材料及数量
			$a[$key] = M('material')->field('title')->where(array('id'=>$value))->find();
			$a[$key]['num'] = $materialnum[$key];
			$a[$key]['id'] = $value;
			$a[$key]['js'] = $key;
		}
		foreach ($pay_method as $key => $value) {  
		//付款方式和每种方式的金额
			$b[$key] = M('paytype')->field('id,name')->where(array('id'=>$value))->find();
			//$b[$key]['id'] = $value;
			$b[$key]['money'] = $money[$key];
			$b[$key]['js'] = $key;
		}
		
		//dump($materialnum);die;
		$this->assign('list',$list);
		$this->assign('a',$a);
		$this->assign('an',count($a));
		$this->assign('b',$b);
		$this->assign('bn',count($b));
		$this->assign('id',$id);
		$this->assign('bonus',$bonus);
		$this->assign('doctor',$doctor);
		$this->assign('paytype',$paytype);
		$this->assign('material',$material);
		$this->display();
	}
	public function editpost(){
		$id = $_POST['id'];
		//dump($id);
		$data = I('post.');
		$count = (count($data)-13)/2;
		if($data['isdebt']==''||!array_key_exists('isdebt',$data)||empty($data['isdebt'])){
			$data['isdebt'] = 0;
		}
		//dump($count);//die;
		$number = array();
		$material =array();
		$paytype = array();
		$money = array();
		for ($i=0; $i <= $count; $i++) { 
			//$number = 'number'.$i;
			if(array_key_exists("pay_method".$i,$data)){
				array_push($paytype,$data['pay_method'.$i]);
				array_push($money,$data['money'.$i]);
		}
	}
		for ($i=0; $i <= $count; $i++) { 
			//$number = 'number'.$i;
			if(array_key_exists("number".$i,$data)){
				array_push($number,$data['number'.$i]);
				array_push($material,$data['material'.$i]);
		}
	}
		for($j=0;$j<count($number);$j++){
			$price = M('material')->where(array('id'=>$material[$j]))->getField('price');
			$id = M('material')->where(array('id'=>$material[$j]))->find();
			//dump($id['num']);
			$daa['num'] = $id['num'] - $number[$j];
			M('material')->where(array('id'=>$material[$j]))->save($daa);
			//dump($daa['num']);
			$data['material_cost'] += $number[$j]*$price;
		}
		//die;
		if($data['isintro']==0||$data['isintro']==-1){
			$data['isinhos'] = -1;
		}
		if($data['tuijianren']!=-1){
			$tuijian['uid'] = $data['tuijianren'];
			$tuijian['money'] = ($data['pay_fee']-$data['process_cost']-$data['material_cost'])*0.10;//推荐人所得奖金
			$tuijian['time'] = strtotime(I('time', '0000-00-00'));
			//M('tuijian')->add($tuijian);
		}
		$data['sickphone'] = $data['sick_phone'];
		unset($data['sick_phone']);
		$data['time'] = strtotime(I('time', '0000-00-00'));
		$data['number'] = implode(',', $number);
		$data['material'] = implode(',',$material);
		$data['pay_method'] = implode(',',$paytype);
		$data['money'] = implode(',',$money);
		unset($data['id']);
		
		$res = M('payment')->where(array('id'=>$_POST['id']))->save($data);
		//dump($id);dump($data);dump($res);die;
		if($res){
			$this->success('修改成功');
		}else{
			$this->error('修改失败');
		}
	}
	public function del(){
		$id = I('id');
		//dump($id);die;
		$ret = M('payment')->where(array('id'=>$id))->find();
		if(!$ret){
			$this->error('数据不存在');
		}else{
			$res = M('payment')->where(array('id'=>$id))->delete();
			if($res){
				$this->success('删除成功');
			}else{
				$this->error('删除失败');
			}
		}
	}
	public function addAjax(){
		$id = $_POST['id'];
		if(empty($id)){
			$data['info']='';
			$data['status']=0;
		}else{
		$ret = M('bonus')->where(array('id'=>$id))->find();
		$data['status'] = 1;
		$data['info']=$ret['boundary'].'元以下提成比率为'.$ret['downper'].'%，以上为'.$ret['upper'].'%';
	}
		$this->ajaxReturn($data);
	}
	public function debtAjax(){
		$doctor_id = $_POST['id'];

		$ret = M('payment')->where(array('doctor'=>$doctor_id,'isdebt'=>1))->select();
		if($ret){
		$data['info'] = '该医生还有患者费用欠款为交清';
		$data['status'] = 1;
		}else{
		$data['info'] = '';
		$data['status'] = 0;
		}
		$this->ajaxReturn($data);
	}
	public function stamp(){
		$id = $_GET['id'];
		//var_dump($this->cny('180.13'));
		$res = M('payment')->where(array('id'=>$id))->find();
		$res['bigmoney'] = $this->cny($res['pay_fee']);
		if(substr($res['bigmoney'],-3)=='圆'){
			$res['bigmoney'] = $res['bigmoney'].'整';
		}
		$doctor_name = M('memberdetail')->where(array('userid'=>$res['doctor']))->find();
		$res['doctor_name'] = $doctor_name['realname'];
		//var_dump($res);//die;
		if(strpos($res['pay_method'],',')){
			$pay_method = explode(',',$res['pay_method']);
			$money = explode(',',$res['money']);
		}else{
			$pay_method[0] = $res['pay_method'];
			$money[0] = $res['money'];
		}
		foreach ($pay_method as $key => $value) {
			$pay[$key]['pay_method'] = $value;
			$pay[$key]['money'] = $money[$key];
			$result = M('paytype')->where(array('id'=>$value))->field('name')->find();
			$pay[$key]['pay_name'] = $result['name'];
		}
		//dump($pay);
		$this->assign('res',$res);
		$this->assign('pay',$pay);
		$this->assign();
		$this->display();
	}
	public function jfAjax(){
		$id = $_POST['id'];
		$data['isdebt'] = 0;
		$ret = M('payment')->where(array('id'=>$id))->save($data);
		if($ret){
			$data['info'] = '操作成功';
			$data['status'] = 1;
		}else{
			$data['info'] = '请不要重复操作';
			$data['status'] = 0;
		}
		$this->ajaxReturn($data);
	}

	function cny($ns) { 
    static $cnums=array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖"), 
        $cnyunits=array("圆","角","分"), 
        $grees=array("拾","佰","仟","万","拾","佰","仟","亿"); 
    list($ns1,$ns2)=explode(".",$ns,2); 
    $ns2=array_filter(array($ns2[1],$ns2[0])); 
    $ret=array_merge($ns2,array(implode("",$this->cny_map_unit(str_split($ns1),$grees)),"")); 
    $ret=implode("",array_reverse($this->cny_map_unit($ret,$cnyunits))); 
    return str_replace(array_keys($cnums),$cnums,$ret); 
	}
function cny_map_unit($list,$units) { 
    $ul=count($units); 
    $xs=array(); 
    foreach (array_reverse($list) as $x) { 
        $l=count($xs); 
        if ($x!="0" || !($l%4)) $n=($x=='0'?'':$x).($units[($l-1)%$ul]); 
        else $n=is_numeric($xs[0][0])?$x:''; 
        array_unshift($xs,$n); 
    } 
    return $xs; 
}
}
?>