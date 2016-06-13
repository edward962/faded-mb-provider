angular.module('app.routes', [])

.config(function($stateProvider, $urlRouterProvider) {

  // Ionic uses AngularUI Router which uses the concept of states
  // Learn more here: https://github.com/angular-ui/ui-router
  // Set up the various states which the app can be in.
  // Each state's controller can be found in controllers.js
  $stateProvider



  .state('splash', {
    url: '/splash.php',
    templateUrl: 'templates/splash.php',
    controller: 'splashCtrl'
  })

  .state('register', {
    url: '/register.php',
    templateUrl: 'templates/register.php',
    controller: 'registerCtrl'
  })

  .state('login', {
    url: '/login.php',
    templateUrl: 'templates/login.php',
    controller: 'loginCtrl'
  })

  .state('forgotPassword', {
    url: '/forgot-password.php',
    templateUrl: 'templates/forgotPassword.php',
    controller: 'forgotPasswordCtrl'
  })
  
  .state('notApproved', {
    url: '/not-approved.php',
    templateUrl: 'templates/notApproved.php',
    controller: 'notApprovedCtrl'
  })
  
    .state('menu', {
    url: '/side-menu',
    templateUrl: 'templates/menu.php',
    abstract:true
  })

  .state('menu.accountSettings', {
    url: '/account-settings.php',
    views: {
      'side-menu': {
        templateUrl: 'templates/accountSettings.php',
        controller: 'accountSettingsCtrl'
      }
    }
  })

  .state('menu.passwordSettings', {
    url: '/password-settings.php',
    views: {
      'side-menu': {
        templateUrl: 'templates/passwordSettings.php',
        controller: 'passwordSettingsCtrl'
      }
    }
  })
  
  
 
  
 
  .state('menu.blogs', {
    url: '/blogs.php/:id/:title/:content',
    views: {
      'side-menu': {
        templateUrl: 'templates/blogs.php',
        controller: 'blogsCtrl'
      }
    }
  })
  
   .state('menu.blogsView', {
    url: '/blogsView.php/:id/:title/:content',
    views: {
      'side-menu': {
        templateUrl: 'templates/blogsView.php',
        controller: 'blogsViewCtrl'
      }
    }
  })
  
  .state('menu.pages', {
    url: '/pages.php',
    views: {
      'side-menu': {
        templateUrl: 'templates/pages.php',
        controller: 'pagesCtrl'
      }
    }
  })
  
   .state('menu.pagesView', {
    url: '/pagesView.php/:id/:title/:content',
    views: {
      'side-menu': {
        templateUrl: 'templates/pagesView.php',
        controller: 'pagesViewCtrl'
      }
    }
  })
  
  .state('menu.users', {
    url: '/users.php',
    views: {
      'side-menu': {
        templateUrl: 'templates/users.php',
        controller: 'usersCtrl'
      }
    }
  })
  
   .state('menu.usersView', {
    url: '/usersView.php/:id/:username/:email/:profile_picture/:first_name/:last_name',
    views: {
      'side-menu': {
        templateUrl: 'templates/usersView.php',
        controller: 'usersViewCtrl'
      }
    }
  })
  
   .state('menu.usersMessage', {
    url: '/usersMessage.php/:id/:username/:email/:profile_picture',
    views: {
      'side-menu': {
        templateUrl: 'templates/usersMessage.php',
        controller: 'usersMessageCtrl'
      }
    }
  })
  
    .state('menu.usersReviews', {
    url: '/usersReviews.php/:id/:username/:email/:profile_picture',
    views: {
      'side-menu': {
        templateUrl: 'templates/usersReviews.php',
        controller: 'usersReviewsCtrl'
      }
    }
  })
  
 
  

  
  .state('menu.requests', {
    url: '/requests.php',
    views: {
      'side-menu': {
        templateUrl: 'templates/requests.php',
        controller: 'requestsCtrl'
      }
    }
  })
  
  .state('menu.requestsView', {
    url: '/requestsView.php/:id/:start/:end/:created_by_user_id/:provided_to_user_id/:accepted/:paid/:price/:reviewed_by_client/:reviewed_by_provider',
    views: {
      'side-menu': {
        templateUrl: 'templates/requestsView.php',
        controller: 'requestsViewCtrl'
      }
    }
  })
  
  .state('menu.requestsMessage', {
    url: '/requestsMessage.php/:id/:start/:end/:created_by_user_id/:provided_to_user_id/:accepted/:paid/:price/:reviewed_by_client/:reviewed_by_provider',
    views: {
      'side-menu': {
        templateUrl: 'templates/requestsMessage.php',
        controller: 'requestsMessageCtrl'
      }
    }
  })
  
  .state('menu.requestsReview', {
    url: '/requestsReview.php/:id/:start/:end/:created_by_user_id/:provided_to_user_id/:accepted/:paid/:price/:reviewed_by_client/:reviewed_by_provider',
    views: {
      'side-menu': {
        templateUrl: 'templates/requestsReview.php',
        controller: 'requestsReviewCtrl'
      }
    }
  })

  
  .state('menu.messages', {
    url: '/messages.php',
    views: {
      'side-menu': {
        templateUrl: 'templates/messages.php',
        controller: 'messagesCtrl'
      }
    }
  })
  
  
   .state('menu.messagesView', {
    url: '/messagesView.php/:id/:content/:type/:datetime/:to_user_id/:from_user_id/:to/:from',
    views: {
      'side-menu': {
        templateUrl: 'templates/messagesView.php',
        controller: 'messagesViewCtrl'
      }
    }
  })

    
  .state('menu.messagesReply', {
    url: '/messagesReply.php/:id/:content/:type/:datetime/:to_user_id/:from_user_id/:to/:from',
    views: {
      'side-menu': {
        templateUrl: 'templates/messagesReply.php',
        controller: 'messagesReplyCtrl'
      }
    }
  })
  
   .state('menu.reviews', {
    url: '/reviews.php',
    views: {
      'side-menu': {
        templateUrl: 'templates/reviews.php',
        controller: 'reviewsCtrl'
      }
    }
  })
  
  
   .state('menu.reviewsView', {
    url: '/reviewsView.php/:id/:datetime/:content/:by',
    views: {
      'side-menu': {
        templateUrl: 'templates/reviewsView.php',
        controller: 'reviewsViewCtrl'
      }
    }
  })

  .state('menu.calendar', {
    url: '/calendar.php',
    views: {
      'side-menu': {
        templateUrl: 'templates/calendar.php',
        controller: 'calendarCtrl'
      }
    }
  })
  
    .state('menu.social', {
    url: '/social.php',
    views: {
      'side-menu': {
        templateUrl: 'templates/social.php',
        controller: 'socialCtrl'
      }
    }
  })


$urlRouterProvider.otherwise('/splash.php')

  

});