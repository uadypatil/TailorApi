<?php


defined("BASEPATH") or exit("No direct Script Access Allowed");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers:*");

class Main extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("MainModel");

        // auto create admin user
        $this->MainModel->AutoCreateAdmin();

        // php mailer library        
        $this->load->library('phpmailer_lib');
    }


    function Login()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $email = $this->input->post("email");
            $password = $this->MainModel->encryptData($this->input->post("password"));
            $type = $this->input->post("type");
            $user = $this->MainModel->AuthenticateLoginPost($email, $password, $type);

            if ($user != null) {
                $this->output->set_status_header(200);
                $response = array("status" => "Success", "data" => $user, "message" => "Login Successfull");
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "Error", "message" => "Wrong Credentials");
            }
        } else {
            $this->output->set_status_header(405);
            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    // function for registration
    function registrationPost()
    {
        $returned = "";
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $formdata = $this->input->post();
            $formdata['password'] = $this->MainModel->encryptData($formdata["password"]);

            $authenticationdata = array(
                'email' => $formdata['email'],
                'contactno' => $formdata['contactno'],
                'password' => $formdata['password'],
                'type' => $formdata['type'],
            );

            $authid = $this->MainModel->AuthenticationPost($authenticationdata);
            if ($authid != null) {
                if ($authid == "contactno exist") {
                    $this->output->set_status_header(404);
                    $response = array("status" => "Error", "message" => "Contact Number Already Exist");
                    $returned  = -1;
                } else if ($authid == "email exist") {
                    $this->output->set_status_header(404);
                    $returned = -2;
                } else {

                    if ($formdata['type'] == "tailor") {
                        // adding tailor data to array
                        $tailordata = array(
                            "authid" => $authid,
                            "name" => $formdata['name'],
                            "document_type" => $formdata['document_type']
                        );

                        if (isset($formdata['shop_license'])) {
                            $tailordata["shop_license"] = $formdata['shop_license'];
                        }


                        $config['upload_path'] = 'uploads/tailor/documents';
                        $config['allowed_types'] = 'jpg|jpeg|png|gif';
                        $config['max_size'] = 51200; // 50 MB in KB
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);

                        $data = []; // Initialize an array to hold the response data

                        // Check if a file is uploaded

                        if (isset($_FILES["document"])) {
                            if (!$this->upload->do_upload('document')) {
                                $data['error'] = $this->upload->display_errors();
                                print_r($data);
                            } else {
                                $data['upload_data'] = $this->upload->data();
                                $tailordata['document'] = $data['upload_data']['file_name'];
                            }
                        }


                        $returned = $this->MainModel->setTailorDataPost($tailordata);
                    } else if ($formdata['type'] == "user") {
                        // adding userdata to array
                        $userdata = array(
                            "authid" => $authid,
                            "name" => $formdata['name']
                        );

                        $returned = $this->MainModel->setUserDataPost($userdata);
                    } else {
                        $returned = -3;
                    }
                }
            }

            // $user = $this->MainModel->loginValidation($username, $password, $role);
            if ($returned === -1) {
                $response = array("status" => "Error", "message" => "Contact number already exist");
            } else if ($returned === -2) {
                $response = array("status" => "Error", "message" => "Email already exist");
            } else if ($returned === -3) {
                $response = array("status" => "Error", "message" => "Invalid User");
            } else if ($returned != null) {
                $this->output->set_status_header(200);
                $response = array("status" => "Success", "message" => "Registration Successfull");
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "Error", "message" => "Failed to Register " . $authid . " | " . $returned);
            }
        } else {
            $this->output->set_status_header(405);
            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }



    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Forgot Password Functions
    function SendResetPasswordLink()
    {
        $email = $this->input->post("email");
        // $role = $this->input->post("role");
        $user = $this->MainModel->getuserByEmail($email);
        if ($user != null) {
            $token = substr(bin2hex(random_bytes(5)), 0, 10);
            $expires_at = date("Y-m-d H:i:s", strtotime('+1 hour'));

            if ($this->MainModel->generateToken($email, $token, $expires_at)) {
                $reset_link = 'Main/ResetPassword?token=' . $token;
                $mail = $this->phpmailer_lib->load();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'amplifierlover007@gmail.com'; // Your email
                $mail->Password = 'irjw yygg kfni dnue'; // Your email password
                $mail->SMTPSecure = 'tls'; // 'tls' for port 587
                $mail->Port = 587; // Port for 'tls'
                $mail->setFrom('amplifierlover007@gmail.com', 'Sevakalpak');
                $mail->addAddress($email);

                $mail->Subject = 'Password Reset Request';
                $mail->isHTML(true);

                $mailContent = "<h1>Password Reset</h1>
              <p>Click the link below to reset your password:</p>
              <p><a href='$reset_link'>Reset Password</a></p>";
                $mail->Body = $mailContent;

                try {
                    if ($mail->send()) {
                        $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(['message' => 'Password reset link has been sent to your email.']));
                    } else {
                        $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(500)
                            ->set_output(json_encode(['error' => 'Failed to send password reset link.']));
                    }
                } catch (Exception $e) {
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['error' => 'Failed to send password reset link.', 'details' => $mail->ErrorInfo]));
                }
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['error' => 'Failed to store reset token.']));
            }
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode(['error' => 'No Email Found']));
        }
    }


    function ResetPasswordPost()
    {
        $token = $this->input->post("token");
        $password = $this->input->post("Password");
        if (!$this->MainModel->checkTokenValidity($token)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['error' => 'Invalid or expired token.']));
            return;
        }

        $tokendata = $this->MainModel->getTokenData($token);
        $email = $token["email"];
        // $role = $token["role"];
        $result = $this->MainModel->updatePasswordByEmail($email, $this->MainModel->encryptData($password));
        if ($result) {
            $this->MainModel->deleteToken($token);
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(['message' => 'Password has been updated.']));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['error' => 'Failed to update password.']));
        }
    }

    function CheckTokenValidity($token)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $valid = $this->MainModel->checkTokenValidity($token);
            if ($valid) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "message" => "Token is Valid");
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "error", "message" => "Invalid or Expired Token");
            }
        } else {
            $this->output->set_status_header(405);
            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    // reset password ends


    // function to send otp
    function sendOTP()
    {
        $email = $this->input->post("email");

        if (!$this->MainModel->isMailExist($email)) {
            $otp = $this->generateOTP();
            $expires_at = date("Y-m-d H:i:s", strtotime('+3 minute'));

            if ($this->MainModel->addOTP($email, $otp, $expires_at)) {
                $mail = $this->phpmailer_lib->load();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'amplifierlover007@gmail.com'; // Your email
                $mail->Password = 'irjw yygg kfni dnue'; // Your email password
                $mail->SMTPSecure = 'tls'; // 'tls' for port 587
                $mail->Port = 587; // Port for 'tls'
                $mail->setFrom('amplifierlover007@gmail.com', 'Team Sevakalpak');
                $mail->addAddress($email);

                $mail->Subject = 'Mail Varification by Team Sevakalpak';
                $mail->isHTML(true);

                $mailContent = "<h1>Here is the OTP</h1>
                <h3>for varifying your mail</h3>
              <p>Your OTP for varifying mail is: <strong>{$otp}</strong>. Do not share the otp to any one</p>
              
              <p>Thank You</p>
              <p>Best Regards</p>
              <h3>Team SevaKalpak</h3>";
                $mail->Body = $mailContent;

                try {
                    if ($mail->send()) {
                        $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(array("status"=>"success", 'message'=>'OTP is mailed')));
                    } else {
                        $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(500)
                            ->set_output(json_encode(['error' => 'Failed to send password reset link.']));
                    }
                } catch (Exception $e) {
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['error' => 'Failed to send password reset link.', 'details' => $mail->ErrorInfo]));
                }
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['error' => 'Failed to store reset token.']));
            }
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['error' => 'Mail already exists.']));
        }
    }

    // function to authenticate otp
    function AuthenticateOTP(){
        $email = $this->input->post("email");
        $otp = $this->input->post("otp");

        $status = $this->MainModel->matchOTP($email, $otp);
        if($status){
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(['message' => 'OTP matched']));
        }else{
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['message' => 'OTP not matched']));
        }
    }

    // function to generate otp
    function generateOTP()
    {
        $randomnum = rand(1000, 9999);
        return $randomnum;
    }   // function ends
}
