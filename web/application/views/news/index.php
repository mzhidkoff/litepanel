<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Новости сайта</h2>
				</div>
				<?php foreach($news as $item): ?> 
				<div class="card mb-3">
				  <!--<img src="..." class="card-img-top" alt="...">-->
				  <div class="card-body">
					<h5 class="card-title">Новость #<?php echo $item['news_id'] ?> (<?php echo $item['news_title'] ?>)</h5>
					<p class="card-text"><?php echo $item['news_text'] ?></p>
					<p class="card-text"><small class="text-muted"><i class="icon ion-md-calendar"></i> <?php echo date("d.m.Y в H:i", strtotime($item['news_date_add'])) ?></small></p>
				  </div>
				</div>
				<?php endforeach; ?> 
				<?php echo $pagination ?> 
<?php echo $footer ?>
