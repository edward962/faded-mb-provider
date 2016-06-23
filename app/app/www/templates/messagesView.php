<ion-view title="View Message">
    <ion-content overflow-scroll="true" padding="true" scroll="false" class="has-header">

			
	<div class="card">
  <div class="item item-divider">
    <div ng-if="params.to_user_id == my_user_id">RECEIVED</div><div ng-if="params.to_user_id != my_user_id">SENT</div> | {{params.datetime}}
  </div>
  <div class="item item-text-wrap">
		{{params.content}}</br>
		From: <div ng-if="params.from_user_id == my_user_id">ME</div><div ng-if="params.to_user_id != my_user_id">{{params.from}}</div> | To:  <div ng-if="params.to_user_id == my_user_id">Me</div><div ng-if="params.to_user_id != my_user_id">{{param.to_user_id}}</div>
  </div>
  <div class="item item-divider">
		<div ng-if="params.to_user_id == session_user_id">
		<div class="button-bar">
  		<a  ui-sref="menu.messagesReply({id: '{{params.id}}',to_user_id: '{{params.to_user_id}}',from_user_id: '{{params.from_user_id}}', to: '{{params.to}}', from: '{{params.from}}', type: '{{params.type}}', datetime: '{{params.datetime}}', content: '{{params.content}}' })" 
	class="button button-royal">Reply</a>
		</div>
  </div>
			</div>
	</div>

      
    </ion-content>
</ion-view>