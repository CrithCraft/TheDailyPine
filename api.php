<?php
header('Content-type: text/html; charset=UTF-8');

//  Проверяем переменные из ссылки
if (count($_REQUEST)>0){
    require_once 'api/apiEngine.php';
    /* Разбираем значения для вызова определнной функции API
    foreach(массив as ячейка => значение) */
    foreach ($_REQUEST as $apiFunctionName => $apiFunctionParams) {
        $APIEngine=new APIEngine($apiFunctionName,$apiFunctionParams);
        echo $APIEngine->callApiFunction();
        break;
    }
} else {
    // Иначе выдаем, то что мы ничего не использовали из api
    $jsonError->error='No function called';
    echo json_encode($jsonError);
}
?>