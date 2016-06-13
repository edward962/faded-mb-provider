<ion-view title="View user">
    <ion-content overflow-scroll="true" padding="true" scroll="false" class="has-header">
<?php include '../lib/init.php'; ?>
<?php include '../lib/message.php'; ?>
			<?php $user_data = process_api_get($base_url,'/user_data'); ?>
			<?php $_SESSION['userid']= $user_data->id; ?>
			<?php if (!$_SESSION['userid']) { echo '<meta http-equiv="refresh" content="0; url= ' . $device_url . '" />'; } ?>
			
<div class="card">
  <div class="item item-text-wrap">
      <img src="{{params.profile_picture}}">
      <h2>{{params.username}}</h2>
      <p>Client</p>
			<h2>First name: {{params.first_name}}</h2>
			<h2>Last name: {{params.last_name}}</h2>
  </div>
	<div class="button-bar">
  <a ui-sref="menu.usersMessage({id: '{{params.id}}', username: '{{params.username}}', email: '{{params.email}}', profile_picture: '{{params.profile_picture}}' })" class="button button-royal">Message</a>
  <!--<a ui-sref="menu.usersReviews({id: '{{params.id}}', username: '{{params.username}}', email: '{{params.email}}', profile_picture: '{{params.profile_picture}}' })" class="button button-royal">Reviews</a>-->
</div>
</div>

      
    </ion-content>
</ion-view>