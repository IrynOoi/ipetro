<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_controller extends CI_Controller {
    public function index() {
        // Load your view
        $this->load->view('test');
    }
}
?>
