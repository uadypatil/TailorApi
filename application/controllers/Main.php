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

        $this->load->Model("AdminModel");
        $this->AdminModel->autocreateAdmin();
    }


    function Login()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $formdata = $this->input->post();

            // Validate input fields
            if ($formdata == null) {
                $this->output->set_status_header(400);
                $response = array("status" => "error", "message" => "Missing required fields");
                $this->output->set_content_type("application/json")->set_output(json_encode($response));
                return;
            }

            $email = trim($formdata["email"]);
            $password = $formdata["password"];
            $type = trim($formdata["type"]);

            // Authenticate user
            $user = $this->MainModel->AuthenticateLoginPost($email, $password, $type);

            if ($user !== null) {
                // Generate token for authenticated user (optional)
                $token = bin2hex(random_bytes(16)); // Example token
                $this->output->set_status_header(200);
                $response = array(
                    "status" => "success",
                    "data" => $user,
                    "token" => $token, // Include token in the response
                    "message" => "Login successful"
                );
            } else {
                $this->output->set_status_header(401); // Unauthorized
                $response = array("status" => "error", "message" => "Invalid credentials");
            }
        } else {
            $this->output->set_status_header(405); // Method Not Allowed
            $response = array("status" => "error", "message" => "Request method not allowed");
        }

        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function RegistrationPost()
    {
        $returned = "";
        if ($this->input->server('REQUEST_METHOD') === "POST") {
            $formdata = $this->input->post();

            if (empty($formdata['email']) || empty($formdata['contactno']) || empty($formdata['password']) || empty($formdata['type'])) {
                $this->output->set_status_header(400);
                $response = array("status" => "Error", "message" => "Missing required fields");
                $this->output->set_content_type("application/json")->set_output(json_encode($response));
                return;
            }

            $authenticationdata = array(
                'email' => $formdata['email'],
                'contactno' => $formdata['contactno'],
                'password' => $formdata['password'],
                'type' => $formdata['type'],
            );

            $authid = $this->MainModel->AuthenticationPost($authenticationdata);

            if ($authid === "contactno exist") {
                $this->output->set_status_header(409);
                $response = array("status" => "Error", "message" => "Contact Number Already Exists");
                $returned  = -1;
            } elseif ($authid === "email exist") {
                $this->output->set_status_header(409);
                $response = array("status" => "Error", "message" => "Email Already Exists");
                $returned = -2;
            } elseif ($authid > 0) {
                if ($formdata['type'] === "tailor") {
                    $tailordata = array(
                        "authid" => $authid,
                        "name" => $formdata['name'],
                        "document_type" => $formdata['document_type'],
                    );

                    if (!empty($formdata['shop_license'])) {
                        $tailordata["shop_license"] = $formdata['shop_license'];
                    }

                    if (!empty($_FILES['document']['name'])) {
                        $config['upload_path'] = 'uploads/tailor/documents';
                        $config['allowed_types'] = 'jpg|jpeg|png|gif';
                        $config['max_size'] = 51200;

                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);

                        if ($this->upload->do_upload('document')) {
                            $upload_data = $this->upload->data();
                            $tailordata['document'] = $upload_data['file_name'];
                        } else {
                            $this->output->set_status_header(400);
                            $response = array("status" => "Error", "message" => $this->upload->display_errors());
                            $this->output->set_content_type("application/json")->set_output(json_encode($response));
                            return;
                        }
                    }

                    $returned = $this->MainModel->setTailorDataPost($tailordata);

                    $this->output->set_status_header(200);
                    $response = array("status" => "Success", "message" => "Registration Successful");
                    $this->output->set_content_type("application/json")->set_output(json_encode($response));

                } elseif ($formdata['type'] === "user") {
                    $userdata = array(
                        "authid" => $authid,
                        "name" => $formdata['name']
                    );
                    $returned = $this->MainModel->setUserDataPost($userdata);

                    $this->output->set_status_header(200);
                    $response = array("status" => "Success", "message" => "Registration Successful");
                    $this->output->set_content_type("application/json")->set_output(json_encode($response));

                } else {
                    $this->output->set_status_header(400);
                    $response = array("status" => "Error", "message" => "Invalid User Type");
                    // $returned = -3;
                    $this->output->set_content_type("application/json")->set_output(json_encode($response));
                }
            } else {
                $response = array("status" => "Error", "message" => "Failed to Register");
                $this->output->set_status_header(500);
                $this->output->set_content_type("application/json")->set_output(json_encode($response));
            }

            // if ($returned > 0) {
            //     $this->output->set_status_header(200);
            //     $response = array("status" => "Success", "message" => "Registration Successful");
            //     $this->output->set_content_type("application/json")->set_output(json_encode($response));
            // }
        } else {
            $this->output->set_status_header(405);
            $response = array("status" => "Error", "message" => "Method Not Allowed");
            $this->output->set_content_type("application/json")->set_output(json_encode($response));
        }
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
    function SendOTP()
    {
        $email = $this->input->post("email");

        if ($email != null || $email != "") {

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
                                ->set_output(json_encode(array("status" => "success", 'message' => 'OTP is mailed', "success" => true)));
                        } else {
                            $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(500)
                                ->set_output(json_encode(array('error' => 'Failed to send password reset link.', "success" => false)));
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
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['error' => 'Empty mail passed, please fill mail']));
        }
    }

    // function to authenticate otp
    function AuthenticateOTP()
    {
        $email = $this->input->post("email");
        $otp = $this->input->post("otp");

        $status = $this->MainModel->matchOTP($email, $otp);
        if ($status) {
            $this->MainModel->deleteOTP($email);

            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array('message' => 'OTP matched', 'success' => true)));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(array('message' => 'OTP not matched', 'success' => false)));
        }
    }

    // function to generate otp
    function generateOTP()
    {
        $randomnum = rand(1000, 9999);
        return $randomnum;
    }   // function ends


    // function to reset password
    function ResetPassword()
    {
        $email = $this->input->post("email");
        $newPassword = $this->input->post("password");

        if (!empty($email) && !empty($newPassword)) {
            // Encrypt the new password
            $passwordData = array("Password" => $this->UserModel->encryptData($newPassword));

            // Update the password in the database
            if ($this->UserModel->UpdatePasswordByMail($email, $passwordData)) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode([
                        'success' => true,
                        'message' => 'Password updated successfully'
                    ]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode([
                        'success' => false,
                        'message' => 'Failed to update password, please try again.'
                    ]));
            }
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Email and password fields cannot be empty.'
                ]));
        }
    }


    // function to resend OTP
    public function resendOTP()
    {
        $email = $this->input->post("email");

        if (!empty($email)) {
            // Check if the email exists
            if ($this->UserModel->isMailExist($email)) {
                // Generate a new OTP
                $otp = $this->generateOTP();
                $expires_at = date("Y-m-d H:i:s", strtotime('+3 minutes'));

                // Update OTP and expiration in the database
                if ($this->UserModel->updateOTP($email, $otp, $expires_at)) {
                    // Send the new OTP via email
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

                    $mail->Subject = 'Resent OTP Verification by Team Sevakalpak';
                    $mail->isHTML(true);

                    // Create email body content
                    $mailContent = "<h1>Your OTP Code</h1>
                    <h3>for verifying your email</h3>
                    <p>Your OTP for email verification is: <strong>{$otp}</strong>. Please keep it secure and do not share it with anyone.</p>
                    <p>Thank You</p>
                    <p>Best Regards</p>
                    <h3>Team SevaKalpak</h3>";
                    $mail->Body = $mailContent;

                    // Send email and handle the result
                    if ($mail->send()) {
                        $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(['success' => true, 'message' => 'OTP resent successfully.']));
                    } else {
                        $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(500)
                            ->set_output(json_encode(['success' => false, 'message' => 'Failed to resend OTP.']));
                    }
                } else {
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['success' => false, 'message' => 'Failed to update OTP in the system.']));
                }
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode(['success' => false, 'message' => 'Email does not exist in our records.']));
            }
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['success' => false, 'message' => 'No email provided.']));
        }
    }
}
