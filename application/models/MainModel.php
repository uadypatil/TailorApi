<?php

defined("BASEPATH") or exit("No direct Script Access Allowed");

class MainModel extends CI_Model
{

    // constructor
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }   //  constructor ends

    // function to encrypt data
    function encryptData($data)
    {
        $iv = substr(hash('sha256', $data, true), 0, 16);
        $key = hash('sha256', $iv, true);
        $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode($iv . $encryptedData);
    }   // functione ends

    // function to decrypt data
    function decryptData($encryptedData)
    {
        $decodedData = base64_decode($encryptedData);
        $iv = substr($decodedData, 0, 16);
        $encryptedData = substr($decodedData, 16);
        $key = hash('sha256', $iv, true);
        $decryptedData = openssl_decrypt($encryptedData, 'aes-256-cbc', $key, 0, $iv);
        return $decryptedData;
    }   // function ends

    // runction to add authentication details to database
    function AuthenticationPost($formdata)
    {
        // Check for unique contact number
        $this->db->where('contactno', $formdata['contactno']);
        $query1 = $this->db->get('auth');

        // Check for unique email
        $this->db->where('email', $formdata['email']);
        $query2 = $this->db->get('auth');

        // If either contact number or email already exists, return an error message
        if ($query1->num_rows() > 0) {
            return "contactno exist";
        } elseif ($query2->num_rows() > 0) {
            return "email exist";
        } else {
            // Proceed with insert if unique
            $status = $this->db->insert("auth", $formdata);
            if ($status) {
                $id = $this->db->insert_id();
                return $id;
            } else {
                return null;
            }
        }
    }   // function ends

    // function to register the user
    function setUserDataPost($userdata)
    {
        $status = $this->db->insert("user", $userdata);
        return $status;
    }   // function ends

    // function to register the tailor
    function setTailorDataPost($tailordata)
    {
        $status = $this->db->insert("tailor", $tailordata);
        return $status;
    }   // function ends

    // function to authenticate login 
    function AuthenticateLoginPost($email, $password, $type)
    {
        if ($type == "user") {            // for user
            $this->db->select("auth.*, user.*");
            $this->db->from("auth");
            $this->db->join("user", "auth.id = user.authid");
            $this->db->where("auth.email", $email);
            $this->db->where("auth.password", $password);
            $userdata = $this->db->get()->row();
        } else if ($type == "tailor") {    // for tailor
            $this->db->select("auth.*, tailor.*");
            $this->db->from("auth");
            $this->db->join("tailor", "auth.id = tailor.authid");
            $this->db->where("auth.email", $email);
            $this->db->where("auth.password", $password);
            $this->db->where("tailor.requeststatus", "approved");
            $userdata = $this->db->get()->row();
        } else if ($type == "admin") {     // for admin
            $this->db->where("email", $email);
            $this->db->where("password", $password);
            $userdata = $this->db->get("auth")->row();
        }

        if ($userdata != null) {
            return $userdata;
        } else {
            return null;
        }
    }   // function ends


    // function to auto create admin user
    function AutoCreateAdmin()
    {
        $this->db->where("type", "admin");
        $data = $this->db->get("auth")->row();
        if ($data == null) {
            $admindata = array(
                "email" => "admin@manasvi.tech",
                "contactno" => "7385975192",
                "password" => $this->encryptData("Admin@man1"),
                "type" => "admin"
            );
            $this->db->insert("auth", $admindata);
            return $this->db->insert_id();
        }
    }   // function ends

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Forgot Password Functions

    function getuserByEmail($email)
    {
        $this->db->where(array(
            "email" => $email
        ));
        $user = $this->db->get("auth");

        if ($user != null) {
            return $user;
        }
        return null;
    }

    function generateToken($email, $token, $expires_at)
    {
        $data = array(
            'email' => $email,
            'token' => $token,
            'expires_at' => $expires_at,
        );
        $user = $this->db->get_where("passwordresettokens", array("email" => $email))->row_array();
        if ($user != null) {
            $this->db->where("email", $email);
            return $this->db->update("passwordresettokens", $data);
        }

        // Insert new token
        return $this->db->insert('passwordresettokens', $data);
    }




    function checkTokenValidity($token)
    {
        $this->db->where('token', $token);
        $this->db->where('expires_at >', date("Y-m-d H:i:s"));
        $query = $this->db->get('passwordresettokens');
        return $query->num_rows() === 1;
    }

    function getTokenData($token)
    {
        $data = $this->db->get_where("passwordresettokens", array("token" => $token))->row_array();
        if ($data != null) {
            return $data;
        }
        return null;
    }

    function getUserEmailByToken($token)
    {
        $user = $this->db->get_where("passwordresettokens", array("token" => $token))->row();
        return $user->email;
    }

    function updatePasswordByEmail($email, $password)
    {
        $this->db->set('password', $password);
        $this->db->where(array(
            "email" => $email
        ));
        return $this->db->update("auth");
    }

    function deleteToken($token)
    {
        $this->db->where("token", $token);
        $this->db->delete("passwordresettokens");
    }
    //      reset password ends

    // function to save otp in otptabe
    function addOTP($mail, $otp, $expires_at)
    {
        $status = $this->db->insert("otptable", array(
            "email" => $mail,
            "otp" => $otp,
            "expires_at" => $expires_at
        ));

        return $status;
    }   //  function ends

    // function to match otp
    function matchOTP($email, $otp)
    {
        $this->db->where("email", $email);
        $this->db->where("otp", $otp);
        $data = $this->db->get("otptable")->row();

        if ($data) {
            return true;
        } else {
            return false;
        }
    }   // function ends

    // function to delete generated otp
    function deleteOTP($email)
    {
        $this->db->where("email", $email);
        $status = $this->db->delete("otptable");
        return $status;
    }   // function ends

    // function to check mail exists or not
    function isMailExist($email)
    {
        $this->db->where("email", $email);
        $status = $this->db->get("auth")->row();
        if ($status) {
            return true;
        } else {
            return false;
        }
    }   // function ends


    // function to update otp
    public function updateOTP($email, $otp, $expires_at)
    {
        $data = array(
            'otp' => $otp,
            'expires_at' => $expires_at
        );
        $this->db->where('email', $email);
        return $this->db->update('otptable', $data);
    }   // function ends

    // function to update password by email
    function UpdatePasswordByMail($email, $passwordData)
    {
        $this->db->where("Email", $email);
        return $this->db->update("auth", $passwordData);
    }   // function ends


}
