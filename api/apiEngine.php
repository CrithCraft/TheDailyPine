<?php
require_once ('apiConstants.php');

class APIEngine {

    private $apiFunctionName;
    private $apiFunctionParams;

    // Статичная функция для подключения API из других API при необходимости в методах
    static function getApiEngineByName($apiName) {
        require_once $apiName . '.php';
        $apiClass = new $apiName();
        return $apiClass;
    }
    
    // ! Конструктор
    // $apiFunctionName - название API и вызываемого метода
    // $apiFunctionParams - JSON параметры метода в строковом представлении
    function __construct($apiFunctionName, $apiFunctionParams) {
        $this->apiFunctionParams = stripcslashes($apiFunctionParams);
        // Парсим на массив из двух элементов [0] - название API, [1] - название метода в API
        $this->apiFunctionName = explode('_', $apiFunctionName);
    }

    // Создаем базовый JSON ответ
    function createDefaultJson() {
        $retObject = json_decode('{}');
        $response = APIConstants::RESPONSE;
        $retObject->$response = json_decode('{}');
        return $retObject;
    }
    
    // ! Вызов функции по переданным параметрам в конструкторе
    function callApiFunction() {
        $resultFunctionCall = $this->createDefaultJson(); // Создаем базовый JSON ответ
        $apiName = strtolower($this->apiFunctionName[0]); // название API проиводим к нижнему регистру
        // ! Проверка наличия API файла
        if (file_exists('api/'.$apiName . '.php')) {
            $apiClass = APIEngine::getApiEngineByName($apiName); // Получаем объект API
            $apiReflection = new ReflectionClass($apiName); // Через рефлексию получем информацию о классе объекта
            try {
                $functionName = $this->apiFunctionName[1]; // Название метода для вызова
                $apiReflection->getMethod($functionName); // Провераем наличие метода
                $response = APIConstants::RESPONSE;
                $jsonParams = json_decode($this->apiFunctionParams); // Декодируем параметры запроса в JSON объект
                // ! Проверка отправленных данных на тип и их выполнение
                if ($jsonParams) {
                    $resultFunctionCall->$response = $apiClass->$functionName($jsonParams); // Вызыаем метод в API который вернет JSON обект
                } else {
                    // Если ошибка декодирования JSON параметров запроса
                    $resultFunctionCall->errno = APIConstants::$ERROR_ENGINE_PARAMS;
                    $resultFunctionCall->error = 'Error given params';
                }
            } catch (Exception $ex) {
                // Непредвиденное исключение
                $resultFunctionCall->error = $ex->getMessage();
            }
        } else {
            // Если запрашиваемый API не найден
            $resultFunctionCall->errno = 100; //Ошибка в параметрах запроса к серверу.
            $resultFunctionCall->error = 'File not found';
            $resultFunctionCall->REQUEST = $_REQUEST;
        }
        return json_encode($resultFunctionCall);
    }
}

?>