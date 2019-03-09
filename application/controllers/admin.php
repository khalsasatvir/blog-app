<?php

class admin extends MY_controller

{

public function welcome()
{

$this->load->model('loginmodel','ar');

$this->load->library('pagination');

$config=[

       'base_url' => base_url('admin/welcome'),

       'per_page' =>4,

       'total_rows' => $this->ar->num_rows(),

       'full_tag_open'=>"<ul class='pagination'>",

       'full_tag_close'=>"</ul>",

       'next_tag_open' =>"<li>",

       'next_tag_close' => "</li>",

       'prev_tag_open' =>"<li>",

       'prev_tag_close' => "</li>",

       'num_tag_open'  => "<li>",

       'num_tag_close'  =>"</li>",

        'cur_tag_open'  =>"<li class='active'></li><a>",

        'cur_tag_close'  =>"</a></li>"



];

$this->pagination->initialize($config);


$articles=$this->ar->articleList($config['per_page'],$this->uri->segment(3));

$this->load->view('admin/dashboard',['articles'=>$articles]);


}

public function adduser()
{

$this->load->view('admin/add_article');

}

public function userValidation()
{
  $config=[
  'upload_path'=>'./upload/',
  'allowed_types'=>'gif|jpg|png',
  ];

  $this->load->library('upload',$config);

if($this->form_validation->run('add_article_rules') && $this->upload->do_upload())
{
$post=$this->input->post();

$data=$this->upload->data();

$image_path=base_url("upload/".$data['raw_name'].$data['file_ext']);

  $post['image_path']=$image_path;

$this->load->model('loginmodel','useradd');

if($this->useradd->add_articles($post))
{

$this->session->set_flashdata('msg','Article added successfully');

$this->session->set_flashdata('msg_class','alert-success');


}

else
{

$this->session->set_flashdata('msg','Article not added Please try again!!');

$this->session->set_flashdata('msg_class','alert-danger');


}

return redirect('admin/welcome');


}


else
{

$upload_error=$this->upload->display_errors();

$this->load->view('admin/add_article',compact('upload_error'));


}

}

public function edituser($id)
{

 $this->load->model('loginmodel');

 $article=$this->loginmodel->find_article($id);

 $this->load->view('admin/edit_article',['article'=>$article]);

}

public function updatearticle($article_id)
{

if($this->form_validation->run('add_article_rules'))
{

$post=$this->input->post();

//$articleid=$post['article_id'];

//unset($articleid);

$this->load->model('loginmodel','editupdate');

if($this->editupdate->update_article($article_id,$post))
{


$this->session->set_flashdata('msg','Article Updated successfully');

$this->session->set_flashdata('msg_class','alert-success');

}

else
{


$this->session->set_flashdata('msg','Article not Updated ... Please try again!!');

$this->session->set_flashdata('msg_class','alert-danger');


}

return redirect('admin/welcome');

}

else
{

$this->load->view('admin/edituser');


}



}



public function delarticle()
{

$id=$this->input->post('id');

$this->load->model('loginmodel','delarticle');

if($this->delarticle->del($id))
{


$this->session->set_flashdata('msg','Article Deleted Successfully');

$this->session->set_flashdata('msg_class','alert-success');



}

else
{


$this->session->set_flashdata('msg','Article not deleted successfully Please try again!!');

$this->session->set_flashdata('msg_class','alert-danger');




}

return redirect('admin/welcome');

}


public function __construct()
    {

     parent::__construct();

     if(!$this->session->userdata('id'))
     return redirect('login');


    }


public function logout()
{

 $this->session->unset_userdata('id');
 return redirect('login');


}

public function register()
{

$this->load->view('admin/register');

}

public function sendemail()
{

$this->form_validation->set_rules('uname','User Name','required|alpha');

$this->form_validation->set_rules('pass','Password','required|max_length[12]');

$this->form_validation->set_rules('fname','First Name','required|alpha');

$this->form_validation->set_rules('lname','Last Name','required|alpha');

$this->form_validation->set_rules('email','Email','required|valid_email|is_unique[users.email]');

$this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');


if($this->form_validation->run())

{

    $this->load->library('email');


    $this->email->from(set_value('email'),set_value('fname'));
    $this->email->to("satvirsinghsatvir@gmail.com");
    $this->email->subject("Registration Greeting...");

    $this->email->message("Thank you for Registration");

    $this->email->set_newline("\r\n");

    $this->email->send();

    if($this->email->send()){

      show_error($this->email->print_debugger()); }



      else{

        echo "Your email has been sent!";

      }

     }


     else
     {

       $this->load->view('Admin/register');



     }



    }



}



?>
