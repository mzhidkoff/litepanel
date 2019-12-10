<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Создать запрос</h2>
				</div>
				<form action="#" id="createForm" method="POST">
					<div class="form-group row">
						<label for="name" class="col-sm-3 col-form-label">Тема:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="name" name="name" placeholder="Введите тему">
						</div>
					</div>
					<div class="form-group row">
						<label for="text" class="col-sm-3 col-form-label">Сообщение:</label>
						<div class="col-sm-7">
							<textarea class="form-control" id="text" name="text" rows="5"></textarea>
						</div>
					</div>
					<div class="form-group row">
						<div class="offset-sm-3 col-sm-9">
							<button type="submit" class="btn btn-primary">Создать</button>
						</div>
					</div>
				</form>
				<script>
					$('#createForm').ajaxForm({ 
						url: '/tickets/create/ajax',
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
									setTimeout("redirect('/tickets/view/index/" + data.id + "')", 1500);
									break;
							}
						},
						beforeSubmit: function(arr, $form, options) {
							$('button[type=submit]').prop('disabled', true);
						}
					});
				</script>
<?php echo $footer ?>
