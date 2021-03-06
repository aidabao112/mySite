describe('Contact service', function () {
    beforeEach(angular.mock.module('mySiteApp'));

    it('Should return an array of ID if they are errors', angular.mock.inject(function (contactService) {
        var $scope = {
            email: 'sqssdzazeaze', // Bad email
            objet: 'object',
            message: 'a message',
            answerCaptcha: '' // No answer
        };
        var tabResult = contactService.validateData($scope);
        expect(tabResult.length > 0).toBe(true);
    }));
});
