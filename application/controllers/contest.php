<?php
/**
 * Created by PhpStorm.
 * User: yunlong
 * Date: 14-4-8
 * Time: 下午8:18
 */

class Contest extends Base {

    protected $fieldsArr = array(
        "id"=>array("name"=>"ID","readonly" => 1),
        "name"=>array("name"=>"名称"),
        "description"=>array("name"=>"描述","type" =>"textarea","intro"=>"可以为空"),
        "rateRule"=>array("name"=>"评分规则id","intro"=>"多个id用,分割,例如1,2,5"),
        "online"=>array("name"=>"是否上线", "intro" => "0:下线 1：上线"),
    );

    public function __construct()
    {
        parent::__construct();

        $this->load->model('contest_model');

//        $this->output->enable_profiler(TRUE);
    }

    public function index()
    {
        $this->load->library('table');

        $contests = $this->contest_model->get_contest();
        foreach($contests as &$v) {
            $editUrl = base_url('/contest/edit/'.$v['id']);
            $delUrl = base_url('/contest/del/'.$v['id']);
            $v['handle'] = string_to_html_a($editUrl,'编辑').' '.string_to_html_a($delUrl,'删除','onclick="return confirm(\'确认要删除该项吗？\')"');
        }

        $this->table->set_template($this->table_tmpl);
        foreach($this->fieldsArr as $t){
            $tableHead[] = $t["name"];
        };
        $tableHead[] = "操作";
        $this->table->set_heading($tableHead);
        $data['contestsTable'] = empty($contests)?"":$this->table->generate($contests);

        $data['title'] = '浏览比赛';
        $data['createUrl'] = base_url('/contest/edit');

        $this->load->view('templates/header', $data);
        $this->parser->parse('contest/index', $data);
        $this->load->view('templates/footer');

//        $this->output->cache(10);
    }

    public function edit($id=0)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $contestInfo = array();
        if($id != 0) {
            $contestInfo = $this->contest_model->get_contest($id);
            $data['title'] = '修改一个比赛';
            $data['submitUrl'] = base_url("/contest/edit/".$id);
        }else {
            $data['title'] = '创建一个比赛';
            $data['submitUrl'] = base_url('/contest/edit');
        }

        $data['formContent'] = get_edit_fields_table($this->fieldsArr,$contestInfo);

        $this->form_validation->set_rules('name', '名称', 'required');
        $this->form_validation->set_rules('online', '是否上线', 'required|integer|less_than[2]|greater_than[-1]');

        if ($this->form_validation->run() === FALSE)
        {
            $this->parser->parse('templates/header', $data);
            $this->parser->parse('contest/edit',$data);
            $this->load->view('templates/footer');

        }
        else
        {
            $this->contest_model->set_contest();
            $this->index();
        }
    }

    public function del($id){
        $this->contest_model->del_contest($id);
        $this->index();

    }
}