<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Создание локации</h2>
				</div>
				<form action="#" id="createForm" method="POST">
					<div class="pb-2 mt-4 mb-2">
						<h4>Основная информация</h4>
					</div>
					<div class="form-group row">
						<label for="name" class="col-sm-3 col-form-label text-sm-right">Название:</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" id="name" name="name" placeholder="Введите название">
						</div>
					</div>
					<div class="form-group row">
						<label for="ip2" class="col-sm-3 col-form-label text-sm-right">IP SSH Connect:</label>
						<div class="col-sm-3">
							<input type="text" class="form-control" id="ip2" name="ip2" placeholder="Введите IP">
						</div>
					</div>
					<div class="form-group row">
						<label for="ip" class="col-sm-3 col-form-label text-sm-right">IP:</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" id="ip" name="ip" placeholder="Введите IP">
						</div>
					</div>
					<div class="form-group row">
						<label for="user" class="col-sm-3 col-form-label text-sm-right">Имя пользователя:</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" id="user" name="user" placeholder="root">
						</div>
					</div>
					<div class="pb-2 mt-4 mb-2">
						<h4>Пароль</h4>
					</div>
					<div class="form-group row">
						<label for="password" class="col-sm-3 col-form-label text-sm-right">Пароль:</label>
						<div class="col-sm-4">
							<input type="password" class="form-control" id="password" name="password" placeholder="Пароль">
						</div>
					</div>
					<div class="form-group row">
						<label for="password2" class="col-sm-3 col-form-label text-sm-right">Повторите пароль:</label>
						<div class="col-sm-4">
							<input type="password" class="form-control" id="password2" name="password2" placeholder="Повторите пароль">
						</div>
					</div>
					<div class="pb-2 mt-4 mb-2">
						<h4>Дополнительная информация</h4>
					</div>
					<div class="form-group row">
						<label for="status" class="col-sm-3 col-form-label text-sm-right">Статус:</label>
						<div class="col-sm-3">
							<select class="form-control" id="status" name="status">
								<option value="0">Выключена</option>
								<option value="1">Включена</option>
							</select>
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
						url: '/admin/locations/create/ajax',
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
									setTimeout("redirect('/admin/locations')", 1500);
									break;
							}
						},
						beforeSubmit: function(arr, $form, options) {
							$('button[type=submit]').prop('disabled', true);
						}
					});
				</script>
<?php echo $footer ?>
