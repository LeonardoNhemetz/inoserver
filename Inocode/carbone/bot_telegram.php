<?php

// Define as informações do bot do Telegram e o chat_id para enviar a mensagem
$botToken = '6224562887:AAFx3YaGlp6_3W_t_wzU2QMR4iMCNRgK1NI';
$chat_id = '-926336437';

function enviarAlertaTelegram($nome, $whatsapp, $pessoa, $tamanhoesp, $tipo_espelho, $acessorios, $instalacao){

    global $botToken, $chat_id; // torna as variáveis globais disponíveis dentro da função

    // Define a mensagem que será enviada para o Telegram
    $text = "CHEGOU UM NOVO LEAD DO CARBONE!!! \n\nNome do Cliente:  $nome\nWhatsApp:  $whatsapp\n Tipo de pessoa:  $pessoa\nTamanho:  $tamanhoesp\nTipo de Espelho: $tipo_espelho\nAcessórios:  $acessorios\nInstalação:  $instalacao";

    // Monta a URL para enviar a mensagem para o bot do Telegram
    $telegram_url = "https://api.telegram.org/bot".$botToken."/sendMessage?chat_id=".$chat_id."&text=".urlencode($text);
    file_get_contents($telegram_url);
}

?>
