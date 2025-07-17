<?
namespace Local\Custom\EventHandlers;

use Bitrix\Main\Loader;
use CIBlockElement;
use CEventLog;

class Iblock
{
    protected static $author = [];

    static function OnBeforeIBlockElementUpdate(&$arFields)
    {
        Loader::includeModule('iblock');
        global $APPLICATION;
        if ($arFields['IBLOCK_ID'] != IBLOCK_REVIEWS_ID) {
            return true;
        }

        $arFields['PREVIEW_TEXT'] = str_replace('#del#', '', $arFields['PREVIEW_TEXT']);
        $len = strlen($arFields['PREVIEW_TEXT']);
        if ($len < 5) {
           // $APPLICATION->ThrowException('Текст анонса слишком короткий: '.$len);
            //return false; й
        }
     //   echo "fff";

        //exit;
        //echo "<pre>";print_r($arFields);echo "</pre>";exit;
    }

    static function OnBeforeAuthor(&$arFields)
    {
        static::$author = [];
        $props = CIBlockElement::GetProperty(IBLOCK_REVIEWS_ID, $arFields['ID'], "sort", "asc",
            ['CODE' => 'AUTHOR']);
        if ($prop = $props->Fetch()) {
            //echo "<pre>";print_r($prop);echo "</pre>";
            static::$author = ['ID' => $prop['ID'], 'VALUE_ID' => $prop['PROPERTY_VALUE_ID'], 'VALUE' => $prop['VALUE']];
        }
    }
    static function OnAfterIBlockElementUpdate(&$arFields)
    {
        Loader::includeModule('iblock');
        global $APPLICATION;
/*
        echo "<pre>";
        print_r(static::$author);
        print_r($arFields);echo "</pre>";
        exit;
  */
       // if (!$arFields['RESULT'])
//            return;

        /*
        $props = CIBlockElement::GetProperty(IBLOCK_REVIEWS_ID, $arFields['ID'], "sort", "asc",
            ['CODE' => 'AUTHOR']);
        if ($prop = $props->Fetch()) {
            //echo "<pre>";print_r($prop);echo "</pre>";
            static::$author = ['ID' => $prop['ID'], 'VALUE_ID' => $prop['PROPERTY_VALUE_ID'], 'VALUE' => $prop['VALUE']];
        }
*/




       // exit;
 /*
        $props = CIBlockElement::GetProperty(IBLOCK_REVIEWS_ID, $arFields['ID'], '', '',
            ['CODE' => 'ddd']);
        while ($prop = $props->Fetch()) {
            echo "<pre>";print_r($prop);echo "</pre>";
        }
        exit;
        */

        if (static::$author['VALUE'] != $arFields['PROPERTY_VALUES']
            [static::$author['ID']][static::$author['VALUE_ID']?:'n0']['VALUE']) {
            CEventLog::Add([
                'SEVERITY' => 'INFO',
                'AUDIT_TYPE_ID' => 'ex2_590',
                'MODULE_ID' => '',
                'ITEM_ID' => $arFields['ID'],
                'DESCRIPTION' => "В рецензии ".$arFields['ID']." изменился автор 
            с ".static::$author['VALUE']." на ". $arFields['PROPERTY_VALUES'][static::$author['ID']][static::$author['VALUE_ID']?:'n0']['VALUE'],
            ]);
        }
        //echo "<pre>";print_r($arFields['PROPERTY_VALUES'][$author['ID']][$author['PROPERTY_VALUE_ID']], 'VALUE');
        //echo "</pre>";exit;

    }

}