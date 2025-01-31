<?php

defined("BASEPATH") or exit("No direct Script Access Allowed");

class TailorModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("MainModel");
    }


    // function to get tailor profile details
    function getTailorProfileDetailsById($id)
    {
        $this->db->select("tailor.*, auth.*, address.`landmark`, address.`district`, address.`town`, address.`city`, address.`state`, address.`pincode`, address.`gmap_link`");
        $this->db->from("tailor");
        $this->db->join("auth", "tailor.authid = auth.id");
        $this->db->join("address", "address.tailorid = tailor.id", "left");
        $this->db->where("tailor.id", $id);
        $data = $this->db->get()->row();
        if ($data != null) {
            return $data;
        } else {
            return null;
        }
    }   // function ends

    // function update tailor authentication detail
    function updateTailorAuthDetailsByAuthId($id, $data)
    {
        $this->db->select("tailor.authid as authid");
        $this->db->from("tailor");
        $this->db->where("tailor.id", $id);
        $authid = $this->db->get()->row();
        if ($authid != null) {
            $this->db->where("id", $authid->authid);
            $this->db->set($data);
            $returned = $this->db->update("auth");
            if ($returned != null) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }   // function ends

    // function to update tailor details
    function updateTailorDetailsById($id, $data)
    {
        $this->db->where("id", $id);
        $this->db->set($data);
        $status = $this->db->update("tailor");
        if ($status != null) {
            return true;
        } else {
            return false;
        }
    }   // function ends

    // function to update tailor address details
    function updateTailorAddressDetails($tailorId, $formdata)
    {
        $this->db->where("tailorid", $tailorId);
        $query = $this->db->get("address");

        if ($query->num_rows() > 0) {
            // Record exists, perform update
            $this->db->where("tailorid", $tailorId);
            $status = $this->db->update("address", $formdata);
        } else {
            // Record doesn't exist, perform insert
            $formdata['tailorid'] = $tailorId; // Ensure tailorid is included in the insert data
            $status = $this->db->insert("address", $formdata);
        }

        if ($status) {
            return true; // Operation successful
        } else {
            return false; // Operation failed
        }
    }   // function ends

    // function to delete tailor record
    function deleteTailorRecordByTailorId($id)
    {
        $this->db->select("authid");
        $this->db->from("tailor");
        $this->db->where("id", $id);
        $auth = $this->db->get()->row();

        $this->db->where("id", $id);
        $tailorstatus = $this->db->delete("tailor");

        $this->db->where("id", $auth->authid);
        $authstatus = $this->db->delete("auth");

        if ($tailorstatus && $authstatus) {
            return true;
        } else {
            return false;
        }
    }   // function ends


    /////////////////////////////////////////////////////// faqs
    // function to get all faqs by tailor id
    function getFaqByTailorId($id)
    {
        $this->db->where("tailor_id", $id);
        $data = $this->db->get("faqs")->result();

        if ($data != null) {
            return $data;
        } else {
            return null;
        }
    }   // function ends

    // function to add faq
    function addFaq($data)
    {
        $status = $this->db->insert("faqs", $data);
        return $status;
    }   // function ends

    // function to update faq
    function updateFaqById($id, $data)
    {
        $this->db->where("id", $id);
        $this->db->set($data);
        $status = $this->db->update("faqs");
        return $status;
    }   // function ends

    // function to delete faq
    function deleteFaqById($id)
    {
        $this->db->where("id", $id);
        $status = $this->db->delete("faqs");
        return $status;
    }   // function ends

    // function to update the password for tailor
    function updatePasswordByAuthId($tailorid, $oldpass, $newpass)
    {

        $this->db->select("tailor.authid");
        $this->db->from("tailor");
        $this->db->where("tailor.id", $tailorid);
        $data = $this->db->get()->row();

        if ($data != null) {
            $this->db->where("id", $data->authid);
            $this->db->where("password", $oldpass);
            $this->db->set(array(
                "password" => $newpass
            ));
            $status = $this->db->update("auth");
            if ($status) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }   // function ends

    // function to contact with admin for tailor
    function addConactUsPost($data)
    {
        $status = $this->db->insert("contactform", $data);
        return $status;
    }   // function ends

    // ////////////////////////////////////////////////// orders or appointments
    // function to get count of pending orders
    function getCountPendingOrdersByTailorId($id)
    {
        $this->db->where("tialorid", $id);
        $this->db->where("bookingstatus", "pending");
        $count = $this->db->count_all("appointments");
        return $count;
    }   // function ends

    // function to get approved orders
    function getApprovedOrdersCountByTailorId($id)
    {
        $this->db->where("tailorid", $id);
        $this->db->where("bookingstatus", "approved");
        $count = $this->db->count_all("appointments");
        return $count;
    }   // function ends

    // function to get completed orders
    function getCompletedOrdersCountByTailorId($id)
    {
        $this->db->where("tailorid", $id);
        $this->db->where("completestatus", "completed");
        $count = $this->db->count_all("appointments");
        return $count;
    }   // funtion ends

    // function to get calculated user rating 
    function getTotalUserRatingByTailorId($id)
    {
        $this->db->select("sum(stars) as starssum");
        $this->db->from("feedback");
        $this->db->where("tailorid", $id);
        $sum = $this->db->get();
        $totalrating = $sum->starssum / 5;
        return $totalrating;
    }


    // funciton to get appointments list
    function getAppointmentsByTailorId($id)
    {
        $this->db->where("tailorid", $id);
        $this->db->where("bookingstatus !=", "pending");
        $data = $this->db->get("appointments")->result();
        if ($data != null) {
            return $data;
        } else {
            return null;
        }
    }   // function ends


    //////////////////////////////////////////////////////// user reviews
    // function to get all reviews
    function getAllReviewsByTailorId($id)
    {
        $this->db->select("user.*, auth.*, feedback.*");
        $this->db->from("feedback");
        $this->db->join("user", "user.id = feedback.userid");
        $this->db->join("auth", "user.authid = auth.id");
        $this->db->where("feedback.tailorid", $id);
        $data = $this->db->get()->result();
        if ($data != null) {
            return $data;
        } else {
            return null;
        }
    }   // function ends

    // function to get states
    function getStates(){
        return $this->db->get("states")->result();
    }   // function endsget 

    // function to get states
    function getCities(){
        $this->db->order_by("city", "ASEC");
        return $this->db->get("cities")->result();
    }   // function endsget 

    // function to accept appointment request
    function AcceptAppointment($id){
        $this->db->set("bookingstatus", "approved");
        $this->db->where("id", $id);
        $status = $this->db->update("appointments");
        if($status != null){
            return $status;
        }else{
            return null;
        }
    }   // function ends

    // function to get pending appointments
    function getPendingAppointments($tailorId){
        $this->db->where("tailorid", $tailorId);
        $this->db->where("bookingstatus", "pending");
        $data = $this->db->get("appointments")->result();
        if ($data != null) {
            return $data;
        } else {
            return null;
        }
    }   // function ends

    // function to accept appointment request
    function CompleteAppointment($id){
        $this->db->set("completestatus", "completed");
        $this->db->where("id", $id);
        $status = $this->db->update("appointments");
        if($status != null){
            return $status;
        }else{
            return null;
        }
    }   // function ends

    // function to get avg. user rating 
    function UserRatingCountByTailorId($tailorId){
        $this->db->select("avg(stars) as rating");
        $this->db->where("tailorid", $tailorId);
        $this->db->from("feedback");
        $data = $this->db->get()->row();
        if($data != null){
            return number_format($data->rating, 1);
        }else{
            return 0;
        }
    }   // function ends

}
