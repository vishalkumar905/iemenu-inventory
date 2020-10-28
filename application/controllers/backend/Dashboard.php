<?php

class Dashboard extends Backend_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->titleHeader = $this->navTitle = 'Dashboard';

        $this->load->view($this->template);
    }
}

?>