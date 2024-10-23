
    function UploadServices()
    {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formdata = $this->input->post();
        $config['upload_path'] = 'uploads/services';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = 51200; // 50 MB in KB
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (isset($_FILES["eventimage"])) {
            if (!$this->upload->do_upload('eventimage')) {
                $data['error'] = $this->upload->display_errors();
                print_r($data);
            } else {
                $data['upload_data'] = $this->upload->data();
                $formdata['eventimage'] = $data['upload_data']['file_name'];
            }
        }
        $result = $this->EventManagerModel->uploadServices($formdata);
        if ($result == true) {
            $this->output->set_status_header(201);
            $response = array("status" => "success", "message" => "Services added successfully");
        } else {
            $this->output->set_status_header(500);
            $response = array("status" => "error", "message" => "Some error occured while adding services");
        }
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }
    
}