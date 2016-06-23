<ion-view title="Message user">
    <ion-content overflow-scroll="true" padding="true" scroll="false" class="has-header">

			
			
			 <form class="list validate" ng-submit="submit(action, to_user_id, content)" method="post">
         <input type="hidden" name="action" ng-model="action" value="message_user">
				  <input type="hidden" name="to_user_id" ng-model="to_user_id" value="{{params.id}}">
            <ion-list>
                <label class="item item-input">
                    <span class="input-label">Content</span>
                    <input type="text"  name="content" ng-model="content" placeholder="">
                </label>
            </ion-list>
            <button id="register-button3" class="button button-royal  button-block">Send</button>
        </form>

      
    </ion-content>
</ion-view>