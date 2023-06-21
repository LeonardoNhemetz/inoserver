<?php

// Define as informações do bot do Telegram e o chat_id para enviar a mensagem
$botToken = '6224562887:AAFx3YaGlp6_3W_t_wzU2QMR4iMCNRgK1NI';
$chat_id = '-926336437';

function enviarAlertaTelegram($nome, $whatsapp,$tipo_imovel, $areas_interesse, $id){

    global $botToken, $chat_id; // torna as variáveis globais disponíveis dentro da função

    // Define a mensagem que será enviada para o Telegram
    $text = "CHEGOU UM NOVO LEAD!!! \n\nNome do Cliente:  $nome\nWhatsApp:  $whatsapp\n\nÁrea(s) de Interesse: $areas_interesse\n\nID: $id";

    // Monta a URL para enviar a mensagem para o bot do Telegram
    $telegram_url = "https://api.telegram.org/bot".$botToken."/sendMessage?chat_id=".$chat_id."&text=".urlencode($text);
    file_get_contents($telegram_url);
}

?>
