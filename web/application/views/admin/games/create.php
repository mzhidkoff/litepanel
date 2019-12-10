<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Создание игры</h2>
				</div>
				<form action="#" id="createForm" method="POST">
					<div class="pb-2 mt-4 mb-2">
						<h4>Основная информация</h4>
					</div>
					<div class="form-group row">
						<label for="name" class="col-sm-3 col-form-label text-sm-right">Название:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="name" name="name" placeholder="Введите название">
						</div>
					</div>
					<div class="form-group row">
						<label for="code" class="col-sm-3 col-form-label text-sm-right">Код:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="code" name="code" placeholder="Код игры">
						</div>
					</div>
					<div class="form-group row">
						<label for="query" class="col-sm-3 col-form-label text-sm-right">Query-драйвер:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="query" name="query" placeholder="Драйвер игры">
						</div>
					</div>
					<div class="form-group row">
						<label for="image" class="col-sm-3 col-form-label text-sm-right">URL картинки:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="image" name="image" placeholder="URL картинки игры">
						</div>
					</div>
					<div class="pb-2 mt-4 mb-2">
						<h4>Дополнительная информация</h4>
					</div>
					<div class="form-group row">
						<label for="minslots" class="col-sm-3 col-form-label text-sm-right">Слоты:</label>
						<div class="col-sm-5">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">от</span></div>
								<input type="text" class="form-control" id="minslots" name="minslots">
								<div class="input-group-prepend"><span class="input-group-text">до</span></div>
								<input type="text" class="form-control" id="maxslots" name="maxslots">
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="minport" class="col-sm-3 col-form-label text-sm-right">Порты:</label>
						<div class="col-sm-5">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">от</span></div>
								<input type="text" class="form-control" id="minport" name="minport">
								<div class="input-group-prepend"><span class="input-group-text">до</span></div>
								<input type="text" class="form-control" id="maxport" name="maxport">
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="price" class="col-sm-3 col-form-label text-sm-right">Стоимость:</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="text" class="form-control" id="price" name="price">
								<div class="input-group-append">
									<span class="input-group-text">руб.</span>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="status" class="col-sm-3 col-form-label text-sm-right">Статус:</label>
						<div class="col-sm-4">
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
						url: '/admin/games/create/ajax',
						dataType: 'json',
						success: function(data) {
							switch(data.status) {
								case 'error':
									showError(data.error);
									$('button[type=submit]').prop('disabled', false);
									break;
								case 'success':
									showSuccess(data.success);
									setTimeout("redirect('/admin/games')", 1500);
									break;
							}
						},
						beforeSubmit: function(arr, $form, options) {
							$('button[type=submit]').prop('disabled', true);
						}
					});
				</script>
<?php echo $footer ?>
