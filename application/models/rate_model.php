<?php
/**
 * Created by PhpStorm.
 * User: yunlong
 * Date: 14-4-8
 * Time: 下午8:09
 */


class Rate_model extends CI_Model{
    protected $tableName;

    public function __construct()
    {
        $this->load->database();
        $this->tableName = "rate";

    }

    public function get_rate($id = false,$key='')
    {
        if($id === false)
        {
            $query = $this->db->get('rate');
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

        $query = $this->db->get_where('rate',array('id'=>$id));
        return $query->row_array();

    }

    public function get_rate_in($arr)
    {
        $this->db->where_in('id',$arr);
        $query = $this->db->get($this->tableName);
        return $query->result_array();
    }

    public function set_rate()
    {
        $data = elements(array('name','detail','subId','weight','score'),$this->input->post());
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

    public function del_rate($id) {
        $this->db->where('id',$id);
        return $this->db->delete($this->tableName);
    }

} 