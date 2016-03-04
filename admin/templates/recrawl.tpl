{nocache}
<table class="table table-responsive" style="max-width:600px; margin: 0 auto !important;">
<tr class="bg-primary"><th class="text-center">Crawl Status</th></tr>
{foreach from=$crawl_list item=item}
	<tr class="
		{if $item.crawled>=4}
		bg-danger
		{elseif $item.crawled==3}
		bg-warning
		{elseif $item.crawled==2}
		bg-success
		{elseif $item.crawled==1}
		bg-purple
		{else}
		bg-info
		{/if}
	">
		<td>
			<div class="col-md-10">{$item.url}</div>
			{if $item.crawled!=0 && $item.crawled!=1}
				<div class="col-md-1 recrawl" action="recrawl" id="{$item.ID}" name="id" value="{$item.ID}"><span class="glyphicon glyphicon-refresh" style="color:darkgreen; cursor: pointer;"></span></div>
			{else}
				<div class="col-md-1"></div>
			{/if}
			{if $item.crawled!=4}
			<div class="col-md-1 recrawl active" id="{$item.ID}" action="delete" name="id" value="{$item.ID}"><span class="glyphicon glyphicon-remove-sign" style="color:darkred; cursor: pointer;"></span></div>
			{else}
				<div class="col-md-1"></div>
			{/if}
		</td>
	</tr>
{/foreach}
</table>
{/nocache}