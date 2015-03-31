<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $title ?> -评分管理</title>
    <script src="<?php echo base_url('assets/js/bootstrap.min.js')?>"></script>
    <link href="<?php echo base_url('assets/css/index.css')?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets/css/bootstrap-responsive.min.css')?>" rel="stylesheet" type="text/css" />
</head>
<body>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="page-header">
                <h1>
                    轻应用评分系统后台管理 <small><a href="http://rateadminnew.duapp.com/" style="font-size:16px;color: red">切换至新版</a></small>   <small><a href="<?=base_url("login/logout")?>" style="font-size:16px;float:right"><?='用户名:'.$this->session->userdata('username')."    "?>退出</a></small>
                </h1>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span2" id="feature-list">
            <ul class="nav nav-list">
                <li class="nav-header">
                    配置数据
                </li>
                <li>
                    <a href="<?=base_url("contest/index")?>">比赛</a>
                </li>
                <li>
                    <a href="<?=base_url("rater/index")?>">评委</a>
                </li>
                <li>
                    <a href="<?=base_url("team/index")?>">团队</a>
                </li>
                <li>
                    <a href="<?=base_url("rate/index")?>">评分规则</a>
                </li>
                <li class="nav-header">
                    评分结果
                </li>
                <li>
                    <a href="<?=base_url("user_rate/index")?>">结果统计</a>
                </li>
            </ul>
        </div>
        <div class="span10">

