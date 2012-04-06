<?php
	class Pages_model extends CI_Model {
		private $config;
		
		public function __construct() {
			$this->config = array(
				'protocol'	=> 'mail',
				'wordwrap'	=> TRUE,
				'mailtype'	=> 'html',
				'charset'	=> 'utf-8'
			);

			$this->email->initialize($this->config);
		}
		public function verify_first_user() {
			$query = $this->db->query('SELECT COUNT(id) AS nr FROM users;');
			$result = $query->result_array();
			
			return $result[0]['nr'] == 0;
		}
		
		public function get_user_id($username) {
			$this->db->select('id');
			$this->db->from('users');
			$this->db->where('username', $username);
			$userId = $this->db->get();
			$result = $userId->result_array();
			$userId = $result[0]['ID'];
			
			return $userId;
		}
		
		public function is_admin($username) {
			$this->db->select('is_admin');
			$this->db->from('users');
			$this->db->where('username', $username);
			$result = $this->db->get()->result_array();
			$admin = $result[0]['is_admin'];
			
			return $admin;
		}
		
		public function create_user($data) {
			$this->db->insert('users', $data);
			
			return $this->db->insert_id();
		}
		
		public function verify_username($username) {
			$this->db->select('*');
			$this->db->from('users');
			$this->db->where('username', $username);
			$verifyUsername = $this->db->get();
			
			return $verifyUsername->num_rows == 0;
		}
		
		public function verify_email($email) {
			$this->db->select('*');
			$this->db->from('users');
			$this->db->where('email', $email);
			$verifyEmail = $this->db->get();
			
			return $verifyEmail->num_rows == 0;
		}
		
		public function validate_combination($username, $password) {
			$this->db->select('password');
			$this->db->from('users');
			$this->db->where('username', $username);
			$validateCombination = $this->db->get();
			$dbPassword = $validateCombination->result_array();
			$dbPassword = $this->encrypt->decode($dbPassword[0]['password']);
			
			return $dbPassword == $password;
		}
		
		public function send_confirmation_mail($email, $username, $confirm_code) {
			$this->email->from('noreply@adn.phpfogapp.com', 'Echipa Virtual Tour');
			$this->email->to($email);
			
			$this->email->subject('[Virtual Tour] Confirm you account');
			$this->email->set_alt_message('To view the message, please use an HTML compatible email viewer!');
			$this->email->message('
				<html>
					<head>
					</head>
					<body>
						<h2>Dear ' . $username . '</h2><br />
						<p>Thank you for registerring to our website.</p>
						<p>Your confirmation link is <a href="http://adn.phpfogapp.com/pages/confirm">here</a></p>
						<p>If you can\'t click the link above, copy this link <span style="color:blue">http://adn.phpfogapp.com/pages/confirm</span> and paste it in your browser\'s address bar.</p>
						<p>Your confirm code: <b>'.$confirm_code.'</b></p><br />
						<p>Thanks</p>
						<p>Echipa Virtual Tour</p>
					</body>
				</html>');
			$this->email->send();
			//echo $this->email->print_debugger();
		}
		
		public function recover_password_mail($email, $username) {
			$this->db->select('password');
			$this->db->from('users');
			$this->db->where('username', $username);
			$result = $this->db->get();
			$result = $result->result_array();
			$password = $this->encrypt->decode($result[0]['password']);
			
			$this->email->from('noreply@adn.phpfogapp.com', 'Echipa Virtual Tour');
			$this->email->to($email);
			
			$this->email->subject('[Virtual Tour] Recover your password');
			$this->email->set_alt_message('To view the message, please use an HTML compatible email viewer!');
			$this->email->message('
				<html>
					<head>
					</head>
					<body>
						<h2>Dear ' . $username . '</h2><br />
						<p>You or someone who thinks is you requested to reset your password.</p>
						<p>Your password is <b>' . $password . '</b></p><br />
						<p>Thanks</p>
						<p>Echipa Virtual Tour</p>
					</body>
				</html>');
			$this->email->send();
			//echo $this->email->print_debugger();
		}
		
		public function recover_username_mail($email) {
			$this->db->select('username');
			$this->db->from('users');
			$this->db->where('email', $email);
			$result = $this->db->get();
			$result = $result->result_array();
			$username = $result[0]['username'];
			
			$this->email->from('noreply@adn.phpfogapp.com', 'Echipa Virtual Tour');
			$this->email->to($email);
			
			$this->email->subject('[Virtual Tour] Recover your username');
			$this->email->set_alt_message('To view the message, please use an HTML compatible email viewer!');
			$this->email->message('
				<html>
					<head>
					</head>
					<body>
						<h2>Dear user</h2><br />
						<p>You or someone who thinks is you requested to recover your username.</p>
						<p>Your username is <b>' . $username . '</b>' . '.</p><br />
						<p>Thanks</p>
						<p>Echipa Virtual Tour</p>
					</body>
				</html>');
			$this->email->send();
			//echo $this->email->print_debugger();
		}
		
		public function logged_in($username) {
			$this->db->query('UPDATE users SET last_login = NOW() WHERE username = \'' . $username . '\'');
			
			return true;
		}
		
		public function verify_captcha($captcha) {
			$expiration = time() - 7200;
			$this->db->query('DELETE FROM captcha WHERE captcha_time < ' . $expiration);
			
			$sql = 'SELECT COUNT(captcha_id) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?';
			$binds = array($_POST['captcha'], $this->input->ip_address(), $expiration);
			$query = $this->db->query($sql, $binds);
			$row = $query->row();
			
			if($row->count == 0) {
				return false;
			} else {
				return true;
			}
		}
		
		public function is_user_confirmed($username) {
			$this->db->select('confirmed');
			$this->db->from('users');
			$this->db->where('username', $username);
			$query = $this->db->get();
			$result = $query->result_array();
			$confirmed = $result[0]['confirmed'];
			
			return $confirmed == 1 ? true : false;
		}
		
		public function verify_confirm_code($confirmCode) {
			$this->db->select('username');
			$this->db->from('users');
			$this->db->where('confirm_code', $confirmCode);
			$query = $this->db->get();
			$result = $query->result_array();
			$username = $result[0]['username'];
			
			if($username) {
				return $username;
			} else {
				return false;
			}
		}
		
		public function activate_account($username) {
			$this->db->query('UPDATE users SET confirmed = 1 WHERE username = \'' . $username . '\'');
		}
	}
?>