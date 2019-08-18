define([
    'ko',
    'uiComponent',
    'mage/url',
    'mage/storage',
], function (ko, Component, urlBuilder,storage) {
    'use strict';

    return Component.extend({

        defaults: {
            template: 'Magenest_KnockoutJs/search',
        },

        productList: ko.observableArray([]),
        Name:ko.observable(),
        getProduct: function () {
         /*alert('asdasd');*/

            var self = this;
            self.productList([]);
            var serviceUrl = urlBuilder.build('knockout/search/search?name='+self.Name()); //m2agento/knockout/index/index ;
            return storage.post(
                serviceUrl,
                ''
            ).done(
                function (response) {

                    var arrResponse = JSON.parse(response);
                    alert(arrResponse);
                    var lenght = arrResponse.length;

                    for(var i=0 ; i < lenght; i++ ) {
                        self.productList.push(JSON.parse(JSON.stringify(arrResponse[i])));}

                }
            ).fail(
                function (response) {
                    alert(response);
                }
            );
        },

    });
});