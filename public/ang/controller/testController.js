app.controller('testController',['$scope','$ngConfirm','$interval',	'$ngConfirmDefaults','$timeout',function ($scope, $ngConfirm, $interval, $ngConfirmDefaults, $timeout) {
		$scope.deleteUser = function () {
			$ngConfirm({
				title: 'Delete user?',
				content: 'This dialog will automatically trigger \'cancel\' in 6 seconds if you don\'t respond.',
				autoClose: 'cancel|8000',
				buttons: {
					deleteUser: {
						text: 'delete user',
						btnClass: 'btn-red',
						action: function () {
							$ngConfirm('Deleted the user!');
						}
					},
					cancel: function () {
						$ngConfirm('action is canceled');
					}
				}
			});
		};
		$scope.logoutMyself = function () {
			$ngConfirm({
				title: 'Logout?',
				content: 'Your time is out, you will be automatically logged out in 10 seconds.',
				autoClose: 'logoutUser|10000',
				buttons: {
					logoutUser: {
						text: 'logout myself',
						btnClass: 'btn-green',
						action: function () {
							$ngConfirm('The user was logged out');
						}
					},
					cancel: function () {
						$ngConfirm('canceled');
					}
				}
			});
		};
}]);