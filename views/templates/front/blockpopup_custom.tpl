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