<ion-view title="Reply Message">
    <ion-content overflow-scroll="true" padding="true" scroll="false" class="has-header">

			
			<form class="list validate" ng-submit="submit(action, to_user_id, content)">
          <input type="hidden" name="action" ng-model="action" value="reply_message">
					<input type="hidden" name="to_user_id" ng-model="to_user_id" value="{{params.from_user_id}}">
            <ion-list>
                <label class="item item-input">
                    <span class="input-label">Message</span>
                    <input type="text"  name="content" ng-model="content" placeholder="">
                </label>
            </ion-list>
            <button id="register-button3" class="button button-royal  button-block">Reply</button>
        </form>
		      
    </ion-content>
</ion-view>