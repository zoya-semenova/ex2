<?php

namespace Local\Custom\EventHandlers;

use Bitrix\Main\Loader;
use Bitrix\Main\Mail\Event;
use CIBlockElement;
use CUser;

class Search {

    protected static $reviews = [];
    protected static $authors = [];
    protected static $disallow = false;

    protected static function getClasses() {
        if (self::$disallow) {
            return;
        }
        self::$disallow = true;
//echo "fff";
        Loader::IncludeModule("iblock");
        $res = CIBlockElement::GetList(
            [],
            [
                "IBLOCK_ID" => 5,
                "ACTIVE" => "Y",
                "!PROPERTY_AUTHOR" => false,
            ],
            false,
            false,
            [
                "ID",
                "PROPERTY_AUTHOR.ID",
            ]
        );
        static::$reviews = [];
        while ($row = $res->GetNext())
        {echo "<pre>";print_r($row);echo "</pre>";exit;
            static::$reviews[$row['ID']]['AUTHOR'] = $row['PROPERTY_AUTHOR_VALUE'];
        }
        echo "<pre>";
        $obEnum = new \CUserFieldEnum;
        $rsEnum = $obEnum->GetList(array(), array("USER_FIELD_NAME" => "UF_USER_CLASS"));
        $enum = array();
        while($arEnum = $rsEnum->Fetch())
        {
            $enum[$arEnum["ID"]] = $arEnum["VALUE"];
        }
        print_r($enum);
//print_r(static::$reviews);
        $arFilter = array(//"ID" => implode('|', array_column( static::$reviews, 'AUTHOR')),
            "ACTIVE" => "Y", 'GROUPS_ID' => [6]
    , //'UF_AUTHOR_STATUS_VALUE' => [34,35]
            'UF_AUTHOR_STATUS' => [34]//CIBlockElement::SubQuery('ID', ['IBLOCK_ID'=> 6, 'CODE' => 'public'])
        );
//print_r($arFilter);
        $arParams["SELECT"] = array("ID", "UF_USER_CLASS");
        $rsRes = CUser::GetList('','',$arFilter,$arParams);
        global $USER_FIELD_MANAGER;

        $aUserField = $USER_FIELD_MANAGER->GetUserFields(
            'USER',
            [2,4]
        ); // array
        //if ($aUserField = $aUserField->GetNext()) {
            print_r($aUserField);
        //}
   //     exit;
        static::$authors = [];
        while ($arRes = $rsRes->Fetch()) {//echo "<pre>";print_r($arRes);exit;
            static::$authors[$arRes['ID']]['CLASS'] = $enum[$arRes['UF_USER_CLASS']];
        }
    }
    // создаем обработчик события "BeforeIndex"
    static function BeforeIndexHandler(&$arFields)
    {

        if($arFields["MODULE_ID"] == "iblock" && $arFields["PARAM2"] == 5)
        {
            static::getClasses();
                $arFields["TITLE"] .= " ". static::$authors[static::$reviews[$arFields["ITEM_ID"]]['AUTHOR']]['CLASS'];  // Добавим свойство в конец заголовка индексируемого элемента
        //echo "<pre>";print_r(static::$authors);print_r(static::$reviews);print_r($arFields);exit;
        }
        return $arFields; // вернём изменения
    }

}