<!doctype html>
<title>Site Maintenance</title>
<head>
	<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
	<script> var BASE_URL = "<?php echo base_url(); ?>"; </script>
</head>
<style>
  body { text-align: center; padding: 150px; }
  h1 { font-size: 50px; }
  body { font: 20px Helvetica, sans-serif; color: #333; }
  article { display: block; text-align: left; width: 650px; margin: 0 auto; }
  a { color: #dc8100; text-decoration: none; }
  a:hover { color: #333; text-decoration: none; }
</style>
<body>
	<div style="position:absolute; top:10px; right:10px;">
		<a href="<?php echo base_url(); ?>users/authentication" style="margin-right:20px;">Login</a>
		<a href="<?php echo base_url(); ?>users/authentication/logout">Logout</a>
	</div>
	<article>
	    <h1>We&rsquo;ll be back soon!</h1>
	    <div>
	        <p>Sorry for the inconvenience but we&rsquo;re performing some maintenance at the moment. If you need to you can always contact us, otherwise we&rsquo;ll be back online shortly!</p>
	        <p>&mdash; The Team</p>
					<?php if($this->_SuperAdmin) : ?>
						<p style="float:right;"><button style="padding:15px;" onclick="openSystem()">OPEN SYSTEM</button></p>
						<script>
							function openSystem(){
								$.get(BASE_URL + 'maintenance/open_system',function(rs){
									if(rs == 'success'){
										window.location.href = BASE_URL;
									}
								});
							}
							</script>
						<?php endif; ?>
							<script>
							setInterval(function(){
								$.get(BASE_URL + 'maintenance/check_open_system', function(rs){
									if(rs == 'open'){
										window.location.href = BASE_URL;
									}
								});
							}, 10000);
							</script>
	    </div>
	</article>
</body>
