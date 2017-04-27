		<div id="wrapper">
			<div id="page-wrapper">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<h1 class="page-header">事業者：一覧</h1>
						</div>
					</div>

					<div class="form-horizontal well">
						<div class="row">
							<?php
					        $attr = array("class" => "form-horizontal", "role" => "form", "id" => "search_operator_form", "name" => "search_operator_form");
					        echo form_open("operators/search", $attr);?>
					        <input type="hidden" name="search_name_hidden" value="<?= set_value('search_name', '')?>" />
					        <input type="hidden" name="service_provider_hidden" value="<?php echo set_value('service_provider', ''); ?>" />
					        <input type="hidden" name="column_name_hidden" value="" />
					        <input type="hidden" name="sort_type_hidden" value="" />
					        <input type="hidden" name="sort_service_provider_hidden" value="" />
							<div class="col-lg-6">
								<div class="form-group">
									<label class="control-label col-md-3">キーワード</label>
									<div class="col-md-9">
										<input type="text" class="form-control" placeholder="事業者名など、空白区切りで複数キーワード" id="search_name" name="search_name" value="<?php echo set_value('search_name', ''); ?>">
									</div>
								</div>
							</div>
							<div class="col-lg-6">

								<div class="form-group">
									<label class="control-label col-md-3">契約SP</label>
									<div class="col-md-9">
										<div class="btn-group">
											<?php foreach($arr_service_providers as $data) : ?>
											<button type="button" id="service_provider_<?= $data['id'];?>" name="<?= $data['name'];?>" value="<?= $data['id'];?>" onclick="selectOperator(this.value);" class="btn btn-primary <?= set_value('service_provider') == $data["id"] ? '' : 'btn-outline' ?> "><i id="service_provider_i_<?= $data['id'];?>" class="fa <?= set_value('service_provider') == $data["id"] ? 'fa-check-circle-o' : 'fa-circle-o' ?> fa-fw"></i> <?php echo html_escape($data['name']);?></button>
											<?php endforeach;?>
											<input type="hidden" name="service_provider" value="<?php echo set_value('service_provider', ''); ?>" />
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-12 text-center">
								<button id="btn_clear" name="btn_clear" type="reset" class="btn btn-default" onclick="clearFrom();"><i class="fa fa-eraser fa-fw"></i>クリア</button>
								<button id="btn_search" name="btn_search" type="submit" class="btn btn-primary"><i class="fa fa-search fa-fw"></i>絞り込む</button>
							</div>
							<?php echo form_close(); ?>
						</div>
					</div>

					<div id="operators_list">
						<?php $this->load->view('manage/operator/operators_ajax_pagination_data'); ?>
					</div>


					<hr>
					<div class="row">
						<div class="col-lg-6 col-lg-offset-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									設定反映
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-12 text-right">
											2017年04月04日 12:34:56 反映済み
											<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#syncModal">
												<i class="fa fa-refresh fa-fw"></i> 設定反映
											</button>
										</div>
									</div>
								</div>
							</div>
							<div class="modal fade" id="syncModal" tabindex="-1" role="dialog" aria-labelledby="syncModalLabel">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title" id="syncModalLabel">設定反映</h4>
										</div>
										<div class="modal-body">
											事業者情報を下記サーバーに反映します。よろしいですか？<br>
											・AAAサーバー<br>
											・BBBサーバー<br>
											・同期設定ファイルよりサーバー名を一覧で表示<br>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
											<button type="button" class="btn btn-warning"><i class="fa fa-refresh fa-fw"></i> 設定を反映する</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<footer>
						<p class="pull-right"><a href="#">Back to top</a></p>
						<p>© 2017 Company, Inc.</p>
					</footer>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			function selectOperator(id) {
				$("#service_provider_"+id).removeClass("btn-outline");
				$("#service_provider_i_"+id).addClass("fa-check-circle-o");
		    	$("#service_provider_i_"+id).removeClass("fa-circle-o");
				$("input[name=service_provider]:hidden").val(id);
				var service_providers = <?php echo json_encode($condition_service_providers); ?>;
				service_providers.forEach(function(service_provider) {
				    if(service_provider != id){
				    	$("#service_provider_"+service_provider).addClass("btn-outline");
				    	$("#service_provider_i_"+service_provider).removeClass("fa-check-circle-o");
				    	$("#service_provider_i_"+service_provider).addClass("fa-circle-o");
					}
				});
			};
			function clearFrom() {
				$('#search_name').removeAttr('value');
				$("input[name=service_provider]:hidden").val('');
				var service_providers = <?php echo json_encode($condition_service_providers); ?>;
				service_providers.forEach(function(service_provider) {
					$("#service_provider_"+service_provider).addClass("btn-outline");
			    	$("#service_provider_i_"+service_provider).removeClass("fa-check-circle-o");
			    	$("#service_provider_i_"+service_provider).addClass("fa-circle-o");
				});
			};

			function arrangementData (column, sort, sort_service_provider) {
				$('input[name=column_name_hidden]').val(column);
				$('input[name=sort_type_hidden]').val(sort);
				$('input[name=sort_service_provider_hidden]').val(sort_service_provider);
				var search_name = $('input[name=search_name_hidden]').val();
		        var service_provider = $('input[name=service_provider_hidden]').val();
				$.ajax({
					method: "POST",
					url: "<?php echo base_url(); ?>operators/arrangementData",
					data: {
						column: column,
						sort: sort,
						sort_service_provider: sort_service_provider,
						search_name: search_name,
						service_provider: service_provider
					},
					success: function(data) {
						$("#operators_list").html(data);
					}
				});
			}
		</script>