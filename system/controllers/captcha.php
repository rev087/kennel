<?php
	class Captcha_controller
	{
		public function index()
		{
			$captcha = new Captcha();
			$captcha->output();
		}
	}
?>
