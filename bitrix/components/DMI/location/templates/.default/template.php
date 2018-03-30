<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$jsCallback = str_replace("'", "\'", $arResult['SETTINGS']['TF_LOCATION_CALLBACK']).';';
if( $arParams['ORDER_TEMPLATE'] == 'Y' )
{
	
	if( !empty($arParams['PARAMS']['ONCITYCHANGE']) )
		$jsCallback .= $arParams['PARAMS']['ONCITYCHANGE'].'();';
	if( !empty($arParams['PARAMS']['JS_CALLBACK']) )
		$jsCallback .= $arParams['PARAMS']['JS_CALLBACK'].'();';
	?>
	<a href="#tfLocationPopup" class="<?=$arResult['SETTINGS']['TF_LOCATION_ORDERLINK_CLASS']?> tf_location_link in_order" onclick="tfLocationPopupOpen('<?=$arResult['COMPONENT_PATH']?>', '<?=$jsCallback?>'); return false;"><span><?=$arResult['CITY_NAME']?></span></a>
	<input type="hidden" name="<?=$arParams['PARAMS']['INPUT_NAME']?>" class="tf_location_city_input" value="<?=$arResult['CITY_ID']?>">
	<?
}
else
{
	?>
	<a href="#tfLocationPopup" class="<?=$arResult['SETTINGS']['TF_LOCATION_HEADLINK_CLASS']?> tf_location_link" onclick="tfLocationPopupOpen('<?=$arResult['COMPONENT_PATH']?>', '<?=$jsCallback?>'); return false;">
		<?
		if( strlen($arResult['SETTINGS']['TF_LOCATION_HEADLINK_TEXT']) > 0 )
			echo $arResult['SETTINGS']['TF_LOCATION_HEADLINK_TEXT'], ':';
		?>
		<span><?=$arResult['CITY_NAME']?></span>
	</a>
	<?
}

if( $arResult['CALL_POPUP'] == 'Y' )
{
	?><script>$().ready(function() {tfLocationPopupOpen('<?=$arResult['COMPONENT_PATH']?>', '<?=$jsCallback?>');});</script><?
}

if ($GLOBALS['TF_LOCATION_TEMPLATE_LOADED'] != 'Y')
{
	?>
	<div class="custom-popup-2014-overlay" style="display:none;"></div>
	<div class="custom-popup-2014" style="display:none; border-radius:<?=intval($arResult['SETTINGS']['TF_LOCATION_POPUP_RADIUS'])?>px"><div class="custom-popup-2014-content">
		<div class="popup-title"><?=GetMessage("TF_LOCATION_CHECK_CITY")?></div>
		<div class="popup-search-wrapper"><input type="text" autocomplete="off" name="search" class="field-text city-search"><a href="#" class="clear_field"></a></div>
		<ul class="current-list"></ul>
		<div class="popup-city nice-scroll"><div class="inner"></div><div class="shadow"></div></div>
	</div><div class="custom-popup-2014-close"></div></div>
	<?
	$GLOBALS['TF_LOCATION_TEMPLATE_LOADED'] = 'Y';
}

?>
<script type="text/javascript">
	if( !window.BX && top.BX )
		window.BX = top.BX;
</script>
