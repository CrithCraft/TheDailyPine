<?php
class APIConstants {

    //Результат запроса - параметр в JSON ответе
    const RESULT_CODE = "resultCode";
    
    //Ответ - используется как параметр в главном JSON ответе в apiEngine
    const RESPONSE = "response";
    
    //Нет ошибок
    const ERROR_NO_ERRORS = 0;
    
    //Ошибка в переданных параметрах
    const ERROR_PARAMS = 1;
    
    //Ошибка в подготовке SQL запроса к базе
    const ERROR_STMP = 2;

    //Ошибка запись не найдена
    const ERROR_RECORD_NOT_FOUND = 3;
    
    //Ошибка в параметрах запроса к серверу. Не путать с ошибкой переданных параметров в метод
    const ERROR_ENGINE_PARAMS = 100;
    
    //Ошибка zip архива
    const ERROR_ENSO_ZIP_ARCHIVE = 1001;
    
}
?>