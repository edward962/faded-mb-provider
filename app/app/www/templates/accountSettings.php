<ion-view title="Account settings">
    <ion-content overflow-scroll="true" padding="true" scroll="true" class="has-header">
        <form class="list"  ng-submit="submit(action, username, email, profile_picture, first_name, last_name, phone_number, qualification, teaching_experience, specialities)" class="validate form-horizontal" enctype="multipart/form-data">
			  <input type="hidden" name="action" ng-model="action" >

            <ion-list>
                <label class="item item-input">
                    <span class="input-label">Username</span>
                    <input type="text" name="username" ng-model="username" placeholder="" >
                </label>
                <label class="item item-input">
                    <span class="input-label">Email</span>
                    <input type="email" name="email" ng-model="email" placeholder="" >
                </label>
                <label class="item item-input">
                    <span class="input-label"></span>
                    <img src="{{user_data.profile_picture}}" width="100" height="100" alt="Circle Image">
                </label>
                <label class="item item-input">
                    <span class="input-label">Profile picture</span>
                    <input type="file" name="profile_picture" ng-model="profile_picture" placeholder="" >
                </label>
								<label class="item item-input">
                    <span class="input-label">First name</span>
                    <input type="text" name="first_name" ng-model="first_name" placeholder="" >
                </label>
								<label class="item item-input">
                    <span class="input-label">Last name</span>
                    <input type="text" name="last_name" ng-model="last_name" placeholder="" >
                </label>
								<label class="item item-input">
                    <span class="input-label">Phone</span>
                    <input type="text" name="phone_number" ng-model="phone_number" placeholder="" >
                </label>
								<label class="item item-input">
                    <span class="input-label">Qualification</span>
                    <input type="text" name="qualification" ng-model="qualification" placeholder="" >
                </label>
								<label class="item item-input">
                    <span class="input-label">Experience</span>
                    <input type="text" name="teaching_experience" ng-model="teaching_experience" placeholder="" >
                </label>

								<label class="item item-input">
                    <span class="input-label">Specialities</span>
                    <input type="text" name="specialities" ng-model="specialities" placeholder="" >
                </label>
            <button id="accountSettings-button9" class="button button-royal  button-block">Save</button>
        </form>
    </ion-content>
</ion-view>