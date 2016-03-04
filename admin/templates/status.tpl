{nocache}

{include file='page_header.tpl'}


			{if $checked_login == 2 }
			<div class="container bg-warning">
				<h1 class="text-center">Crawl Status</h1>
				<div class="col-md-12">
					
				</div>
				<div class="clearfix"></div>
				<div class="table-responsive text-center" id="status-table">
<style>
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th
{
    border: 1px solid #000;
}
.table {
	border-right: 1px solid #000;
	border-left: 1px solid #000;
}
.bg-success {
    background-color: #BEDCB1;
}
.bg-purple {
    color: #333;
    background-color: #C8AED8;
}
</style>
<script src="js/status.js"></script>

					<div class="col-md-12 col-sm-12 col-xs-12 text-center" style="padding:10px;">
						<div class="col-md-12 text-center" style="padding:10px; background-color:#fff; float:none; !important; max-width:600px; margin: 0 auto !important;">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="col-md-12 bg-danger">Deleted</div>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="col-md-12 bg-info">Pending First Crawl</div>
							</div>
							<div class="clearfix"></div>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="col-md-12 bg-purple">Pending Re-Crawl</div>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="col-md-12 bg-warning">Failed Crawl</div>
							</div>
							<div class="clearfix"></div>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="col-md-12 bg-success">Successful Crawl</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>

					{include file='recrawl.tpl'}
				</div>
				<div class="clearfix"></div>
				<br><br>
			</div>
			{/if}
			{include file='page_footer.tpl'}
		</body>
	</html>

{/nocache}