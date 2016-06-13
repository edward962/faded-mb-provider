<ion-view title="Forgot Password">
    <ion-content overflow-scroll="true" padding="true" class="has-header">
        <div>
            <p>Please enter your email and we will send you a new password right away.</p>
        </div>
        <form class="list"  ng-submit="submit(action, email)" class="validate">
        <input type="hidden" name="action" ng-model="action" value="forgot_password">
            <ion-list>
                <label class="item item-input">
                    <span class="input-label">Email</span>
                    <input type="email" name="email" ng-model="email" placeholder="">
                </label>
            </ion-list>
            <div class="spacer" style="height: 40px;"></div>
            <button id="forgotPassword-button7" class="button button-royal  button-block">Send password</button>
        </form>
    </ion-content>
</ion-view>