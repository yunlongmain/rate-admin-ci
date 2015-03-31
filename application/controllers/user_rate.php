<?php
/**
 * Created by PhpStorm.
 * User: yunlong
 * Date: 14-4-8
 * Time: 下午8:18
 */

class User_rate extends Base {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('user_rate_model');
        $this->load->model('rate_model');
        $this->load->model('rater_model');
        $this->load->model('contest_model');
        $this->load->model('team_model');
    }

    public function index()
    {
        $this->load->library('table');

        $data['title'] = '评分管理';
        $data['submitUrl'] = base_url('/user_rate/index');

        $data['tableContent'] ='';
        $data['resultTitle'] ='';
        $data['resultLength'] ='';

        $this->table->set_template($this->table_tmpl);

        if($this->input->get('contestId')) {
            if($this->input->get('act') == "stat") {
                $sqlQuery='select * from user_rate inner join (team) on (user_rate.teamId=team.id) where user_rate.contestId='.$this->input->get('contestId');
                $query = $this->db->query($sqlQuery);
                $rateArr =$query->result_array();
//                var_dump($rateArr);

                if(!empty($rateArr)) {
                    $contestInfo = $this->contest_model->get_contest($this->input->get('contestId'));

                    $rateRuleIdArr = explode(",",$contestInfo["rateRule"]);
                    $rateRuleDataOri = $this->rate_model->get_rate_in($rateRuleIdArr);
                    $rateRuleData = array_add_key($rateRuleDataOri,'id');

                    $tableHead = array("id","团队桌号","团队名字","app名字");
                    $tableContents = array();
                    $filterSubRateRule = array();//子项的已被加到它的归属项里，所以过滤子项
                    foreach($rateRuleData as $k => $v ) {
                        //过滤子项
                        if(!empty($filterSubRateRule[$k])) {
                            continue;
                        }

                        $subId = $v['subId'];
                        if($subId != 0) {
                            $filterSubRateRule[$subId] = $subId;
                        }

                        $tableHead[] = $v['name'];

                        foreach($rateArr as $fTeamId => $fTeamRateInfo) {

                            $rateDetailArr = json_decode($fTeamRateInfo['rateDetail'],true);

                            if(empty($tableContents[$fTeamId])) {
                                $tableContents[$fTeamId] = elements(array('teamId','teamDisplayId','name','appName'),$fTeamRateInfo);
                            }

                            $tableContents[$fTeamId][] = intval($rateDetailArr[$k]);
                            if($subId != 0) {
                                //-5 是因为 已有'teamId','teamDisplayId','name','appName' 和id从0开始
                                $tableContents[$fTeamId][count($tableContents[$fTeamId]) - 5] += intval($rateDetailArr[$subId]);
                            }
                        }

                    }

                    $rateRuleNum = count($rateRuleData) - count($filterSubRateRule);

                    $rateDataGroupByTeam = array();
                    foreach($tableContents as $rateInfo) {
                        $teamId = $rateInfo['teamId'];
                        if(empty($rateDataGroupByTeam[$teamId])) {
                            $rateDataGroupByTeam[$teamId] = $rateInfo;
//                            $rateDataGroupByTeam[$teamId]['count'] = 1;
                        }else {
                            for($i=0;$i<$rateRuleNum;$i++) {
                                $rateDataGroupByTeam[$teamId][$i] += $rateInfo[$i];
                            }
//                            $rateDataGroupByTeam[$teamId]['count']++;
                        }
                    }

                    //
//                    var_dump($rateRuleData);
                    $tableHead[] = "排名";
                    $i = 0;//与解析ratedetail时相同，所以按$rateRuleData中去掉子项后的raterule顺序对应$rateDataGroupByTeam 0，1，2...
                    foreach($rateRuleData as $k => $v ) {
                        //过滤子项
                        if(!empty($filterSubRateRule[$k])) {
                            continue;
                        }

                        $this->table->set_caption($v['name']."值排名");

                        foreach($rateDataGroupByTeam as $k=>$v)
                        {
                            $sort[$k] = $v[$i];
                        }
                        array_multisort($sort,SORT_DESC,$rateDataGroupByTeam);

                        $this->table->set_heading($tableHead);
                        $outputTable[] = $this->table->generate(array_add_rank($rateDataGroupByTeam));

                        $i++;

                    }
//                    var_dump($outputTable);

                    $data['tableContent'] = join('<br>',$outputTable);
                }

            }else{
                $rateArr = $this->user_rate_model->get_user_rate_by_contest($this->input->get('contestId'));
                if(!empty($rateArr)) {
                    $contestInfo = $this->contest_model->get_contest($this->input->get('contestId'));

                    $rateRuleIdArr = explode(",",$contestInfo["rateRule"]);
                    $rateRuleData = $this->rate_model->get_rate_in($rateRuleIdArr);
                    $rateRuleData = array_add_key($rateRuleData,'id');

                    $tableHead = array("评委id","团队id",'最终修改时间');
                    $tableContents = array();
                    foreach($rateRuleData as $k => $v ) {
                        $tableHead[] = $v['name'];

                        foreach($rateArr as $fTeamId => $fTeamRateInfo) {

                            $rateDetailArr = json_decode($fTeamRateInfo['rateDetail'],true);

                            if(empty($tableContents[$fTeamId])) {
                                $tableContents[$fTeamId] = elements(array('raterId','teamId','utime'),$fTeamRateInfo);
                            }

                            $tableContents[$fTeamId][] = $rateDetailArr[$k];
                        }

                    }

                    $this->table->set_heading($tableHead);

                    $data['tableContent'] = empty($tableContents)?"":$this->table->generate($tableContents);

                }
            }
            $data['resultTitle'] = '比赛Id='.$this->input->get('contestId');
        }

       if($this->input->get('raterId')) {
            $data['resultTitle'] = '评委Id='.$this->input->get('raterId');

            $rateArr = $this->user_rate_model->get_user_rate_row_arr(array('raterId'=>$this->input->get('raterId')),'teamId');
            $data['resultLength'] = '评委共评了 '.count($rateArr).' 队';

            if(!empty($rateArr)) {
                $raterInfo = $this->rater_model->get_rater($this->input->get('raterId'));
                $contestInfo = $this->contest_model->get_contest($raterInfo['contestAuth']);

                $rateRuleIdArr = explode(",",$contestInfo["rateRule"]);
                $rateRuleData = $this->rate_model->get_rate_in($rateRuleIdArr);
                $rateRuleData = array_add_key($rateRuleData,'id');

                $tableHead = array("团队id",'最终修改时间');
                $tableContents = array();
                foreach($rateRuleData as $k => $v ) {
                    $tableHead[] = $v['name'];

                    foreach($rateArr as $fTeamId => $fTeamRateInfo) {

                        $rateDetailArr = json_decode($fTeamRateInfo['rateDetail'],true);

                        if(empty($tableContents[$fTeamId])) {
                            $tableContents[$fTeamId] = elements(array('teamId','utime'),$fTeamRateInfo);
                        }

                        $tableContents[$fTeamId][] = $rateDetailArr[$k];
                    }

                }

                $this->table->set_heading($tableHead);
                $data['tableContent'] = empty($tableContents)?"":$this->table->generate($tableContents);
            }


       }

        if($this->input->get('teamId')) {
            $data['resultTitle'] = '团队Id='.$this->input->get('teamId');
            $rateArr = $this->user_rate_model->get_user_rate_row_arr(array('teamId'=>$this->input->get('teamId')),'raterId');
            $data['resultLength'] = '该团队共被 '.count($rateArr).' 位评委评分';
            if(!empty($rateArr)) {
                $teamInfo = $this->team_model->get_team($this->input->get('teamId'));
                $contestInfo = $this->contest_model->get_contest($teamInfo['contestId']);

                $rateRuleIdArr = explode(",",$contestInfo["rateRule"]);
                $rateRuleData = $this->rate_model->get_rate_in($rateRuleIdArr);
                $rateRuleData = array_add_key($rateRuleData,'id');

                $tableHead = array("评委id",'最终修改时间');
                $tableContents = array();
                foreach($rateRuleData as $k => $v ) {
                    $tableHead[] = $v['name'];

                    foreach($rateArr as $fTeamId => $fTeamRateInfo) {

                        $rateDetailArr = json_decode($fTeamRateInfo['rateDetail'],true);

                        if(empty($tableContents[$fTeamId])) {
                            $tableContents[$fTeamId] = elements(array('raterId','utime'),$fTeamRateInfo);
                        }

                        $tableContents[$fTeamId][] = $rateDetailArr[$k];
                    }

                }

                $this->table->set_heading($tableHead);

                $data['tableContent'] = empty($tableContents)?"":$this->table->generate($tableContents);
            }

        }

        $this->load->view('templates/header', $data);
        $this->parser->parse('user_rate/index', $data);
        $this->load->view('templates/footer');
    }

}