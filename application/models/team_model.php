<?php
/**
 * Created by PhpStorm.
 * User: yunlong
 * Date: 14-4-8
 * Time: 下午8:09
 */


class Team_model extends CI_Model{
    protected $tableName;

    public function __construct()
    {
        $this->load->database();
        $this->tableName = "team";

    }

    public function get_team($id = false)
    {
        if($id === false)
        {
            $query = $this->db->get('team');
            return $query->result_array();
        }

        $query = $this->db->get_where('team',array('id'=>$id));
        return $query->row_array();
    }

    public function get_team_by_contest($id)
    {
        $query = $this->db->get_where($this->tableName,array('contestId'=>$id));
        return $query->result_array();
    }

    public function set_team()
    {
        $data = elements(array('contestId','name','teamDisplayId','description','appName','appDesc'),$this->input->post());
        $data = array_filter($data,function($v){
            return $v !== '';
        });

        $id = $this->input->post('id');
        if(empty($id)) {
            return $this->db->insert($this->tableName, $data);
        } else {
            $this->db->where("id",$id);
            return $this->db->update($this->tableName,$data);
        }
    }

    public function del_team($id) {
        $this->db->where('id',$id);
        return $this->db->delete($this->tableName);
    }

} 