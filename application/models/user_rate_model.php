<?php
/**
 * Created by PhpStorm.
 * User: yunlong
 * Date: 14-4-8
 * Time: 下午8:09
 */

class User_rate_model extends CI_Model{
    protected $tableName;

    public function __construct()
    {
        $this->load->database();
        $this->tableName = "user_rate";

    }

    public function get_user_rate_row($raterId,$teamId)
    {
        $this->db->where('raterId', $raterId);
        $this->db->where('teamId', $teamId);
        $query = $this->db->get($this->tableName);
        return $query->row_array();
    }

    public function get_user_rate_row_arr($where,$key='')
    {
        $query = $this->db->get_where($this->tableName,$where);
        $result = $query->result_array();
        if(empty($key)) {
            return $result;
        } else {
            $keyResult = array();
            foreach($result as $v) {
                $keyResult[$v[$key]] = $v;
            }
            return $keyResult;
        }
    }

    public function get_user_rate_by_contest($contestId,$groupBy='')
    {
        if($groupBy) {
            $this->db->group_by($groupBy);
        }
        $this->db->where('contestId',$contestId);
        $query = $this->db->get($this->tableName);
        return $query->result_array();
    }

    public function set_user_rate()
    {
        $data = array(
            "teamId" => $this->input->post("teamId"),
            "contestId" => $this->session->userdata('contestAuth'),
            "raterId" => $this->session->userdata('userid'),
        );

        $rateRuleArr = array();
        foreach($this->input->post() as $k => $v) {
            if(substr($k, 0, 11) == 'rateDetail-') {
                $rateRuleId = intval(substr($k, 11));
                $rateRuleArr[$rateRuleId] = intval($v);
            }
        }

        $data['rateDetail'] = json_encode($rateRuleArr);
        $data['score'] = array_sum($rateRuleArr);

        if($this->input->post("isnew") == 1) {
            return $this->db->insert($this->tableName, $data);
        } else {
            $data['utime'] = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);

            $this->db->where("raterId",$data['raterId']);
            $this->db->where("teamId",$data['teamId']);
            return $this->db->update($this->tableName,$data);
        }
    }

}