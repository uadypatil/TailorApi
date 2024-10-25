<?php


defined("BASEPATH") or exit("No direct Script Access Allowed");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers:*");

// url: https://localhost/TailorApi/User/
class User extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("MainModel");
        $this->load->model("UserModel");

        // auto create admin user
        $this->MainModel->AutoCreateAdmin();

        // php mailer library        
        $this->load->library('phpmailer_lib');
    }







}
