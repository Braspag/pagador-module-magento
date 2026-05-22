# Braspag Pagador plugin for Magento 2
Official Braspag plugin for Magento 2 built to offer frictionless payments online.

## Integration
The plugin integrates with the Braspag Pagador API.

## Available Payment Methods

### Credit Card
- Installments
- Silent Order Post
- Authenticate 3DS/VBV
- Authenticate 3DS 2.0
- Split Payment
- AVS
- Checkout Card View
- AntiFraud

### Credit Card JustClick
- Authorize Only
- Authorize and Capture

### Debit Card
- Authenticate 3DS 2.0
- Split Payment
- Checkout Card View
- AntiFraud

### Pix
- QR Code
- Split Payment
- AntiFraud

### Boleto
- Instructions
- Assignor
- Split Payment
- Expirations Day
- AntiFraud

### Voucher
- Order
- Split Payment
- AntiFraud

### Wallet
- Tokenized card payments
- Split Payment
- AntiFraud

### Split Payment
- Trava de pagamentos
- Pós-transacional

## Requirements
This plugin supports:
- PHP 7.4 to 8.4
- Magento 2.3.3 and higher (tested up to 2.4.8)

## Installation
You can install our plugin through Composer:

```bash
composer require braspag/magento2-module-braspagpagador
composer update
bin/magento module:enable Braspag_BraspagPagador
bin/magento setup:upgrade
```

## Configuration
After installation has completed go to:

Stores > Settings > Configuration

Sales > Payment Methods > Other Payment Methods > Braspag Braspag.

## Support
You can create issues on our Magento Repository.

In case of specific problems with your account, please contact braspag@webjump.com.br.

## Contributing
Pull requests are welcome.
For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.
