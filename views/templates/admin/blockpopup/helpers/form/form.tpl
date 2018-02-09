{extends file="helpers/form/form.tpl"}
{block name="input"}
	{if ($input.type == 'selecttwotype')}

			<div class="{if isset($input.hideclass)}{$input.hideclass}{/if} {$input.name} {$input.name}_class" id="{$input.name}_id">
			<select name="selecttwotype_{$input.name}" class="selecttwotype_{$input.name}_cls" id="selecttwotype_{$input.name}_id" multiple="true">
			    {foreach from=$input.initvalues item=initval}
			        {if isset($fields_value[$input.name])}
			            {assign var=settings_def_value value=","|explode:$fields_value[$input.name]}
			            {if $initval['id']|in_array:$settings_def_value}
			                {$selected = 'selected'}
			            {else}
			                {$selected = ''}
			            {/if}
			        {else}
			            {$selected = ''}
			        {/if}
			        <option {$selected} value="{$initval['id']}">{$initval['name']}</option>
			    {/foreach}
			</select>
			<input type="hidden" name="{$input.name}" id="{$input.name}" value="{if isset($input.defvalues)}{$input.defvalues}{else}{$fields_value[$input.name]}{/if}" class=" {$input.name} {$input.type}_field">
			</div>
			<script type="text/javascript">

			    // START SELECT TWO CALLING
			    $(function(){
			        var defVal = $("input#{$input.name}").val();
			        if(defVal.length){
			            var ValArr = defVal.split(',');
			            for(var n in ValArr){
			                $( "select#selecttwotype_{$input.name}_id" ).children('option[value="'+ValArr[n]+'"]').attr('selected','selected');
			            }
			        }
			        $( "select#selecttwotype_{$input.name}_id" ).select2( { placeholder: "{$input.placeholder}", width: 200, tokenSeparators: [',', ' '] } ).on('change',function(){
			            var data = $(this).select2('data');
			            var select = $(this);
			            var field = select.next("input#{$input.name}");
			            var saved = '';
			            select.children('option').attr('selected',null);
			            if(data.length)
			                $.each(data, function(k,v){
			                    var selected = v.id;   
			                    select.children('option[value="'+selected+'"]').attr('selected','selected');
			                    if(k > 0)
			                        saved += ',';
			                    saved += selected;                                
			                });
			             field.val(saved);   
			        });
			    });
 			// END SELECT TWO CALLING
			</script>
			<style type="text/css">
				.select2-container.select2-container-multi
				{ 
					width: 100% !important;
				}
			</style>
	{elseif ($input.type == 'selectchange')}
		<script type="text/javascript">
		// START SELECT WISE TYPE SHOW
		{if isset($input.hideclass)}
				    $(document).ready(function() {  
				    	{$input.name}_main_fnc();
				    	$("#product_type").on('change',function() { 
				    		{$input.name}_main_fnc();
				    	 } );
					 } );
				    function {$input.name}_show_hide(key,value,selected){
				    	if(key == selected){
				    		$("#"+value+"_id").parent().parent().show(500);
				    	}
				    }
				    function {$input.name}_main_fnc(){
				    	$(".{$input.hideclass}").parent().parent().hide(500);
				    	{foreach from=$input.dependency item=curval key=curkey}
				    		{$input.name}_show_hide('{$curkey}','{$curval}',$("#product_type option:selected").val());
			    		{/foreach}
				    }
		{/if}
		// END SELECT WISE TYPE SHOW
		</script>
	{else}
		{$smarty.block.parent}
	{/if}
{/block}
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
