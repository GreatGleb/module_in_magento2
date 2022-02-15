define([
    "jquery",
    "Magento_Ui/js/modal/modal",
    'Magento_Customer/js/customer-data'
],function($, modal, customerData) {

    let options = {
        type: 'popup',
        responsive: true,
        title: 'Request Price',
        buttons: [{
            text: $.mage.__('Cancel'),
            class: '',
            click: function () {
                this.closeModal();
            }
        }, {
            text: $.mage.__('Request price'),
            class: '',
            click: request
        }]
    };

    function request() {
        let form = document.querySelector('#product-request-price-modal .modal-body-content');
        let inputs = form.querySelectorAll('input'); // [form.querySelector('input#popUp-name'), form.querySelector('input#popUp-email')];

        let isOk = 1;
        for(let i = inputs.length-1; i >= 0; i--) {
            let input = inputs[i];
            if (!input.checkValidity()) {
                input.reportValidity()
                isOk = 0;
            }
        }

        if(!isOk) {
            return 0;
        }

        let request = {};
        request['name'] = form.querySelector('input#popUp-name').value;
        request['email'] = form.querySelector('input#popUp-email').value;
        request['comment'] = form.querySelector('textarea#popUp-comment').value;
        request['entity_id'] = form.querySelector('input#popUp-entityId').value;

        request = JSON.stringify(request);

        $.ajax({
            url: "/product/request/price",
            type: "POST",
            data: "request="+request,
            success: function(data) {
                $('#product-request-price-modal').modal('closeModal');
            },
            error: function (xhr, status, error) {
                $('#product-request-price-modal').modal('closeModal');
            }
        });
    }

    let popup = modal(options, $('#product-request-price-modal'));
    $("#product-request-price-button").click(function() {
        $('#product-request-price-modal').modal('openModal');
    });
});
