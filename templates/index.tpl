<html>

	<head>

		<title>Site Search</title>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" integrity="sha384-aUGj/X2zp5rLCbBxumKTCw2Z50WgIr1vs/PFN4praOTvYXWlVyh2UtNUU0KAUhAX" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>

<style>

.search-title {
	color:#0033FF;
	margin-top:20px;
}

.search-content {
	word-wrap:break-word;
}

.search-url {
	color:#00AA00;
}

</style>

</head>
<body>
<br>
{nocache}
<div class="container">
	<h1 class="text-center">Leathorn's Site Search</h1>
	<div class="col-md-12 text-center">
		<form action="index.php" method="GET">
			<input type="text" class="search" value="{$search_term}" name="q">
			<input type="submit" value="submit">
		</form>

{if $search_results!=null }


	{foreach $search_results as $result}
	<div class="col-md-12 text-left">
		<div class="search-title col-md-offset-1 col-md-10">
			<a href="{$result.url}">{$result.title}</a>
		</div>
	</div>
	<div class="col-md-12 text-left">
		<div class="search-content col-md-offset-1 col-md-10">
			{$result.content}
		</div>
	</div>
	<div class="col-md-12 text-left">
		<div class="search-url col-md-offset-1 col-md-10">
			{$result.url}
		</div>
	</div>
	{/foreach}


{else}
Please Enter a Search Term
{/if}
<br>
{if $total_rows > count($search_results)}
	{for $page=1 to $pages}
		<a href="index.php?q={$search_term}&p={$page}">{$page}</a>
	{/for}
{/if}
{/nocache}
	</div>

</div>s

</body>
</html>