<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Пополнение баланса</h2>
				</div>
				<form class="pb-4" action="#" id="payForm" method="POST">
					<div class="form-group row">
						<div class="offset-sm-3 col-sm-9"><span class="text-dark pr-2">Оплата с помощью</span> <img src="http://777md.com/blog/uploads/2019/09/7f55a8755anitpay.png" height="32"></div>
					</div>
					<div class="form-group row">
						<label for="ammount" class="col-sm-3 col-form-label text-sm-right">Сумма:</label>
						<div class="col-sm-5">
							<div class="input-group">
							  <input type="text" class="form-control" id="ammount" name="ammount" placeholder="Сумма" value="100">
							  <div class="input-group-append">
								<span class="input-group-text">руб.</span>
							  </div>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<div class="offset-sm-3 col-sm-9">
							<button type="submit" class="btn btn-primary">Продолжить</button>
						</div>
					</div>
				</form>
				<script>
					$('#payForm').ajaxForm({ 
						url: '/account/pay/ajax',
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
									redirect(data.url);
									break;
							}
						},
						beforeSubmit: function(arr, $form, options) {
							$('button[type=submit]').prop('disabled', true);
						}
					});
				</script>
<?php echo $footer ?>
