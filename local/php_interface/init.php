<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

require_once __DIR__ . "/constants.php";

Loader::includeModule('local.custom');

use Local\Custom\EventHandlers\Main;

Main::getUserClass();
Main::getUserClass();
//exit;
AddEventHandler("main", "OnProlog",
    [
        'Local\Custom\EventHandlers\Main',
        "getUserClass"
    ]);
AddEventHandler("main", "OnEpilog",
    [
        'Local\Custom\EventHandlers\Main',
        "getUserClass"
    ]);

$eventManager = \Bitrix\Main\EventManager::getInstance();
/*
$eventManager->addEventHandler(
    'form', //  Название модуля, в котором происходит событие
    'OnAfterResultAdd', //  Название события
    'onAfterResultAddHandler' //  Класс и метод обработчика
);
*/
use Bitrix\Main\EventManager;
use Bitrix\Main\Event;
use Bitrix\Main\EventResult;
use Bitrix\Main\Application;


     function onAfterResultAddHandler($WEB_FORM_ID, $RESULT_ID): EventResult
    {
        // действие обработчика распространяется только на форму с ID=6
        if ($WEB_FORM_ID == 1)
        {
            // запишем в дополнительное поле 'user_ip' IP-адрес пользователя
            //CFormResult::SetField($RESULT_ID, 'user_ip', $_SERVER["REMOTE_ADDR"]);
            $res = CFormResult::GetDataByID($RESULT_ID, array(),
                $arResult, $arAnswers, array());

            //  Получаем значения полей формы
            $data = array('method' => '');
            foreach ($arAnswers as $FIELD_SID => $arAnswer) {
                foreach ($arAnswer as $key => $value) {
                    $data[$FIELD_SID] = $value['USER_TEXT'];
                }

            }
           // echo "<pre>";print_r($arResult);echo "</pre>";
           // echo "<pre>";print_r($arAnswer);echo "</pre>";
           // exit;
            //формируем массив для передачи в bitrix24
            $crmUrl = 'https://192.168.31.50/';

            $arParams = array(
               // 'LOGIN' => 'PolyakovMaksim', // обязательно, логин для доступа к crm
               // 'PASSWORD' => 'Avx16@ru12x!448H', // обязательно, пароль для доступа к crm
                'LOGIN' => 'admin', // обязательно, логин для доступа к crm
                'PASSWORD' => 'bitrix', // обязательно, пароль для доступа к crm
                'TITLE' => 'test', // обязательно, название лида
                "SOURCE_ID" => 'WEB',
                //"SOURCE_ID" => 'WEBFORM',
               // 'POST' => 'ffff',
                //"UTM_SOURCE" => $data['UTM_SOURCE'],
                "NAME" => $data['NAME'] ? $data['NAME'] : 'Случайное имя',
                'ASSIGNED_BY_ID' => 4335, // Ответственный
                "EMAIL" => [["VALUE" => $data['EMAIL'], "VALUE_TYPE" => "WORK"]],
                "PHONE" => [["VALUE" => $data['PHONE'], "VALUE_TYPE" => "WORK"]],
                "COMMENTS" => $data['COMMENT'] ? "Лид создан автоматически; ".$data['COMMENT'] : "Лид создан автоматически",
                //"UF_CRM_1581408073" => $data['DATE'], //дата модификации лида
            );
            $obHttp = new \Bitrix\Main\Web\HttpClient;
          //  $result = $obHttp->post($crmUrl.'crm/configs/import/lead.php', $arParams);

            // определяем URL
            $url =$crmUrl.'crm/configs/import/lead.php';
         //   $url = 'https://bitrix.komek.ru/crm/configs/import/lead.php';
// описываем параметры  лида
            $ParamLid = http_build_query($arParams);
// обращаемся к сформированному URL при помощи функции curl_exec для создания лида
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_POST => 1,
                CURLOPT_HEADER => 0,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url,
                CURLOPT_POSTFIELDS => $ParamLid,
            ));
            $result2 = curl_exec($ch);
            curl_close($ch);

            echo "<pre>";print_r($result2);echo "</pre>";exit;
            $result = json_decode(str_replace('\'', '"', $result), true);

          //  echo "<pre>";print_r($result);echo "</pre>";exit;
            return $result;

            echo "<pre>";print_r($arValues);echo "</pre>";exit;
        }
    }

//exit;


