<ion-view title="Pages">
    <ion-content overflow-scroll="true" padding="true" scroll="false" class="has-header">



			
<div class="list card" ng-repeat="page in pages">

  <a ui-sref="menu.pagesView({id: page.id , title: page.title, content: page.content })" 
	class="item item-icon-left">
 {{page.title}}
  </a>

	</div>
			
		<div ng-if="pages.length == 0">
    No pages found
		</div>
			
			


    </ion-content>
</ion-view>