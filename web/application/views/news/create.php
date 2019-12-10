<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Создать новость</h2>
				</div>
				<form action="#" id="createForm" method="POST">
					<div class="form-group row">
						<label for="name" class="col-sm-3 col-form-label">Заголовок:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="name" name="name" placeholder="Введите заголовок">
						</div>
					</div>
					<div class="form-group row">
						<label for="text" class="col-sm-3 col-form-label">Текст:</label>
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
						url: '/news/create/ajax',
						dataType: 'json',
						success: function(data) {
							switch(data.status) {
								case 'error':
									showError(data.error);
									$('button[type=submit]').prop('disabled', false);
									break;
								case 'success':
									showSuccess(data.success);
									//setTimeout("redirect('/news/index')", 1500);
									break;
							}
						},
						beforeSubmit: function(arr, $form, options) {
							$('button[type=submit]').prop('disabled', true);
						}
					});
				</script>
<?php echo $footer ?>
