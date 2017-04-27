		<div id="wrapper">
			<div id="page-wrapper">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<h1 class="page-header">
								<a href="<?= site_url('operators') ?>"><i class="fa fa-arrow-circle-left fa-fw"></i></a>
								事業者：編集
							</h1>
						</div>
					</div>
					　<?php if ($this->session->flashdata('message')): ?>
						<div class="alert alert-dismissible alert-info">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<strong>メッセージ</strong>
							<p><?= $this->session->flashdata('message') ?></p>
						</div>
						<?php $this->session->unmark_flash('message'); ?>
					<?php endif; ?>
					<?php if ($this->session->flashdata('error_message_update')): ?>
					<div class="alert alert-dismissible alert-danger">
						<button type="button" class="close" data-dismiss="alert">×</button>
							<strong>エラー</strong>
							<p><?= $this->session->flashdata('error_message_update') ?></p>
					</div>
					<?php $this->session->unmark_flash('error_message_update'); ?>
					<?php endif; ?>
					<form class="form-horizontal" method="post" role="form" action="<?= site_url('operators/update/'.$operator->operator_id) ?>">
						<input type="hidden" name="operator_id" value="<?php echo $operator->operator_id; ?>" />
						<?php if (isset($arr_ip_address)): ?>
							<input type="hidden" name="count_ip_address" value="<?= count($arr_ip_address); ?>" />
						<?php else: ?>
							<input type="hidden" name="count_ip_address" value="<?= count($ip_addresss); ?>" />
						<?php endif;?>

						<?php if (isset($arr_ip_address_id_delete)): ?>
							<?php foreach($arr_ip_address_id_delete as $ip_address_id_dlt) : ?>
								<input type="hidden" name="ip_address_id_delete_<?= $count_ip_address_id_delete ++ ?>" value="<?= $ip_address_id_dlt ?>" />
							<?php endforeach;?>
							<input type="hidden" name="count_arr_ip_address_id_delete" value="<?= count($arr_ip_address_id_delete) ?>" />
						<?php endif;?>

						<div class="panel panel-default">
							<div class="panel-body form-horizontal">
								<div class="row">
									<div class="col-lg-6">
										<legend>事業者情報</legend>
										<?php if($this->session->has_userdata('error_business_name_upd') && set_value('business_name') === '') : ?>
										<div class="form-group has-error">
										<?php else: ?>
										<div class="form-group">
										<?php endif;?>
											<label class="control-label col-md-3"><span class="text-danger">※</span>事業者名</label>
											<div class="col-md-9">
											<?php if (isset($business_name)): ?>
												<input type="text" class="form-control" name="business_name"  placeholder="" value="<?= html_escape($business_name); ?>">
											<?php else: ?>
												<input type="text" class="form-control" name="business_name"  placeholder="" value="<?= html_escape($operator->operator_name); ?>">
											<?php endif;?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">利用開始日</label>
											<div class="col-md-4">
											<?php if (isset($start_date0)): ?>
												<input type="text" class="form-control" name="start_date" id="start_date" placeholder="" value="<?= html_escape($start_date); ?>" >
											<?php else: ?>
												<input type="text" class="form-control" name="start_date" id="start_date"  placeholder="" value="<?php if(isset($operator->start_date) && $operator->start_date != '0000-00-00'){ echo date("Y/m/d", strtotime($operator->start_date));}?>">
											<?php endif;?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">利用終了日</label>
											<div class="col-md-4">
												<?php if (isset($end_date)): ?>
													<input type="text" class="form-control" name="end_date" id="end_date" placeholder="" value="<?= html_escape($end_date); ?>" >
												<?php else: ?>
													<input type="text" class="form-control" name="end_date" id="end_date"  placeholder="" value="<?php if(isset($operator->end_date) && ($operator->end_date != '0000-00-00')){ echo date("Y/m/d", strtotime($operator->end_date));}?>">
												<?php endif;?>
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
										<div class="form-group" id="group_add_ip_<?= $count_ip_address ++ ?>">
											<label class="control-label col-md-3">No.<?= $count_ip_address - 1 ?></label>
											<div class="col-md-4">
												<div class="input-group">
													<input type="text" class="form-control" name="ip_address_<?= $count_ip_address - 1 ?>" value="<?= $ip_address['ip_address'] ?>">
													<input type="hidden" name="operator_ip_address_<?= $count_ip_address - 1 ?>" value="<?= $ip_address['ip_address_id'] ?>" />
													<span class="input-group-btn">
														<button class="btn btn-default" id="<?= $count_ip_address - 1 ?>" onclick="removeGroupIP(this.id);" type="button"><i class="fa fa-times" ></i> 削除</button>'
													</span>
												</div>
											</div>
										</div>
										<input type="hidden" name="delete_operator_ip_address_<?= $count_ip_address - 1 ?>" value="" />
										<?php endforeach;?>
										<?php else: ?>
										<?php if (count($ip_addresss) <= 0): {echo '';} else: ?>
										<?php foreach($ip_addresss as $data) : ?>
										<div class="form-group" id="group_add_ip_<?= $count_ip_address ++ ?>">
											<label class="control-label col-md-3">No.<?= $count_ip_address - 1 ?></label>
											<div class="col-md-4">
												<div class="input-group">
													<input type="text" class="form-control" name="ip_address_<?= $count_ip_address - 1 ?>" value="<?= html_escape($data['ip_address']); ?>">
													<input type="hidden" name="operator_ip_address_<?= $count_ip_address - 1 ?>" value="<?php echo $data['operator_ip_address_id']; ?>" />
													<span class="input-group-btn">
														<button class="btn btn-default" id="<?= $count_ip_address - 1 ?>" onclick="removeGroupIP(this.id);" type="button"><i class="fa fa-times" ></i> 削除</button>'
													</span>
												</div>
											</div>
										</div>
										<input type="hidden" name="delete_operator_ip_address_<?= $count_ip_address - 1 ?>" value="" />
										<?php endforeach;?>
										<?php endif; ?>
										<?php endif;?>
									</div>
								</div>
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-body form-horizontal">
								<div class="row">
									<?php foreach($service_providers as $data) : ?>
									<div class="col-lg-6">
										<?php foreach($arr_service_providers as $service_provider_item) : ?>
										<?php if ($data['service_provider'] == $service_provider_item['id']): ?>
										<legend><?= $service_provider_item['name'] ?></legend>
										<?php endif;?>
										<?php endforeach;?>
										<div class="form-group">
											<label class="control-label col-md-3"><span class="text-danger">※</span>契約状況</label>
											<div class="col-md-9 btn-group" id="contract_status_<?= $data['service_provider'] ?>">
												<?php
												if ($this->session->has_userdata('chk_input_agreement_'.$data['service_provider'])): ?>
													<button type="button" id="no_agreement_<?= $data['service_provider'] ?>" class="btn btn-primary <?= $this->session->flashdata('chk_input_agreement_'.$data['service_provider']) == 1? 'btn-outline': '' ?>" onclick="selectNoAgreement(<?= $data['service_provider'] ?>);">
													<i class="fa <?= $this->session->flashdata('chk_input_agreement_'.$data['service_provider']) == 0 ? 'fa-check-circle-o' : 'fa-circle-o' ?> fa-fw" id="no_agreement_<?= $data['service_provider'] ?>_i"></i> 未契約</button>
													<button type="button" id="agreement_<?= $data['service_provider'] ?>"  class="btn btn-primary <?= $this->session->flashdata('chk_input_agreement_'.$data['service_provider']) == 0? 'btn-outline': '' ?>" onclick="selectAgreement(<?= $data['service_provider'] ?>);">
													<i class="fa <?= $this->session->flashdata('chk_input_agreement_'.$data['service_provider']) == 1 ? 'fa-check-circle-o' : 'fa-circle-o' ?> fa-fw" id="agreement_<?= $data['service_provider'] ?>_i"></i> 契約中</button>
													<input type="hidden"  name="chk_input_agreement_<?= $data['service_provider'] ?>" value="<?= html_escape($this->session->flashdata('chk_input_agreement_'.$data['service_provider'])); ?>" />
												<?php else: ?>
													<button type="button" id="no_agreement_<?= $data['service_provider'] ?>" class="btn btn-primary <?= $data['contract_status'] == 1? 'btn-outline': '' ?>" onclick="selectNoAgreement(<?= $data['service_provider'] ?>);">
													<i class="fa <?= $data['contract_status'] == 0 ? 'fa-check-circle-o' : 'fa-circle-o' ?> fa-fw" id="no_agreement_<?= $data['service_provider'] ?>_i"></i> 未契約</button>
													<button type="button" id="agreement_<?= $data['service_provider'] ?>"  class="btn btn-primary <?= $data['contract_status'] == 0? 'btn-outline': '' ?>" onclick="selectAgreement(<?= $data['service_provider'] ?>);">
													<i class="fa <?= $data['contract_status'] == 1 ? 'fa-check-circle-o' : 'fa-circle-o' ?> fa-fw" id="agreement_<?= $data['service_provider'] ?>_i"></i> 契約中</button>
													<input type="hidden"  name="chk_input_agreement_<?= $data['service_provider'] ?>" value="<?= html_escape($data['contract_status']); ?>" />
												<?php endif;?>
											</div>
										</div>
										<?php if($this->session->has_userdata('error_service_provider_upd_'. $data['service_provider']) && $this->session->flashdata('identifying_code_'.$data['service_provider']) === '') : ?>
										<div id="form_group_indentify_code_ud_<?= $data['service_provider'] ?>" class="form-group has-error">
										<?php else: ?>
										<div class="form-group">
										<?php endif;?>
											<label class="control-label col-md-3">識別コード</label>
											<div class="col-md-6">
												<?php if ($this->session->has_userdata('chk_input_agreement_'.$data['service_provider'])): ?>
													<input type="text" class="form-control" name="identifying_code_<?= $data['service_provider'] ?>"  placeholder="" id="identifying_code"
													value="<?= $this->session->flashdata('chk_input_agreement_'.$data['service_provider']) == 1? html_escape($this->session->flashdata('identifying_code_'.$data['service_provider'])) : '' ?>" <?= $this->session->flashdata('chk_input_agreement_'.$data['service_provider']) == 1? '' : 'disabled' ?>>
												<?php else: ?>
													<input type="text" class="form-control" name="identifying_code_<?= $data['service_provider'] ?>"  placeholder="" id="identifying_code" value="<?= $data['contract_status'] == 1? html_escape($data['identifying_code']) : '' ?>"  <?= $data['contract_status'] == 1? '' : 'disabled' ?>>
												<?php endif;?>
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
			var click =$("input[name=count_ip_address]:hidden").val();
			function addIPAddress() {
				click++;
				var dummy = '<div class="form-group" id="group_add_ip_'+click+'">'
					+ '<label class="control-label col-md-3">No.'+ click +'</label>'
					+ '<div class="col-md-4">'
					+ '	<div class="input-group">'
					+ '		<input type="text" class="form-control" name="ip_address_'+ click +'" value="" >'
					+ '		<input type="hidden" name="operator_ip_address_' + click + '" value="" />'
					+ '		<span class="input-group-btn">'
					+ '			<button class="btn btn-default" id="'+click+'" onclick="removeGroupIP(this.id);" type="button"><i class="fa fa-times" ></i> 削除</button>'
					+ '		</span>'
					+ '	</div>'
					+ '</div>'
					+ '</div>';
	    		document.getElementById('ip_address').innerHTML += dummy;
	    		$("input[name=count_ip_address]").val(click);
	    		$("input[name=count_ip_address_action]").val(click);
			};

			function removeGroupIP(id) {
				var value= $("input[name=ip_address_"+id+"]").val();
				var list= $("input[name=count_ip_address]").val();
				var ip_address_id = $("input[name=operator_ip_address_"+ id +"]:hidden").val();
				$("input[name=delete_operator_ip_address_"+ id +"]:hidden").val(ip_address_id);
				$("#group_add_ip_"+id).remove();
			};

			function selectNoAgreement(id) {
				$("#no_agreement_"+id+'_i').addClass("fa-check-circle-o");
				$("#no_agreement_"+id+'_i').removeClass("fa-circle-o");
				$("#no_agreement_"+id).removeClass("btn-outline");
				$("input[name=chk_input_agreement_"+id+']:hidden').val('0');
				//
				$("input[name=identifying_code_"+id+']').attr("disabled", true);
				$("input[name=identifying_code_"+id+']').val('');
				//
				$("#agreement_"+id+'_i').removeClass("fa-check-circle-o");
				$("#agreement_"+id+'_i').addClass("fa-circle-o");
				$("#agreement_"+id).addClass("btn-outline");
				$("#form_group_indentify_code_ud_"+id).removeClass("has-error");
			};
			function selectAgreement(id) {
				$("#agreement_"+id+'_i').addClass("fa-check-circle-o");
				$("#agreement_"+id+'_i').removeClass("fa-circle-o");
				$("#agreement_"+id).removeClass("btn-outline");
				$("input[name=chk_input_agreement_"+id+']:hidden').val('1');
				//
				$("input[name=identifying_code_"+id+']').attr("disabled", false);
				//
				$("#no_agreement_"+id+'_i').removeClass("fa-check-circle-o");
				$("#no_agreement_"+id+'_i').addClass("fa-circle-o");
				$("#no_agreement_"+id).addClass("btn-outline");
			};

		</script>