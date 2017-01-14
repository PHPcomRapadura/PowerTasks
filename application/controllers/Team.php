<?php
defined('BASEPATH') or exit('Sem Permissão');

class Team extends CI_Controller{
  public function index(){
    $user = authorize(1);

    $this->load->model('TeamModel');

    $page = [
      'page_title' => "Meus Times",
      'page_content' => 'team/list',
      'user' => $user,
      'teams' => $this->TeamModel->searchTeamsThatIManagement($user->id_user),
    ];

    $this->load->view('public/base', $page);
  }

  public function add(){
    $user = authorize(1);

    $page = [
      'page_title' => 'Novo Time',
      'page_content' => 'team/add',
      'user' => $user,
    ];

    $this->load->view('public/base', $page);
  }

  public function insert(){
    $user = authorize(1);

    $new_team = new stdClass();
    $new_team->name = $this->input->post('name', true);
    $new_team->description = $this->input->post('description', true);
    $new_team->admin_id = $user->id_user;
    $new_team->logo = 'team.png';
    $new_team->created_in = datetime_current();

    $this->load->model('TeamModel');
    $insert_team = $this->TeamModel->insert($new_team);

    if(!$insert_team){
      $this->session->set_flashdata('error', 'Erro ao criar time');
      redirect('time/novo');
    }

    if($_FILES['photo']['name'] != ""){
      $filename = upload_photo($insert_team, 'photo', './assets/img/teams/');
      if(!$filename){
        $this->session->set_flashdata('error', 'Por favor tente outra imagem');
        redirect('time/novo');
      }

      $new_team->logo = $filename;
      $this->TeamModel->updateById($new_team, $insert_team);
    }


    $this->session->set_flashdata('success', 'Novo Time Criado');
    redirect('time');
  }

  public function edit($id){
    $user = authorize(1);

    $team = $this->iManageThisTeam($id, $user->id_user);

    $page = [
      'page_title' => 'Editar Time',
      'page_content' => 'team/edit',
      'user' => $user,
      'team' => $team,
    ];

    $this->load->view('public/base', $page);
  }

  public function update($id){
    $user = authorize(1);

    $team = $this->iManageThisTeam($id, $user->id_user);

    $team->name = $this->input->post('name', true);
    $team->description = $this->input->post('description', true);

    if($_FILES['photo']['name'] != ""){
      $filename = upload_photo($id, 'photo', './assets/img/teams/');
      if(!$filename){
        $this->session->set_flashdata('error', 'Por favor tente outra imagem');
        redirect('time/editar/'.$id);
      }

      $team->logo = $filename;
    }

    $this->TeamModel->updateById($team, $id);

    $this->session->set_flashdata('success', 'Dados do Time Atualizados');
    redirect('time');
  }

  public function delete($id){
    $user = authorize(1);

    $this->iManageThisTeam($id, $user->id_user);

    $this->TeamModel->deleteById($id);

    $this->session->set_flashdata('success', 'Time Excluído');

    redirect('time');
  }

  public function iManageThisTeam($id, $user_id){
    $this->load->model('TeamModel');

    $my_team = $this->TeamModel->iManageThisTeam($id, $user_id);

    if(!$my_team){
      $this->session->set_flashdata('error', 'Escolha um time válido');
      redirect('/');
    }

    return $my_team;
  }
}
