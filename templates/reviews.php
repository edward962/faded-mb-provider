<ion-view title="Reviews">
    <ion-content overflow-scroll="true" padding="true" scroll="false" class="has-header">

			
	<div ng-if="reviews != null">
		
			
		<div class="list card" ng-repeat="review in reviews">
  <a ui-sref="menu.reviewsView({id: review.id, datetime: review.datetime, content: review.content, by: review.by_user_id})" class="item item-icon-left">
    By {{review.by_user_id}}
  </a>

	</div>
</div>

	<div ng-if="reviews == null" style="text-align:center;">
    No reviews found
			</div>
      
    </ion-content>
</ion-view>