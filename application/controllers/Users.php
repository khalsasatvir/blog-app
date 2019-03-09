<?php

class Users extends MY_controller

{

public function index()
{

log_message('error','error message in this line');

log_message('debug','debug message in this line');

log_message('info', 'Index method called');


  $this->load->model('loginmodel','ar');
  $this->load->library('pagination');
  $config=[
  'base_url' => base_url('users/index'),
  'per_page' =>2,
  'total_rows' => $this->ar->all_articles_count(),
  'full_tag_open'=>"<ul class='pagination'>",
  'full_tag_close'=>"</ul>",
  'next_tag_open' =>"<li>",
  'next_tag_close' =>"</li>",
  'prev_tag_open' =>"<li>",
  'prev_tag_close' =>"</li>",
  'num_tag_open' =>"<li>",
  'num_tag_close' =>"<li>",
  'cur_tag_open' =>"<li class='active'><a>",
  'cur_tag_close' =>"</a></li>"

  ];

  $this->pagination->initialize($config);
  $articles=$this->ar->all_articleList($config['per_page'],$this->uri->segment(3));

  $this->load->view('users/homepage',compact('articles'));
  }
public function register()
 {

log_message('info', 'Register method called');

  $this->load->view('admin/register');
 }
 public function sendemail()
 {

  $this->form_validation->set_rules('uname','User Name','required|alpha');
  $this->form_validation->set_rules('pass','Password','required|max_length[12]');
  $this->form_validation->set_rules('fname','First Name','required|alpha');
  $this->form_validation->set_rules('lname','last Name','required|alpha');
  $this->form_validation->set_rules('email','Email','required|valid_email|is_unique[users.email]');
$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
  if($this->form_validation->run())
  {
    $this->load->library('email');

    $this->email->from(set_value('email'),set_value('fname'));
    $this->email->to("ajay.suneja1993@gmail.com");
    $this->email->subject("Registratiion Greeting..");
    $this->email->message("Thank  You for Registratiion");
    $this->email->set_newline("\r\n");
    $this->email->send();
     if (!$this->email->send()) {
    show_error($this->email->print_debugger()); }
  else {
    echo "Your e-mail has been sent!";
  }
  }
  else
  {
   $this->load->view('Admin/register');
  }
 }
}
?>
