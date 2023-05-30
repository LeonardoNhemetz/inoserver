<?php
// Conecta ao banco de dados
$host = 'mysql';
$user = 'inocode';
$password = 'inocode';
$database = 'inocode';
$mysqli = new mysqli($host, $user, $password, $database);

// Verifica se houve algum erro na conexão
if ($mysqli->connect_error) {
    die('Erro na conexão: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

//* Acesso: ipdohost.com.br/inocode/inocode.php?vendedor=Nome%20do%20Vendedor&empresa=Empresa
//* imobibot.ddns.net/inocode/inocode.php?vendedor=Nome%20do%20Vendedor&empresa=Empresa
//* localhost:7777/inocode/inocode.php?vendedor=Nome%20do%20Vendedor&empresa=Empresa
// Verifica se os dados foram submetidos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	if (empty($_POST['area-de-interesse'])) {
		die('Selecione pelo menos uma área de interesse.');
		}

    // Obtém os dados do formulário
    $nome = $_POST['nome'];
    $whatsapp = $_POST['whatsapp'];
	$areas_interesse = '';
	if (isset($_POST['area-de-interesse']) && count($_POST['area-de-interesse']) > 0) {
		$areas_interesse = implode(", ", $_POST['area-de-interesse']); // Transforma o array em uma string separada por vírgulas
	}
    $vendedor = $_GET['vendedor'];
    $empresa = $_GET['empresa'];
    
    // Prepara a query de inserção
	$stmt = $mysqli->prepare("INSERT INTO indicacoes (nome_cliente, telefone_cliente, nome_vendedor_inocode, empresa_vendedor_inocode, areas_interesse) VALUES (?, ?, ?, ?, ?)");

    // Verifica se houve algum erro na preparação
    if (!$stmt) {
        die('Erro na preparação: (' . $mysqli->errno . ') ' . $mysqli->error);
    }

    // Define os parâmetros e executa a query
    $stmt->bind_param('sssss', $nome, $whatsapp, $vendedor, $empresa, $areas_interesse);
    $stmt->execute();

    // Verifica se houve algum erro na execução
    if ($stmt->errno) {
        die('Erro na execução: (' . $stmt->errno . ') ' . $stmt->error);
    }

    // Fecha a conexão com o banco de dados
    $stmt->close();
    $mysqli->close();

	//!enviar alerta pro Telegrão
	include("bot_telegram.php"); // Inclui o arquivo do bot do Telegram
	enviarAlertaTelegram($nome, $whatsapp, $areas_interesse, $vendedor, $empresa); // Chama a função do bot para enviar a mensagem

	header("Location: https://www.instagram.com/inovattiontecnologia/");
	exit();

}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <style>
	/* Seleciona todos os elementos de checkbox */
	input[type="checkbox"] {
	/* Define uma largura e altura maiores */
	width: 25px;
	height: 25px;
	
	}

	/* Seleciona o label da checkbox */
	.form-check-label {
	/* Adiciona um pouco mais de espaço entre a checkbox e o texto */
	margin-left: 10px;
	font-size: 1.2em;
	
	}
	.container {
		display: flex;
		justify-content: center;
		align-items: top;
		height: 100vh;
		background-color: #f5f5f5;
	}
	form {
		max-width: 700px;
		width: 100%;
		padding: 1em;
		background-color: #D9F1FF;
		border-radius: 15px;
		box-shadow: 0 0 10px rgba(0,0,0,0.1);
	}
    body {
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	height: 100vh;
	background-color: #f5f5f5;
	}
    h5 {
      text-align: center;
	  margin-top: 1em;
      margin-bottom: 1em;
    }
    .form-group label {
		align-items: center;
    	font-weight: bold;
    }
    .btn-primary {
      margin-top: 2em;
      width: 100%;
    }
  </style>
</head>
<body>
  <div class="container">
		<div class="col-md-6 col-sm-12">
			<form method="POST">
				<img src="imagem.jpeg" class="img-fluid mx-auto d-block" alt="Imagem">
				<h5>Digite suas informações abaixo que entraremos em contato!</h5>
				<div class="form-group">
					<label for="nome">Nome:</label>
					<input type="text" class="form-control" id="nome" name="nome" required>
				</div>
				<div class="form-group">
					<label for="whatsapp">WhatsApp:</label>
					<input type="text" class="form-control" id="whatsapp" name="whatsapp" required>
				</div>
				<div class="form-group">
					<h5>Área de interesse:</h5>
					<div class="form-check">
					<input class="form-check-input" type="checkbox" id="automacao-residencial" name="area-de-interesse[]" value="automacao-residencial">
					<label class="form-check-label" for="automacao-residencial">Automação Residencial</label>
					</div>
					<div class="form-check">
					<input class="form-check-input" type="checkbox" id="automacao-industrial" name="area-de-interesse[]" value="automacao-industrial">
					<label class="form-check-label" for="automacao-industrial">Automação Industrial</label>
					</div>
					<div class="form-check">
					<input class="form-check-input" type="checkbox" id="espelho-inteligente" name="area-de-interesse[]" value="espelho-inteligente">
					<label class="form-check-label" for="espelho-inteligente">Espelho Inteligente</label>
					</div>
				</div>
				<input type="hidden" name="vendedor" value="<?php echo htmlspecialchars($_GET['vendedor']); ?>">
				<input type="hidden" name="empresa" value="<?php echo htmlspecialchars($_GET['empresa']); ?>">
				<button type="submit" class="btn btn-primary">Enviar</button>
			</form>
		</div>
	</div>
</body>
</html>