<?php
/**
 * Created by PhpStorm.
 * User: yunlong
 * Date: 14-4-8
 * Time: 下午8:18
 */

class Rate extends Base {

    protected $fieldsArr = array(
        "id"=>array("name"=>"ID","readonly" => 1),
        "name"=>array("name"=>"名称"),
        "detail"=>array("name"=>"评分细节","type" =>"textarea"),
        "subId"=>array("name"=>"评分子id","intro" => "此项评分的总分=此项评分+子项评分，适用于有额外加分项"),
        "weight"=>array("name"=>"权重", "intro" => "0-100之间 默认20,例如 20为权重20%"),
        "score"=>array("name"=>"分数", "intro" => "总分，默认100"),
    );

    public function __construct()
    {
        parent::__construct();

        $this->load->model('rate_model');

//        $this->output->enable_profiler(TRUE);
    }

    public function index()
    {
        $this->load->library('table');

        $rates = $this->rate_model->get_rate();
        foreach($rates as &$v) {
            $editUrl = base_url('/rate/edit/'.$v['id']);
            $delUrl = base_url('/rate/del/'.$v['id']);
            $v['handle'] = string_to_html_a($editUrl,'编辑').' '.string_to_html_a($delUrl,'删除','onclick="return confirm(\'确认要删除该项吗？\')"');
        }

        $this->table->set_template($this->table_tmpl);
        foreach($this->fieldsArr as $t){
            $tableHead[] = $t["name"];
        };
        $tableHead[] = "操作";
        $this->table->set_heading($tableHead);
        $data['ratesTable'] = empty($rates)?"":$this->table->generate($rates);

        $data['title'] = '浏览评分细则';
        $data['createUrl'] = base_url('/rate/edit');

        $this->load->view('templates/header', $data);
        $this->parser->parse('rate/index', $data);
        $this->load->view('templates/footer');

//        $this->output->cache(10);
    }

    public function edit($id=0)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $rateInfo = array();
        if($id != 0) {
            $rateInfo = $this->rate_model->get_rate($id);
            $data['title'] = '修改一个评分规则';
            $data['submitUrl'] = base_url("/rate/edit/".$id);
        }else {
            $data['title'] = '创建一个评分规则';
            $data['submitUrl'] = base_url('/rate/edit');
        }

        $data['formContent'] = get_edit_fields_table($this->fieldsArr,$rateInfo);

        $this->form_validation->set_rules('name', '名称', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $this->parser->parse('templates/header', $data);
            $this->parser->parse('rate/edit',$data);
            $this->load->view('templates/footer');

        }
        else
        {
            $this->rate_model->set_rate();
            $this->index();
        }
    }

    public function del($id){
        $this->rate_model->del_rate($id);
        $this->index();

    }
}