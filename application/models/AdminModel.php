<?php  

defined("BASEPATH") or exit("No direct Script Access Allowed");

class AdminModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("MainModel");
    }

    function checkifadminexists()
    {
        $data = $this->db->get("admin")->result();
        if (!empty($data)) {
            return true;
        } else {
            return false;
        }
    }
   

    function createAdmin()
    {
        $admineexists = $this->checkifadminexists();
        if ($admineexists == true) {
            return "Already Exists";
        } else {
            $data = array(
                "username" => "admin",
                "password" => $this->MainModel->encryptData("admin"),
                "role" =>"Admin",
            );
            $this->db->insert("admin", $data);
            return "Created";
        }
    }

    function updatePassword($password)
    {
        $this->db->set("password",$password);
        $this->db->where("username","admin");
        $result = $this->db->update("admin");
        return $result;
    }
}



?>