<ion-view title="Seminars">
    <ion-content overflow-scroll="true" padding="true" scroll="false" class="has-header">
<div ng-if="blogs != null">
	

	<div class="list card" ng-repeat="blog in blogs">
    <a ui-sref="menu.blogsView({id: blog.id, title: blog.title, content:blog.content })" class="item item-icon-left">
      {{blog.title}}
    </a>
</div>
	
</div>
<div ng-if="blogs == null">
    No seminars found
			</div>

    </ion-content>
</ion-view>