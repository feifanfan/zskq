<?php
/**
 *　                  oooooooooooo
 *
 *                  ooooooooooooooooo
 *                       o
 *                      o
 *                     o        o
 *                    oooooooooooo
 *
 *         ～～         ～～         　　～～
 *       ~~　　　　　~~　　　　　　　　~~
 * ~~～~~～~~　　　~~~～~~～~~～　　　~~~～~~～~~～
 * ·······              ~~XYHCMS~~            ·······
 * ·······  闲看庭前花开花落 漫随天外云卷云舒 ·······
 * ·············     www.xyhcms.com     ·············
 * ··················································
 * ··················································
 *
 * @Author: gosea <gosea199@gmail.com>
 * @Date:   2014-06-21 10:00:00
 * @Last Modified by:   gosea
 * @Last Modified time: 2016-06-21 12:31:45
 */
namespace Manage\Controller;

class MemberController extends CommonController
{

    public function index()
    {

        $keyword = I('keyword', '', 'htmlspecialchars,trim'); //关键字
        $where   = array('member.id' => array('gt', 0));
        $hid = (session('hid')>0)?(session('hid')):'';
        if (!empty($keyword)) {
            if (strpos($keyword, '@')) {
                $where['member.email'] = $keyword;
            } else {
                $where['member.nickname'] = $keyword;
            }
        }
        $hid = (session('hid')>0)?(session('hid')):'';
        //dump($hid);
        if($hid!=''){
            $where['member.hid'] = $hid;
        }
        $count = D('MemberView')->where($where)->count();

        $page           = new \Common\Lib\Page($count, 10);
        $page->rollPage = 7;
        $page->setConfig('theme', '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $limit = $page->firstRow . ',' . $page->listRows;
        $list  = D('MemberView')->nofield('password,encrypt')->where($where)->order('member.id desc')->limit($limit)->select();
        foreach ($list as $key => $value) {
            //dump($value['id']);
            $member = M('memberdetail')->where('userid='.$value['id'])->find();
            $list[$key]['realname'] = $member['realname'];
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
        $actionName = strtolower(CONTROLLER_NAME);
        if (IS_POST) {
            $this->addPost();
            exit();
        }
        $hid = (session('hid')>0)?(session('hid')):'';
        //dump($hid);
        if($hid==''){
            $hos = M('hospital')->select();
        }else{
            $hos = M('hospital')->where(array('id'=>$hid))->select();
        }

        $list = M('membergroup')->where(array('id' => array('gt', 1)))->select();
        $this->assign('hos',$hos);
        $this->assign('vlist', $list);
        $this->display();
    }

    //
    public function addPost()
    {
        $password = I('password', '');
        //M验证
        $validate = array(
            array('email', 'require', '用户名必须填写！'), // 内置正则验证邮箱
            array('groupid', 'require', '请选择会员组！'),
            array('password', 'require', '密码必须填写！'),
            array('email', '', '用户名已经存在！', 0, 'unique', 1), //使用这个是否存在，auto就不能自动完成
        );

        $db = M('member');
        if (!$db->validate($validate)->create()) {
            $this->error($db->getError());
        }

        $data             = I('post.');
        $passwordinfo     = I('password', '', 'get_password');
        $data['regtime']  = time();
        $data['password'] = $passwordinfo['password'];
        $data['encrypt']  = $passwordinfo['encrypt'];
        //dump($data);die;
        $data['hid'] = $data['hospital'];
       //dump($data);die;
        if ($id = $db->add($data)) {
            if($data['ishyg']!=null){
                $member['ishyg'] = 1;
            }else{
                $member['ishyg'] = 0;
            }
            if($data['isbd']!=null){
                $member['isbd']=1;
                $member['bd_salary']=$data['bd_salary'];
            }else{
                $member['isbd']=0;
                $member['bd_salary']=0;
            }
            $member['hid'] = $data['hid'];
            $member['userid'] = $id;
            $member['realname'] = $data['realname'];
            $member['base_salary'] = floatval($data['base_salary']);
            $member['insurance'] = floatval($data['insurance']);
            M('memberdetail')->add($member);
            $this->success('添加成功', U('Member/index'));
        } else {
            $this->error('添加失败');
        }
    }

    //编辑
    public function edit()
    {
        //当前控制器名称
        $id         = I('id', 0, 'intval');
        $actionName = strtolower(CONTROLLER_NAME);

        if (IS_POST) {
            $this->editPost();
            exit();
        }
        $vod = M('memberdetail')->where(array('userid'=>$id))->select();
        $list = M('membergroup')->where(array('id' => array('gt', 1)))->select();
        
        $vo   = M($actionName)->find($id);
        //dump($vod);
        $this->assign('vod',$vod);
        $this->assign('vlist', $list);
        $this->assign('vo', $vo);
        $this->display();
    }

    //修改文章处理
    public function editPost()
    {

        $data          = I('post.');
        $id            = $data['id']            = I('id', 0, 'intval');
        $data['email'] = trim($data['email']);

        if (empty($data['email'])) {
            $this->error('用户名必须填写！');
        }


        if (M('member')->where(array('email' => $data['email'], 'id' => array('neq', $id)))->find()) {
            $this->error('失败，用户名已经存在！');
        }
       // dump($data);die;
        $data['groupid'] = I('groupid', 0, 'intval');
        $data['islock']  = I('islock', 0, 'intval');

        if (!empty($data['password'])) {
            $passwordinfo     = I('password', '', 'get_password');
            $data['password'] = $passwordinfo['password'];
            $data['encrypt']  = $passwordinfo['encrypt'];
            //dump($data);die;
        } else {
            unset($data['password']); //删除密码，防止被添加
        }
        
        if (false !== M('member')->save($data)) {
            if($data['ishyg']=='on'){
                $data['ishyg']=1;
            }
            if($data['isbd']=='on'){
                $data['isbd'] =1;
                if($data['bd_salary']){
                    $data['bd_salary'] = (float)$data['bd_salary'];

                }else{
                    $data['bd_salary']=0;
                }
            }
           
           $ret =  M('memberdetail')->where(array('userid'=>$data['id']))->save($data);
            if($ret){
            $this->success('修改成功', U('Member/index'));
            }
        } else {

            $this->error('修改失败');
        }

    }

    //用户资料
    public function person()
    {
        //当前控制器名称
        $id         = I('id', 0, 'intval');
        $actionName = strtolower(CONTROLLER_NAME);
        if (empty($id)) {
            $this->error('参数错误');
        }

        if (IS_POST) {
            $data['realname'] = I('realname', '', 'htmlspecialchars,trim');
            $data['birthday'] = I('birthday', '0000-00-00');
            $data['sex']      = I('sex', 0, 'intval');
            $data['address']  = I('address', '');
            $data['tel']      = I('tel', '');
            $data['mobile']   = I('mobile', '');
            $data['qq']       = I('qq', '');
            $data['maxim']    = I('maxim', '');

            $data['userid']     = $id;
            $data['updatetime'] = time();
            $new                = I('new', 0, 'intval');
            if (empty($data['realname'])) {
                $this->error('请输入姓名！');
            }

            $result = true;
            if ($new) {
                $result = M('memberdetail')->add($data);
            } else {
                $result = M('memberdetail')->save($data);
            }

            if (false !== $result) {
                $this->success('修改用户资料成功', U('Member/index'));
            } else {

                $this->error('修改用户资料失败');
            }
            exit();
        }

        $userdetail = M('memberdetail')->where(array('userid' => $id))->find();
        if (!$userdetail) {
            $userdetail = array(
                'userid'   => $id,
                'realname' => '',
                'sex'      => 0,
                'birthday' => '1990-1-1',
                'address'  => '',
                'tel'      => '',
                'mobile'   => '',
                'qq'       => '',
                'maxim'    => '',
            );
            $userdetail['new'] = 1;
        } else {
            $userdetail['new'] = 0;
        }

        $this->assign('vo', $userdetail);
        $this->assign('type', '用户基本资料');
        $this->display();
    }

    //彻底删除
    public function del()
    {

        $id        = I('id', 0, 'intval');
        $batchFlag = I('get.batchFlag', 0, 'intval');
        //批量删除
        if ($batchFlag) {
            $this->delBatch();
            return;
        }

        if (M('member')->delete($id)) {
            M('memberdetail')->delete($id);
            $this->success('彻底删除成功', U('Member/index'));
        } else {
            $this->error('彻底删除失败');
        }
    }

    //批量彻底删除
    public function delBatch()
    {

        $idArr = I('key', 0, 'intval');
        if (!is_array($idArr)) {
            $this->error('请选择要彻底删除的项');
        }
        $where = array('id' => array('in', $idArr));

        if (M('member')->where($where)->delete()) {
            M('memberdetail')->where(array('userid' => array('in', $idArr)))->delete();
            $this->success('彻底删除成功', U('Member/index'));
        } else {
            $this->error('彻底删除失败');
        }
    }

}
