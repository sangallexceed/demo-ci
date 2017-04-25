					<div class="row">
						<div class="col-sm-6">
							<div class="dataTables_info" role="status" aria-live="polite">57件中 1から10 を表示</div>
						</div>
						<div class="col-sm-6">
							<div class="dataTables_paginate paging_simple_numbers">
								<div class="text-right">
									<?= $this->ajax_pagination_operators->create_links(); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<div class="table-responsive">
								<table class="table table-bordered table-hover">
									<thead>
										<tr>
											<th class="col-md-1">
												No
												<a href="#"><i class="fa fa-arrow-up"></i></a>
												<a href="#"><i class="fa fa-arrow-down"></i></a>
											</th>
											<th class="col-md-3">
												事業者名
												<a href="#"><i class="fa fa-arrow-up"></i></a>
												<a href="#"><i class="fa fa-arrow-down"></i></a>
											</th>
											<?php foreach($agreements as $agreement) : ?>
											<th class="col-md-1">
												<?php echo $agreement['name']; ?>
												<a href="#"><i class="fa fa-arrow-up"></i></a>
												<a href="#"><i class="fa fa-arrow-down"></i></a>
											</th>
											<?php endforeach;?>
											<th class="col-md-2">
												利用開始日
												<a href="#"><i class="fa fa-arrow-up"></i></a>
												<a href="#"><i class="fa fa-arrow-down"></i></a>
											</th>
											<th class="col-md-2">
												利用終了日
												<a href="#"><i class="fa fa-arrow-up"></i></a>
												<a href="#"><i class="fa fa-arrow-down"></i></a>
											</th>
											<th class="col-md-1"><a href="<?php echo site_url('operators/create') ?>" class="btn btn-success btn-block btn-sm"><i class="fa fa-plus fa-fw"></i> 新規登録</a></th>
										</tr>
									</thead>
									<tbody>
										<?php if (count($operators) <= 0): {echo '';} else: ?>
										<?php foreach ($operators as $data): ?>
										<tr>
											<td><?= $data['operator_id'] ?></td>
											<td><?= $data['operator_name']?></td>
											<?php foreach($agreements as $agreement) : ?>
											<td>
												<?php if($data['service_provider_'.$agreement['id']] === '1')
												{ echo '<button type="button" class="btn btn-info btn-circle"><i class="fa fa-check"></i></button>';}else {
												'';}?>
											</td>
											<?php endforeach;?>
											<td><?php if(isset($data['start_date']) && ($data['start_date'] != '0000-00-00')){ echo date("Y/m/d", strtotime($data['start_date']));}?></td>
											<td><?php if(isset($data['end_date']) && ($data['end_date'] != '0000-00-00')){ echo date("Y/m/d", strtotime($data['end_date']));}?></td>
											<th><a href="<?= site_url('operators/update') ?>/<?= $data['operator_id'] ?>" class="btn btn-primary btn-outline btn-block btn-sm"><i class="fa fa-pencil-square-o fa-fw"></i> 編集</a></th>
										</tr>
										<?php endforeach; ?>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="dataTables_info" role="status" aria-live="polite">57件中 1から10 を表示</div>
						</div>
						<div class="col-sm-6">
							<div class="dataTables_paginate paging_simple_numbers">
								<div class="text-right">
									<?= $this->ajax_pagination_operators->create_links(); ?>
								</div>
							</div>
						</div>
					</div>