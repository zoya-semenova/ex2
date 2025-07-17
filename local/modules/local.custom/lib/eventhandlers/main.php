<?php

namespace Local\Custom\EventHandlers;

use Bitrix\Main\Localization\Loc;
use CUser;
use Bitrix\Main\Mail\Event;
use CSite;

Loc::loadMessages(__FILE__);

class Main {

    protected static $class = '';

    protected static $enum = null;

    static function OnBuildGlobalMenu(&$arFields, &$aModuleMenu)
    {
        global $USER;
/*
        echo "<pre>";print_r($arFields);
        print_r($aModuleMenu);
        print_r($USER);
        exit;
*/

        return;
       // if (!CSite::InGroup([5]))
       //     return;

        $globalMenu = [];
        foreach($arFields as $key => $arField) {
            if ($key == 'global_menu_content') {
                $globalMenu['global_menu_content'] = $arField;
            }
        }

        $globalMenu['global_menu_ex2'] = [

                'menu_id' => 'ex2',
                'text' => Loc::getMessage('MENU_EX2'),
    'title' => 'Быстрый доступ',
    'url' => 'index.php?lang=ru',
    'sort' => 50,
            'items_id' => 'global_menu_ex2',
    'items' => [
        Array
        (
            "url" => "https://test1/",
            "title" => "ссылка1",
            "text" => "ссылка1",
            "page_icon" => "clouds_page_icon",
            "items_id" => "menu_ex2_link_1",
            "items" => array()
        ),
        Array
        (
            "url" => "https://test1/",
            "title" => "ссылка2",
            "text" => "ссылка2",
            "page_icon" => "clouds_page_icon",
            "items_id" => "menu_ex2_link_2",
            "items" => array()
        )
    ]
        ];
        $arFields = $globalMenu;

        $moduleMenu = [];
        foreach ($aModuleMenu as $key => $arMenu) {
            if ($arMenu['parent_menu'] == 'global_menu_content') {
                $moduleMenu[$key] = $arMenu;
            }
        }
        $aModuleMenu = $moduleMenu;

/*
        echo "<pre>";print_r($arFields);
        print_r($aModuleMenu);
        exit;
*/
    }

    static function OnBeforeUserUpdate(&$arFields)
    {

        $arFilter = array("ID" => $arFields['ID']);
        $arParams["SELECT"] = array("UF_USER_CLASS");
        $arRes = CUser::GetList('','',$arFilter,$arParams);
        if ($arRes = $arRes->Fetch()) {//echo "<pre>";print_r($arRes);exit;

            static::$class = $arRes['UF_USER_CLASS'];
        }

        //return false;
     //   echo "<pre>";print_r($arFields);exit;
    }

    public static function getUserClass()
    {
global $APPLICATION;
        if (!isset(static::$enum)) {//echo "111fff";
            file_put_contents( "/home/bitrix/www/123.txt", "ddd", FILE_APPEND);
            $obEnum = new \CUserFieldEnum;
            $rsEnum = $obEnum->GetList(array(), array("USER_FIELD_NAME" => 'UF_USER_CLASS'));
            static::$enum = array();
            while($arEnum = $rsEnum->Fetch())
            {
                static::$enum[$arEnum["ID"]] = $arEnum["VALUE"];
            }
        }
    }

    static function OnAfterUserUpdate(&$arFields)
    {
//        echo static::$class;
//        echo "<pre>";print_r($arFields);
//
//        exit;
        if (!$arFields['RESULT'])
            return;

        if ($arFields['UF_USER_CLASS'] != static::$class) {

            static::getUserClass();
            Event::send([
                'EVENT_NAME' => 'EX2_AUTHOR_INFO',
                'LID' => 's1',
                'C_FIELDS' => [
                    'OLD_USER_CLASS' => static::$enum[static::$class],
                    'NEW_USER_CLASS' => static::$enum[$arFields['UF_USER_CLASS']],
                ]
            ]);
        }


        //echo "<pre>";print_r($enum);exit;
    }


    static function onBeforeAdd(&$event, &$lid, &$arFields)

    {

        //echo "<pre>";print_r($arFields);exit;

        if ($event == 'USER_INFO') {
            self::getUserClass();
            $arFilter = array("ID" => $arFields['USER_ID']);
            $arParams["SELECT"] = array("UF_USER_CLASS");
            $arRes = CUser::GetList('','',$arFilter,$arParams);
            if ($arRes = $arRes->Fetch()) {//echo "<pre>";print_r($arRes);exit;
                $arFields['CLASS'] = static::$enum[$arRes['UF_USER_CLASS']];
            }
        }




    }

}