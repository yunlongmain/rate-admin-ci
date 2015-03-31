<?php
/**
 * Created by PhpStorm.
 * User: yunlong
 * Date: 14-4-8
 * Time: 下午8:09
 */


class Contest_model extends CI_Model{
    protected $tableName;

    public function __construct()
    {
        $this->load->database();
        $this->tableName = "contest";

    }

    public function get_contest($id = false)
    {
        if($id === false)
        {
            $query = $this->db->get('contest');
            return $query->result_array();
        }

        $query = $this->db->get_where('contest',array('id'=>$id));
        return $query->row_array();
    }

    public function set_contest()
    {
        $data = elements(array('name','description','rateRule','online'),$this->input->post());
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

    public function del_contest($id) {
        $this->db->where('id',$id);
        return $this->db->delete($this->tableName);
    }

} 