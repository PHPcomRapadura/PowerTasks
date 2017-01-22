<?php
defined('BASEPATH') or exit('Sem Permissão');

class Tag extends CI_Controller{
  public function index(){

  }

  public function insert(){
    $user = authorize(1);

    $new_tag = new stdClass();
    $new_tag->name = $this->input->post('name', true);
    $new_tag->color = $this->input->post('color', true);
    $new_tag->description = $this->input->post('description', true);
    $new_tag->created_in = datetime_current();
    $new_tag->user_id = $user->id_user;

    $this->load->model('TagModel');
    $this->TagModel->insert($new_tag);

    $this->session->set_flashdata('success', 'Etiqueta Criada');
    redirect('tarefas#tags');
  }

  public function delete($id){
    $user = authorize(1);

    $tag = $this->thisIsMyTag($id, $user->id_user);

    $this->TagModel->deleteById($id);

    $this->session->set_flashdata('success', 'Etiqueta Excluída');

    redirect('tarefas#tags');
  }

  public function edit($id){
    $user = authorize(1);

    $tag = $this->thisIsMyTag($id, $user->id_user);

    $page = array(
      'page_title' => 'Editar Etiqueta',
      'page_content' => 'tag/edit',
      'user' => $user,
      'tag' => $tag,
    );

    $this->load->view('public/base', $page);
  }

  public function update($id){
    $user = authorize(1);

    $tag = $this->thisIsMyTag($id, $user->id_user);

    $tag->name = $this->input->post('name', true);
    $tag->description = $this->input->post('description', true);
    $tag->color = $this->input->post('color', true);

    $this->TagModel->updateById($tag, $id);

    $this->session->set_flashdata('success', 'Etiqueta Atualizada');
    redirect('tarefas#tags');
  }

  public function thisIsMyTag($id_tag, $user_id){
    $this->load->model('TagModel');

    $tag = $this->TagModel->searchByIdAndUser($id_tag, $user_id);

    if(!$tag){
      $this->session->set_flashdata('error', 'Essa etiqueta não é sua');
      redirect('tarefas#tags');
    }

    return $tag;
  }
}
