	{include file='page_header.tpl'}
	{if $checked_login == 0 }
		<div class="container bg-warning">
			<h1 class="text-center">Account Setup</h1>
			<div class="col-md-12">
				<form method="POST">
					<label class="col-md-offset-4 col-md-4 text-center" for="username">Username 
						<input type="username" class="form-control" placeholder="Username" name="username">
					</label>
					<div class="clearfix"></div>
					<label class="col-md-offset-4 col-md-4 text-center" for="email">Email 
						<input type="email" class="form-control" placeholder="Email" name="email">
					</label>
					<div class="clearfix"></div>
					<label class="col-md-offset-4 col-md-4 text-center" for="password">Password 
						<input type="password" class="form-control" placeholder="Password" name="password">
					</label>
					<div class="clearfix"></div>
					<label class="col-md-offset-4 col-md-4 text-center" for="repassword">Re-Enter Password 
						<input type="password" class="form-control" placeholder="Re-Enter Password" name="repassword">
					</label>
					<div class="clearfix"></div>
					<div class="col-md-offset-4 col-md-4 text-center text-danger">
						{$message}
					</div>
					<div class="col-md-offset-4 col-md-4 text-center">
						<input type="hidden" name="enteradmin" value="enteradmin">
						<button type="submit" class="btn btn-default">Submit</button>
					</div>
				</form>
			{elseif $checked_login == 2 }
			<div class="container bg-warning">
				<h1 class="text-center">Site Search</h1>
				<div class="col-md-12">
					<h1>Welcome!</h1>
					<h3>Choosen an option above</h3>

		{else}
			{if $forgot==true}
			<div class="container bg-warning">
				<h1 class="text-center">Forgot Password</h1>
				<div class="col-md-12">
					{if $emailsent==true }
					<div class="col-md-offset-4 col-md-4 text-center text-danger">
						{$message}
					</div>
					{else}
					<form method="POST">
						<label class="col-md-offset-4 col-md-4 text-center" for="username">Email 
							<input type="email" class="form-control" placeholder="email" name="email">
						</label>
						<div class="clearfix"></div>
						<div class="col-md-offset-4 col-md-4 text-center text-danger">
							{$message}
						</div>
						<div class="col-md-offset-4 col-md-4 text-center">
							<input type="hidden" name="forgotpassword" value="forgotpassword">
							<button type="submit" class="btn btn-default">Submit</button>
						</div>
					</form>
					{/if}				
			{else}
			<div class="container bg-warning">
				<h1 class="text-center">Login</h1>
				<div class="col-md-12">
					<form method="POST">
						<label class="col-md-offset-4 col-md-4 text-center" for="username">Username 
							<input type="username" class="form-control" placeholder="Username" name="username">
						</label>
						<div class="clearfix"></div>
						<label class="col-md-offset-4 col-md-4 text-center" for="password">Password 
							<input type="password" class="form-control" placeholder="Password" name="password">
						</label>
						<div class="clearfix"></div>
						<div class="col-md-offset-4 col-md-4 text-center text-danger">
							{$message}
						</div>
						<div class="col-md-offset-4 col-md-4 text-center">
							<input type="hidden" name="enterlogin" value="enterlogin">
							<button type="submit" class="btn btn-default">Submit</button>
						</div>
						<div class="col-md-offset-4 col-md-4 text-center">
							<a href="index.php?forgot=true">Forgot Password?</a>
						</div>
					</form>
					{/if}
				{/if}
			</div>
		</div>
		{include file='page_footer.tpl'}
	</body>
</html>