<!-- newsletter popup -->
<div class="blockpopup_custom">
	<div class="blockpopup_custom_content">
		<p class="sub_title">{$result.subtitle}</p>
		<h4 class="title">{$result.title}</h4>
		<p class="content">{$result.description}</p>
		<div class="newsletter_popup_bottom"> 
			<input type="checkbox" id="newsletter_popup_dont_show_again">
			<label for="newsletter_popup_dont_show_again">{l s='Don\'t show this popup again' mod='thnxblockpopup'}</label>
		</div>
	</div>
</div>
{thnxblockpopup_js name="thnxblockpopupc_{$result.id_thnxblckpopuptbl}"}
{literal}
<script type="text/javascript">
	$(document).ready(function($){
		$("#newsletter_popup_dont_show_again").on("click",function(){
			setcookie_popup("thnxblockpopupp{/literal}{$result.id_thnxblckpopuptbl}{literal}",'hide');
			$(".mfp-close").trigger("click");
		});
	});
</script>
{/literal}
{/thnxblockpopup_js}
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
