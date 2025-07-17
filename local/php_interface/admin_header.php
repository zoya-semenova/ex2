<?php

//if(CSite::InGroup([6])){
if (!CSite::InGroup([5]))
    return;
    global $adminMenu;

    //Скрываем кнопку "Переход в Битрикс24"
  //  unset($adminMenu->aGlobalMenu['global_menu_crm_site_master']);
//unset($adminMenu->aGlobalMenu['global_menu_store']);
foreach ($adminMenu->aGlobalMenu as $key => $arMenu) {
    if ($key !== 'global_menu_content' && $key !== 'global_menu_ex2') {
     //   unset($adminMenu->aGlobalMenu[$key]);
    }
}
/*
    foreach ($adminMenu->aGlobalMenu['global_menu_store']['items'] as $k => $v){
        if($v['title'] != 'Список заказов'){
            unset($adminMenu->aGlobalMenu['global_menu_store']['items'][$k]);
        }
    }
*/
//echo "<pre>";print_r($adminMenu->aGlobalMenu);echo "</pre>";exit;
    //Скрываем хлебные крошки и меню с каталогом товаров в подменю "Заказы"
    ?>

    <?php
//}