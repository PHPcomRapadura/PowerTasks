<?php
defined('BASEPATH') or exit('Sem Permissão');

require_once(APPPATH.'controllers/Team.php');

class TeamMember extends Team{
  public function view($team_id){
    $user = authorize(1);

    $this->load->model(array('TeamMemberModel', 'TeamTagModel', 'TeamTaskModel'));
    $team = parent::iManageThisTeam($team_id, $user->id_user);

    $availableMembers = $this->TeamMemberModel->searchAvailableMembersForThisTeam($team_id, $user->id_user);

    $members = $this->TeamMemberModel->searchAllMembersOfTeam($team_id);
    $teamAdmin = $this->TeamModel->searchAdminTheOfTeam($team_id);

    $tags = $this->TeamTagModel->searchAllByTeam($team_id);

    $tasksToDo = $this->TeamTaskModel->searchAllByTeamAndStatus($team_id, 0);
    $tasksDone = $this->TeamTaskModel->searchAllByTeamAndStatus($team_id, 1);

    $page = array(
      'page_title' => $team->name,
      'page_content' => 'team/view',
      'user' => $user,
      'team' => $team,
      'admin' => $teamAdmin,
      'tags' => $tags,
      'tasksToDo' => $tasksToDo,
      'tasksDone' => $tasksDone,
      'availableMembers' => $availableMembers,
      'members' => $members,
    );

    $this->load->view('public/base', $page);
  }

  public function addMember(){
    $team_id = $this->input->post('team_id', true);
    $member_id = $this->input->post('member_id', true);

    $user = authorize(1);

    $this->load->model('TeamMemberModel');

    $team = parent::iManageThisTeam($team_id, $user->id_user);

    $teamMember = array('member_id'=>$member_id, 'team_id'=>$team_id, 'created_in'=>datetime_current());
    $this->TeamMemberModel->insertMemberInTeam($teamMember);

    $this->session->set_flashdata('success', 'Novo Usuário inserido no grupo');
    redirect('time/'.$team_id.'#members');
  }

  public function removeMember($team_id, $member_id){
    $user = authorize(1);
    $team = parent::iManageThisTeam($team_id, $user->id_user);

    $this->load->model('TeamMemberModel');

    $this->TeamMemberModel->removeMemberOfTeam($team_id, $member_id);

    $this->session->set_flashdata('success', 'O usuário foi removido do time');
    redirect('time/'.$team_id.'/#members');
  }
}
