{if !empty($results)}
{thnxblockpopup_js name="thnxblockpopupp_cookie"}
<script type="text/javascript">
function setcookie_popup(n,v) {
	var name = n;
	var val = v;
	var expiredate = new Date();
	expiredate.setMonth(expiredate.getMonth()+3);
	document.cookie = name + "=" + escape(val) +";path=/;" + ((expiredate==null)?"" : ("; expires=" + expiredate.toGMTString()))
}
function getcookie_popup(arg){
	var name = arg + "=";
	var cook = document.cookie.split(';');
	for(var i=0; i<cook.length; i++) {
		var c = cook[i];
		while (c.charAt(0)==' ') c = c.substring(1);
		if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
	}
	return "";
}
</script>
{/thnxblockpopup_js}
	{foreach from=$results item=result}
		{assign var="xrtstarttime" value=1000}
		{assign var="xrtclosetime" value=100000}
		<div id="thnxblockpopup_{$result.id_thnxblckpopuptbl}" class="thnxblockpopup white-popup mfp-hide" style="background-image:url({$modules_dir}thnxblockpopup/img/{$result.image}); max-width: {$result.width|intval}px; min-height: {$result.height|intval}px;">
			<div class="thnxblockpopup_content">
				{if $result.popuptype == 'newsletter'}
					{include file="module:thnxblockpopup/views/templates/front/newsletter_popup.tpl"}
				{/if}
				{if $result.popuptype == 'custom'}
					{include file="module:thnxblockpopup/views/templates/front/blockpopup_custom.tpl"}
				{/if}
			</div>
		</div>
	{if isset($result.starttime) && !empty($result.starttime)}
		{assign var="xrtstarttime" value=$result.starttime}
	{/if}
	{if isset($result.staytime) && !empty($result.staytime)}
		{assign var="xrtclosetime" value=($xrtstarttime+$result.staytime)}
	{/if}
{thnxblockpopup_js name="thnxblockpopupp_{$result.id_thnxblckpopuptbl}"}
		{literal}
		<script type="text/javascript">
		function thnxloadpopup_{/literal}{$result.id_thnxblckpopuptbl}{literal}(){
			if(getcookie_popup("thnxblockpopupp{/literal}{$result.id_thnxblckpopuptbl}{literal}") != 'hide'){
				if ($(document.body).width() > 767){
					var popupWidth = parseInt({/literal}{$result.width|intval}{literal});
					var popupHeight = parseInt({/literal}{$result.height|intval}{literal});
					if (!!$.prototype.magnificPopup)
					$.magnificPopup.open({
						items: {
						    src: '#thnxblockpopup_{/literal}{$result.id_thnxblckpopuptbl}{literal}',
						    type: 'inline'
						  },
					});

				};
			};
		}
		function thnxclosepopup_{/literal}{$result.id_thnxblckpopuptbl}{literal}(){
			$(".mfp-close").trigger("click");
		}
			jQuery(document).ready(function($){
				setTimeout(thnxloadpopup_{/literal}{$result.id_thnxblckpopuptbl}{literal}, {/literal}{$xrtstarttime}{literal});
				setTimeout(thnxclosepopup_{/literal}{$result.id_thnxblckpopuptbl}{literal}, {/literal}{$xrtclosetime}{literal});
			});
		</script>
		{/literal}
{/thnxblockpopup_js}
	{/foreach}
{/if}
{*
/**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
*}
