# Braspag Pagador plugin for Magento 2
Official Braspag plugin for Magento 2 build to offer frictionless payments online.

## Integration
The plugin integrates with Braspag Pagador API.

## Available Payment Methods

### Credit Card

- Installments
- Silent Order Post
- Authenticate 3DS/VBV
- Authenticate 3DS 2.0
- Split Payment
- AVS
- Checkout Card View
    
### Credit Card JustClick
- Authorize Only
- Authorize and Capture
    
### Debit Card

- Authenticate 3DS 2.0
- Split Payment
- Checkout Card View

### Boleto

- Instructions
- Assignor    
- Split Payment
- Expirations Day

### Split Payment

- Trava de pagamentos
- Pós-transacional


## Requirements
This plugin supports:
- PHP 5.6 version and higher.
- Magento2 version 2.3.3 and higher.

## Installation
You can install our plugin through Composer:

```bash
composer require webjump/magento2-module-braspagpagador
composer update
bin/magento module:enable Webjump_BraspagPagador
bin/magento setup:upgrade
```

## Configuration
After installation has completed go to:

Stores > Settings > Configuration

Sales > Payment Methods > Other Payment Methods > Webjump Braspag.

## Support
You can create issues on our Magento Repository.

In case of specific problems with your account, please contact braspag@webjump.com.br.

## Contributing
Pull requests are welcome.
For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.
