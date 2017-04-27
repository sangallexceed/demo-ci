<?php
use PhpParser\Node\Stmt\Echo_;
?>
		<div id="wrapper">
			<div id="page-wrapper">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<h1 class="page-header">
								<a href="<?= site_url('operators') ?>"><i class="fa fa-arrow-circle-left fa-fw"></i></a>
								事業者：新規登録
							</h1>
						</div>
					</div>
					<?php if ($this->session->flashdata('error_message_create')): ?>
						<div class="alert alert-dismissible alert-danger">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<strong>エラー</strong>
							<p><?= $this->session->flashdata('error_message_create') ?></p>
						</div>
						<?php $this->session->unmark_flash('error_message_create'); ?>
					<?php endif; ?>
					<form class="form-horizontal" method="post" role="form" action="<?= site_url('operators/create') ?>">
						<?php if (isset($arr_ip_address_crt)): ?>
						<input type="hidden" name="count_ip_address_crt" value="<?= count($arr_ip_address_crt); ?>" />
						<?php else: ?>
						<input type="hidden" name="count_ip_address_crt" value="" />
						<?php endif;?>
						<div class="panel panel-default">
							<div class="panel-body form-horizontal">
								<div class="row">
									<div class="col-lg-6">
										<legend>事業者情報</legend>
										<?php if($this->session->has_userdata('error_business_name_crt') && set_value('business_name') === '') : ?>
										<div class="form-group has-error">
										<?php else: ?>
										<div class="form-group">
										<?php endif;?>
											<label class="control-label col-md-3"><span class="text-danger">※</span>事業者名</label>
											<div class="col-md-9">
												<input type="text" class="form-control" name="business_name"  placeholder=""  value="<?= set_value('business_name',''); ?>" >
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">利用開始日</label>
											<div class="col-md-4">
												<input class="form-control" id="start_date" name="start_date" placeholder="" value="<?php echo set_value('start_date', ''); ?>" >
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">利用終了日</label>
											<div class="col-md-4">
												<input class="form-control" id="end_date" name="end_date" placeholder="" value="<?php echo set_value('end_date', ''); ?>" >
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
										<?php if (isset($arr_ip_address_crt)): ?>
										<?php foreach($arr_ip_address_crt as $ip_address) : ?>
										<div class="form-group" id="group_add_ip_<?= $count_ip_address_crt ++ ?>">
											<label class="control-label col-md-3">No.<?= $count_ip_address_crt - 1 ?></label>
											<div class="col-md-4">
												<div class="input-group">
													<input type="text" class="form-control" name="ip_address_<?= $count_ip_address_crt - 1 ?>" value="<?= $ip_address['ip_address'] ?>">
													<input type="hidden" name="operator_ip_address_<?= $count_ip_address_crt - 1 ?>" value="<?= $ip_address['ip_address'] ?>" />
													<span class="input-group-btn">
														<button class="btn btn-default" id="<?= $count_ip_address_crt - 1 ?>" onclick="removeGroupIP(this.id);" type="button"><i class="fa fa-times" ></i> 削除</button>'
													</span>
												</div>
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
												<button type="button" id="no_agreement_<?= $data['id'] ?>" class="btn btn-primary <?= set_value('chk_input_agreement_'.$data['id']) == 1 ? 'btn-outline' : '' ?>" name="no_agreement" onclick="selectAgreement(<?= $data['id'] ?>, this.name);">
												<i class="fa <?= set_value('chk_input_agreement_'.$data['id']) == 0 ? 'fa-check-circle-o' : 'fa-circle-o' ?> fa-fw" id="no_agreement_<?= $data['id'] ?>_i"></i> 未契約</button>
												<button type="button" id="agreement_<?= $data['id'] ?>"  class="btn btn-primary <?= set_value('chk_input_agreement_'.$data['id']) == 0 ? 'btn-outline' : '' ?>" name="agreement" onclick="selectAgreement(<?= $data['id'] ?>, this.name);">
												<i class="fa <?= set_value('chk_input_agreement_'.$data['id']) == 1 ? 'fa-check-circle-o' : 'fa-circle-o' ?> fa-fw" id="agreement_<?= $data['id'] ?>_i"></i> 契約中</button>
												<input type="hidden"  name="chk_input_agreement_<?= $data['id'] ?>" value="<?php echo set_value('chk_input_agreement_'.$data['id'],''); ?>" />
											</div>
										</div>
										<?php if($this->session->has_userdata('error_service_provider_'. $data["id"]) && set_value('indentify_code_'.$data['id']) === '') : ?>
										<div id="form_group_indentify_code_<?= $data['id'] ?>" class="form-group has-error">
										<?php else: ?>
										<div class="form-group">
										<?php endif;?>
											<label class="control-label col-md-3">識別コード</label>
											<div class="col-md-6">
												<input type="text" class="form-control" name="indentify_code_<?= $data['id'] ?>"  placeholder="" id="indentify_code" <?= set_value('chk_input_agreement_'.$data['id']) == 0 ? 'disabled' : '' ?> value="<?php echo set_value('indentify_code_'.$data['id'],''); ?>">
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
										<a href="<?= site_url('operators') ?>"><i class="fa fa-arrow-circle-left fa-3x fa-fw"></i></a>
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

		<script type="text/javascript">
			$('#start_date').datetimepicker({
				timeFormat: 'HH:mm:ss',
				dateFormat: 'yy/mm/dd'
			});
			$('#end_date').datetimepicker({
				timeFormat: 'HH:mm:ss',
				dateFormat: 'yy/mm/dd'
			});
			var click =$("input[name=count_ip_address_crt]:hidden").val();
			function addIPAddress() {
				if(click < 100){
					click++;
					var dummy = '<div class="form-group" id="group_add_ip_'+click+'">'
						+ '<label class="control-label col-md-3" id="label_'+ click +'">No.'+ click +'</label>'
						+ '<div class="col-md-4">'
						+ '	<div class="input-group">'
						+ '		<input type="text" class="form-control" name="ip_address_'+ click +'" value="" >'
						+ '		<span class="input-group-btn">'
						+ '			<button class="btn btn-default" id="'+click+'" onclick="removeGroupIP(this.id);" type="button"><i class="fa fa-times" ></i> 削除</button>'
						+ '		</span>'
						+ '	</div>'
						+ '</div>'
						+ '</div>';
		    		document.getElementById('ip_address').innerHTML += dummy;
		    		$("input[name=count_ip_address_crt]").val(click);
				}
			};

			function removeGroupIP(id) {
				$("#group_add_ip_"+id).remove();
				var value= $("input[name=ip_address_"+id+"]").val();
				var list= $("input[name=count_ip_address_crt]").val();
			};

			function selectAgreement(id, name) {
				if (name == 'no_agreement')
				{
					$("#no_agreement_"+id+'_i').addClass("fa-check-circle-o");
					$("#no_agreement_"+id+'_i').removeClass("fa-circle-o");
					$("#no_agreement_"+id).removeClass("btn-outline");
					$("input[name=chk_input_agreement_"+id+']:hidden').val('0');
					//
					$("input[name=indentify_code_"+id+']').attr("disabled", true);
					$("input[name=indentify_code_"+id+']').val('');
					//
					$("#agreement_"+id+'_i').removeClass("fa-check-circle-o");
					$("#agreement_"+id+'_i').addClass("fa-circle-o");
					$("#agreement_"+id).addClass("btn-outline");
					$("#form_group_indentify_code_"+id).removeClass("has-error");
				}
				else
				{
					$("#agreement_"+id+'_i').addClass("fa-check-circle-o");
					$("#agreement_"+id+'_i').removeClass("fa-circle-o");
					$("#agreement_"+id).removeClass("btn-outline");
					$("input[name=chk_input_agreement_"+id+']:hidden').val('1');
					//
					$("input[name=indentify_code_"+id+']').attr("disabled", false);
					//
					$("#no_agreement_"+id+'_i').removeClass("fa-check-circle-o");
					$("#no_agreement_"+id+'_i').addClass("fa-circle-o");
					$("#no_agreement_"+id).addClass("btn-outline");
				}
			};

		</script>
