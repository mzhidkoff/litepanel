<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Редактирование профиля</h2>
				</div>
				<form action="#" id="editForm" method="POST">
					<div class="pb-2 mt-4 mb-2">
						<h4>Основная информация</h4>
					</div>
					<div class="form-group row">
						<label for="firstname" class="col-sm-3 col-form-label text-sm-right">Имя:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="firstname" name="firstname" placeholder="Введите свое имя" value="<?php echo $user['firstname'] ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="lastname" class="col-sm-3 col-form-label text-sm-right">Фамилия:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="lastname" name="lastname" placeholder="Введите свою фамилию" value="<?php echo $user['lastname'] ?>">
						</div>
					</div>
					<div class="pb-2 mt-4 mb-2">
						<h4>Пароль</h4>
					</div>
					<div class="form-group row">
						<div class="offset-sm-3 col-sm-9">
							<div class="checkbox">
								<label><input type="checkbox" id="editpassword" name="editpassword" onChange="togglePassword()"> Изменить пароль</label>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="password" class="col-sm-3 col-form-label text-sm-right">Пароль:</label>
						<div class="col-sm-5">
							<input type="password" class="form-control" id="password" name="password" placeholder="Пароль" disabled>
						</div>
					</div>
					<div class="form-group row">
						<label for="password2" class="col-sm-3 col-form-label text-sm-right">Повторите пароль:</label>
						<div class="col-sm-5">
							<input type="password" class="form-control" id="password2" name="password2" placeholder="Повторите пароль" disabled>
						</div>
					</div>
					<div class="form-group row">
						<div class="offset-sm-3 col-sm-9">
							<button type="submit" class="btn btn-primary">Изменить</button>
						</div>
					</div>
				</form>
				<script>
					$(window).on('load', function () {
						if(location.hash == '#new-password') {
							$('#editpassword').prop('checked', true); togglePassword();
							$('#password').focus();
						}
				    });
					$('#editForm').ajaxForm({ 
						url: '/account/edit/ajax',
						dataType: 'text',
						success: function(data) {
							console.log(data);
							data = $.parseJSON(data);
							switch(data.status) {
								case 'error':
									showError(data.error);
									break;
								case 'success':
									showSuccess(data.success);
									break;
							}
							$('button[type=submit]').prop('disabled', false);
						},
						beforeSubmit: function(arr, $form, options) {
							$('button[type=submit]').prop('disabled', true);
						}
					});
					function togglePassword() {
						var status = $('#editpassword').is(':checked');
						if(status) {
							$('#password').prop('disabled', false);
							$('#password2').prop('disabled', false);
						} else {
							$('#password').prop('disabled', true);
							$('#password2').prop('disabled', true);
						}
					}
				</script>
<?php echo $footer ?>
