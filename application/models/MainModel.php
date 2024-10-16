<?php  

defined("BASEPATH") or exit("No direct Script Access Allowed");

class MainModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();

    }
 function encryptData($data)
    {
        $iv = substr(hash('sha256', $data, true), 0, 16);
        $key = hash('sha256', $iv, true);
        $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode($iv . $encryptedData);
    }
    function decryptData($encryptedData)
    {
        $decodedData = base64_decode($encryptedData);
        $iv = substr($decodedData, 0, 16);
        $encryptedData = substr($decodedData, 16);
        $key = hash('sha256', $iv, true);
        $decryptedData = openssl_decrypt($encryptedData, 'aes-256-cbc', $key, 0, $iv);
        return $decryptedData;
    }
    function loginValidation($username,$password,$role)
    {

        if ($role=="Admin") {
            $Admin=$this->db->get_where("admin",array("username"=>$username,"password"=>$password))->row();
            if($Admin!=null)
            {
                $Admin->role="Admin";
                return $Admin;
            }
            else{
                return null;
            }
        } 
        else if ($role=="School") {
            $School=$this->db->get_where("school",array("username"=>$username,"password"=>$password))->row();
            if($School!=null)
            {
                $School->role="School";
                return $School;
            }
            else{
                return null;
            }
        } 
        else if ($role=="Staff") {
            $Staff=$this->db->get_where("staff",array("username"=>$username,"password"=>$password))->row();
            if($Staff!=null)
            {
                $Staff->role="Staff";

                return $Staff;
            }
            else{
                return null;
            }
        } 
        else if ($role=="Student") {
            $Student=$this->db->get_where("student",array("username"=>$username,"password"=>$password))->row();
            if($Student!=null)
            {
                $Student->role="Student";
                return $Student;
            }
            else{
                return null;
            }
        } 
        else {
            $Class=$this->db->get_where("class",array("username"=>$username,"password"=>$password))->row();
            if($Class!=null)
            {
                $Class["role"]="Class";

                return $Class;
            }
            else{
                return null;
            }
        }
    }
}



?>