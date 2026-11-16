<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->model('Auth_model', 'auth');
        $this->load->model('Log_model', 'logging');
    }

    public function output_json($data, $encode = true) {
        if($encode) $data = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($data);
    }

    public function index() {
        $this->load->helper('init');

        if (!database_connected() || is_database_empty() || is_setting_empty()) {
            redirect('install');
        }

        if ($this->session->userdata('is_login')) {
            redirect('dashboard');
        } else {
            $this->load->model('Users_model', 'users');
            $this->load->model('Settings_model', 'settings');

            $setting = $this->settings->getSetting();
            $data['setting'] = $setting;
            $data['username'] = [
                'name' => 'username',
                'id' => 'identity',
                'type' => 'text',
                'placeholder' => 'Username',
                'autofocus' => 'autofocus',
                'class' => 'form-control',
                'autocomplete' => 'off'
            ];
            $data['password'] = [
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'placeholder' => 'Password',
                'class' => 'form-control',
            ];
            $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->load->view('_templates/auth/_header', $data);
            $this->load->view('auth/new_login');
            $this->load->view('_templates/auth/_footer');
        }
    }

    public function login() {
        $success = false;
        $message = '';
        $role = '0';
        if ($this->input->post()) {
            // cek post
            $this->load->library('form_validation');

            $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[5]|max_length[50]');
            $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[5]|max_length[50]');
            if ($this->form_validation->run() === TRUE) {
                // lanjutkan jika lolos validasi
                $username = htmlspecialchars($this->input->post('username'), ENT_QUOTES, 'UTF-8');
                $password = htmlspecialchars($this->input->post('password'), ENT_QUOTES, 'UTF-8');

                // Cek percobaan login (error login)
                $attempts = $this->auth->cekErrorLogin($username);
                if (count($attempts) >= 3) {
                    // Cek selisih waktu dari percobaan login terakhir
                    // Gunakan `end()` untuk mengambil waktu terakhir
                    $timeDifference = time() - end($attempts)->time;
                    if ($timeDifference < 300) {
                        // Jika belum 5 menit
                        $message = 'Anda sudah 3x melakukan percobaan login. Silakan tunggu 5 menit atau hubungi Administrator.';
                        $this->output_json(['success' => $success, 'message' => $message]);
                        return;
                    } else {
                        // Jika lebih dari 5 menit, reset percobaan login
                        $this->auth->resetErrorLogin($username);
                    }
                }

                // Ambil data user berdasarkan username
                $user = $this->auth->login($username);
                if ($user) {
                    if ($user->active === '1') {
                        // Lanjutkan proses login jika belum 3x salah atau waktu blokir sudah habis
                        if (password_verify($password, $user->password)) {
                            $role = $user->role;
                            // cek apakah akun nonaktif
                            // Jika login berhasil
                            $this->session->set_userdata([
                                'user_id' => $user->id,
                                'username' => $user->username,
                                'role' => $user->group_id,
                                'role_name' => $user->role_name,
                                'is_login' => true
                            ]);
                            $this->auth->resetErrorLogin($username);
                            $message = 'Login berhasil';
                            $success = true;
                            session_write_close();
                            $this->logging->saveLog(1, 'Login');
                        } else {
                            // Jika password salah
                            $error = [
                                'ip_address' => $this->input->ip_address(),
                                'login' => $username,
                                'time' => time()
                            ];
                            $this->auth->errorLogin($error); // Catat kesalahan login
                            $message = 'Username atau Password salah!';
                        }
                    } else {
                        $message = 'Akun Anda dinonaktifkan!';
                    }
                } else {
                    // Jika user tidak ditemukan
                    $message = 'Akun tidak terdaftar!';
                }
            } else {
                $message = 'Username atau Password salah!';
                $invalid = [
                    'username' => form_error('username'),
                    'password' => form_error('password')
                ];
            }
        }

        $data = [
            'success' 	=> $success,
            'message' 	=> $message,
            'error'     => $invalid ?? [],
            'role'      => $role
        ];
        $this->output_json($data);
    }

    public function register() {
        if ($this->input->post()) {
            $group = $this->input->post('role');
            $data = [
                'username' => $this->input->post('username'),
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'first_name' => '',
                'last_name' => ''
            ];
            $this->auth->register($data, $group);
            redirect('auth');
        } else {
            $this->load->view('register');
        }
    }

    public function logout() {
        $this->logging->saveLog(2, 'Logout');
        $this->session->sess_destroy();
        redirect('auth');
    }
}