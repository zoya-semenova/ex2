<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$itemIDs = [];
foreach ($arResult['ITEMS'] as $key => $arItem)
{
	$arItem['PRICES']['PRICE']['PRINT_VALUE'] = number_format((float)$arItem['PRICES']['PRICE']['PRINT_VALUE'], 0, '.', ' ');
	$arItem['PRICES']['PRICE']['PRINT_VALUE'] .= ' '.$arItem['PROPERTIES']['PRICECURRENCY']['VALUE_ENUM'];

	$arResult['ITEMS'][$key] = $arItem;

    $itemIDs[] = $arItem['ID'];
}


$userIDs = [];
$arRes = CUser::GetList('','',['GROUPS_ID' => [6], 'ACTIVE' => 'Y'
    , 'UF_AUTHOR_STATUS' => [34]
],
    ['SELECT' => ['ID'
        //,'UF_AUTHOR_STATUS'
    ]]);
while ($res = $arRes->Fetch()) {//echo "<pre>";print_r($res);echo "</pre>";exit;
    $userIDs[] = $res['ID'];
}

//echo "<pre>";print_r($userIDs);echo "</pre>";exit;

$relatedReview = [];
$productCount = [];
if ($itemIDs && $userIDs) {
    $res = CIBlockElement::GetList(
        ["PROPERTY_PRODUCT_VALUE"=> "DESC", "ID" => "ASC"],
        [
            "IBLOCK_ID" => 5,
            "ACTIVE" => "Y",
            "PROPERTY_PRODUCT" => $itemIDs,
            "PROPERTY_AUTHOR" => $userIDs,
        ],
        false,
        false,
        [
            "ID",
            "IBLOCK_ID",
            "NAME",
            "PROPERTY_PRODUCT",
        ]
    );
    while ($row = $res->GetNext())
    {//echo "<pre>";print_r($row);echo "</pre>";exit;
        $arResult["EXTRA"][$row["PROPERTY_PRODUCT_VALUE"]][] = $row;
        $productCount[$row["PROPERTY_PRODUCT_VALUE"]] = $row["PROPERTY_PRODUCT_VALUE"];
        if (empty($relatedReview)) {
            $relatedReview = $row;
        }
    }
}


$arResult['PRODUCT_COUNT'] = count($productCount);

$this->__component->SetResultCacheKeys(array('PRODUCT_COUNT'));

$arResult['REVIEW_RELATED'] = $relatedReview;