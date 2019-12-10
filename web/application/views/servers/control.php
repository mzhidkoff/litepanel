<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Управление сервером</h2>
				</div>
				<div class="card mb-4">
					<div class="card-header">Информация о сервере</div>
					<div class="card-body">
						<table class="table mb-0">
							<tr>
								<th width="200px" rowspan="20" style="border-top: 0">
									<div align="center">
										<img src="<?php echo $server['image_url'] ?>" style="width:160px; margin-bottom:5px;">
									</div>
									<?php if($server['server_status'] == 1): ?> 
									<button style="width: 100%;margin-bottom: 5px;" type="button" class="btn btn-success" onClick="sendAction(<?php echo $server['server_id'] ?>,'start')"><span class="glyphicon glyphicon-off"></span> Включить</button>
									<button style="width: 100%;margin-bottom: 5px;" type="button" class="btn btn-warning" onClick="sendAction(<?php echo $server['server_id'] ?>,'reinstall')"><span class="glyphicon glyphicon-refresh"></span> Переустановить</button>
									<?php elseif($server['server_status'] == 2): ?> 
									<button style="width: 100%;margin-bottom: 5px;" type="button" class="btn btn-danger" onClick="sendAction(<?php echo $server['server_id'] ?>,'stop')"><span class="glyphicon glyphicon-off"></span> Выключить</button>
									<button style="width: 100%;margin-bottom: 5px;" type="button" class="btn btn-info" onClick="sendAction(<?php echo $server['server_id'] ?>,'restart')"><span class="glyphicon glyphicon-refresh"></span> Перезапустить</button>
									<?php endif; ?>
								</th>
								<th style="border-top: 0">Игра:</th>
								<td style="border-top: 0"><?php echo $server['game_name'] ?></td>
							</tr>
							<?php if($query): ?> 
							<tr>
								<th>Название:</th>
								<td><?php echo $query['hostname'] ?></td>
							</tr>
							<tr>
								<th>Игроки:</th>
								<td><?php echo $query['players'] ?> из <?php echo $query['maxplayers'] ?></td>
							</tr>
							<tr>
								<th>Игровой режим:</th>
								<td><?php echo $query['gamemode'] ?></td>
							</tr>
							<tr>
								<th>Карта:</th>
								<td><?php echo $query['mapname'] ?></td>
							</tr>
							<?php elseif(!$query): ?> 
							<tr>
								<th>Название:</th>
								<td><span class="badge badge-info">Нет данных</span></td>
							</tr>
							<tr>
								<th>Игроки:</th>
								<td><span class="badge badge-info">Нет данных</span></td>
							</tr>
							<tr>
								<th>Игровой режим:</th>
								<td><span class="badge badge-info">Нет данных</span></td>
							</tr>
							<tr>
								<th>Карта:</th>
								<td><span class="badge badge-info">Нет данных</span></td>
							</tr>
							<?php endif; ?>
							<tr>
								<th>Локация:</th>
								<td><?php echo $server['location_name'] ?></td>
							</tr>
							<tr>
								<th>Адрес:</th>
								<td><?php echo $server['location_ip'] ?>:<?php echo $server['server_port'] ?></td>
							</tr>
							<tr>
								<th>Слоты:</th>
								<td><?php echo $server['server_slots'] ?></td>
							</tr>
							<tr>
								<th>Дата окончания оплаты:</th>
								<td><?php echo date("d.m.Y", strtotime($server['server_date_end'])) ?> <a href="/servers/pay/index/<?php echo $server['server_id'] ?>" class="badge badge-light">Продлить</a></td>
							</tr>
							<tr>
								<th>Статус:</th>
								<td>
									<?php if($server['server_status'] == 0): ?> 
									<span class="badge badge-warning">Заблокирован</span>
									<?php elseif($server['server_status'] == 1): ?> 
									<span class="badge badge-danger">Выключен</span>
									<?php elseif($server['server_status'] == 2): ?> 
									<span class="badge badge-success">Включен</span>
									<?php elseif($server['server_status'] == 3): ?> 
									<span class="badge badge-warning">Установка</span>
									<?php endif; ?> 
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="card mb-4">
					<div class="card-header">Статистика сервера</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-7">
								<div id="statsGraph" style="height: 220px; width:666px;"></div><div style="width:666px;"><hr></div>
								<b>CPU нагрузка</b>
								<div style="width:666px" class="progress progress-striped active">
									<div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo (($server['server_status'] != 2) ? '0' : $server['server_cpu_load']) ?>%" <span=""></div>
								</div>
								<span class="badge badge-info"><?php if($server['server_status'] != 2){echo 'Нет данных';}else{echo $server['server_cpu_load']."%";}?></span><div style="width:666px;"><hr></div>
								<b>RAM нагрузка</b>
								<div style="width:666px" class="progress progress-striped active">
									<div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo (($server['server_status'] != 2) ? '0' : $server['server_ram_load']) ?>%" <span=""></div>
								</div>
								<span class="badge badge-info"><?php if($server['server_status'] != 2){echo 'Нет данных';}else{echo $server['server_ram_load']."%";}?></span><div style="width:666px;"><hr></div>
								<b>Загруженность сервера</b>
								<div style="width:666px" class="progress progress-striped active">
								<?php $percent=$query['players']*100/$query['maxplayers'];?>
									<div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percent ?>%" <span=""></div>
								</div>
								<span class="badge badge-info"><?php if($server['server_status'] != 2){echo 'Нет данных';}else{echo $percent."%";}?></span>
							</div>
						</div>
					</div>
				</div>
				<div class="card mb-4">
					<div class="card-header">Доступ</div>
					<div class="card-body">
						<table class="table table-borderless mb-0">
							<tr>
								<th>Хост:</th>
								<td><?php echo $server['location_ip'] ?></td>
							</tr>
							<tr>
								<th>Логин:</th>
								<td>gs<?php echo $server['server_id'] ?></td>
							</tr>
							<tr>
								<th>Пароль:</th>
								<td><?php echo $server['server_password'] ?></td>
							</tr>
							<tr>
								<th>База Данных:</th>
								<td><?php if($server['server_database'] == 1){echo 'Активна (<a href="/phpmyadmin/index.php?pma_username=gs'.$server['server_id'].'&pma_password='.$server['server_password'].'&db=gs'.$server['server_id'].'" target="_blank">перейти</a>)';}else{echo 'Неактивна';} ?></td>
							</tr>
							<tr>
								<th>Скачать FTP Клиент:</th>
								<td><a href="https://filezilla.ru/get/" target="_blank">Скачать</a></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="card mb-4">
					<div class="card-header">Просмотр консоли</div>
					<textarea  rows="20" id="console_log" class="w-100 border-0">Загрузка...</textarea>
				</div>
				<script>
				setInterval(function() { 
					$.ajax({
					  type: 'POST',
					  url: '/servers/control/console/<?php echo $server['server_id'] ?>',
					  dataType: 'text',
					  success: function(data) {
						data = $.parseJSON(data);
						switch(data.status) {
							case 'error':
								showError(data.error);
								break;
							case 'success':
								$('#console_log').html(data.text);
								var textarea = document.getElementById('console_log');
								textarea.scrollTop = textarea.scrollHeight;
								break;
						}
					  }
					});
				}, 2000);//time in milliseconds 
				</script>
				<div class="pb-2 mt-4 mb-2">
					<h3>Редактирование</h3>
				</div>
				<form action="#" id="editForm" method="POST">
					<div class="form-group row">
						<div class="offset-sm-3 col-sm-9">
							<div class="checkbox">
								<label><input type="checkbox" id="editpassword" name="editpassword" onChange="togglePassword()"> Изменить пароль</label>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="password" class="col-sm-3 col-form-label">Пароль:</label>
						<div class="col-sm-4">
							<input type="password" class="form-control" id="password" name="password" placeholder="Введите пароль" disabled>
						</div>
					</div>
					<div class="form-group row">
						<label for="password2" class="col-sm-3 col-form-label">Повтор пароля:</label>
						<div class="col-sm-4">
							<input type="password" class="form-control" id="password2" name="password2" placeholder="Повторите пароль" disabled>
						</div>
					</div>
					<div class="form-group row">
						<div class="offset-sm-3 col-sm-9">
							<button type="submit" class="btn btn-primary">Сохранить</button>
						</div>
					</div>
				</form>
				<script>
					var serverStats = [
						<?php foreach($stats as $item): ?> 
						[<?php echo strtotime("$item[server_stats_date] GMT+0") * 1000 ?>, <?php echo $item['server_stats_players'] ?>],
						<?php endforeach; ?> 
					];
					$.plot($("#statsGraph"), [serverStats], {
						xaxis: {
							mode: "time",
							timeformat: "%H:%M"
						},
						yaxis: {
							min: 0,
							max: <?php echo $server['server_slots'] ?>
						},
						series: {
							lines: {
								show: true,
								fill: true
							},
							points: {
								show: true
							}
						},
						grid: {
							borderWidth: 0,
							hoverable: true,
							clickable: true
						},
						colors: [ "#428BCA" ]
					});
					
					$("#statsGraph").bind("plothover", function (event, pos, item) {
						if (item) {
							if (previousPoint != item.dataIndex) {

								previousPoint = item.dataIndex;

								$("#tooltip").remove();
								var x = item.datapoint[0], y = item.datapoint[1];

								showTooltip(item.pageX, item.pageY, "Игроков " + y);
							}
						} else {
							$("#tooltip").remove();
							previousPoint = null;            
						}
					});
					
					function showTooltip(x, y, contents) {
						$("<div id='tooltip'>" + contents + "</div>").css({
							position: "absolute",
							display: "none",
							top: y - 45,
							left: x + 5,
							border: "1px solid #fdd",
							padding: "2px",
							"background-color": "#fee",
							opacity: 0.80
						}).appendTo("body").fadeIn(200);
					}
					
					$('#editForm').ajaxForm({ 
						url: '/servers/control/ajax/<?php echo $server['server_id'] ?>',
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
					
					function sendAction(serverid, action) {
						switch(action) {
							case "reinstall":
							{
								if(!confirm("Вы уверенны в том, что хотите переустановить сервер? Все данные будут удалены.")) return;
								break;
							}
						}
						$.ajax({ 
							url: '/servers/control/action/'+serverid+'/'+action,
							dataType: 'text',
							success: function(data) {
								console.log(data);
								data = $.parseJSON(data);
								switch(data.status) {
									case 'error':
										showError(data.error);
										$('#controlBtns button').prop('disabled', false);
										break;
									case 'success':
										showSuccess(data.success);
										setTimeout("reload()", 1500);
										break;
								}
							},
							beforeSend: function(arr, options) {
								if(action == "reinstall") showWarning("Сервер будет переустановлен в течении 10 минут!");
								$('#controlBtns button').prop('disabled', true);
							}
						});
					}
					
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
