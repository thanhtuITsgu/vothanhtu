define([
    'ko',
    'uiComponent',
    'mage/url',
    'mage/storage',
], function (ko, Component, urlBuilder,storage) {
    'use strict';
    var id=1;

    return Component.extend({

        defaults: {
            template: 'Magenest_KnockoutJs/test',
        },

        productList: ko.observableArray([]),

        getProduct: function () {
            var self = this;
            /*self.query = ko.observable("");*/
            var serviceUrl = urlBuilder.build('knockout/test/product?id='+id); //m2agento/knockout/index/index ;
            id ++;
            return storage.post(
                serviceUrl,
                ''
            ).done(
                function (response) {
                    self.productList.push(JSON.parse(response));
                }
            ).fail(
                function (response) {
                    alert(response);
                }
            );
        },

    });
});