<?php
/**
 * Created by PhpStorm.
 * User: yunlong
 * Date: 14-4-8
 * Time: 下午8:18
 */

class Team extends Base {

    protected $fieldsArr = array(
        "id"=>array("name"=>"ID","readonly" => 1),
        "contestId"=>array("name"=>"比赛id"),
        "name"=>array("name"=>"名称"),
        "teamDisplayId"=>array("name"=>"组队id，每场比赛内部的ID"),
        "description"=>array("name"=>"描述","type" =>"textarea","intro"=>"可以为空"),
        "appName"=>array("name"=>"作品名称","type" =>"textarea","intro"=>"可以为空"),
        "appDesc"=>array("name"=>"作品描述","type" =>"textarea","intro"=>"可以为空"),
    );

    public function __construct()
    {
        parent::__construct();

        $this->load->model('team_model');
    }

    public function index()
    {
        $this->load->library('table');

        $contestId = $this->input->get('contestId');

        if(empty($contestId)) {
            $teams = $this->team_model->get_team();
        } else {
            $teams = $this->team_model->get_team_by_contest($contestId);
        }

        foreach($teams as &$v) {
            $editUrl = base_url('/team/edit/'.$v['id']);
            $delUrl = base_url('/team/del/'.$v['id']);
            $v['handle'] = string_to_html_a($editUrl,'编辑').' '.string_to_html_a($delUrl,'删除','onclick="return confirm(\'确认要删除该项吗？\')"');
        }

        $this->table->set_template($this->table_tmpl);
        foreach($this->fieldsArr as $t){
            $tableHead[] = $t["name"];
        };
        $tableHead[] = "操作";
        $this->table->set_heading($tableHead);

        $data['teamsTable'] = empty($teams)?"":$this->table->generate($teams);

        $data['title'] = '浏览团队';
        $data['createUrl'] = base_url('/team/edit').(empty($contestId)?'':'?contestId='.$contestId);
        $data['contestId'] = $contestId;
        $data['submitContestId'] =  base_url('/team/index');

        $this->load->view('templates/header', $data);
        $this->parser->parse('team/index', $data);
        $this->load->view('templates/footer');

//        $this->output->cache(10);
    }

    public function edit($id=0)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $teamInfo = array();
        if($id != 0) {
            $teamInfo = $this->team_model->get_team($id);
            $data['title'] = '修改一个组对';
            $data['submitUrl'] = base_url("/team/edit/".$id);
        }else {
            $data['title'] = '创建一个组对';
            $data['submitUrl'] = base_url('/team/edit');
        }

        $contestId = $this->input->get('contestId');
        if(!empty($contestId)) {
            $teamInfo["contestId"] = $contestId;
        }

        $data['formContent'] = get_edit_fields_table($this->fieldsArr,$teamInfo);

        $this->form_validation->set_rules('contestId', '比赛id', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('name', '名称', 'required');
        $this->form_validation->set_rules('teamDisplayId', '组队id', 'required|is_natural_no_zero');

        if ($this->form_validation->run() === FALSE)
        {
            $this->parser->parse('templates/header', $data);
            $this->parser->parse('team/edit',$data);
            $this->load->view('templates/footer');

        }
        else
        {
            $this->team_model->set_team();
            $this->index();
        }
    }

    public function del($id){
        $this->team_model->del_team($id);
        $this->index();

    }
}