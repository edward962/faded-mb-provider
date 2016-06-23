<?php include '../lib/message.php'; ?>
<ion-view title="Register">
    <ion-content overflow-scroll="true" padding="true" scroll="false" class="has-header">
<?php include '../lib/init.php'; ?>
        <form class="list validate" ng-submit="submit(action, username, email, phone_number, password, confirm_password)">
          <input type="hidden" name="action" ng-model="action" value="register">
            <ion-list>
                <label class="item item-input">
                    <span class="input-label">Username</span>
                    <input type="text"  name="username" ng-model="username"  placeholder="">
                </label>
                <label class="item item-input">
                    <span class="input-label">Email</span>
                    <input type="email" name="email" ng-model="email" placeholder="">
                </label>
                <label class="item item-input">
                    <span class="input-label">Phone</span>
                    <input type="text" name="phone_number" ng-model="phone_number" placeholder="">
                </label>
                <label class="item item-input">
                    <span class="input-label">Password</span>
                    <input type="password" name="password" ng-model="password" placeholder="">
                </label>
                <label class="item item-input">
                    <span class="input-label">Confirm Password</span>
                    <input type="password" name="confirm_password" ng-model="confirm_password" placeholder="">
                
            </ion-list>
            <button id="register-button3" class="button button-royal  button-block">Register</button>
        </form>
    </ion-content>
</ion-view>