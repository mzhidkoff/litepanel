<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Все локации</h2>
				</div>
				<table class="table">
					<thead>
						<tr>
							<th>ID</th>
							<th>Статус</th>
							<th>Название</th>
							<th>IP</th>
							<th>Пользователь</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($locations as $item): ?> 
						<tr class="table-<?php if($item['location_status'] == 0){echo 'danger';} elseif($item['location_status'] == 1){echo 'success';} ?>" onClick="redirect('/admin/locations/edit/index/<?php echo $item['location_id'] ?>')">
							<td>#<?php echo $item['location_id'] ?></td>
							<td>
								<?php if($item['location_status'] == 0): ?> 
								<span class="badge badge-danger">Выключена</span>
								<?php elseif($item['location_status'] == 1): ?> 
								<span class="badge badge-success">Включена</span>
								<?php endif; ?> 
							</td>
							<td><?php echo $item['location_name'] ?></td>
							<td><?php echo $item['location_ip'] ?></td>
							<td><?php echo $item['location_user'] ?></td>
						</tr>
						<?php endforeach; ?> 
						<?php if(empty($locations)): ?> 
						<tr style="background-color: rgba(0,0,0,.05)">
							<td colspan="5" class="text-center">На данный момент нет локаций.</td>
						<tr>
						<?php endif; ?> 
						<tr>
							<td colspan="5" class="text-center"><a href="/admin/locations/create" class="btn btn-light"><i class="icon ion-md-add"></i> Создать локацию</a></td>
						<tr>
					</tbody>
				</table>
				<?php echo $pagination ?> 
<?php echo $footer ?>
