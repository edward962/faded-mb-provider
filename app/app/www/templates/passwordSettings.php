<ion-view title="Password Settings">
    <ion-content overflow-scroll="true" padding="true" class="has-header">

        <form class="list" ng-submit="submit(action, old_password, new_password, confirm_new_password)" class="validate form-horizontal">
        <input type="hidden" name="action" ng-model="action" value="save_password">
            <ion-list>
                <label class="item item-input">
                    <span class="input-label">Old Password</span>
                    <input type="password" name="old_password" ng-model="old_password" placeholder="">
                </label>
                <label class="item item-input">
                    <span class="input-label">New Password</span>
                    <input type="password" name="new_password" ng-model="new_password" placeholder="">
                </label>
                <label class="item item-input">
                    <span class="input-label">Confirm Password</span>
                    <input type="password" name="confirm_new_password" ng-model="confirm_new_password" placeholder="">
                </label>
            </ion-list>
            <div class="spacer" style="height: 40px;"></div>
            <button id="passwordSettings-button10" class="button button-royal  button-block">Save</button>
        </form>
    </ion-content>
</ion-view>