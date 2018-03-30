<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

CModule::IncludeModule('DMI.location');
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/DMI.location/classes/settings.php');

$settings = TF_LOCATION_Settings::GetSettings();
if( $settings['TF_LOCATION_JQUERY_INCLUDE']!="N" )
    $GLOBALS['APPLICATION']->AddHeadScript($componentPath.'/js/jquery-2.1.4.min.js');
$GLOBALS['APPLICATION']->AddHeadScript($componentPath.'/js/fancybox-2.1.5.js');

if( isset($_SESSION['TF_LOCATION_SELECTED_CITY']) && !empty($_SESSION['TF_LOCATION_SELECTED_CITY_NAME']) )
{
    $arResult['CITY_ID'] = $_SESSION['TF_LOCATION_SELECTED_CITY'];
    $arResult['CITY_NAME'] = $_SESSION['TF_LOCATION_SELECTED_CITY_NAME'];
}
else
{
    include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/DMI.location/classes/SxGeo.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/DMI.location/classes/helpers.php');
    mb_internal_encoding("cp-1251");
    $SxGeo = new TF_LOCATION_SxGeo($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/DMI.location/classes/SxGeoCity.dat', SXGEO_BATCH | SXGEO_MEMORY);
    $city = $SxGeo->get(TF_LOCATION_Helpers::GetRealIp());
    mb_internal_encoding(LANG_CHARSET);
    unset($SxGeo);
    $arResult['CITY_NAME'] = iconv('UTF-8', LANG_CHARSET, $city['city']);
    if( CModule::IncludeModule('sale') )
    {
        $db_vars = CSaleLocation::GetList(array("CITY_NAME"=>"ASC"), array("LID" => LANGUAGE_ID), false, false, array());
        while ($vars = $db_vars->Fetch())
        {
            if( $_SESSION['TF_LOCATION_SELECTED_CITY'] > 0 )
            {
                if( $vars['ID'] == $_SESSION['TF_LOCATION_SELECTED_CITY'] )
                {
                    $arResult['CITY_ID'] = $vars['ID'];
                    $arResult['CITY_NAME'] = $vars['CITY_NAME'];
                }
            }
            else
            {
                if ($vars['CITY_NAME'] == $arResult['CITY_NAME'])
                {
                    $arResult['CITY_ID'] = $vars['ID'];
                    $_SESSION['TF_LOCATION_SELECTED_CITY'] = $vars['ID'];
                    $_SESSION['TF_LOCATION_SELECTED_CITY_NAME'] = $vars['CITY_NAME'];
                }
            }
        }
    }
}
if ( empty($arResult['CITY_ID']) || empty($arResult['CITY_NAME']) )
{
    $arResult['CITY_NAME'] = GetMessage("TF_LOCATION_CHOOSE");
    $arResult['CITY_ID'] = false;
    if( $settings['TF_LOCATION_ONUNKNOWN'] == 'Y' )
        $arResult['CALL_POPUP'] = 'Y';
}

$arResult['SETTINGS'] = $settings;
$arResult['COMPONENT_PATH'] = $componentPath;
$this->IncludeComponentTemplate();
return $arResult;
