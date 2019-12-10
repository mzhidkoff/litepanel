<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Все запросы</h2>
				</div>
				<table class="table">
					<thead>
						<tr>
							<th>ID</th>
							<th>Статус</th>
							<th>Тема</th>
							<th>Пользователь</th>
							<th>Дата создания</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($tickets as $item): ?> 
						<tr class="table-<?php if($item['ticket_status'] == 0){echo 'primary';} elseif($item['ticket_status'] == 1){echo 'warning';} elseif($item['ticket_status'] == 2){echo 'success';}?>" onClick="redirect('/admin/tickets/view/index/<?php echo $item['ticket_id'] ?>')">
							<td>#<?php echo $item['ticket_id'] ?></td>
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
							<td><?php echo $item['user_firstname'] ?> <?php echo $item['user_lastname'] ?></td>
							<td><?php echo date("d.m.Y в H:i", strtotime($item['ticket_date_add'])) ?></td>
						</tr>
						<?php endforeach; ?> 
						<?php if(empty($tickets)): ?> 
						<tr style="background-color: rgba(0,0,0,.05)">
							<td colspan="5" style="text-align: center;">На данный момент нет запросов в службу поддержки.</td>
						<tr>
						<?php endif; ?> 
					</tbody>
				</table>
				<?php echo $pagination ?> 
<?php echo $footer ?>
