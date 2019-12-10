<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Редактирование игры</h2>
				</div>
				<div class="btn-group">
					<a href="/admin/servers/index?gameid=<?php echo $game['game_id'] ?>" class="btn btn-secondary"><i class="icon ion-md-cloud"></i> Сервера игры</a>
					<a href="/admin/games/edit/delete/<?php echo $game['game_id'] ?>" class="btn btn-danger"><i class="icon ion-md-trash"></i> Удалить игру</a>
				</div>
				<form action="#" id="editForm" method="POST">
					<div class="pb-2 mt-4 mb-2">
						<h4>Основная информация</h4>
					</div>
					<div class="form-group row">
						<label for="name" class="col-sm-3 col-form-label text-sm-right">Название:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="name" name="name" placeholder="Введите название" value="<?php echo $game['game_name'] ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="code" class="col-sm-3 col-form-label text-sm-right">Код:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="code" name="code" placeholder="Введите код" value="<?php echo $game['game_code'] ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="query" class="col-sm-3 col-form-label text-sm-right">Query-драйвер:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="query" name="query" value="<?php echo $game['game_query'] ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="image" class="col-sm-3 col-form-label text-sm-right">URL картинки:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="image" name="image" value="<?php echo $game['image_url'] ?>">
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
								<input type="text" class="form-control" id="minslots" name="minslots" value="<?php echo $game['game_min_slots'] ?>">
								<div class="input-group-prepend"><span class="input-group-text">до</span></div>
								<input type="text" class="form-control" id="maxslots" name="maxslots" value="<?php echo $game['game_max_slots'] ?>">
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="minport" class="col-sm-3 col-form-label text-sm-right">Порты:</label>
						<div class="col-sm-5">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">от</span></div>
								<input type="text" class="form-control" id="minport" name="minport" value="<?php echo $game['game_min_port'] ?>">
								<div class="input-group-prepend"><span class="input-group-text">до</span></div>
								<input type="text" class="form-control" id="maxport" name="maxport" value="<?php echo $game['game_max_port'] ?>">
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="price" class="col-sm-3 col-form-label text-sm-right">Стоимость:</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="text" class="form-control" id="price" name="price" value="<?php echo $game['game_price'] ?>">
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
								<option value="0"<?php if($game['game_status'] == 0): ?> selected="selected"<?php endif; ?>>Выключена</option>
								<option value="1"<?php if($game['game_status'] == 1): ?> selected="selected"<?php endif; ?>>Включена</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<div class="offset-sm-3 col-sm-9">
							<button type="submit" class="btn btn-primary">Изменить</button>
						</div>
					</div>
				</form>
				<script>
					$('#editForm').ajaxForm({ 
						url: '/admin/games/edit/ajax/<?php echo $game['game_id'] ?>',
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
									break;
							}
						},
						beforeSubmit: function(arr, $form, options) {
							$('button[type=submit]').prop('disabled', true);
						}
					});
				</script>
<?php echo $footer ?>
