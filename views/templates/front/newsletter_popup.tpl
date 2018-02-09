<!-- newsletter popup -->
<div class="newsletter_popup">
	<div class="newsletter_popup_content">
		<h4 class="title">{$result.title}</h4>
		<p class="sub_title">{$result.subtitle}</p>
		<p class="content">{$result.description}</p>
		<form action="{$link->getPageLink('index', null, null, null, false, null, true)|escape:'html':'UTF-8'}" method="post">
			<div class="form-group" >
				<input class="inputNew form-control grey newsletter-input" id="newsletter-input1" type="text" name="email" size="18" value="" placeholder="{l s='Your Email' mod='thnxblockpopup'}"/>
                <button type="submit" name="submitNewsletter" class="btn btn-default button button-small">
                    <span>{l s='Subscribe' mod='thnxblockpopup'}</span>
                </button>
				<input type="hidden" name="action" value="0" />
			</div>
		</form>
		<div class="newsletter_popup_bottom"> 
			<input type="checkbox" id="newsletter_popup_dont_show_again">
			<label for="newsletter_popup_dont_show_again">{l s='Don\'t show this popup again' mod='thnxblockpopup'}</label>
		</div>
	</div>
</div>
{thnxblockpopup_js name="thnxblockpopupn_{$result.id_thnxblckpopuptbl}"}
{literal}
<script type="text/javascript">
	jQuery(document).ready(function($){
		$("#newsletter_popup_dont_show_again").on("click",function(){
			setcookie_popup("thnxblockpopupp{/literal}{$result.id_thnxblckpopuptbl}{literal}",'hide');
			$(".mfp-close").trigger("click");
		});
		$("[name=submitNewsletter]").on("click",function(e){
			e.preventDefault();
			var data = {
				'action_type':'submit_newsletter',
				'action': $("[name=action]").val(),
				'email': $(".newsletter_popup #newsletter-input1").val(),
				'id_newsletter':{/literal}{$result.id_thnxblckpopuptbl}{literal}
			};
			$.ajax({
				url: thnx_base_dir + 'modules/thnxblockpopup/ajax.php',
				data: data,
				type: 'post',
				dataType: 'json',
				success: function(result){
					setcookie_popup("thnxblockpopupp{/literal}{$result.id_thnxblckpopuptbl}{literal}",'hide');
		        	if(!!$.prototype.magnificPopup){
		        		$.magnificPopup.close();
		        		$(".mfp-close").trigger("click");
		        	}
		        	if(!!$.prototype.magnificPopup)
		        	$.magnificPopup.open({
		        	  items: {
		        	    src: '<div class="white-popup">'+result.msg+'</div>', // can be a HTML string, jQuery object, or CSS selector
		        	    type: 'inline'
		        	  }
		        	});
				}
			});
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
