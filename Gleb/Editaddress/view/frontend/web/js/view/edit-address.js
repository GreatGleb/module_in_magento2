define([
        'ko',
        'uiComponent'
    ], function (ko, Component) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Gleb_Editaddress/edit-address'
            },
            isRegisterNewsletter: true
        });
    }
);
