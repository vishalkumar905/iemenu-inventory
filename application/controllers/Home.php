<?php

class Home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('UserModel', 'user');
	}

	public function index()
	{
		$submit = $this->input->post('submit');
		
		if ($submit === 'Submit')
		{
			$this->form_validation->set_rules('userEmail', 'email', 'required');
			$this->form_validation->set_rules('userPass', 'password', 'required');
			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

			if ($this->form_validation->run())
			{
				$condition = [
					'email' => $this->input->post('userEmail'),
					'password' => $this->input->post('userPass'),
				];

				$user = $this->user->getWhereCustom('*', $condition)->result_array();
				if (!empty($user))
				{
					$user = $user[0];
					$loggedInData = [
						'isLoggedIn' => true,
						'loggedInUserData' => [
							'userId' => $user['id'],
							'email' => $user['email'],
							'type' => $user['type'],
						]
					];
					
					$this->session->set_userdata($loggedInData);

					redirect('backend/dashboard');
				}
				else
				{
					$flashData = [
						'flashMessage' => 'Incorrect Credentials'
					];

					$this->session->set_flashdata($flashData);

					redirect(current_url());
				}
			}
		}

		$data['flashMessage'] = $this->session->flashdata('flashMessage');

		$this->load->view('login/index', $data);
	}
}

?>