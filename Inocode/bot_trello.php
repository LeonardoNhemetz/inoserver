<?php

$card_name = "exemplo";
$card_desc = "exemplo descrição";
function create_trello_card($card_name, $card_desc) {
    
    // Dados
    $api_key = "a8965e1593a593e8853d0462c50e6e18";
    $api_token = "ATTAd7ab96122fba7a1fd791bbf05dc8372fe262e5dc343e4e4043df26f9664a0a7193DE5884";
    $board_id = "PmpLGU6r";
    $list_id = "OhrwFsxp";
    
    // Define a URL para criar um novo card
    $url = "https://api.trello.com/1/cards";
    
    // Define os dados do novo card
    $data = array(
        "key" => $api_key,
        "token" => $api_token,
        "idList" => $list_id,
        "name" => $card_name,
        "desc" => $card_desc
    );
    
    // Define as opções para a chamada da API
    $options = Unirest\Request::put(
        'https://api.trello.com/1/boards/'.$board_id,
        $data
    );
    
    // Cria o contexto da chamada da API
    $context = stream_context_create($options);
    
    // Faz a chamada da API para criar o novo card
    $result = file_get_contents($url, false, $context);
    
    // Retorna o resultado
    return $result;
}

$result = create_trello_card($card_name, $card_desc);
echo $result;



?>