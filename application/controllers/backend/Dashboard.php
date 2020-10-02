<?php

class Dashboard extends CI_Controller
{
    private $template;
    
    public function __construct()
    {
        parent::__construct();
        $this->template = 'backend/backend.template.php';
    }

    public function index()
    {
        $this->titleHeader = $this->navTitle = 'Dashboard';

        $this->load->view($this->template);
    }
}

?>