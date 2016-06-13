<ion-view title="Review Request">
    <ion-content overflow-scroll="true" padding="true" scroll="false" class="has-header">

			
			<form class="list validate" ng-submit="submit(action, for_user_id, request_id, content)">
          <input type="hidden" name="action" ng-model="action" value="reviewuser_request">
				  <input type="hidden" name="for_user_id" ng-model="for_user_id" value="{{params.created_by_user_id}}">
					<input type="hidden" name="request_id" ng-model="request_id" value="{{params.id}}">
            <ion-list>
                <label class="item item-input">
                    <span class="input-label">Content</span>
                    <input type="text"  name="content" ng-model="content" placeholder="">
                </label>
            </ion-list>
            <button id="register-button3" class="button button-royal  button-block">Review</button>
        </form>
		      
    </ion-content>
</ion-view>