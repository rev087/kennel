<h1>Access Denied</h1>

<p class="msg error">ERROR: You do not have permission to access this feature</p>

<form method="post" action="<?php print url('ksetup/login'); ?>">
	<fieldset>
		<legend>Login</legend>
		<label>user <input type="text" name="username" autofocus /></label>
		<label>pass <input type="password" name="password" /></label>
		<input type="submit" />
	</fieldset>
</form>
