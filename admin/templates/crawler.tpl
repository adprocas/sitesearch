{nocache}
{include file='page_header.tpl'}
	<div class="container" style="padding:20px;">
	{if $checked_login == 2 }
				<h1 class="text-center">Crawler</h1>

				<div class="col-md-12">
					{if $crawl_table_empty == true}
					<form method="POST">
						<label class="col-md-offset-4 col-md-4 text-center" for="url">Domain 
							<input type="username" class="form-control" placeholder="Enter Start Domain" name="url">
							<span class="help-block">This will be the domain used to index pages<br>This can be changed by deleting all content</span>
						</label>
						<div class="col-md-offset-4 col-md-4 text-center">
							<button type="submit" class="btn btn-default">Submit</button>
						</div>
					{else}
					<form method="GET">
					<div class="col-md-12 col-sm-12 col-xs-12 text-center" style="padding:10px;">
						<button type="submit" class="btn btn-info" name="startcrawl" value="true">Start Crawl</button>
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12 text-center" style="padding:10px;">
						<div class="col-md-12 text-center" style="padding:10px; background-color:#fff; float:none; !important; max-width:600px; margin: 0 auto !important;">
							<div class="col-md-6 col-sm-12 col-xs-12">
								<div class="col-md-12 bg-danger">First Crawl</div>
							</div>
							<div class="col-md-6 col-sm-12 col-xs-12">
								<div class="col-md-12 bg-info">Re-Crawl</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					<div class="clearfix"></div>
						<div class="table-responsive text-center">
							<table class="table table-responsive" style="max-width:600px; margin: 0 auto !important;">
							<tr class="bg-primary"><th class="text-center">Pages To Crawl</th></tr>
							{foreach from=$crawl_list item=item}
								{if $item.crawled==0}
									<tr class="bg-danger">
										<td class="bg-danger">{$item.url}</td>
									</tr>
								{else if $item.crawled==1}
									<tr class="bg-info">
										<td class="bg-info">{$item.url}</td>
									</tr>
								{/if}
							{/foreach}
							</table>
						</div>
						{/if}
					</form>
				{/if}
			</div>
				{$crawl_report}
		</div>
		{include file='page_footer.tpl'}
	</body>
</html>
{/nocache}