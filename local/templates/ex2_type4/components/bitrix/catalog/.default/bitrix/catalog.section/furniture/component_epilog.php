<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

//echo $APPLICATION->GetDirProperty('ex2_meta');exit;
//echo "<pre>";print_r($arResult);echo "</pre>";exit;

//echo $APPLICATION->GetDirProperty('ex2_meta');exit;
if (isset($arResult['PRODUCT_COUNT']))
    $APPLICATION->SetPageProperty('ex2_meta', str_replace('#count#',
        $arResult['PRODUCT_COUNT'], $APPLICATION->GetPageProperty('ex2_meta') ? : $APPLICATION->GetDirProperty('ex2_meta')));

//$APPLICATION->SetPageProperty('ex2_meta', "fff");

?>