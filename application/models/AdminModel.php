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

    // function to autocreate admin user
    function autocreateAdmin()
    {
        $this->db->where("type", "admin");
        $count = $this->db->count_all("auth");

        if ($count > 0) {
            return false;
        } else {
            $data = array(
                "email" => "admin@manasvi.tech",
                "contactno" => "9028889915",
                "password" => $this->MainModel->encryptData("Admin@mt1"),
                "type" => "admin"
            );
            $status = $this->db->insert("auth", $data);
            if ($status != null) {
                return true;
            } else {
                return false;
            }
        }
    }   // function ends

    // function to get total tailor count
    function getTotalTailorCount()
    {
        $this->db->select("count(*) as tailorcount");
        $this->db->from("tailor");
        $this->db->join("auth", "auth.id = tailor.authid", "left");
        $this->db->where("tailor.requeststatus", "accepted");
        $countdata = $this->db->get()->row();
        
        if ($countdata && isset($countdata->tailorcount)) {
            return $countdata;
        } else {
            return (object) ['tailorcount' => 0];
        }
    }   // function ends

    // function to get total users
    function getTotalUserCount()
    {
        $this->db->select("count(*) as usercount");
        $this->db->from("user");
        $this->db->join("auth", "auth.id = user.authid", "left");
        // $this->db->where-("user.requeststatus", "accepted");
        $countdata = $this->db->get()->row();

        if ($countdata && isset($countdata->usercount)) {
            return $countdata;
        } else {
            // Return a default object with usercount set to 0 if the result is null
            return (object) ['usercount' => 0];
        }
    }   // function ends

    // function to get all pending tailor requests
    function getTailorPendingRequestCount()
    {
        $this->db->select("count(*) as tailorcount");
        $this->db->from("tailor");
        $this->db->join("auth", "auth.id = tailor.authid", "left");
        $this->db->where("tailor.requeststatus", "pending");
        $countdata = $this->db->get()->row();
        
        if ($countdata && isset($countdata->tailorcount)) {
            return $countdata;
        } else {
            // Return a default object with usercount set to 0 if the result is null
            return (object) ['tailorcount' => 0];
        }
    }   // function ends

    // function to get request accepted tailor's list
    function getApprovedTailorList()
    {
        $this->db->select("auth.*, tailor.*");
        $this->db->from("tailor");
        $this->db->join("auth", "auth.id = tailor.id");
        $this->db->where("tailor.requeststatus", "accepted");
        $data = $this->db->get()->result();
        if ($data != null) {
            return $data;
        } else {
            return null;
        }
    }   // function ends

    // function to get pending tailor requests
    function getTailorPendingRequests()
    {
        $this->db->select("auth.*, tailor.*");
        $this->db->from("tailor");
        $this->db->join("auth", "auth.id = tailor.id");
        $this->db->where("tailor.requeststatus", "pending");
        $data = $this->db->get()->result();
        if ($data != null) {
            return $data;
        } else {
            return null;
        }
    }   // function ends

    // function to get admin profile data
    function getAdminProfileData()
    {
        $this->db->where("type", "admin");
        $data = $this->db->get("auth")->row();
        if ($data != null) {
            return $data;
        } else {
            return null;
        }
    }   // function ends

    // function to update admin password
    function updateAdminPassword($oldpass, $newpass)
    {
        $this->db->where("type", "admin");
        $this->db->where("email", "admin@manasvi.tech");
        $this->db->where("password", $oldpass);
        $this->db->set(array("password" => $newpass));
        $status = $this->db->update("auth");
        if ($status) {
            return true;
        } else {
            return false;
        }
    }   // function ends

}
