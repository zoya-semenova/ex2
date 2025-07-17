<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/*
 * Здесь размещается код, выполняемый каждый раз при подключении этого модуля
 */

require_once __DIR__ . "/functions.php";
require_once __DIR__ . "/constants.php";

//$eventManager = \Bitrix\Main\EventManager::getInstance();

AddEventHandler('iblock', 'OnBeforeIBlockElementAdd',
    ['Local\Custom\EventHandlers\Iblock',
    'OnBeforeIBlockElementUpdate'
    ]
    );
AddEventHandler('iblock', 'OnBeforeIBlockElementUpdate',
    ['Local\Custom\EventHandlers\Iblock',
        'OnBeforeIBlockElementUpdate']);
AddEventHandler('iblock', 'OnBeforeIBlockElementUpdate',
    ['Local\Custom\EventHandlers\Iblock',
        'OnBeforeAuthor']);
AddEventHandler('iblock', 'OnAfterIBlockElementUpdate',
    ['Local\Custom\EventHandlers\Iblock',
        'OnAfterIBlockElementUpdate']);


AddEventHandler('main', 'OnBuildGlobalMenu', [
    'Local\Custom\EventHandlers\Main',
    'OnBuildGlobalMenu'
], 1000000000);


AddEventHandler('main', 'OnBeforeUserUpdate',
    ['Local\Custom\EventHandlers\Main',
        'OnBeforeUserUpdate']);
AddEventHandler('main', 'OnAfterUserUpdate',
    ['Local\Custom\EventHandlers\Main',
        'OnAfterUserUpdate']);

AddEventHandler(

    'main',

    //'OnBeforeEventSend',
    'OnBeforeEventAdd',

    ['Local\Custom\EventHandlers\Main',
        'onBeforeAdd']

);

/*
AddEventHandler("main", "OnSendUserInfo", "MyOnSendUserInfoHandler");
 function MyOnSendUserInfoHandler(&$arParams)
{
    echo "<pre>";print_r($arParams);echo "</pre>";exit;
    if(strlen($arParams['USER_FIELDS']['LAST_NAME'])<=0)
        $arParams['FIELDS']['CUSTOM_NAME'] = $arParams['USER_FIELDS']['LAST_NAME'];
    else
        $arParams['FIELDS']['CUSTOM_NAME'] = $arParams['USER_FIELDS']['LOGIN'];
    // теперь в шаблоне USER_INFO можно использовать макрос #CUSTOM_NAME#
}
*/

// регистрируем обработчик
AddEventHandler("search", "BeforeIndex",
    [
        'Local\Custom\EventHandlers\Search',
        "BeforeIndexHandler"
    ]);
