<?php

{include file='page_header.tpl'}


			{if $checked_login == 2 }
			<div class="container bg-warning">
				<h1 class="text-center">Settings</h1>
				<div class="col-md-12">
					<form method="POST">
						<label class="col-md-offset-4 col-md-4 text-center" for="base_url">Base Url With http:// (ex. http://url.com) 
						<input type="base_url" class="form-control" placeholder="Base URL (http://url.com)" name="base_url" value={$base_url}>
						</label>
						<div class="clearfix"></div>
						<label class="col-md-offset-4 col-md-4 text-center" for="admin_url">Admin Url With http:// (ex. http://url.com/admin) 
						<input type="admin_url" class="form-control" placeholder="Admin URL (http://url.com/admin)" name="admin_url" value={$admin_url}>
						</label>
						<div class="clearfix"></div>
						<div class="col-md-offset-4 col-md-4 text-center text-danger">
							{$message}
						</div>
						<div class="col-md-offset-4 col-md-4 text-center">
							<input type="hidden" name="entersettings" value="entersettings">
							<button type="submit" class="btn btn-default">Submit</button>
						</div>
					</form>
				</div>
			</div>
			{/if}
			{include file='page_footer.tpl'}
		</body>
	</html>