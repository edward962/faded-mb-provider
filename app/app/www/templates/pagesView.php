<ion-view title="View Page">
    <ion-content overflow-scroll="true" padding="true" scroll="false" class="has-header">
<?php include '../lib/init.php'; ?>
<?php include '../lib/message.php'; ?>
			<?php $user_data = process_api_get($base_url,'/user_data'); ?>
			<?php $_SESSION['userid']= $user_data->id; ?>
			<?php if (!$_SESSION['userid']) { echo '<meta http-equiv="refresh" content="0; url= ' . $device_url . '" />'; } ?>

			
	<div class="card">
  <div class="item item-divider">
    {{params.title}}
  </div>
  <div class="item item-text-wrap">
    {{params.content}}
  </div>
  <div class="item item-divider"> 
  </div>
	</div>

      
    </ion-content>
</ion-view>