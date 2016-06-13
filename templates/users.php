<ion-view title="Clients">
    <ion-content overflow-scroll="true" padding="true" scroll="false" class="has-header">


	

<div ng-if="users != null">
	
		
<div class="list" ng-repeat="user in users">
<div ng-if="user.type == 'client'">
    <a class="item item-avatar" ui-sref="menu.usersView({id: user.id, username: user.username, email: user.email, profile_picture: user.profile_picture, first_name: user.first_name, last_name: user.last_name})">
      <img src="{{user.profile_picture}}">
      <h2>{{user.username}}</h2>
      <p>Client</p>
    </a>
	</div>
</div>
		</div>


			
	<div ng-if="users == null">
    No clients found
	</div>

	

      
    </ion-content>
</ion-view>