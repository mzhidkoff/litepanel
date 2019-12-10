<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Добро пожаловать, <?php echo $user_firstname ?>!</h2>
				</div>

    			<div class="card border-0 mb-4">
					<div class="card-header border-0">Информация о вашем аккаунте</div>
					<table class="table table-bordered">
						<tr>
							<th>Фамилия:</th>
							<td><?php echo $user_lastname ?></td>
						</tr>
						<tr>
							<th>Имя:</th>
							<td><?php echo $user_firstname ?></td>
						</tr>
						<tr>
							<th>E-Mail:</th>
							<td><?php echo $user_email ?></td>
						</tr>
						<tr>
							<th>Баланс:</th>
							<td><?php echo $user_balance ?> рублей</td>
						</tr>
					</table>
				</div>
				<div class="pb-2 mt-4 mb-2">
					<h4>Ваши сервера</h4>
				</div>
				<table class="table">
					<thead>
						<tr>
							<th>ID</th>
							<th>Статус</th>
							<th>Игра</th>
							<th>Локация</th>
							<th>Адрес</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($servers as $item): ?> 
						<tr class="table-<?php if($item['server_status'] == 0){echo 'warning';}elseif($item['server_status'] == 1){echo 'danger';}elseif($item['server_status'] == 2){echo 'success';}elseif($item['server_status'] == 3){echo 'warning';}?>" onClick="redirect('/servers/control/index/<?php echo $item['server_id'] ?>')">
							<td>№<?php echo $item['server_id'] ?></td>
							<td>
							<?php if($item['server_status'] == 0): ?> 
								<span class="badge badge-warning">Заблокирован</span>
							<?php elseif($item['server_status'] == 1): ?> 
								<span class="badge badge-danger">Выключен</span>
							<?php elseif($item['server_status'] == 2): ?> 
								<span class="badge badge-success">Включен</span>
							<?php elseif($item['server_status'] == 3): ?> 
								<span class="badge badge-warning">Установка</span>
							<?php endif; ?> 
							</td>
							<td><?php echo $item['game_name'] ?></td>
							<td><?php echo $item['location_name'] ?></td>
							<td><?php echo $item['location_ip'] ?>:<?php echo $item['server_port'] ?></td>
						</tr>
						<?php endforeach; ?> 
						<?php if(empty($servers)): ?> 
						<tr style="background-color: rgba(0,0,0,.05)">
							<td colspan="5" style="text-align: center;">На данный момент у вас нет серверов.</td>
						<tr>
						<?php endif; ?> 
					</tbody>
				</table>
				<div class="pb-2 mt-4 mb-2">
					<h4>Ваши запросы</h4>
				</div>
				<table class="table">
					<thead>
						<tr>
							<th>ID</th>
							<th>Статус</th>
							<th>Тема</th>
							<th>Дата создания</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($tickets as $item): ?> 
						<tr class="table-<?if($item['ticket_status'] == 0){echo 'primary';}elseif($item['ticket_status'] == 1){echo 'warning';}elseif($item['ticket_status'] == 2){echo 'success';}?>" onClick="redirect('/tickets/view/index/<?php echo $item['ticket_id'] ?>')">
							<td>№<?php echo $item['ticket_id'] ?></td>
							<td>
								<?php if($item['ticket_status'] == 0): ?> 
								<span class="badge badge-primary">Закрыт</span>
								<?php elseif($item['ticket_status'] == 1): ?> 
								<span class="badge badge-warning">Ответ от клиента</span>
								<?php elseif($item['ticket_status'] == 2): ?> 
								<span class="badge badge-success">Ответ от поддержки</span>
								<?php endif; ?>  
							</td>
							<td><?php echo $item['ticket_name'] ?></td>
							<td><?php echo date("d.m.Y в H:i", strtotime($item['ticket_date_add'])) ?></td>
						</tr>
						<?php endforeach; ?> 
						<?php if(empty($tickets)): ?> 
						<tr style="background-color: rgba(0,0,0,.05)">
							<td colspan="4" style="text-align: center;">На данный момент у вас нет запросов.</td>
						<tr>
						<?php endif; ?> 
					</tbody>
				</table>
<?php echo $footer ?>
