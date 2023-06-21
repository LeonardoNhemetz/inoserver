<?php
// Conecta ao banco de dados
$host = 'mysql';
$user = 'root';
$password = '2023Inovatt1on';
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


    // Obtém os dados do formulário
    $nome = $_POST['nome'];
    $whatsapp = $_POST['whatsapp'];
	$tamanhoesp = $_POST['tamanhoesp'];
	$tipo_espelho = $_POST['tipo-de-espelho'];
	$acessorios = '';
	if (isset($_POST['acessorios']) && count($_POST['acessorios']) > 0) {
		$acessorios = implode(", ", $_POST['acessorios']); // Transforma o array em uma string separada por vírgulas
	}
	$instalacao = '';
	if (isset($_POST['instalacao']) && count($_POST['instalacao']) > 0) {
		$instalacao = implode(", ", $_POST['instalacao']); // Transforma o array em uma string separada por vírgulas
	}

    
    // Prepara a query de inserção
	$stmt = $mysqli->prepare("INSERT INTO carbone (nome_cliente, whatsapp_cliente, tamanhoesp, tipoesp, acessorios, instalacao) VALUES (?, ?, ?, ?, ?, ?)");

    // Verifica se houve algum erro na preparação
    if (!$stmt) {
        die('Erro na preparação: (' . $mysqli->errno . ') ' . $mysqli->error);
    }

    // Define os parâmetros e executa a query
    $stmt->bind_param('ssssss', $nome, $whatsapp, $tamanhoesp, $tipo_espelho, $acessorios, $instalacao);
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
	enviarAlertaTelegram($nome, $whatsapp, $tamanhoesp, $tipo_espelho, $acessorios, $instalacao); // Chama a função do bot para enviar a mensagem

	header("Location: https://www.instagram.com/inovattiontecnologia/");
	exit();

}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro Inovattion</title>
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
	  font-weight: bold;
    }
    .form-group label {
		align-items: center;
    	
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
					<input type="text" class="form-control" placeholder="(xx) yyyyy-yyyy" id="whatsapp" name="whatsapp" required>
				</div>
				<div class="form-group">
					<label for="tamanhoesp">Tamanho do Espelho (Altura(mm) x Largura(mm)):</label>
					<input type="text" class="form-control" placeholder="1200mm x 800mm" id="tamanhoesp" name="tamanhoesp" required>
				</div>
				<div class="form-group">
					<h5>Tipo de Espelho:</h5>
					<div class="form-check">
						<input class="form-check-input" type="radio" id="lapidado-prata" name="tipo-de-espelho" value="lapidado-prata">
						<label class="form-check-label" for="lapidado-prata">Lapidado Prata</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="radio" id="lapidado-bronze" name="tipo-de-espelho" value="lapidado-bronze">
						<label class="form-check-label" for="lapidado-bronze">Lapidado Bronze</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="radio" id="bisotado-prata" name="tipo-de-espelho" value="bisotado-prata">
						<label class="form-check-label" for="bisotado-prata">Bisotado Prata</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="radio" id="bisotado-bronze" name="tipo-de-espelho" value="bisotado-bronze">
						<label class="form-check-label" for="bisotado-bronze">Bisotado Bronze</label>
					</div>
				</div>
				<div class="form-group">
					<h5>Acessórios do Espelho:</h5>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" id="botao-led" name="acessorios[]" value="botao-som">
						<label class="form-check-label" for="botao-som">Botão touch para ligar/desligar led</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" id="botao-led" name="acessorios[]" value="botao-som">
						<label class="form-check-label" for="botao-som">Botão touch para ligar/desligar led dimerizavel</label>
					</div>
					
					<div class="form-check">
						<input class="form-check-input" type="checkbox" id="botao-desembacar" name="acessorios[]" value="botao-desembacar">
						<label class="form-check-label" for="botao-desembacar">Botão touch com desembaçador</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" id="botao-som" name="acessorios[]" value="botao-som">
						<label class="form-check-label" for="botao-som">Botão touch para ligar a caixa de som (Bluetooth)</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" id="sensor-temperatura" name="acessorios[]" value="sensor-temperatura">
						<label class="form-check-label" for="sensor-temperatura">Sensor de temperatura embutido</label>
					</div>						
				</div>
				<div class="form-group">
					<h5>Serviços de instalação:</h5>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" id="instalacao_espelho" name="instalacao[]" value="instalacao_espelho">
						<label class="form-check-label" for="instalacao_espelho">Instalação do Espelho</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" id="instalacao_caixa" name="instalacao[]" value="instalacao_caixa">
						<label class="form-check-label" for="instalacao_caixa">Instalação da caixa de som embutida no teto</label>
					</div>
				</div>		
				<button type="submit" class="btn btn-primary">Enviar</button>
			</form>
		</div>
	</div>

	
</body>
</html>