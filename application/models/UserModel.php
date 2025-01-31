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
    function getUserProfileData($id)
    {
        $this->db->select("auth.*, user.*,auth.*, user.*");
        $this->db->from("user");
        $this->db->join("auth", "user.authid = auth.id");
        $this->db->where("user.id", $id);
        $data = $this->db->get()->row();
        if ($data != null) {
            return $data;
        } else {
            return null;
        }
    }   // function ends

    // function to update user authentication data
    function updateUserAuthData($authid, $data)
    {
        $this->db->where("id", $authid);
        $this->db->set($data);
        $status = $this->db->update("auth");
        return $status;
    }   // function ends

    // function to update user data
    function updateUserData($userid, $data)
    {
        $this->db->where("id", $userid);
        $this->db->set($data);
        $status = $this->db->update("user");
        return $status;
    }   // function ends

    // function to update password
    function updateUserPassword($userid, $oldpass, $newpass)
    {
        $this->db->select("user.authid");
        $this->db->from("user");
        $this->db->where("user.id", $userid);
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

    /////////////////////////////////////////////////////////////////////////   tailors
    // function to get experienced tailors
    function getExperiencedTailorData()
    {
        $this->db->select("auth.*, tailor.*, auth.id as authid, tailor.id as tailorid");
        $this->db->from("tailor");
        $this->db->join("auth", "auth.id = tailor.authid");
        $this->db->order_by("experience", "DESC");
        $this->db->limit(6);
        $data = $this->db->get()->result();
        if ($data != null) {
            return $data;
        } else {
            return null;
        }
    }   // function ends

    // function to get single tailor data with faqs 
    function getTailorDataById($tailorid)
    {
        $this->db->select("tailor.*, auth.*");
        $this->db->from("tailor");
        $this->db->join("auth", "auth.id = tailor.authid");
        $this->db->where("tailor.id", $tailorid);
        $data = $this->db->get()->row();
        if ($data != null) {
            return $data;
        } else {
            return null;
        }
    }   // function ends

    // function to get faqs for tailor
    function getTailorFaqsByTailorId($tailorid)
    {
        $this->db->where("tailor_id", $tailorid);
        $data = $this->db->get("faqs")->result();
        if ($data != null) {
            return $data;
        } else {
            return null;
        }
    }   // function ends

    // function to get reviews by tailor id
    function getTailorReviewsByTailorId($tailorid)
    {
        $this->db->select("user.profilepic, feedback.*");
        $this->db->from("feedback");
        $this->db->join("user", "user.id = feedback.userid");
        $this->db->where("feedback.tailorid", $tailorid);
        $data = $this->db->get()->result();
        if ($data != null) {
            return $data;
        } else {
            return null;
        }
    }   // function ends

    // function to get appointments by user id
    function getAppointmentsByUserId($userid)
    {
        $this->db->select("appointments.*, auth.contactno, tailor.name, tailor.profilepic, tailor.certification");
        $this->db->select("IFNULL(AVG(feedback.stars), 0) as rating"); // Calculate the average rating
        $this->db->from("appointments");
        $this->db->join("tailor", "tailor.id = appointments.tailorid");
        $this->db->join("auth", "auth.id = tailor.authid");
        $this->db->join("feedback", "feedback.tailorid = tailor.id", "left");
        $this->db->where("appointments.userid", $userid);
        $this->db->group_by("appointments.id"); // Group by appointment ID to ensure proper aggregation
        $this->db->order_by("appointments.id", "DESC"); // Optional: order results by appointment ID

        $data = $this->db->get()->result();

        // return $this->db->last_query();
        return ($data) ? $data : null;
    }   // functione ends

    // function to book an appointment
    function bookAppointment($data)
    {
        $status = $this->db->insert("appointments", $data);
        return $status;
    }   // function ends


    // function to get appointment by id
    function getAppintmentById($id)
    {
        $this->db->where("id", $id);
        $data = $this->db->get("appointments")->row();
        if ($data) {
            return $data;
        } else {
            return null;
        }
    }   // function ends

    // function to update the appointment
    function updateAppintment($id, $data)
    {
        $this->db->where("id", $id);
        $this->db->set($data);
        $status = $this->db->update("appointments");
        return $status;
    }   // function ends

    // function to add feedback form
    function addFeedbackPost($data)
    {
        $status = $this->db->insert("feedback", $data);
        return $status;
    }   // function ends

    // function to search for location
    function searchLoc($search_term)
    {
        $this->db->select("auth.id as authid, auth.email as email, auth.contactno, 
            tilor.id as tailorid, tailor.name, tailor.experience, tailor.specialization, 
            tailor.profilepic, tailor.certification, adress.*");
        $this->db->from("address");
        $this->db->join("tailor", "tailor.id = address.tailorid");
        $this->db->join("auth", "auth.id = tailor.authid");

        if (!empty($location)) {
            $this->db->like("tailorid", $search_term);
            $this->db->or_like("landmark", $search_term);
            $this->db->or_like("district", $search_term);
            $this->db->or_like("town", $search_term);
            $this->db->or_like("village", $search_term);
            $this->db->or_like("state", $search_term);
            $this->db->or_like("pincode", $search_term);
        }

        if (!empty($service)) {
            $this->db->where('eventmanager.service', $service);
        }

        $data = $this->db->get()->result();
        return $data;
    }

    // function to get tailors by location and services
    function getTailorByLocationService($location, $service)
    {
        $this->db->select("tailor.*");
        $this->db->from("tailor");
        // $this->db->like("specialization", $service);        // Others

        // Check if the location is valid (not empty or default values)
        if ($location != "" && $location != "All Location" && $location != "Others") {
            $this->db->like("address", $location);
        }

        // Check if the service is valid (not empty or default values)
        if ($service != "" && $service != "All Services" && $service != "Others") {
            $this->db->like("specialization", $service);
        }

        $data = $this->db->get()->result();
        if ($data != null) {
            return $data;
        } else {
            return null;
        }
    }   // function ends

    // function to get location for search
    function getLocationsForSearch()
    {
        $this->db->select("`id`, `landmark`, `district`, `town`, `city`, `state`, `pincode`");
        $this->db->from("address");
        $this->db->group_by("landmark");
        $this->db->order_by("landmark", "ASEC");
        $data = $this->db->get()->result();
        if ($data != null) {
            return $data;
        } else {
            return null;
        }
    }
}
