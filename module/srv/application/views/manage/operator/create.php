		<div id="wrapper">
			<div id="page-wrapper">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<h1 class="page-header">
								<a href="<?= site_url('operator') ?>"><i class="fa fa-arrow-circle-left fa-fw"></i></a>
								事業者：新規登録
							</h1>
						</div>
					</div>
					<form class="form-horizontal" method="post" role="form" action="<?= site_url('operator/create') ?>">
						<?php if (isset($arr_ip_address)): ?>
						<input type="hidden" name="count_ip_address" value="<?= count($arr_ip_address); ?>" />
						<input type="hidden" name="number_ip_address" value="<?= count($arr_ip_address); ?>" />
						<?php else: ?>
						<input type="hidden" name="count_ip_address" value="" />
						<input type="hidden" name="number_ip_address" value="" />
						<?php endif;?>
						<div class="panel panel-default">
							<div class="panel-body form-horizontal">
								<div class="row">
									<div class="col-lg-6">
										<legend>事業者情報</legend>
										<?php if(form_error('business_name') != '') : ?>
										<div class="form-group has-error">
										<?php else: ?>
										<div class="form-group">
										<?php endif;?>
											<label class="control-label col-md-3"><span class="text-danger">※</span>事業者名</label>
											<div class="col-md-9">
												<input type="text" class="form-control" name="business_name"  placeholder=""  value="<?= set_value('business_name',''); ?>" >
												<?php echo form_error('business_name', '<div class="help-block">', '</div>');?>
											</div>
										</div>
										<?php if(form_error('start_date') != '') : ?>
										<div class="form-group has-error">
										<?php else: ?>
										<div class="form-group">
										<?php endif;?>
											<label class="control-label col-md-3">利用開始日</label>
											<div class="col-md-4">
												<input class="form-control" id="start_date" name="start_date" placeholder="" value="<?php echo set_value('start_date', ''); ?>" >
												<?php echo form_error('start_date', '<div class="help-block">', '</div>');?>
											</div>
										</div>
										<?php if(form_error('end_date') != ''  || $error_date != '') : ?>
										<div class="form-group has-error">
										<?php else: ?>
										<div class="form-group">
										<?php endif;?>
											<label class="control-label col-md-3">利用終了日</label>
											<div class="col-md-4">
												<input class="form-control" id="end_date" name="end_date" placeholder="" value="<?php echo set_value('end_date', ''); ?>" >
												<?php echo form_error('end_date', '<div class="help-block">', '</div>');?>
												<?php if($error_date != '') : ?>
												<div class="help-block"><?= $error_date  ?></div>
												<?php endif; ?>
											</div>
										</div>
									</div>
									<div class="col-lg-6" id="ip_address">
										<legend>アクセス制限</legend>
										<div class="form-group">
											<label class="control-label col-md-3">接続許可IPアドレス</label>
											<div class="col-md-9">
												<button type="button" class="btn btn-primary" name="add_ip_address" onclick="addIPAddress();"><i class="fa fa-plus fa-fw"></i> 追加</button>
											</div>
										</div>
										<?php if (isset($arr_ip_address)): ?>
										<?php foreach($arr_ip_address as $ip_address) : ?>
										<?php if ($ip_address['message_error_ip_address'] != '') : ?>
											<div class="form-group has-error" id="group_add_ip_<?= $count ++ ?>">
										<?php else: ?>
											<div class="form-group" id="group_add_ip_<?= $count ++ ?>">
										<?php endif;?>
											<label class="control-label col-md-3">No.<?= $count - 1 ?></label>
											<div class="col-md-4">
												<div class="input-group">
													<input type="text" class="form-control" name="ip_address_<?= $count - 1 ?>" value="<?= $ip_address['ip_address'] ?>">
													<input type="hidden" name="operator_ip_address_<?= $count - 1 ?>" value="<?= $ip_address['ip_address'] ?>" />
													<span class="input-group-btn">
														<button class="btn btn-default" id="<?= $count - 1 ?>" onclick="removeGroupIP(this.id);" type="button"><i class="fa fa-times" ></i> 削除</button>'
													</span>
												</div>
												<?php if ($ip_address['message_error_ip_address'] != '') : ?>
												<div class="help-block"><?php echo $ip_address['message_error_ip_address'] ?></div>
												<?php endif;?>
											</div>
										</div>
										<?php endforeach;?>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-body form-horizontal">
								<div class="row">
									<?php foreach($arr_service_providers as $data) : ?>
									<div class="col-lg-6">
										<legend><?= $data['name'] ?></legend>
										<div class="form-group">
											<label class="control-label col-md-3"><span class="text-danger">※</span>契約状況</label>
											<div class="col-md-9 btn-group" id="contract_status_<?= $data['id'] ?>">
												<button type="button" id="no_service_provider_<?= $data['id'] ?>" class="btn btn-primary <?= set_value('chk_input_service_provider_'.$data['id']) == 1 ? 'btn-outline' : '' ?>" name="no_service_provider" onclick="selectServiceprovider(<?= $data['id'] ?>, this.name);">
												<i class="fa <?= set_value('chk_input_service_provider_'.$data['id']) == 0 ? 'fa-check-circle-o' : 'fa-circle-o' ?> fa-fw" id="no_service_provider_<?= $data['id'] ?>_i"></i> 未契約</button>
												<button type="button" id="service_provider_<?= $data['id'] ?>"  class="btn btn-primary <?= set_value('chk_input_service_provider_'.$data['id']) == 0 ? 'btn-outline' : '' ?>" name="ye_service_provider" onclick="selectServiceprovider(<?= $data['id'] ?>, this.name);">
												<i class="fa <?= set_value('chk_input_service_provider_'.$data['id']) == 1 ? 'fa-check-circle-o' : 'fa-circle-o' ?> fa-fw" id="service_provider_<?= $data['id'] ?>_i"></i> 契約中</button>
												<input type="hidden"  name="chk_input_service_provider_<?= $data['id'] ?>" value="<?php echo set_value('chk_input_service_provider_'.$data['id'],''); ?>" />
											</div>
										</div>
										<?php if(form_error('identifying_code_'.$data['id']) != '') : ?>
										<div id="form_group_indentifying_code_<?= $data['id'] ?>" class="form-group has-error">
										<?php else: ?>
										<div class="form-group">
										<?php endif;?>
											<label class="control-label col-md-3">識別コード</label>
											<div class="col-md-6">
												<input type="text" class="form-control" name="identifying_code_<?= $data['id'] ?>"  placeholder="" id="indentify_code" <?= set_value('chk_input_service_provider_'.$data['id']) == 0 ? 'disabled' : '' ?> value="<?php echo set_value('identifying_code_'.$data['id'],''); ?>">
												<?php echo form_error('identifying_code_'.$data['id'], '<span id="error_message_indentifying_code_'.$data['id'].'" class="help-block">', '</span>');?>
											</div>
										</div>
									</div>
									<?php endforeach;?>
								</div>
							</div>
						</div>
					<div class="row">
						<div class="col-lg-12">
							<form role="form">
								<div class="row">
									<div class="col-lg-4 col-lg-offset-4 text-center">
										<button type="submit" class="btn btn-success btn-block btn-lg"><i class="fa fa-floppy-o fa-fw"></i> 保存</button>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<a href="<?= site_url('operator') ?>"><i class="fa fa-arrow-circle-left fa-3x fa-fw"></i></a>
									</div>
								</div>
							</form>
							<footer>
								<hr>
								<p class="pull-right"><a href="#">Back to top</a></p>
								<p>© 2017 Company, Inc.</p>
							</footer>
						</div>
					</div>
					</form>
				</div>
			</div>
		</div>

		<script src="<?= site_url('assets/vendor/operatorJs/operatorJs.js'); ?>"></script>
