<!DOCTYPE html>
<html>
<head>
  <title>Consulta de Chamados GLPI</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="estilo.css">

  <!-- Google font-->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">


</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="#"></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"></a>
        </li>
      </ul>
    </div>
  </nav><br><br>

  <div class="container">

    <div class="row">
      <div class="col-md-12">
        <div class="card-deck">
          <?php

          // INFORMAR OS DADOS DE CONEXÃO DO SERVIDOR E BANCO DE DADOS DO GLPI
          $hostname = "";
          $database = "";
          $username = "";
          $password = "";

          // Conectar ao banco de dados do GLPI
          $connection = mysqli_connect($hostname, $username, $password, $database);

          // Verificar se a conexão foi bem sucedida
          if (!$connection) {
              die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
          }

          // Consultar a quantidade de chamados abertos
          $queryAbertos = "SELECT COUNT(*) AS total_abertos FROM glpi_tickets WHERE status = 1";
          $resultAbertos = mysqli_query($connection, $queryAbertos);
          $rowAbertos = mysqli_fetch_assoc($resultAbertos);
          $totalAbertos = $rowAbertos['total_abertos'];

          // Consultar a quantidade de chamados atribuídos
          $queryAtribuidos = "SELECT COUNT(*) AS total_atribuidos FROM glpi_tickets WHERE status = 2";
          $resultAtribuidos = mysqli_query($connection, $queryAtribuidos);
          $rowAtribuidos = mysqli_fetch_assoc($resultAtribuidos);
          $totalAtribuidos = $rowAtribuidos['total_atribuidos'];

          // Consultar a quantidade de chamados pendentes
          $queryPendentes = "SELECT COUNT(*) AS total_pendentes FROM glpi_tickets WHERE status = 4";
          $resultPendentes = mysqli_query($connection, $queryPendentes);
          $rowPendentes = mysqli_fetch_assoc($resultPendentes);
          $totalPendentes = $rowPendentes['total_pendentes'];

          // Consultar a quantidade de chamados finalizados
          $queryFinalizados = "SELECT COUNT(*) AS total_finalizados FROM glpi_tickets WHERE status = 5";
          $resultFinalizados = mysqli_query($connection, $queryFinalizados);
          $rowFinalizados = mysqli_fetch_assoc($resultFinalizados);
          $totalFinalizados = $rowFinalizados['total_finalizados'];

          // Exibir as informações em cards com base no status
          echo "<div class='card card-aberto'>";
          echo "<div class='card-body'>";
          echo "<h5 class='card-title'>Chamados Abertos</h5>";
          echo "<p class='card-text'>$totalAbertos</p>";
          echo "</div>";
          echo "</div>";

          echo "<div class='card card-atribuido'>";
          echo "<div class='card-body'>";
          echo "<h5 class='card-title'>Chamados Atribuídos</h5>";
          echo "<p class='card-text'>$totalAtribuidos</p>";
          echo "</div>";
          echo "</div>";

          echo "<div class='card card-pendente'>";
          echo "<div class='card-body'>";
          echo "<h5 class='card-title'>Chamados Pendentes</h5>";
          echo "<p class='card-text'>$totalPendentes</p>";
          echo "</div>";
          echo "</div>";

          echo "<div class='card card-finalizado'>";
          echo "<div class='card-body'>";
          echo "<h5 class='card-title'>Chamados Finalizados</h5>";
          echo "<p class='card-text'>$totalFinalizados</p>";
          echo "</div>";
          echo "</div>";

          // Fechar a conexão com o banco de dados
          mysqli_close($connection);
          ?>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-md-12">
        <div class="table-container">
          <?php
          // Conectar novamente ao banco de dados do GLPI
          $connection = mysqli_connect($hostname, $username, $password, $database);

          // Verificar se a conexão foi bem sucedida
          if (!$connection) {
              die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
          }

          // Consultar os últimos chamados com as categorias
          $queryUltimosChamados = "SELECT t.id, t.name, t.status as status, t.date as date, c.name AS category_name
                                  FROM glpi_tickets AS t
                                  INNER JOIN glpi_itilcategories AS c
                                  ON t.itilcategories_id = c.id
                                  ORDER BY t.id DESC
                                  LIMIT 10";
          $resultUltimosChamados = mysqli_query($connection, $queryUltimosChamados);

          // Exibir os últimos chamados em uma tabela
          echo "<table class='table table-striped table-hover table-bordered'>";
          echo "<thead class='thead-dark'>";
          echo "<tr>";
          echo "<th>ID</th>";
          echo "<th>Título</th>";
          echo "<th>Categoria</th>";
          echo "<th>Data de Abertuda</th>";
          echo "<th>Status</th>";
          echo "<th>Visualizar Chamado</th>"; // Nova coluna adicionada

          // Adicione aqui as outras colunas desejadas
          echo "</tr>";
          echo "</thead>";
          echo "<tbody>";

          while ($row = mysqli_fetch_assoc($resultUltimosChamados)) {
              echo "<tr>";
              echo "<td>" . $row["id"] . "</td>";
              echo "<td>" . $row["name"] . "</td>";
              echo "<td>" . $row["category_name"] . "</td>";
              echo "<td>" . date("d/m/Y", strtotime($row["date"])) . "</td>";

              // Definir a classe CSS do status com base no valor
              $statusClass = "";
              switch ($row["status"]) {
                  case 1:
                      $statusClass = "bg-success text-white";
                      $statusText = "Aberto";
                      break;
                  case 2:
                      $statusClass = "bg-warning text-dark";
                      $statusText = "Atribuído";
                      break;
                  case 4:
                      $statusClass = "bg-danger text-white";
                      $statusText = "Pendente";
                      break;
                  case 5:
                      $statusClass = "bg-info text-white";
                      $statusText = "Finalizado";
                      break;
                  case 6:
                      $statusClass = "bg-dark text-white";
                      $statusText = "Fechado";
                      break;
                  default:
                      $statusClass = "";
                      $statusText = "Desconhecido";
                      break;
              }

              // Exibir o status com a classe CSS definida
              echo "<td class='$statusClass'>";
              echo $statusText;
              echo "</td>";

              // INFORMAR O ENDEREÇO DO GLPI NO LINK ABAIXO PARA REDIRECIONAR AO CHAMADO
              echo "<td>";
              echo "<a href='http:/ENDEREÇO - DO SEU SERVIDOR GLPI /glpi/front/ticket.form.php?id=" . $row['id'] . "' target='_blank'>Visualizar</a>";
              echo "</td>";

              // Adicione aqui as outras colunas desejadas
              echo "</tr>";
          }

          echo "</tbody>";
          echo "</table>";

          // Fechar a conexão com o banco de dados
          mysqli_close($connection);
          ?>
        </div>
      </div>
    </div>
  </div>


  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>.
  
  <script>
    $(document).ready(function() {
      // Função para atualizar a página a cada 10 segundos
      setInterval(function() {
        location.reload();
      }, 10000);
    });
  </script>
</body>
</html>
