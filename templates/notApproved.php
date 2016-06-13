<ion-view title="Not Approved">
    <ion-content overflow-scroll="true" padding="true" class="has-header">
<?php include '../lib/init.php'; ?>
<?php include '../lib/message.php'; ?>
        <div>
            <p>Your account is waiting approval from admin, please check back later.</p>
        </div>
            <div class="spacer" style="height: 40px;"></div>
            <a ng-click="logout()" class="button button-royal  button-block">Logout</a>

    </ion-content>
</ion-view>