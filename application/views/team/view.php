<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<link rel="stylesheet" href="<?php echo base_url('assets/datatables/css/datatables.bootstrap.min.css'); ?>">
<script src="<?php echo base_url('assets/datatables/js/jquery.datatables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/datatables.bootstrap.min.js'); ?>"></script>

<script>
  $(document).ready(function(){
    // $('select').select2();

    $('[data-placement="top"]').tooltip();
    $('[data-toggle="popover"]').popover();

    var hash = window.location.hash;
    hash && $('ul.nav a[href="' + hash + '"]').tab('show');

    $('#table_members').DataTable({
      responsive: true
    });
    $('[id="table_tasks"]').DataTable({
      responsive: true
    });

    function formatState (state) {
    if (!state.id || state.value == "") { return state.text; }

      var $state = $(
        '<span><img width="30px;" src="<?php echo base_url(); ?>assets/img/users/' + state.title + '" class="img-flag" /> ' + state.text + '</span>'
      );
      return $state;
    };

    $(".js-example-templating").select2({
      templateResult: formatState
    });

    $('[data-target="#removeMember"]').on("click", function(){
      $('#confirmRemoveMember').attr('href','<?php echo base_url("time/membro/remover/$team->id_team/");?>'+$(this).val());
    });

    $('[data-target="#deleteTask"]').on("click", function(){
      $('#confirmDeleteTask').attr('href','<?php echo base_url("time/tarefa/excluir/$team->id_team/");?>'+$(this).val());
    });
    $('[data-target="#completeTask"]').on("click", function(){
      $('#confirmCompleteTask').attr('href','<?php echo base_url("time/tarefa/concluir/$team->id_team/");?>'+$(this).val());
    });
    $('[data-target="#reopenTask"]').on("click", function(){
      $('#confirmReopenTask').attr('href','<?php echo base_url("time/tarefa/reabrir/$team->id_team/");?>'+$(this).val());
    });
  });
</script>

<!-- <div class="alert alert-info"> -->
<blockquote>
  <p><?php echo $team->description; ?></p>
  <footer>Criado por  <cite title="<?php echo $admin->name; ?>"><?php echo $admin->name; ?></cite></footer>
  <footer>Em <cite title="<?php echo $team->created_in; ?>"><?php echo $team->created_in; ?></cite></footer>
</blockquote>
<!-- </div> -->

<br><br>
<a href="#addTask" class="btn btn-default" data-toggle="collapse" aria-expanded="false" aria-controls="collapseExample">
  <span class="fa fa-plus-circle"></span> Nova Tarefa
</a>
<br><br>
<div class="collapse" id="addTask">
  <div class="well">
    <?php $this->load->view('teamtask/add'); ?>
  </div>
</div>

<br>
<ul class="nav nav-tabs">
  <li><a href="#tags" data-toggle="tab"><span class="fa fa-tags"></span> Etiquetas</a>
  </li>
  <li class="active"><a href="#tasksToDo" data-toggle="tab"><span class="fa fa-exclamation-circle"></span> Tarefas Para Fazer</a>
  </li>
  <li><a href="#tasksDone" data-toggle="tab"><span class="fa fa-check-circle"></span> Tarefas Concluídas</a>
  </li>
  <li><a href="#members" data-toggle="tab"><span class="fa fa-group"></span> Membros</a>
  </li>
  <li><a href="#settings" data-toggle="tab"><span class="fa fa-cog"></span> Configurações</a>
  </li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
  <div class="tab-pane" id="tags">
    <br>
    <a href="#addTag" class="btn btn-link" data-toggle="collapse" aria-expanded="false" aria-controls="collapseExample">
      <span class="fa fa-plus-circle"></span> Nova Etiqueta
    </a>
    <br><br>
    <div class="collapse" id="addTag">
      <div class="well">
        <?php $this->load->view('teamtag/add'); ?>
      </div>
    </div>
    <br><br>
    <?php $this->load->view('teamtag/list'); ?>
  </div>
  <div class="tab-pane fade in active" id="tasksToDo">
    <br>
    <h4>Tarefas</h4>
    <?php $this->load->view('teamtask/listtodo'); ?>
  </div>
  <div class="tab-pane fade" id="tasksDone">
    <br>
    <h4>Tarefas</h4>
    <?php $this->load->view('teamtask/listdone'); ?>
  </div>
  <div class="tab-pane fade" id="members">
    <br>
    <h4>Membros</h4>

    <a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseAddMember" aria-expanded="false" aria-controls="collapseExample">
      <span class="fa fa-plus"></span> Adicionar Membro
    </a><br>
    <div class="collapse" id="collapseAddMember">
      <div class="well">
        <form action="<?php echo base_url('time/membro/adicionar'); ?>" method="post">
          <input type="hidden" name="team_id" value="<?php echo $team->id_team; ?>">
          <select name="member_id" style="width: 100%" class="js-example-templating js-states form-control input-lg">
            <option disabled selected value=""> -- Selecione -- </option>
            <?php
            foreach($availableMembers as $member){
              echo '<option value="',$member->id_user,'" title="',$member->photo,'">',$member->name,'</option>';
            }
            ?>
          </select><br>
          <button class="btn btn-primary">Confirmar</button>

        </form>
      </div>
    </div>

    <br><br>
    <div class="table-responsive">
      <table class="table table-hover table-striped" id="table_members">
        <thead>
          <tr>
            <th>Nome</th>
            <th>Adicionado em</th>
            <th>Opções</th>
          </tr>
        </thead>
        <tbody>
          <?php
            echo '<tr>';
            echo '<td><img width="30px" src="',base_url('assets/img/users/'.$admin->photo),'" class="img-circle">&nbsp;&nbsp;', $admin->name,' <span class="badge">Admin</span></td>';
            echo '<td>',$admin->created_in,'</td>';
            echo '<td></td>';
            echo '</tr>';
            foreach($members as $member){
              echo '<tr>';
              echo '<td><img width="30px" src="',base_url('assets/img/users/'.$member->photo),'" class="img-circle">&nbsp;&nbsp;', $member->name,'</td>';
              echo '<td>',$member->created_in,'</td>';
              echo '<td><button value="',$member->id_user,'" data-target="#removeMember" data-toggle="modal" data-placement="top" class="btn btn-sm btn-danger" title="Remover do Time"><span class="fa fa-trash"></span></button></td>';
              echo '</tr>';
            }
          ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="tab-pane fade" id="settings">
    <br><br>
    <?php $this->load->view('team/edit'); ?>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="removeMember" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Remover Membro do Time</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning">
          <strong>Você deseja remover esse membro?
        </div>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-lg-6">
            <a href="" id="confirmRemoveMember" class="btn btn-block btn-lg btn-danger">Sim</a>
          </div>
          <div class="col-lg-6">
            <button type="button" class="btn btn-block btn-lg btn-default" data-dismiss="modal">Não</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Modal Excluir -->
<div class="modal fade" id="deleteTask" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Nova Tarefa</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-info">
          <strong>Você deseja excluir esta tarefa?
        </div>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-lg-6">
            <a href="" id="confirmDeleteTask" class="btn btn-block btn-lg btn-primary">Sim</a>
          </div>
          <div class="col-lg-6">
            <button type="button" class="btn btn-block btn-lg btn-default" data-dismiss="modal">Não</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Concluir -->
<div class="modal fade" id="completeTask" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Concluir Tarefa</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-info">
          <strong>Você deseja concluir esta tarefa?
        </div>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-lg-6">
            <a href="" id="confirmCompleteTask" class="btn btn-block btn-lg btn-primary">Sim</a>
          </div>
          <div class="col-lg-6">
            <button type="button" class="btn btn-block btn-lg btn-default" data-dismiss="modal">Não</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Reabrir -->
<div class="modal fade" id="reopenTask" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Reabrir Tarefa</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-info">
          <strong>Você deseja reabrir esta tarefa?
        </div>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-lg-6">
            <a href="" id="confirmReopenTask" class="btn btn-block btn-lg btn-primary">Sim</a>
          </div>
          <div class="col-lg-6">
            <button type="button" class="btn btn-block btn-lg btn-default" data-dismiss="modal">Não</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
