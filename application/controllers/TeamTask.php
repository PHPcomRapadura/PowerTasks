<?php
defined('BASEPATH') or exit('Sem Permissão');

require_once(APPPATH.'controllers/Team.php');

class TeamTask extends CI_Controller{
  public function insert($team_id){
    $user = authorize(1);

    $team = Team::iManageThisTeam($team_id, $user->id_user);

    $this->load->model('TeamTaskModel');

    $newTask = new stdClass();
    $newTask->team_id = $team_id;
    $newTask->teamtag_id = $this->input->post('tag', true);
    $newTask->created_by = $user->id_user;
    $newTask->title = $this->input->post('title', true);
    $newTask->priority = $this->input->post('priority', true);
    $newTask->description = $this->input->post('description', true);
    $newTask->created_in = datetime_current();

    $this->TeamTaskModel->insert($newTask);

    $this->session->set_flashdata('success', 'Nova Tarefa Criada');
    redirect('time/'.$team_id);
  }

  public function delete($team_id, $task_id){
    $user = authorize(1);

    $team = Team::iManageThisTeam($team_id, $user->id_user);

    $teamTask = $this->thisTaskBelongsToTheTeam($task_id, $team_id);

    $this->TeamTaskModel->deleteById($task_id);

    $this->session->set_flashdata('success', 'Tarefa Excluída');

    redirect('time/'.$team_id);
  }

  public function edit($team_id, $task_id){
    $user = authorize(1);

    $team = Team::iManageThisTeam($team_id, $user->id_user);

    $teamTask = $this->thisTaskBelongsToTheTeam($task_id, $team_id);

    $page = array(
      'page_title' => 'Editar Etiqueta',
      'page_content' => 'teamtask/edit',
      'user' => $user,
      'task' => $teamTask,
      'team_id' => $team_id,
    );

    $this->load->view('public/base', $page);
  }

  public function update($team_id, $task_id){
    $user = authorize(1);

    $team = Team::iManageThisTeam($team_id, $user->id_user);

    $teamTask = $this->thisTaskBelongsToTheTeam($task_id, $team_id);

    $newTeamTask = new stdClass();
    $newTeamTask->title = $this->input->post('title', true);
    $newTeamTask->description = $this->input->post('description', true);
    $newTeamTask->priority = $this->input->post('priority', true);
    $newTeamTask->teamtag_id = $this->input->post('tag', true);
    $newTeamTask->created_by = $user->id_user;

    $this->TeamTaskModel->updateById($newTeamTask, $task_id);

    $this->session->set_flashdata('success', 'Tarefa Atualizada');
    redirect('time/'.$team_id);
  }

  public function complete($team_id, $task_id){
    $user = authorize(1);

    $teamTask = $this->thisTaskBelongsToTheTeam($task_id, $team_id);

    $newTask = new stdClass();
    $newTask->status = 1;
    $newTask->completed_in = datetime_current();

    $this->TeamTaskModel->updateById($newTask, $task_id);

    $this->session->set_flashdata('success', 'Tarefa Concluída');

    redirect('time/'.$team_id);
  }

  public function reopen($team_id, $task_id){
    $user = authorize(1);

    $teamTask = $this->thisTaskBelongsToTheTeam($task_id, $team_id);

    $newTask = new stdClass();
    $newTask->status = 0;
    $newTask->completed_in = '';

    $this->TeamTaskModel->updateById($newTask, $task_id);

    $this->session->set_flashdata('success', 'Tarefa Reaberta');

    redirect('time/'.$team_id);
  }

  public function thisTaskBelongsToTheTeam($id_task, $team_id){
    $this->load->model('TeamTaskModel');

    $task = $this->TeamTaskModel->searchByIdAndTeam($id_task, $team_id);

    if(!$task){
      $this->session->set_flashdata('error', 'Essa tarefa não pertence ao time');
      redirect('time/'.$team_id);
    }

    return $task;
  }
}
