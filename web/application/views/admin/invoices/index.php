<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Все счета</h2>
				</div>
				<table class="table">
					<thead>
						<tr>
							<th>ID</th>
							<th>Статус</th>
							<th>Пользователь</th>
							<th>Сумма</th>
							<th>Дата создания</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($invoices as $item): ?> 
						<tr class="table-<?php if($item['invoice_status'] == 0){echo 'warning';} elseif($item['user_status'] == 1){echo 'success';} ?>" >
							<td>#<?php echo $item['invoice_id'] ?></td>
							<td>
								<?php if($item['invoice_status'] == 0): ?> 
								<span class="badge badge-warning">Не оплачен</span>
								<?php elseif($item['invoice_status'] == 1): ?> 
								<span class="badge badge-success">Оплачен</span>
								<?php endif; ?> 
							</td>
							<td><?php echo $item['user_firstname'] ?> <?php echo $item['user_lastname'] ?></td>
							<td><?php echo $item['invoice_ammount'] ?> руб.</td>
							<td><?php echo date("d.m.Y в H:i", strtotime($item['invoice_date_add'])) ?></td>
						</tr>
						<?php endforeach; ?> 
						<?php if(empty($invoices)): ?> 
						<tr style="background-color: rgba(0,0,0,.05)">
							<td colspan="6" style="text-align: center;">На данный момент нет счетов.</td>
						<tr>
						<?php endif; ?> 
					</tbody>
				</table>
				<?php echo $pagination ?> 
<?php echo $footer ?>
