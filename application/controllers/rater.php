<?php
/**
 * Created by PhpStorm.
 * User: yunlong
 * Date: 14-4-8
 * Time: 下午8:18
 */

class Rater extends Base {

    protected $fieldsArr = array(
        "id"=>array("name"=>"ID","readonly" => 1),
        "name"=>array("name"=>"名称","intro"=>"可以为空。显示用，记录评委真实姓名"),
        "username"=>array("name"=>"用户名","登录用"),
        "password"=>array("name"=>"密码"),
        "role"=>array("name"=>"角色","intro" =>'0：普通用户；1：管理员 2：超级管理员'),
        "contestAuth"=>array("name"=>"评分比赛id","intro" =>'管理员填0'),
        "description"=>array("name"=>"描述","type" =>"textarea","intro"=>"可以为空"),
    );

    public function __construct()
    {
        parent::__construct();

        $this->load->model('rater_model');
    }

    public function index()
    {
        $this->load->library('table');

        $contestId = $this->input->get('contestId');

        if($contestId === '0' || !empty($contestId)) {
            $raters = $this->rater_model->get_rater_by_contest($contestId);
        } else {
            $raters = $this->rater_model->get_rater();
        }
        foreach($raters as &$v) {
            $editUrl = base_url('/rater/edit/'.$v['id']);
            $delUrl = base_url('/rater/del/'.$v['id']);
//            $resetPasswordUrl = base_url('/rater/resetPassword/'.$v['id']);
//            unset($v['password']);
            $v['handle'] = string_to_html_a($editUrl,'编辑').' '.string_to_html_a($delUrl,'删除','onclick="return confirm(\'确认要删除该项吗？\')"');//.' '.string_to_html_a($resetPasswordUrl,'重置密码');
        }

        $this->table->set_template($this->table_tmpl);
        foreach($this->fieldsArr as $t){
            $tableHead[] = $t["name"];
        };
        $tableHead[] = "操作";
        $this->table->set_heading($tableHead);

        $data['ratersTable'] = empty($raters)?"":$this->table->generate($raters);

        $data['title'] = '浏览评委';
        $data['createUrl'] = base_url('/rater/edit').(empty($contestId)?'':'?contestId='.$contestId);
        $data['contestId'] = $contestId;
        $data['submitContestId'] =  base_url('/rater/index');

        $this->load->view('templates/header', $data);
        $this->parser->parse('rater/index', $data);
        $this->load->view('templates/footer');

//        $this->output->cache(10);
    }

    public function edit($id=0)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $raterInfo = array();
        if($id != 0) {
            $raterInfo = $this->rater_model->get_rater($id);
            $data['title'] = '修改一个评委';
            $data['submitUrl'] = base_url("/rater/edit/".$id);
        }else {
            $data['title'] = '创建一个评委';
            $data['submitUrl'] = base_url('/rater/edit');
        }

        $contestId = $this->input->get('contestId');
        if(!empty($contestId)) {
            $raterInfo["contestAuth"] = $contestId;
        }

        $data['formContent'] = get_edit_fields_table($this->fieldsArr,$raterInfo);

        $this->form_validation->set_rules('username', '用户名', 'required');
        $this->form_validation->set_rules('password', '密码', 'required');
        $this->form_validation->set_rules('role', '角色', 'required|integer|less_than[3]|greater_than[-1]');
        $this->form_validation->set_rules('contestAuth', '评分比赛id', 'required|is_natural_zero');

        if ($this->form_validation->run() === FALSE)
        {
            $this->parser->parse('templates/header', $data);
            $this->parser->parse('rater/edit',$data);
            $this->load->view('templates/footer');

        }
        else
        {
            $this->rater_model->set_rater();
            $this->index();
        }
    }

    public function del($id){
        $this->rater_model->del_rater($id);
        $this->index();

    }

    public function resetPassword($id) {
        $this->load->library('form_validation');

        $raterInfo = $this->rater_model->get_rater($id);
        $data['title'] = '重置用户密码';
        $data['username'] = $raterInfo['username'];
        $data['id'] = $id;
        $data['submitUrl'] = base_url('/rater/resetPassword/'.$id);

        $this->form_validation->set_rules('username', '用户名', 'required');
        $this->form_validation->set_rules('password', '密码', 'required');
        $this->form_validation->set_rules('password2', '重新输入密码', 'required|matches[password]');

        if ($this->form_validation->run() === FALSE)
        {
            $this->parser->parse('templates/header', $data);
            $this->parser->parse('rater/reset_password',$data);
            $this->load->view('templates/footer');
        }
        else
        {
            $this->rater_model->set_rater();
            redirect(base_url('/rater/index'));
        }
    }
}