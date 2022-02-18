define([
    'jquery',
    'uiComponent',
    'ko',
    'mage/url',
    'mage/storage'
], function ($, Component, ko, urlBuilder, storage) {
    'use strict';

    return Component.extend({
        featuredProducts:ko.observable(),
        visible:ko.observable(false),
        initialize: function () {
            this._super();
            this.getFeatureProducts();
        },
        getFeatureProducts: function()
        {
            var self = this;
            var url = urlBuilder.build('featured_product/featuredproduct/load');
            return storage.get(
                url
            ).done(function (response) {
                if (response) {
                    self.featuredProducts(response);
                    self.visible(true);
                    console.log(self.featuredProducts());
                }
            }).error(function (response) {
                console.log(response);
            });
        }
    });
});
