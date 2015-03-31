<?php
/**
 * Created by PhpStorm.
 * User: yunlong
 * Date: 14-7-25
 * Time: 下午6:01
 */

class Base extends CI_Controller {

    private static $instance;

    protected $table_tmpl = array ( 'table_open'  => '<table border="1" cellpadding="2" cellspacing="1" class="mytable">' );

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')){
            redirect(base_url('/login/index'));
            exit();
        }
    }

}