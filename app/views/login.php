<?php $this->setSiteTitle('Login') ?>

<?php $this->start('body'); ?>

	<div class="center">

		<h1>Login</h1>
		<form action="profile" method="post">
			<input type="text" name="username" value="" placeholder="Username"><p />
			<input type="password" name="password" value="" placeholder="Password"><p />
			<input type="submit" name="register" value="Login">
		</form>	

	</div>

<?php $this->end(); ?>
