<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Запрос в службу поддержки #<?php echo $ticket['ticket_id'] ?></h2>
				</div>
    			<div class="card mb-4">
					<div class="card-header">Информация о запросе</div>
					<div class="card-body">
						<table class="table table-borderless mb-0">
							<tr>
								<th>ID Запроса:</th>
								<td>№<?php echo $ticket['ticket_id'] ?></td>
							</tr>
							<tr>
								<th>Тема:</th>
								<td><?php echo $ticket['ticket_name'] ?></td>
							</tr>
							<tr>
								<th>Дата создания:</th>
								<td><?php echo date("d.m.Y в H:i", strtotime($ticket['ticket_date_add'])) ?></td>
							</tr>
							<tr>
								<th>Статус:</th>
								<td>
									<?php if($ticket['ticket_status'] == 0): ?> 
									<span class="badge badge-primary">Закрыт</span>
									<?php elseif($ticket['ticket_status'] == 1): ?> 
									<span class="badge badge-warning">Ответ от клиента</span>
									<?php elseif($ticket['ticket_status'] == 2): ?> 
									<span class="badge badge-success">Ответ от поддержки</span>
									<?php endif; ?> 
								</td>
							</tr>
						</table>
					</div>
				</div>
				<?php foreach($messages as $item): ?> 
				<div class="card mb-3">
					<div class="card-body">
						<h5 class="card-title"><?php echo $item['user_firstname'] ?> <?php echo $item['user_lastname'] ?>:</h5>
						<p class="card-text"><?php echo nl2br($item['ticket_message']) ?></p>
						<p class="card-text"><small class="text-muted"><i class="icon ion-md-calendar"></i> <?php echo date("d.m.Y в H:i", strtotime($item['ticket_message_date_add'])) ?></small></p>
					</div>
				</div>
				<?php endforeach; ?> 
				<?php if($ticket['ticket_status'] != 0): ?> 
				<div class="pb-2 mt-4 mb-2">
					<h3>Отправить сообщение</h3>
				</div>
				<form id="sendForm" action="#" method="POST">
					<div class="form-group row">
						<label for="text" class="col-sm-3 col-form-label">Текст:</label>
						<div class="col-sm-7">
							<textarea class="form-control" id="text" name="text" rows="3" placeholder="Введите текст сообщения"></textarea>
						</div>
					</div>
					<div class="form-group row">
						<div class="offset-sm-3 col-sm-9">
							<div class="checkbox">
								<label><input type="checkbox" id="closeticket" name="closeticket" onChange="toggleText()"> Закрыть запрос</label>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<div class="offset-sm-3 col-sm-9">
							<button type="submit" class="btn btn-primary">Отправить</button>
						</div>
					</div>
				</form>
				<script>
					$('#sendForm').ajaxForm({ 
						url: '/admin/tickets/view/ajax/<?php echo $ticket['ticket_id'] ?>',
						dataType: 'text',
						success: function(data) {
							console.log(data);
							data = $.parseJSON(data);
							switch(data.status) {
								case 'error':
									showError(data.error);
									$('button[type=submit]').prop('disabled', false);
									break;
								case 'success':
									showSuccess(data.success);
									$('#text').val('');
									setTimeout("reload()", 1500);
									break;
							}
						},
						beforeSubmit: function(arr, $form, options) {
							$('button[type=submit]').prop('disabled', true);
						}
					});
					function toggleText() {
						var status = $('#closeticket').is(':checked');
						if(status) {
							$('#text').prop('disabled', true);
						} else {
							$('#text').prop('disabled', false);
						}
					}
				</script>
				<?php endif; ?>
<?php echo $footer ?>
