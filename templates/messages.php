<ion-view title="Messages">
    <ion-content overflow-scroll="true" padding="true" scroll="true" class="has-header">

	<div ng-if="messages != null">
		
		
	<div class="list card" ng-repeat="message in messages">
  <a ui-sref="menu.messagesView({id: message.id , to_user_id: message.to_user_id,  to: message.to_user_id, from_user_id: message.from_user_id, from: message.from_user_id, type: message.type, datetime: message.datetime, content: message.content })" 
	class="item item-icon-left">
<div ng-if="message.to_user_id == session_user_id">
	RECEIVED
		</div>
		<div ng-if="message.from_user_id == session_user_id">
	SENT
		</div>
  </a>

	</div>
		
	</div>

<div ng-if="messages == null" style="text-align:center;">
    No messages found
</div>

	
	
      
    </ion-content>
</ion-view>