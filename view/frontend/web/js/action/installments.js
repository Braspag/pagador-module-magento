define(
    [
        'mage/storage',
        'mage/url',
    ],
    function (
        storage,
        urlBuilder
    ) {
        'use strict';

        return function (amount, cardType) {
           
            if(!Number.isInteger(amount) && amount)
             amount = amount.toString().replace('.',',');

            let serviceUrl;
            serviceUrl = urlBuilder.build('braspag/installments/index?amount='+amount+'&card='+cardType, {});
            return storage.post(
                serviceUrl, false
            )
        };
    }
);
