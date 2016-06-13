<ion-view title="View Request">
    <ion-content overflow-scroll="true" padding="true" scroll="false" class="has-header">
<?php include '../lib/init.php'; ?>
<?php include '../lib/message.php'; ?>
			<?php $user_data = process_api_get($base_url,'/user_data'); ?>
			<?php $_SESSION['userid']= $user_data->id; ?>
			<?php if (!$_SESSION['userid']) { echo '<meta http-equiv="refresh" content="0; url= ' . $device_url . '" />'; } ?>
			
	<div class="card">
  <div class="item item-divider">
  </div>
  <div class="item item-text-wrap">
		Start: {{params.start}}</br>
		End: {{params.end}}</br>
		Client: {{params.created_by_user_id}}</br>
	  Accepted:  {{params.accepted}}</br>
		Reviewed: {{params.reviewed_by_provider}}</br>

  </div>
  <div class="item item-divider">
		<div class="button-bar">
				<span ng-if="params.accepted === 'no'">
				<a  ng-click="requestAccept({{params.id}})" 
	class="button button-royal">Accept</a>
				</span>
  		<a  ui-sref="menu.requestsMessage({id: '{{params.id}}', start: '{{params.start}}', end: '{{params.end}}', created_by_user_id: '{{params.created_by_user_id}}' ,provided_to_user_id: '{{params.provided_to_user_id}}', accepted: '{{params.accepted}}', paid: '{{params.paid}}', price: '{{params.price}}', reviewed_by_client: '{{params.reviewed_by_client}}',reviewed_by_provider: '{{params.reviewed_by_provider}}'})" 
	class="button button-royal">Message</a>
			<span ng-if="params.reviewed_by_provider === 'no'">
				<a  ui-sref="menu.requestsReview({id: '{{params.id}}', start: '{{params.start}}', end: '{{params.end}}', created_by_user_id: '{{params.created_by_user_id}}' ,provided_to_user_id: '{{params.provided_to_user_id}}', accepted: '{{params.accepted}}', paid: '{{params.paid}}', price: '{{params.price}}', reviewed_by_client: '{{params.reviewed_by_client}}',reviewed_by_provider: '{{params.reviewed_by_provider}}'})" 
	class="button button-royal">Review</a>
			</span>
		</div>
  </div>
	</div>

      
    </ion-content>
</ion-view>