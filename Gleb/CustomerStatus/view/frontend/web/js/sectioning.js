define([
    'uiComponent',
    'Magento_Customer/js/customer-data'
], function (Component, customerData) {
    'use strict';

    return Component.extend({
        /** @inheritdoc */
        initialize: function () {
            this._super();
            customerData.reload(['custom_sectioning']);
            this.customsectioning = customerData.get('custom_sectioning'); //pass your custom section name
        }
    });
});
