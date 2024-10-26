<?php

defined("BASEPATH") or exit("No direct Script Access Allowed");

class UserModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("MainModel");
    }

    
    ////////////////////////////////////////////////////// user profile
    // function to get user profile data
    function getUserProfileData($id){
        $this->db->select("auth.*, user.*");
        $this->db->from("user");
        $this->db->join("auth", "user.authid = auth.id");
        $this->db->where("user.id", $id);
        $data = $this->db->get()->row();
        if($data != null){
            return $data;
        }else{
            return null;
        }
    }   // function ends

    // function to update user authentication data
    function updateUserAuthData($authid, $data){
        $this->db->where("id", $authid);
        $this->db->set($data);
        $status = $this->db->update("auth");
        return $status;
    }   // function ends

    // function to update user data
    function updateUserData($userid, $data){
        $this->db->where("id", $userid);
        $this->db->set($data);
        $status = $this->db->update("user");
        return $status;
    }   // function ends

    // function to update password
    function updateUserPassword($authid, $oldpass, $newpass){
        $this->db->where("id", $authid);
        $this->db->where("password", $oldpass);
        $this->db->set(array(
            "password"=>$newpass
        ));
        $status = $this->db->update("auth");
        return $status;
    }   // function ends

    /////////////////////////////////////////////////////////////////////////   tailors
    // function to get experienced tailors
    function getExperiencedTailorData(){
        $this->db->select("auth.*, tailor.*");
        $this->db->from("tailor");
        $this->db->join("auth", "auth.id = tailor.authid");
        $this->db->order_by("experience", "DESC");
        $this->db->limit(6);
        $data = $this->db->get()->result();
        if($data != null){
            return $data;
        }else{
            return null;
        }
    }   // function ends

    // function to get single tailor data with faqs 
    function getTailorDataById($tailorid){
        $this->db->select("tailor.*, auth.*");
        $this->db->from("tailor");
        $this->db->join("auth", "auth.id = tailor.authid");
        $this->db->where("tailor.id", $tailorid);
        $data = $this->db->get()->row();
        if($data != null){
            return $data;
        }else{
            return null;
        }
    }   // function ends

    // function to get faqs for tailor
    function getTailorFaqsByTailorId($tailorid){
        $this->db->where("tailor_id", $tailorid);
        $data = $this->db->get("faqs")->result();
        if($data != null){
            return $data;
        }else{
            return null;
        }
    }   // function ends

    // function to get reviews by tailor id
    function getTailorReviewsByTailorId($tailorid){
        $this->db->select("user.profilepic, review.*");
        $this->db->from("review");
        $this->db->join("user", "user.id = review.userid");
        $this->db->where("review.tailorid", $tailorid);
        $data = $this->db->get()->result(); 
        if($data != null){
            return $data;
        }else{
            return null;
        }
    }   // function ends

    // function to get appointments by user id
    function getAppointmentsByUserId($userid){
        $this->db->where("userid", $userid);
        $data = $this->db->get("appointments")->result();
        if($data != null){
            return $data;
        }else{
            return null;
        }
    }   // functione ends

    // function to book an appointment
    function bookAppointment($data){
        $status = $this->db->insert("appointments", $data);
        return $status;
    }   // function ends
    

    // function to get appointment by id
    function getAppintmentById($id){
        $this->db->where("id", $id);
        $data = $this->db->get("appointments")->row();
        if($data){
            return $data;
        }else{
            return null;
        }
    }   // function ends

    // function to update the appointment
    function updateAppintment($id, $data){
        $this->db->where("id", $id);
        $this->db->set($data);
        $status = $this->db->update("appointments");
        return $status;
    }   // function ends

    // function to add feedback form
    function addFeedbackPost($data){
        $status = $this->db->insert("feedback", $data);
        return $status;
    }   // function ends



    
    


    
}
