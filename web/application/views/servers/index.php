<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Мои сервера</h2>
				</div>
				<table class="table">
					<thead>
						<tr>
							<th>ID</th>
							<th>Статус</th>
							<th>Игра</th>
							<th>Локация</th>
							<th>IP</th>
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
							<td colspan="5" class="text-center">На данный момент у вас нет серверов.</td>
						<tr>
						<?php endif; ?> 
					</tbody>
				</table>
				<center><a href="/servers/order" class="btn btn-light"><i class="icon ion-md-add"></i> Заказать сервер</a></center>
				<?php echo $pagination ?> 
<?php echo $footer ?>
