<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Pages extends CI_Controller {
		private $page;
		
		public function __construct() {
			parent::__construct();
			$this->page = 'pages/';
		}
		
		public function index() {
			$data['title'] = "Index";
			$data['page'] = $this->page;
			
			$this->load->view('templates/header', $data);
			$this->load->view('templates/links');
			$this->load->view('templates/menu');
			$this->load->view('index');
			$this->load->view('templates/footer');
		}
		
		public function register() {
			$data['title'] = "Register";
			$data['page'] = $this->page;
			
			$rules = array(
				array(
					'field' => 'username',
					'label' => 'Username',
					'rules' => 'trim|required|min_length[4]|max_length[16]|xss_clean'
				),
				array(
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'trim|required|min_length[4]|max_length[16]|matches[passconf]'
				),
				array(
					'field' => 'passconf',
					'label' => 'Confirm Password',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'email',
					'label' => 'Email',
					'rules' => 'trim|required|valid_email'
				)
			);
			
			$this->form_validation->set_rules($rules);
			
			$this->load->view('templates/header', $data);
			$this->load->view('templates/links');
			$this->load->view('templates/menu');
			
			$vals = array(
				'img_path'	=> $_SERVER['DOCUMENT_ROOT'] . '/captcha/',
				'img_url'	=> base_url() . '/captcha/'
			);
			
			$captcha = create_captcha($vals);
			
			$info = array(
				'captcha_time'	=> $captcha['time'],
				'ip_address'	=> $this->input->ip_address(),
				'word'		=> $captcha['word']
			);
			
			$query = $this->db->insert_string('captcha', $info);
			$this->db->query($query);
			
			$data['image'] = $captcha['image'];
			
			if($this->form_validation->run()) {
				$username = $this->input->post('username');
				$password = $this->input->post('password');
				$passconf = $this->input->post('passconf');
				$email = $this->input->post('email');
				$captcha = $this->input->post('captcha');
				
				if($this->Pages_model->verify_captcha($captcha) == false) {
					$data['error'] = 'Captcha code not good.';
					$this->load->view('register', $data);
				} elseif($this->Pages_model->verify_email($email) == 0) {
					$data['error'] = 'Email already in use.';
					$this->load->view('register', $data);
				} elseif($this->Pages_model->verify_username($username) == 0) {
					$date['error'] = 'Username already in use';
					$this->load->view('register', $data);
				} elseif($password != $passconf) {
					$data['error'] = 'Passwords do not match';
					$this->load->view('register', $data);
				} else {
					$confirmCode = md5(uniqid(rand()));
					if($this->Pages_model->verify_first_user()) {
						$data = array(
							'username'	=> $username,
							'password'	=> $this->encrypt->encode($password),
							'email'		=> $email,
							'is_admin'	=> 1,
							'confirm_code'	=> $confirmCode,
							'confirmed'	=> 1
						);
					} else {
						$data = array(
							'username'	=> $username,
							'password'	=> $this->encrypt->encode($password),
							'email'		=> $email,
							'is_admin'	=> 0,
							'confirm_code'	=> $confirmCode
						);
					}
					$userId = $this->Pages_model->create_user($data);
					$this->Pages_model->send_confirmation_mail($email, $username, $confirmCode);
					redirect('pages/login');
				}
			} else {
				$this->load->view('register', $data);
			}
			
			$this->load->view('templates/footer');
		}
		
		public function login() {
			$data['title'] = "Login";
			$data['page'] = $this->page;
			
			$rules = array(
				array(
					'field' => 'username',
					'label' => 'Username',
					'rules' => 'required|xss_clean'
				),
				array(
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'required|xss_clean'
				)
			);
			
			$this->form_validation->set_rules($rules);
			$this->form_validation->set_error_delimiters('<em>', '</em>');
			
			$this->load->view('templates/header', $data);
			$this->load->view('templates/links');
			$this->load->view('templates/menu');
			
			if($this->input->post('login')) {
				if($this->form_validation->run()) {
					$username = $this->input->post('username');
					$password = $this->input->post('password');
					
					if($this->Pages_model->validate_combination($username, $password)) {
						if($this->Pages_model->is_user_confirmed($username)) {
							$userId = $this->Pages_model->get_user_id($username);
							$admin = $this->Pages_model->is_admin($username);
							
							$this->session->set_userdata('userId', $userId);
							$this->session->set_userdata('admin', $admin == 1 ? true : false);
							
							$this->Pages_model->logged_in($username);
							redirect('account');
						} else {
							$data['erorr'] = 'You have not confirmed this account yet.<br /><a href="resend">Resend confirmation mail</a>';
							$this->load->view('login', $data);
						}
					} else {
						$data['error'] = '*Waving* That is not the combination you are looking for.';
						$this->load->view('login', $data);
					}
				} else {
					$data['error'] = 'A user does not exist for the username specified.';
					$this->load->view('login', $data);
				}
			} else {
				$this->load->view('login');
			}
			
			$this->load->view('templates/footer');
		}
		
		public function resend() {
			$data['title'] = "Resend";
			$data['page'] = $this->page;
			
			$rules = array(
				array(
					'field' => 'username',
					'label' => 'Username',
					'rules' => 'required|xss_clean'
				),
				array(
					'field' => 'email',
					'label' => 'Email',
					'rules' => 'trim|required|valid_email'
				)
			);
			
			$this->form_validation->set_rules($rules);
			$this->form_validation->set_error_delimiters('<em>', '</em>');
			
			$this->load->view('templates/header', $data);
			$this->load->view('templates/links');
			$this->load->view('templates/menu');
			
			$vals = array(
				'img_path'	=> $_SERVER['DOCUMENT_ROOT'] . '/captcha/',
				'img_url'	=> base_url() . '/captcha/'
			);
			
			$captcha = create_captcha($vals);
			
			$info = array(
				'captcha_time'	=> $captcha['time'],
				'ip_address'	=> $this->input->ip_address(),
				'word'		=> $captcha['word']
			);
			
			$query = $this->db->insert_string('captcha', $info);
			$this->db->query($query);
			
			$data['image'] = $captcha['image'];
			
			if($this->form_validation->run()) {
				$username = $this->input->post('username');
				$email = $this->input->post('email');
				$captcha = $this->input->post('captcha');
				
				if($this->Pages_model->verify_captcha($captcha)) {
					if($this->Pages_model->verify_email($email)) {
						if($this->Pages_model->verify_username($username)) {
							$confirmCode = md5(uniqid(rand()));
							$this->Pages_model->send_confirmation_mail($email, $username, $confirmCode);
							$data['message'] = 'Confirmation mail sent.<br /><a href="login">Back to login page</a>';
							$this->load->view('resend', $data);
						} else {
							$data['error'] = 'Username not in database.';
							$this->load->view('resend', $data);
						}
					} else {
						$data['error'] = 'Email not in database';
						$this->load->view('resend', $data);
					}
				} else {
					$data['error'] = 'Captcha code not good.';
					$this->load->view('resend', $data);
				}
			} else {
				$this->load->view('resend', $data);
			}
			
			$this->load->view('templates/footer');
		}
		
		public function forgot_pw() {
			$data['title'] = 'Forgot Password';
			$data['page'] = $this->page;
			
			$rules = array(
				array(
					'field' => 'username',
					'label' => 'Username',
					'rules' => 'required|xss_clean'
				),
				array(
					'field' => 'email',
					'label' => 'Email',
					'rules' => 'trim|required|valid_email'
				)
			);
			
			$this->form_validation->set_rules($rules);
			$this->form_validation->set_error_delimiters('<em>', '</em>');
			
			$this->load->view('templates/header', $data);
			$this->load->view('templates/links');
			$this->load->view('templates/menu');
			
			$vals = array(
				'img_path'	=> $_SERVER['DOCUMENT_ROOT'] . '/captcha/',
				'img_url'	=> base_url() . '/captcha/'
			);
			
			$captcha = create_captcha($vals);
			
			$info = array(
				'captcha_time'	=> $captcha['time'],
				'ip_address'	=> $this->input->ip_address(),
				'word'		=> $captcha['word']
			);
			
			$query = $this->db->insert_string('captcha', $info);
			$this->db->query($query);
			
			$data['image'] = $captcha['image'];
			
			if($this->form_validation->run()) {
				$username = $this->input->post('username');
				$email = $this->input->post('email');
				$captcha = $this->input->post('captcha');
				
				if($this->Pages_model->verify_captcha($captcha)) {
					if($this->Pages_model->verify_email($email)) {
						if($this->Pages_model->verify_username($username)) {
							$confirmCode = md5(uniqid(rand()));
							$this->Pages_model->recover_password_mail($email, $username);
							$data['message'] = 'Your password has been sent to your email.<br /><a href="login">Back to login page.</a>';
							$this->load->view('forgot-pw', $data);
						} else {
							$data['error'] = 'Username not in database.';
							$this->load->view('forgot-pw', $data);
						}
					} else {
						$data['error'] = 'Email not in database.';
						$this->load->view('forgot-pw', $data);
					}
				} else {
					$data['error'] = 'Captcha code not good.';
					$this->load->view('forgot-pw', $data);
				}
			} else {
				$this->load->view('forgot-pw', $data);
			}
			
			$this->load->view('templates/footer');
		}
		
		public function forgot_user() {
			$data['title'] = 'Forgot Username';
			$data['page'] = $this->page;

			$rules = array(
				array(
					'field' => 'email',
					'label' => 'Email',
					'rules' => 'trim|required|valid_email'
				)
			);
			
			$this->form_validation->set_rules($rules);
			$this->form_validation->set_error_delimiters('<em>','</em>');
			
			$this->load->view('templates/header', $data);
			$this->load->view('templates/links');
			$this->load->view('templates/menu');
			
			$vals = array(
				'img_path'	=> $_SERVER['DOCUMENT_ROOT'] . '/captcha/',
				'img_url'	=> base_url() . '/captcha/'
								);
			
			$captcha = create_captcha($vals);
			
			$data = array(
				'captcha_time'	=> $captcha['time'],
				'ip_address'	=> $this->input->ip_address(),
				'word'			=> $captcha['word']
			);
			
			$query = $this->db->insert_string('captcha', $data);
			$this->db->query($query);
			
			$data['image'] = $captcha['image'];
			
			if($this->form_validation->run()) {
				$email = $this->input->post('email');
				$captcha = $this->input->post('captcha');
				
				if($this->Pages_model->verify_captcha($captcha)) {
					if($this->Pages_model->verify_email($email)) {
						$this->Pages_model->recover_username_mail($email);
						$data['message'] = 'Your username has been sent to your email.<br /><a href="login">Back to login page.</a>';
						$this->load->view('forgot-user', $data);
					} else {
						$data['error'] = 'Email not in database.';
						$this->load->view('forgot-user', $data);
					}
				} else {
					$data['error'] = 'Captcha code not good.';
					$this->load->view('forgot-user', $data);
				}
			} else {
				$this->load->view('forgot-user', $data);
			}
			
			$this->load->view('templates/footer');
		}
		
		public function confirm() {
			$data['title'] = 'Confirm';
			$data['page'] = $this->page;
			
			$rules = array(
				array(
					'field' => 'confirm',
					'label' => 'Confirm Code',
					'rules' => 'required|xss_clean'
				)
			);
			
			$this->form_validation->set_rules($rules);
			$this->form_validation->set_error_delimiters('<em>', '</em>');
			
			$this->load->view('templates/header', $data);
			$this->load->view('templates/links');
			$this->load->view('templates/menu');
			
			if($this->form_validation->run()) {
				$confirmCode = $this->input->post('confirmCode');
				
				if($this->Pages_model->verify_confirm_code($confirmCode)) {
					$username = $this->Pages_model->verify_confirm_code($confirmCode);
					$this->Pages_model->activate_account($username);
					$data['message'] = 'Your account has been activated.<br /><a href="login">Go to login page.</a>';
					$this->load->view('confirm', $data);
				} else {
					$data['error'] = 'Confirm code not good.';
					$this->load->view('confirm', $data);
				}
			} else {
				$this->load->view('confirm');
			}
			
			$this->load->view('templates/footer');
		}
	}
?>