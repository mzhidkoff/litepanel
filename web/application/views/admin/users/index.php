<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Все пользователи</h2>
				</div>
				<table class="table">
					<thead>
						<tr>
							<th>ID</th>
							<th>Статус</th>
							<th>Имя</th>
							<th>Фамилия</th>
							<th>E-Mail</th>
							<th>Дата регистрации</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($users as $item): ?> 
						<tr class="table-<?php if($item['user_status'] == 0){echo 'danger';} elseif($item['user_status'] == 1){echo 'success';} elseif($item['user_status'] == 2){echo 'warning';}?>" onClick="redirect('/admin/users/edit/index/<?php echo $item['user_id'] ?>')">
							<td>#<?php echo $item['user_id'] ?></td>
							<td>
							<?php if($item['user_status'] == 0): ?> 
								<span class="badge badge-danger">Заблокирован</span>
							<?php elseif($item['user_status'] == 1): ?> 
								<span class="badge badge-success">Активен</span>
							<?php endif; ?> 
							</td>
							<td><?php echo $item['user_firstname'] ?></td>
							<td><?php echo $item['user_lastname'] ?></td>
							<td><?php echo $item['user_email'] ?></td>
							<td><?php echo date("d.m.Y", strtotime($item['user_date_reg'])) ?></td>
						</tr>
						<?php endforeach; ?> 
						<?php if(empty($users)): ?> 
						<tr style="background-color: rgba(0,0,0,.05)">
							<td colspan="6" style="text-align: center;">На данный момент нет пользователей.</td>
						<tr>
						<?php endif; ?> 
					</tbody>
				</table>
				<?php echo $pagination ?> 
<?php echo $footer ?>
