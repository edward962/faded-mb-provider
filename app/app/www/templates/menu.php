<ion-side-menus enable-menu-with-back-views="false">
    <ion-side-menu-content>
        <ion-nav-bar class="bar-stable">
            <ion-nav-back-button></ion-nav-back-button>
            <ion-nav-buttons side="left">
                <button class="button button-icon button-clear ion-navicon" menu-toggle="left"></button>
            </ion-nav-buttons>
        </ion-nav-bar>
        <ion-nav-view name="side-menu"></ion-nav-view>
    </ion-side-menu-content>
    <ion-side-menu side="left">
        <ion-header-bar class="bar-stable">
            <div class="title"> <img src="http://fadedbarbershop.co.uk/assets/images/logo_header_mobile.png" height="35" width="120" style="margin-top:5px;"/></div>
        </ion-header-bar>
        <ion-content padding="false" class="side-menu-left has-header">
            <ion-list>
                <ion-item ui-sref="menu.requests" menu-close="">Requests</ion-item>
                <ion-item ui-sref="menu.accountSettings" menu-close="">Account settings</ion-item>
                <ion-item ui-sref="menu.passwordSettings" menu-close="">Password settings</ion-item>
                <ion-item ui-sref="menu.messages" menu-close="">Messages</ion-item>
                <ion-item ui-sref="menu.reviews" menu-close="">Reviews</ion-item>
                <!--<ion-item ui-sref="menu.availability" menu-close="">Availability</ion-item>-->
                <ion-item ui-sref="menu.calendar" menu-close="">Appointments</ion-item>
                <ion-item ui-sref="menu.users" menu-close="">Clients</ion-item>
                <ion-item ui-sref="menu.blogs" menu-close="">Seminars</ion-item>
                <ion-item ui-sref="menu.social" menu-close="">Social</ion-item>
                <!--<ion-item ui-sref="menu.pages" menu-close="">Pages</ion-item>-->
                <ion-item ng-click="logout()">Logout</ion-item>
            </ion-list>
        </ion-content>
    </ion-side-menu>
</ion-side-menus>
