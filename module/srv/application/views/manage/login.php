<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>MVNOモジュール共通API</title>
		<link rel="stylesheet" href="/manage/assets/vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="/manage/assets/vendor/metisMenu/metisMenu.min.css">
		<link rel="stylesheet" href="/manage/assets/vendor/datatables-plugins/dataTables.bootstrap.css">
	    <link rel="stylesheet" href="/manage/assets/vendor/datatables-responsive/dataTables.responsive.css">
		<link rel="stylesheet" href="/manage/assets/vendor/sb-admin-2/css/sb-admin-2.css">
		<link rel="stylesheet" href="/manage/assets/vendor/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="/manage/assets/css/app.css">
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-md-offset-4">
					<div class="login-panel panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">MVNOモジュール共通API</h3>
						</div>
						<div class="panel-body">
							<form role="form" method="post" action="/manage/login/validation">
								<fieldset>
									<?php if ($this->session->has_userdata('error_message')): ?>
										<div class="alert alert-dismissible alert-danger">
											<button type="button" class="close" data-dismiss="alert">×</button>
											<strong>エラー</strong>
											<p><?= $this->session->flashdata('error_message') ?></p>
										</div>
									<?php endif; ?>
									<div class="form-group">
										<input class="form-control" placeholder="ログインID" name="username" type="text" value="<?= isset($username) ? $username : '' ?>" required autofocus>
									</div>
									<div class="form-group">
										<input class="form-control" placeholder="パスワード" name="password" type="password" value="">
									</div>
									<button class="btn btn-lg btn-primary btn-block" type="submit">ログインする</button>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script src="/manage/assets/vendor/jquery/jquery.min.js"></script>
		<script src="/manage/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="/manage/assets/vendor/metisMenu/metisMenu.min.js"></script>
		<script src="/manage/assets/vendor/sb-admin-2/js/sb-admin-2.js"></script>
		<script src="/manage/assets/js/app.js"></script>
	</body>
</html>