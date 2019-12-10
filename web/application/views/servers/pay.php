<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Продление сервера</h2>
				</div>
				<form class="pb-3" action="#" id="payForm" method="POST">
					<div class="pb-2 mt-4 mb-2">
						<h4>Основная информация</h4>
					</div>
					<div class="form-group row">
						<label for="months" class="col-sm-3 col-form-label">Период оплаты:</label>
						<div class="col-sm-3">
							<select class="form-control" id="months" onChange="updatePrice()">
								<option value="1">1 месяц</option>
								<option value="3">3 месяца (-5%)</option>
								<option value="6">6 месяцев (-10%)</option>
								<option value="12">12 месяцев (-15%)</option>
							</select>
						</div>
					</div>
					<div class="pb-1 mt-2 mb-1">
						<h4>Стоимость</h4>
					</div>
					<div class="form-group row">
						<label for="price" class="col-sm-3 col-form-label">Итого:</label>
						<div class="col-sm-5">
							<p class="lead" id="price">0.00 руб.</p>
						</div>
					</div>
					<div class="form-group row">
						<div class="offset-sm-3 col-sm-9">
							<button type="submit" class="btn btn-primary">Продлить</button>
						</div>
					</div>
				</form>
				<script>
					$('#payForm').ajaxForm({ 
						url: '/servers/pay/ajax/<?php echo $server['server_id'] ?>',
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
									setTimeout("redirect('/servers/control/index/<?php echo $server['server_id'] ?>')", 1500);
									break;
							}
						},
						beforeSubmit: function(arr, $form, options) {
							$('button[type=submit]').prop('disabled', true);
						}
					});
					
					$(document).ready(function() {
						updatePrice();
					});
					
					function updatePrice() {
						var price = <?php echo $server['game_price'] ?> * <?php echo $server['server_slots'] ?>;
						var months = $("#months option:selected").val();
						switch(months) {
							case "3":
								price = 3 * price * 0.95;
								break;
							case "6":
								price = 6 * price * 0.90;
								break;
							case "12":
								price = 12 * price * 0.85;
								break;
						}
						$('#price').text(price.toFixed(2) + ' руб.');
					}
				</script>
<?php echo $footer ?>
