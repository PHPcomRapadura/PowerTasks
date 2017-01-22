<?php
defined('BASEPATH') or exit('Sem Permissão');

require_once(APPPATH.'controllers/Team.php');

class TeamTag extends CI_Controller{
  public function index(){

  }

  public function insert($team_id){
    $user = authorize(1);

    $team = Team::iManageThisTeam($team_id, $user->id_user);

    $new_tag = new stdClass();
    $new_tag->team_id = $team_id;
    $new_tag->created_by = $user->id_user;
    $new_tag->name = $this->input->post('name', true);
    $new_tag->color = $this->input->post('color', true);
    $new_tag->description = $this->input->post('description', true);
    $new_tag->created_in = datetime_current();

    $this->load->model('TeamTagModel');
    $this->TeamTagModel->insert($new_tag);

    $this->session->set_flashdata('success', 'Etiqueta Criada');
    redirect('time/'.$team_id.'#tags');
  }

  public function delete($team_id, $tag_id){
    $user = authorize(1);

    $team = Team::iManageThisTeam($team_id, $user->id_user);

    $teamTag = $this->thisTagBelongsToTheTeam($tag_id, $team_id);

    $this->TeamTagModel->deleteById($tag_id);

    $this->session->set_flashdata('success', 'Etiqueta Excluída');

    redirect('time/'.$team_id.'#tags');
  }

  public function edit($team_id, $tag_id){
    $user = authorize(1);

    $team = Team::iManageThisTeam($team_id, $user->id_user);

    $teamTag = $this->thisTagBelongsToTheTeam($tag_id, $team_id);

    $page = array(
      'page_title' => 'Editar Etiqueta',
      'page_content' => 'teamtag/edit',
      'user' => $user,
      'tag' => $teamTag,
      'team_id' => $team_id,
    );

    $this->load->view('public/base', $page);
  }

  public function update($team_id, $tag_id){
    $user = authorize(1);

    $team = Team::iManageThisTeam($team_id, $user->id_user);

    $teamTag = $this->thisTagBelongsToTheTeam($tag_id, $team_id);

    $teamTag->name = $this->input->post('name', true);
    $teamTag->description = $this->input->post('description', true);
    $teamTag->color = $this->input->post('color', true);

    $this->TeamTagModel->updateById($teamTag, $tag_id);

    $this->session->set_flashdata('success', 'Etiqueta Atualizada');
    redirect('time/'.$team_id.'#tags');
  }

  public function thisTagBelongsToTheTeam($id_tag, $team_id){
    $this->load->model('TeamTagModel');

    $tag = $this->TeamTagModel->searchByIdAndTeam($id_tag, $team_id);

    if(!$tag){
      $this->session->set_flashdata('error', 'Essa etiqueta não pertence ao time');
      redirect('time/'.$team_id.'#tags');
    }

    return $tag;
  }
}
