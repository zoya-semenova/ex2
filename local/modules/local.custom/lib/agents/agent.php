<?
namespace Local\Custom\Agents;

use Bitrix\Main\Loader;
use CIBlockElement;
use CEventLog;
use Bitrix\Main\Type\DateTime as BitrixDateTime;

class Agent
{
    static function Agent_ex_610($lastTimeExec = "")
    {
        \Bitrix\Main\Loader::includeModule('iblock');

        $result = CIblockElement::GetList(
            ['SORT' => 'ASC'],
            [
                'IBLOCK_ID' => IBLOCK_REVIEWS_ID,
                "DATE_MODIFY_FROM" => $lastTimeExec ?: (new BitrixDateTime())->add("-1 day"),
            ],
            [],
            false,
            ['ID']
        );

        $count = $result;

//        while ($result->GetNext())
//            print_r($result);
//        $count++;
       // if ($count > 0)
        //{
            CEventLog::Add([
                'SEVERITY' => 'INFO',
                'AUDIT_TYPE_ID' => 'ex2_610',
                'MODULE_ID' => '',
                'DESCRIPTION' => "Изменилось рецензий: $count",
            ]);
       // }

        return __METHOD__ . "(\"" . (new BitrixDateTime())->toString() . "\");";
    }
}

