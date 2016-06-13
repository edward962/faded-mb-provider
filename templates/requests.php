<ion-view title="Requests">
    <ion-content overflow-scroll="true" padding="true" scroll="true" class="has-header">


<div ng-if="requests != null">
	
<div ng-repeat="request in requests">
	
	<div ng-if="request.accepted == 'no'">
	<div class="list card">
  <a ui-sref="menu.requestsView({id: request.id, start: request.start, end: request.end, created_by_user_id: request.created_by_user_id ,provided_to_user_id: request.provided_to_user_id, accepted: request.accepted, paid: request.paid, price: request.price, reviewed_by_client: request.reviewed_by_client ,reviewed_by_provider: request.reviewed_by_provider})" 
	class="item item-icon-left">
  Start : {{request.start}} | End:  {{request.end}}
  </a>
		
	</div>
	
		</div>
	</div>
	</div>
		
		<div ng-if="requests == null">
    No requests found
		</div>

	
	
      
    </ion-content>
</ion-view>