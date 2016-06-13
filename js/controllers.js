angular.module('app.controllers', [])

      
.controller('splashCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
 
 //popup alert starts here
   $scope.showAlert = function(status,message) {
   var alertPopup = $ionicPopup.alert({
     title: status,
     template: message,
   });
 };
 //popup alert ends here

})
   
.controller('registerCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
  //popup alert starts here
   $scope.showAlert = function(status,message) {
   var alertPopup = $ionicPopup.alert({
     title: status,
     template: message,
   });
 };
 //popup alert ends here
  
  
       function showAlert(status, message)
  {
  $ionicPopup.alert({
     title: status,
     template: message,
   });
    
  }
  
  
  $scope.action = null; 
  $scope.username = null; 
  $scope.email = null; 
  $scope.phone_number = null; 
  $scope.password = null; 
  $scope.confirm_password = null; 
  $scope.type = null; 

  
  $scope.submit = function(action, username, email, phone_number, password, confirm_password, type) {
    
    //fields left empty
    //if(!username || !password) {
     //   alert('empty usr or pw');
     //   return;
   // }
    

    var input = {
       username: username,
       email: email,
       phone_number: phone_number,
       password: password,
       confirm_password: confirm_password,
       type: 'provider'

    };
    

     
    var url = 'http://fadedbarbershop.co.uk/rest-all.php/register';

    $http.post(url, input).then(function(response) 
        {
          //success
          var status = response.data.status + '!';
          status = status.trim();
          var message = response.data.message;
          message = message.trim();
          console.log(status);
          if(status.trim() == 'success!')
            {
              $state.go('login');
              showAlert(status,message);
              
            }
         if(status == 'danger')
           {
             showAlert(status,message);
           }
          else
           {
             showAlert(status,message);
           } 
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });
}


})
   
.controller('loginCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
  function showAlert(status, message)
  {
  $ionicPopup.alert({
     title: status,
     template: message,
   });
    
  }
  
  
  $scope.action = null; 
  $scope.username = null; 
  $scope.password = null; 

  
  $scope.submit = function(action, username, password) {
    
    //fields left empty
    //if(!username || !password) {
     //   alert('empty usr or pw');
     //   return;
   // }
    

    var input = {
        username: username,
        password: password

    };
    

     
    var url = 'http://fadedbarbershop.co.uk/rest-all.php/login';

    $http.post(url, input).then(function(response) 
        {
          //success
          var status = response.data.status + '!';
          status = status.trim();
          var message = response.data.message;
          message = message.trim();
          console.log(status);
          if(status.trim() == 'success!')
            {
              $state.go('menu.requests');
              showAlert(status,message);
              
            }
         if(status == 'danger')
           {
             showAlert(status,message);
           }
          else
           {
             showAlert(status,message);
           } 
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });
}
  

})
   
.controller('forgotPasswordCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
  function showAlert(status, message)
  {
  $ionicPopup.alert({
     title: status,
     template: message,
   });
    
  }
  
  
  $scope.action = null; 
  $scope.email = null; 

  
  $scope.submit = function(action, email) {
    
    //fields left empty
    //if(!username || !password) {
     //   alert('empty usr or pw');
     //   return;
   // }
    

    var input = {
        email: email,

    };
    

     
    var url = 'http://fadedbarbershop.co.uk/rest-all.php/forgot_password';

    $http.post(url, input).then(function(response) 
        {
          //success
          var status = response.data.status + '!';
          status = status.trim();
          var message = response.data.message;
          message = message.trim();
          console.log(status);
          if(status.trim() == 'success!')
            {
              $state.go('login');
              showAlert(status,message);
              
            }
         if(status == 'danger!')
           {
             showAlert(status,message);
           }
          else
           {
             showAlert(status,message);
           } 
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });
}
  


})

.controller('notApprovedCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
  //popup alert starts here
   $scope.showAlert = function(status,message) {
   var alertPopup = $ionicPopup.alert({
     title: status,
     template: message,
   });
 };
 //popup alert ends here
  
  
   $scope.logout = function() {
     var input = {
       
    };
        
        var url = 'http://fadedbarbershop.co.uk/rest-all.php/logout';

    $http.post(url, input).then(function(response) 
        {
          //success
          var status = response.data.status + '!';
          status = status.trim();
          var message = response.data.message;
          message = message.trim();
          console.log(status);
          if(status.trim() == 'success!')
            {
              $state.go('splash');
              showAlert(status,message);
              
            }
         if(status == 'danger!')
           {
             showAlert(status,message);
           }
          else
           {
             showAlert(status,message);
           } 
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });
}

})

.controller('menuCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
  //popup alert starts here
   $scope.showAlert = function(status,message) {
   var alertPopup = $ionicPopup.alert({
     title: status,
     template: message,
   });
 };
 //popup alert ends here
  
  $scope.logout = function() {
     var input = {
       
    };
        
        var url = 'http://fadedbarbershop.co.uk/rest-all.php/logout';

    $http.post(url, input).then(function(response) 
        {
          //success
          var status = response.data.status + '!';
          status = status.trim();
          var message = response.data.message;
          message = message.trim();
          console.log(status);
          if(status.trim() == 'success!')
            {
              $state.go('splash');
              showAlert(status,message);
              
            }
         if(status == 'danger!')
           {
             showAlert(status,message);
           }
          else
           {
             showAlert(status,message);
           } 
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });
}
   

})
   
.controller('accountSettingsCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
  //popup alert starts here
 function showAlert(status, message)
  {
  $ionicPopup.alert({
     title: status,
     template: message,
   });
    
  }
  
  //redirect to splash screen if not logged innerHeight
  var url_userdata = 'http://fadedbarbershop.co.uk/rest-all.php/provider/user_data';

    $http.get(url_userdata).then(function(response) 
        {
          var session_user_id = response.data.id
          var approved = response.data.approved
          
          if(angular.isUndefined(session_user_id))
            {
    
              $state.go('splash');
              
            }
         if(approved.trim() == 'no')
           {
              $state.go('notApproved');
           }
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

  
  //show data
  $scope.user_data = null;
  
  var url = 'http://fadedbarbershop.co.uk/rest-all.php/provider/user_data';

    $http.get(url).then(function(response) 
        {
          $scope.user_data = response.data;
          $scope.action = response.data.action;
          $scope.username = response.data.username;  
          $scope.email = response.data.email; 
          $scope.profile_picture = response.data.profile_picture; 
          $scope.first_name = response.data.first_name; 
          $scope.last_name = response.data.last_name; 
          $scope.phone_number = $scope.user_data.phone_number;
          $scope.qualification = response.data.qualification; 
          $scope.teaching_experience = $scope.user_data.teaching_experience; 
          $scope.specialities = $scope.user_data.specialities; 
  
          
       if (response.data.status && response.data.message)
         {
            var status = response.data.status + '!'; 
            var message = response.data.message;
         
             showAlert(status,message);

   
          }
          
      
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });



  
  
  $scope.submit = function(action, username, email, profile_picture, first_name, last_name, phone_number, qualification, teaching_experience, specialities) {
    
    //fields left empty
    //if(!username || !password) {
     //   alert('empty usr or pw');
     //   return;
   // }
    

    var input = {
        username: username,
        email: email,
        profile_picture: profile_picture,
        first_name: first_name,
        last_name: last_name,
        phone_number: phone_number,
        qualification: qualification,
        teaching_experience: teaching_experience,
        specialities: specialities
    };
    

     
    var url = 'http://fadedbarbershop.co.uk/rest-all.php/provider/save_account_settings';

    $http.put(url, input).then(function(response) 
        {
          //success
          var status = response.data.status + '!';
          status = status.trim();
          var message = response.data.message;
          message = message.trim();
          console.log(status);
          if(status.trim() == 'success!')
            {
              $state.go('menu.accountSettings');
              showAlert(status,message);
              
            }
         if(status == 'danger')
           {
             showAlert(status,message);
           }
          else
           {
             showAlert(status,message);
           } 
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });
}
  
  

})
   
.controller('passwordSettingsCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
   //redirect to splash screen if not logged innerHeight
  var url_userdata = 'http://fadedbarbershop.co.uk/rest-all.php/provider/user_data';

    $http.get(url_userdata).then(function(response) 
        {
          var session_user_id = response.data.id
          var approved = response.data.approved
          
          if(angular.isUndefined(session_user_id))
            {
    
              $state.go('splash');
              
            }
         if(approved.trim() == 'no')
           {
              $state.go('notApproved');
           }
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

  
  
  //popup alert starts here
   $scope.showAlert = function(status,message) {
   var alertPopup = $ionicPopup.alert({
     title: status,
     template: message,
   });
 };
 //popup alert ends here
  
  
      function showAlert(status, message)
  {
  $ionicPopup.alert({
     title: status,
     template: message,
   });
    
  }
  
  
  $scope.action = null; 
  $scope.old_password = null; 
  $scope.new_password = null; 
  $scope.confirm_new_password = null; 

  
  $scope.submit = function(action, old_password, new_password, confirm_new_password) {
    
    //fields left empty
    //if(!username || !password) {
     //   alert('empty usr or pw');
     //   return;
   // }
    

    var input = {
       old_password: old_password,
       new_password: new_password,
       confirm_new_password: confirm_new_password

    };
    

     
    var url = 'http://fadedbarbershop.co.uk/rest-all.php/provider/save_password';

    $http.put(url, input).then(function(response) 
        {
          //success
          var status = response.data.status + '!';
          status = status.trim();
          var message = response.data.message;
          message = message.trim();
          console.log(status);
          if(status.trim() == 'success!')
            {
              $state.go('menu.passwordSettings');
              showAlert(status,message);
              
            }
         if(status == 'danger')
           {
             showAlert(status,message);
           }
          else
           {
             showAlert(status,message);
           } 
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });
}


})


.controller('blogsCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
     //redirect to splash screen if not logged innerHeight
  var url_userdata = 'http://fadedbarbershop.co.uk/rest-all.php/provider/user_data';

    $http.get(url_userdata).then(function(response) 
        {
          var session_user_id = response.data.id
          var approved = response.data.approved
          
          if(angular.isUndefined(session_user_id))
            {
    
              $state.go('splash');
              
            }
         if(approved.trim() == 'no')
           {
              $state.go('notApproved');
           }
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

  
  
     //popup alert starts here
 function showAlert(status, message)
  {
  $ionicPopup.alert({
     title: status,
     template: message,
   });
    
  }
  
  $scope.blogs = null;
  
  var url = 'http://fadedbarbershop.co.uk/rest-all.php/provider/blogs';

    $http.get(url).then(function(response) 
        {
          $scope.blogs = response.data;
          
       if (response.data.status && response.data.message)
         {
            var status = response.data.status + '!'; 
            var message = response.data.message;
         
             showAlert(status,message);

   
          }
          
      return;
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });



})

.controller('blogsViewCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
  
   //redirect to splash screen if not logged innerHeight
  var url_userdata = 'http://fadedbarbershop.co.uk/rest-all.php/provider/user_data';

    $http.get(url_userdata).then(function(response) 
        {
          var session_user_id = response.data.id
          var approved = response.data.approved
          
          if(angular.isUndefined(session_user_id))
            {
    
              $state.go('splash');
              
            }
         if(approved.trim() == 'no')
           {
              $state.go('notApproved');
           }
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

  
  
  $scope.showAlert = function(status,message) {
   var alertPopup = $ionicPopup.alert({
     title: status,
     template: message
   });
};
  
    var id = $stateParams.id;
    var title = $stateParams.title;
    var content = $stateParams.content;  
    $scope.state = $state.current
    $scope.params = $stateParams;

})

.controller('pagesCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
  
   //redirect to splash screen if not logged innerHeight
  var url_userdata = 'http://fadedbarbershop.co.uk/rest-all.php/provider/user_data';

    $http.get(url_userdata).then(function(response) 
        {
          var session_user_id = response.data.id
          var approved = response.data.approved
          
          if(angular.isUndefined(session_user_id))
            {
    
              $state.go('splash');
              
            }
         if(approved.trim() == 'no')
           {
              $state.go('notApproved');
           }
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

  
  
  //popup alert starts here
   $scope.showAlert = function(status,message) {
   var alertPopup = $ionicPopup.alert({
     title: status,
     template: message,
   });
 };
 //popup alert ends here
  
  $scope.pages = null;
  
  var url = 'http://fadedbarbershop.co.uk/rest-all.php/provider/pages';

    $http.get(url).then(function(response) 
        {
          $scope.pages = response.data;
          
       if (response.data.status && response.data.message)
         {
            var status = response.data.status + '!'; 
            var message = response.data.message;
         
             showAlert(status,message);

   
          }
          
      return;
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

})

.controller('pagesViewCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
   //redirect to splash screen if not logged innerHeight
  var url_userdata = 'http://fadedbarbershop.co.uk/rest-all.php/provider/user_data';

    $http.get(url_userdata).then(function(response) 
        {
          var session_user_id = response.data.id
          var approved = response.data.approved
          
          if(angular.isUndefined(session_user_id))
            {
    
              $state.go('splash');
              
            }
         if(approved.trim() == 'no')
           {
              $state.go('notApproved');
           }
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

  
  
  $scope.showAlert = function(status,message) {
   var alertPopup = $ionicPopup.alert({
     title: status,
     template: message
   });
};
  
    var id = $stateParams.id;
    var title = $stateParams.title;
    var content = $stateParams.content;  
    $scope.state = $state.current
    $scope.params = $stateParams;

})

.controller('usersCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
   function showAlert(status, message)
  {
  $ionicPopup.alert({
     title: status,
     template: message,
   });
    
  }
  
   //redirect to splash screen if not logged innerHeight
  var url_userdata = 'http://fadedbarbershop.co.uk/rest-all.php/provider/user_data';

    $http.get(url_userdata).then(function(response) 
        {
          var session_user_id = response.data.id
          var approved = response.data.approved
          
          if(angular.isUndefined(session_user_id))
            {
    
              $state.go('splash');
              
            }
         if(approved.trim() == 'no')
           {
              $state.go('notApproved');
           }
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

  
  
  //popup alert starts here
   $scope.showAlert = function(status,message) {
   var alertPopup = $ionicPopup.alert({
     title: status,
     template: message,
   });
 };
 //popup alert ends here
  
  //show data
  
   $scope.users = null;
  
  var url = 'http://fadedbarbershop.co.uk/rest-all.php/provider/users';

    $http.get(url).then(function(response) 
        {
          $scope.users = response.data;
          if($scope.users == null)
            {
              return;
            }
          
       if (response.data.status && response.data.message)
         {
            var status = response.data.status + '!'; 
            var message = response.data.message;
         
             showAlert(status,message);

   
          }
          
     
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });



})

.controller('usersViewCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
  
   //redirect to splash screen if not logged innerHeight
  var url_userdata = 'http://fadedbarbershop.co.uk/rest-all.php/provider/user_data';

    $http.get(url_userdata).then(function(response) 
        {
          var session_user_id = response.data.id
          var approved = response.data.approved
          
          if(angular.isUndefined(session_user_id))
            {
    
              $state.go('splash');
              
            }
         if(approved.trim() == 'no')
           {
              $state.go('notApproved');
           }
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

  
  
  $scope.showAlert = function(status,message) {
   var alertPopup = $ionicPopup.alert({
     title: status,
     template: message
   });
};
  
    var id = $stateParams.id;
    var username = $stateParams.username;
    var email = $stateParams.email;
    var profile_picture = $stateParams.profile_picture;
    var first_name = $stateParams.first_name;
    var last_name = $stateParams.last_name;
    $scope.state = $state.current
    $scope.params = $stateParams;

})

.controller('usersMessageCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
   //redirect to splash screen if not logged innerHeight
  var url_userdata = 'http://fadedbarbershop.co.uk/rest-all.php/provider/user_data';

    $http.get(url_userdata).then(function(response) 
        {
          var session_user_id = response.data.id
          var approved = response.data.approved
          
          if(angular.isUndefined(session_user_id))
            {
    
              $state.go('splash');
              
            }
         if(approved.trim() == 'no')
           {
              $state.go('notApproved');
           }
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

  
  
  $scope.showAlert = function(status,message) {
   var alertPopup = $ionicPopup.alert({
     title: status,
     template: message
   });
};
  
    var id = $stateParams.id;
    var username = $stateParams.username; 
    var email = $stateParams.email;
    var profile_picture = $stateParams.profile_picture; 
    $scope.state = $state.current
    $scope.params = $stateParams;
  
     
       function showAlert(status, message)
  {
  $ionicPopup.alert({
     title: status,
     template: message,
   });
    
  }
  
  
  $scope.action = null; 
  $scope.to_user_id = null; 
  $scope.content = null; 

  
  $scope.submit = function(action, to_user_id, content) {
    
    //fields left empty
    //if(!username || !password) {
     //   alert('empty usr or pw');
     //   return;
   // }
    

    var input = {
       to_user_id: to_user_id,
       content: content

    };
    

     
    var url = 'http://fadedbarbershop.co.uk/rest-all.php/users/message';

    $http.post(url, input).then(function(response) 
        {
          //success
          var status = response.data.status + '!';
          status = status.trim();
          var message = response.data.message;
          message = message.trim();
          console.log(status);
          if(status.trim() == 'success!')
            {
              $state.go('menu.messages');
              showAlert(status,message);
              
            }
         if(status == 'danger')
           {
             showAlert(status,message);
           }
          else
           {
             showAlert(status,message);
           } 
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });
}

  

})

.controller('usersReviewsCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
   //redirect to splash screen if not logged innerHeight
  var url_userdata = 'http://fadedbarbershop.co.uk/rest-all.php/provider/user_data';

    $http.get(url_userdata).then(function(response) 
        {
          var session_user_id = response.data.id
          var approved = response.data.approved
          
          if(angular.isUndefined(session_user_id))
            {
    
              $state.go('splash');
              
            }
         if(approved.trim() == 'no')
           {
              $state.go('notApproved');
           }
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

  
  
  $scope.showAlert = function(status,message) {
   var alertPopup = $ionicPopup.alert({
     title: status,
     template: message
   });
};
  
    var id = $stateParams.id;
    var username = $stateParams.username; 
    var email = $stateParams.email;
    var profile_picture = $stateParams.profile_picture; 
    $scope.state = $state.current
    $scope.params = $stateParams;

})



.controller('requestsCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
     function showAlert(status, message)
  {
  $ionicPopup.alert({
     title: status,
     template: message,
   });
    
  }
  
  
   //redirect to splash screen if not logged innerHeight
  var url_userdata = 'http://fadedbarbershop.co.uk/rest-all.php/provider/user_data';

    $http.get(url_userdata).then(function(response) 
        {
          var session_user_id = response.data.id
          var approved = response.data.approved
          
          if(angular.isUndefined(session_user_id))
            {
    
              $state.go('splash');
              
            }
         if(approved.trim() == 'no')
           {
              $state.go('notApproved');
           }
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

  
  
  //popup alert starts here
   $scope.showAlert = function(status,message) {
   var alertPopup = $ionicPopup.alert({
     title: status,
     template: message,
   });
 };
 //popup alert ends here
  
  
  $scope.requests = null;
  
  var url = 'http://fadedbarbershop.co.uk/rest-all.php/provider/requests';

    $http.get(url).then(function(response) 
        {
          $scope.requests = response.data;
          if(!$scope.requests)
            {
              return;
            }
          
       if (response.data.status && response.data.message)
         {
            var status = response.data.status + '!'; 
            var message = response.data.message;
         
             showAlert(status,message);

   
          }
          
      return;
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

})

.controller('requestsViewCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
  
		var get_username_by_id =function (id,retElem)
{

var url_username = 'http://fadedbarbershop.co.uk/rest-all.php/admin/username_by_id/' + id;

    $http.get(url_username).then(function(response) 
        {
  console.log(response);
          
      if (response.data.status && response.data.message)
         {
            var status = response.data.status + '!'; 
            var message = response.data.message;
         
            //return 'username not found';
			 	$scope.params.created_by_user_id='username not found';
   
          }
          else
          {
			  var username=response.data.username;
			  //onsole.info(username);
             $scope.params.created_by_user_id= username;
			 // console.info('hello');
          	
		  }
         
			
      
      
        }).catch(function(response) 
        {
      			 $scope.params.created_by_user_id='username not found';

        // return 'username not found';
        });
	
}
  
     function showAlert(status, message)
  {
  $ionicPopup.alert({
     title: status,
     template: message,
   });
    
  }
  
   //redirect to splash screen if not logged innerHeight
  var url_userdata = 'http://fadedbarbershop.co.uk/rest-all.php/provider/user_data';

    $http.get(url_userdata).then(function(response) 
        {
          var session_user_id = response.data.id
          var approved = response.data.approved
          
          if(angular.isUndefined(session_user_id))
            {
    
              $state.go('splash');
              
            }
         if(approved.trim() == 'no')
           {
              $state.go('notApproved');
           }
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

  
  
  $scope.showAlert = function(status,message) {
   var alertPopup = $ionicPopup.alert({
     title: status,
     template: message
   });
};
  
    var id = $stateParams.id;
    var start = $stateParams.start;
    var end = $stateParams.end;
    var created_by_user_id = $stateParams.created_by_user_id;
    var provided_to_user_id = $stateParams.provided_to_user_id;
    var accepted = $stateParams.accepted;
    var paid = $stateParams.paid;
    var price = $stateParams.price;
    var reviewed_by_client = $stateParams.reviewed_by_client;
    var reviewed_by_provider = $stateParams.reviewed_by_provider;
    $scope.state = $state.current
    $scope.params = $stateParams;
  	$scope.params.created_by_user_id = get_username_by_id($scope.params.created_by_user_id);
  
  //request accept
  $scope.requestAccept = function(request_id) {
     var input = {
       request_id: request_id
    };
        
        var url = 'http://fadedbarbershop.co.uk/rest-all.php/provider/request/accept';

    $http.put(url, input).then(function(response) 
        {
          //success
          var status = response.data.status + '!';
          status = status.trim();
          var message = response.data.message;
          message = message.trim();
          console.log(status);
          if(status.trim() == 'success!')
            {
              $state.go('menu.requests');
              showAlert(status,message);
              
            }
         if(status == 'danger!')
           {
             showAlert(status,message);
           }
          else
           {
             showAlert(status,message);
           } 
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });
}


})

.controller('requestsMessageCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
   
       function showAlert(status, message)
  {
  $ionicPopup.alert({
     title: status,
     template: message,
   });
    
  }
  
   //redirect to splash screen if not logged innerHeight
  var url_userdata = 'http://fadedbarbershop.co.uk/rest-all.php/provider/user_data';

    $http.get(url_userdata).then(function(response) 
        {
          var session_user_id = response.data.id
          var approved = response.data.approved
          
          if(angular.isUndefined(session_user_id))
            {
    
              $state.go('splash');
              
            }
         if(approved.trim() == 'no')
           {
              $state.go('notApproved');
           }
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

  
  
  $scope.showAlert = function(status,message) {
   var alertPopup = $ionicPopup.alert({
     title: status,
     template: message
   });
};
  
    var id = $stateParams.id;
    var start = $stateParams.start;
    var end = $stateParams.end;
    var created_by_user_id = $stateParams.created_by_user_id;
    var provider_to_user_id = $stateParams.provided_to_user_id;
    var accepted = $stateParams.accepted;
    var paid = $stateParams.paid;
    var price = $stateParams.price;
    var reviewed_by_client = $stateParams.reviewed_by_client;
    var reviewed_by_provider = $stateParams.reviewed_by_provider;
    $scope.state = $state.current
    $scope.params = $stateParams;
  
  
   
  
  
  $scope.action = null; 
  $scope.to_user_id = null; 
  $scope.content = null; 

  
  $scope.submit = function(action, to_user_id, content) {
    
    //fields left empty
    //if(!username || !password) {
     //   alert('empty usr or pw');
     //   return;
   // }
    

    var input = {
       to_user_id: created_by_user_id,
       content: content

    };
    

     
    var url = 'http://fadedbarbershop.co.uk/rest-all.php/provider/request/messageuser';

    $http.post(url, input).then(function(response) 
        {
          //success
          var status = response.data.status + '!';
          status = status.trim();
          var message = response.data.message;
          message = message.trim();
          console.log(status);
          if(status.trim() == 'success!')
            {
              $state.go('menu.messages');
              showAlert(status,message);
              
            }
         if(status == 'danger')
           {
             showAlert(status,message);
           }
          else
           {
             showAlert(status,message);
           } 
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });
}

  
  

})

.controller('requestsReviewCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
   //redirect to splash screen if not logged innerHeight
  var url_userdata = 'http://fadedbarbershop.co.uk/rest-all.php/provider/user_data';

    $http.get(url_userdata).then(function(response) 
        {
          var session_user_id = response.data.id
          var approved = response.data.approved
          
          if(angular.isUndefined(session_user_id))
            {
    
              $state.go('splash');
              
            }
         if(approved.trim() == 'no')
           {
              $state.go('notApproved');
           }
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

  
  
  $scope.showAlert = function(status,message) {
   var alertPopup = $ionicPopup.alert({
     title: status,
     template: message
   });
};
  
     var id = $stateParams.id;
    var start = $stateParams.start;
    var end = $stateParams.end;
    var created_by_user_id = $stateParams.created_by_user_id;
    var provider_to_user_id = $stateParams.provided_to_user_id;
    var accepted = $stateParams.accepted;
    var paid = $stateParams.paid;
    var price = $stateParams.price;
    var reviewed_by_client = $stateParams.reviewed_by_client;
    var reviewed_by_provider = $stateParams.reviewed_by_provider;
    $scope.state = $state.current
    $scope.params = $stateParams;
  
  
     
       function showAlert(status, message)
  {
  $ionicPopup.alert({
     title: status,
     template: message,
   });
    
  }
  
  
  $scope.action = null; 
  $scope.for_user_id = null; 
  $scope.request_id = null;
  $scope.content = null;

  
  $scope.submit = function(action, for_user_id, request_id, content) {
    
    //fields left empty
    //if(!username || !password) {
     //   alert('empty usr or pw');
     //   return;
   // }
    

    var input = {
      for_user_id: for_user_id,
      request_id: request_id,
      content: content

    };
    

     
    var url = 'http://fadedbarbershop.co.uk/rest-all.php/request/reviewuser';

    $http.post(url, input).then(function(response) 
        {
          //success
          var status = response.data.status + '!';
          status = status.trim();
          var message = response.data.message;
          message = message.trim();
          console.log(status);
          if(status.trim() == 'success!')
            {
              $state.go('menu.requests');
              showAlert(status,message);
              
            }
         if(status == 'danger')
           {
             showAlert(status,message);
           }
          else
           {
             showAlert(status,message);
           } 
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });
}

  

})




.controller('messagesCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
    //popup alert starts here
 function showAlert(status, message)
  {
  $ionicPopup.alert({
     title: status,
     template: message,
   });
    
  }
  
   //redirect to splash screen if not logged innerHeight
  var url_userdata = 'http://fadedbarbershop.co.uk/rest-all.php/provider/user_data';

    $http.get(url_userdata).then(function(response) 
        {
          var session_user_id = response.data.id
          var approved = response.data.approved
          $scope.session_user_id = session_user_id;
          
          if(angular.isUndefined(session_user_id))
            {
    
              $state.go('splash');
              
            }
         if(approved.trim() == 'no')
           {
              $state.go('notApproved');
           }
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

  
  
  //popup alert starts here
   $scope.showAlert = function(status,message) {
   var alertPopup = $ionicPopup.alert({
     title: status,
     template: message,
   });
 };
 //popup alert ends here
  
  
  //display data
  $scope.messages = null;
 
  
  var url = 'http://fadedbarbershop.co.uk/rest-all.php/provider/messages';

    $http.get(url).then(function(response) 
        {
          $scope.messages = response.data;
          if($scope.messages == null)
            {
              return;
            }
          
       if (response.data.status && response.data.message)
         {
            var status = response.data.status + '!'; 
            var message = response.data.message;
         
             showAlert(status,message);

   
          }
          
      return;
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });



})

.controller('messagesViewCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
	
	
	var get_username_by_id =function (id,retElem)
{

var url_username = 'http://fadedbarbershop.co.uk/rest-all.php/admin/username_by_id/' + id;

    $http.get(url_username).then(function(response) 
        {
  console.log(response);
          
      if (response.data.status && response.data.message)
         {
            var status = response.data.status + '!'; 
            var message = response.data.message;
         
            //return 'username not found';
			 $scope.params.from='username not found';
   
          }
          else
          {
			  var username=response.data.username;
			  //onsole.info(username);
             $scope.params.from= username;
			 // console.info('hello');
          	
		  }
         
			
      
      
        }).catch(function(response) 
        {
      			 $scope.params.from='username not found';

        // return 'username not found';
        });
	
}
  
  
        //popup alert starts here
 function showAlert(status, message)
  {
  $ionicPopup.alert({
     title: status,
     template: message,
   });
    
  }
  
  
   //redirect to splash screen if not logged innerHeight
  var url_userdata = 'http://fadedbarbershop.co.uk/rest-all.php/provider/user_data';

    $http.get(url_userdata).then(function(response) 
        {
          var session_user_id = response.data.id
          var approved = response.data.approved
           $scope.session_user_id = session_user_id;
          
          if(angular.isUndefined(session_user_id))
            {
    
              $state.go('splash');
              
            }
         if(approved.trim() == 'no')
           {
              $state.go('notApproved');
           }
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

  
  
  $scope.showAlert = function(status,message) {
   var alertPopup = $ionicPopup.alert({
     title: status,
     template: message
   });
};
  
    var id = $stateParams.id;
    var content = $stateParams.content;
    var type = $stateParams.type;
    var datetime = $stateParams.datetime;
    var to_user_id = $stateParams.to_user_id;
    var from_user_id = $stateParams.from_user_id;
    var to = $stateParams.to;
    var from = $stateParams.from;
    $scope.state = $state.current
    $scope.params = $stateParams;
    $scope.params.from = get_username_by_id($scope.params.from);

})

.controller('messagesReplyCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
      //popup alert starts here
 function showAlert(status, message)
  {
  $ionicPopup.alert({
     title: status,
     template: message,
   });
    
  }
  
   //redirect to splash screen if not logged innerHeight
  var url_userdata = 'http://fadedbarbershop.co.uk/rest-all.php/provider/user_data';

    $http.get(url_userdata).then(function(response) 
        {
          var session_user_id = response.data.id
          var approved = response.data.approved
          
          if(angular.isUndefined(session_user_id))
            {
    
              $state.go('splash');
              
            }
         if(approved.trim() == 'no')
           {
              $state.go('notApproved');
           }
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

  
  
  $scope.showAlert = function(status,message) {
   var alertPopup = $ionicPopup.alert({
     title: status,
     template: message
   });
};
  
    var id = $stateParams.id;
    var content = $stateParams.content;
    var type = $stateParams.type;
    var datetime = $stateParams.datetime;
    var to_user_id = $stateParams.to_user_id;
    var from_user_id = $stateParams.from_user_id;
    var to = $stateParams.to;
    var from = $stateParams.from;
    $scope.state = $state.current
    $scope.params = $stateParams;
  
  
  
  
  
  $scope.action = null; 
  $scope.to_user_id = from_user_id; 
  $scope.content = null; 
  
  $scope.submit = function(action, to_user_id, content ) {
    
    //fields left empty
    //if(!username || !password) {
     //   alert('empty usr or pw');
     //   return;
   // }
    

    var input = {
        to_user_id: to_user_id,
        content: content
    };
    

     
    var url = 'http://fadedbarbershop.co.uk/rest-all.php/provider/message/reply';

    $http.post(url, input).then(function(response) 
        {
          //success
          var status = response.data.status + '!';
          status = status.trim();
          var message = response.data.message;
          message = message.trim();
          console.log(status);
          if(status.trim() == 'success!')
            {
              $state.go('menu.messages');
              showAlert(status,message);
              
            }
         if(status == 'danger')
           {
             showAlert(status,message);
           }
          else
           {
             showAlert(status,message);
           } 
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });
}

})

.controller('reviewsCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
   function showAlert(status, message)
  {
  $ionicPopup.alert({
     title: status,
     template: message,
   });
    
  }
  
   //redirect to splash screen if not logged innerHeight
  var url_userdata = 'http://fadedbarbershop.co.uk/rest-all.php/provider/user_data';

    $http.get(url_userdata).then(function(response) 
        {
          var session_user_id = response.data.id
          var approved = response.data.approved
          
          if(angular.isUndefined(session_user_id))
            {
    
              $state.go('splash');
              
            }
         if(approved.trim() == 'no')
           {
              $state.go('notApproved');
           }
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

  
  
 //popup alert ends here
  
  
  //show data
  
  
  var url = 'http://fadedbarbershop.co.uk/rest-all.php/provider/reviews';

    $http.get(url).then(function(response) 
        {
          $scope.reviews = response.data;
          if($scope.reviews == null)
            {
              return;
            }
          
       if (response.data.status && response.data.message)
         {
            var status = response.data.status + '!'; 
            var message = response.data.message;
         
             showAlert(status,message);

   
          }
          
      return;
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });



})


.controller('reviewsViewCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  	var get_username_by_id =function (id,retElem)
{

var url_username = 'http://fadedbarbershop.co.uk/rest-all.php/admin/username_by_id/' + id;

    $http.get(url_username).then(function(response) 
        {
  console.log(response);
          
      if (response.data.status && response.data.message)
         {
            var status = response.data.status + '!'; 
            var message = response.data.message;
         
            //return 'username not found';
			 	$scope.params.by='username not found';
   
          }
          else
          {
			  var username=response.data.username;
			  //onsole.info(username);
             $scope.params.by= username;
			 // console.info('hello');
          	
		  }
         
			
      
      
        }).catch(function(response) 
        {
      			 $scope.params.by='username not found';

        // return 'username not found';
        });
	
}
   //redirect to splash screen if not logged innerHeight
  var url_userdata = 'http://fadedbarbershop.co.uk/rest-all.php/provider/user_data';

    $http.get(url_userdata).then(function(response) 
        {
          var session_user_id = response.data.id
          var approved = response.data.approved
          
          if(angular.isUndefined(session_user_id))
            {
    
              $state.go('splash');
              
            }
         if(approved.trim() == 'no')
           {
              $state.go('notApproved');
           }
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

  
  
  $scope.showAlert = function(status,message) {
   var alertPopup = $ionicPopup.alert({
     title: status,
     template: message
   });
};
    var id = $stateParams.id;
    var by = $stateParams.by;
    var content = $stateParams.content;
    var datetime = $stateParams.datetime;
    $scope.state = $state.current
    $scope.params = $stateParams;
		$scope.params.by = get_username_by_id($scope.params.by);
})

.controller('calendarCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http, $window) {
  
   function showAlert(status, message)
  {
  $ionicPopup.alert({
     title: status,
     template: message,
   });
    
  }
  
  
   //redirect to splash screen if not logged innerHeight
 var url_userdata = 'http://fadedbarbershop.co.uk/rest-all.php/provider/user_data';

    $http.get(url_userdata).then(function(response) 
        {
          var session_user_id = response.data.id
          var approved = response.data.approved
          
          if(angular.isUndefined(session_user_id))
            {
    
              $state.go('splash');
              
            }
         if(approved.trim() == 'no')
           {
              $state.go('notApproved');
           }
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

    //show data
	
	
	
  $scope.requests = null;
	$scope.events = null;

	var filtered;
  
  var url = 'http://fadedbarbershop.co.uk/rest-all.php/provider/requests';

    $http.get(url).then(function(response) 
        {
        
			    var requests = response.data;
					filtered = requests.filter(function (el) {
                      return el.accepted == "yes";
                 }
					);     
		for(var i = 0; i < filtered.length; i++) {
					delete filtered[i]["provided_to_user_id"];
					delete filtered[i]["created_by_user_id"];
					delete filtered[i]["paid"];
					delete filtered[i]["accepted"];
					delete filtered[i]["reviewed_by_provider"];
					delete filtered[i]["reviewed_by_client"];
					delete filtered[i]["price"];
					}
				

  /* config object */
    var v_events=[];
        
	if(filtered)
		{
		
				console.log(filtered);

				$scope.eventSources= filtered;
        v_events=filtered;
				showAlert('success!','got data!');
		}
	else
		{
			showAlert('danger!','havent got any data!');
			
		}
        $scope.uiConfig = {
      calendar:{
        height: 450,
        editable: true,
        selectable: false,
			selectHelper: false,
        header:{
          left: 'month basicWeek basicDay agendaWeek agendaDay',
          center: 'title',
          right: 'today prev,next'
        },
        dayClick: $scope.alertEventOnClick,
        eventDrop: $scope.alertOnDrop,
        eventResize: $scope.alertOnResize,
        events: v_events
      }
				
    };

         
 
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });
  
  
 
  
 


  
  

})

.controller('socialCtrl', function($scope, $state, $stateParams, $ionicPopup, $timeout, $http) {
  
   
   function showAlert(status, message)
  {
  $ionicPopup.alert({
     title: status,
     template: message,
   });
    
  }
  
   //redirect to splash screen if not logged innerHeight
  var url_userdata = 'http://fadedbarbershop.co.uk/rest-all.php/provider/user_data';

    $http.get(url_userdata).then(function(response) 
        {
          var session_user_id = response.data.id
          var approved = response.data.approved
          
          if(angular.isUndefined(session_user_id))
            {
    
              $state.go('splash');
              
            }
         if(approved.trim() == 'no')
           {
              $state.go('notApproved');
           }
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });

  
  

  
  //show data
  $scope.product_data = null;
  
  var url = 'http://fadedbarbershop.co.uk/rest-all.php/provider/product_data';

    $http.get(url).then(function(response) 
        {
          $scope.product_data = response.data;
      if($scope.product_data == null)
        {
          return;
        }
          
       if (response.data.status && response.data.message)
         {
            var status = response.data.status + '!'; 
            var message = response.data.message;
         
             showAlert(status,message);

   
          }
          
      return;
      
        }).catch(function(response) 
        {
      
          showAlert('danger!','Some error occured. Please try again.');
        });
})





   

 