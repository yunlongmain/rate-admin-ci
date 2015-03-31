<?php
/**
 * Created by PhpStorm.
 * User: yunlong
 * Date: 14-4-8
 * Time: 下午8:09
 */


class Rater_model extends CI_Model{
    protected $tableName;

    const ROLE_NORMAL = 0;
    const ROLE_ADMIN = 1;
    const ROLE_SUPER_ADMIN = 2;

    public function __construct()
    {
        $this->load->database();
        $this->tableName = "rater";
    }

    public function get_rater($id = false)
    {
        if($id === false)
        {
            $query = $this->db->get('rater');
            return $query->result_array();
        }

        $query = $this->db->get_where('rater',array('id'=>$id));
        return $query->row_array();
    }

    public function get_rater_by_contest($id)
    {
        $query = $this->db->get_where('rater',array('contestAuth'=>$id));
        return $query->result_array();
    }

    public function set_rater()
    {
        $data = elements(array('name','username','password','role','contestAuth','description'),$this->input->post());
        $data = array_filter($data,function($v){
            return $v !== '' && $v !== false;
        });
//        if(!empty($data['password'])) {
//            $data['password'] = md5($data['password'].$this->config->item('encryption_key'));
//        }

        $id = $this->input->post('id');
        if(empty($id)) {
//            $data['password'] = md5("1".$this->config->item('encryption_key'));
            return $this->db->insert($this->tableName, $data);
        } else {
            $this->db->where("id",$id);
            return $this->db->update($this->tableName,$data);
        }
    }

    public function del_rater($id) {
        $this->db->where('id',$id);
        return $this->db->delete($this->tableName);
    }

    public function signin($username,$password)
    {
        $query = $this->db->get_where($this->tableName,array('username' => $username,'password' => $password));//md5($password.$this->config->item('encryption_key'))

        return $query->row_array();
    }

} 