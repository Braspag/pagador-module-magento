/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
/*browser:true*/
/*global define*/
define(
	[
		"jquery"
	],
	function(
		$
	) {
	'use strict';

	var defaultFormat = /(\d{1,4})/g;
	var cards = [
		{
			type: 'naranja-nevada',
			typeName: 'Naranja e Nevada',
			patterns: [5895],
			regex_include: '^(589562)',
			regex_exclude: '',
			format: defaultFormat,
			length: [16],
			cvcLength: [3],
			luhn: true
		},
		{
			type: 'elo',
			typeName: 'Elo',
			patterns: [4514,6363,4389,5041,6362,5067,4576,4011,6516,6509,5090,5092,5093,5094,5095,5096,5098,5099,6277.6363,6500,6506,6505,6507,6517,6550,6504],
            regex_include: '^(?:40117[8-9]|431274|438935|451416|457393|45763[1-2]|504175|506699|5067(?:[0-6][0-9]|7[0-8])|509[0-9][0-9][0-9]|651652|627780|636297|636368|6500(?:3[1-3]|3[5-9]|4[0-9]|5[0-1])|650(?:4(?:0[5-9]|[1-9][0-9])|5(?:[0-2][0-9]|3[0-8]|4[1-9]|[5-8][0-9]|9[0-8]))|6507(?:0[0-9]|1[0-8]|2[0-7])|^6509(?:0[1-9]|1[0-9]|20)|^6516(?:5[2-9]|[6-7][0-9])|^6550(?:[0-1][0-9]|2[1-9]|[3-4][0-9]|5[0-8])|^65165[2-4]|65048[5-8]|^650489|^65049[0-4])\d{10}$',
			regex_exclude: '',
			format: defaultFormat,
			length: [16],
			cvcLength: [3],
			luhn: true
		}, 
		{
			type: 'carnet',
			typeName: 'Carnet',
			patterns: [2869,5022,5061,5062,5064,5887,6046,6063,6275,6363,6393,6394,6395],
			format: defaultFormat,
			regex_include: '^(286900|502275|506(199|2(0[1-6]|1[2-578]|2[289]|3[67]|4[579]|5[01345789]|6[1-79]|7[02-9]|8[0-7]|9[234679])|3(0[0-9]|1[1-479]|2[0239]|3[02-79]|4[0-49]|5[0-79]|6[014-79]|7[0-4679]|8[023467]|9[1234689])|4(0[0-8]|1[0-7]|2[0-46789]|3[0-9]|4[0-69]|5[0-79]|6[0-38]))|588772|604622|606333|627535|636(318|379)|639(388|484|559))',
			regex_exclude: '',
			length: [16],
			cvcLength: [3],
			luhn: true
		}, {
			type: 'cabal',
			typeName: 'Cabal',
			patterns: [6042,6043,6271,6035,5896],
			regex_include: '^((627170)|(589657)|(603522)|(604((20[1-9])|(2[1-9][0-9])|(3[0-9]{2})|(400))))',
			regex_exclude: '',
			format: defaultFormat,
			length: [16],
			cvcLength: [3],
			luhn: true
		},{
			type: 'visa',
			typeName: 'Visa',
			patterns: [4],
			regex_include: '^4[0-9]{15}$',
			regex_exclude: '^((451416)|(438935)|(40117[8-9])|(45763[1-2])|(457393)|(431274)|(402934))',
			format: defaultFormat,
			length: [13, 16],
			cvcLength: [3],
			luhn: true
		}, {
			type: 'maestro',
			typeName: 'Master',
			patterns: [5018, 502, 503, 506, 56, 58, 639, 6220, 67],
			regex_include: '',
			regex_exclude: '',
			format: defaultFormat,
			length: [12, 13, 14, 15, 16, 17, 18, 19],
			cvcLength: [3],
			luhn: true
		}, {
			type: 'mastercard',
			typeName: 'Master',
			patterns: [51, 52, 53, 54, 55, 22, 23, 24, 25, 26, 27],
			regex_include: '^(5[1-5][0-9]{2}|222[1-9]|22[3-9][0-9]|2[3-6][0-9]{2}|27[01][0-9]|2720)[0-9]{12}$ /^((5(([1-2]|[4-5])[0-9]{8}|0((1|6)([0-9]{7}))|3(0(4((0|[2-9])[0-9]{5})|([0-3]|[5-9])[0-9]{6})|[1-9][0-9]{7})))|((508116)\\d{4,10})|((502121)\\d{4,10})|((589916)\\d{4,10})|(2[0-9]{15})|(67[0-9]{14})|(506387)\\d{4,10})/',
			regex_exclude: '^(514256|514586|526461|511309|514285|501059|557909|501082|589633|501060|501051|501016|589657|553839|525855|553777|553771|551792|528733|549180|528745|517562|511849|557648|546367|501070|601782|508143|501085|501074|501073|501071|501068|501066|589671|589633|588729|501089|501083|501082|501081|501080|501075|501067|501062|501061|501060|501058|501057|501056|501055|501054|501053|501051|501049|501047|501045|501043|501041|501040|501039|501038|501029|501028|501027|501026|501025|501024|501023|501021|501020|501018|501016|501015|589657|589562|501105|557039|542702|544764|550073|528824|522135|522137|562397|566694|566783|568382|569322|504363)',
			format: defaultFormat,
			length: [16],
			cvcLength: [3],
			luhn: true
		}, {
			type: 'amex',
			typeName: 'Amex',
			patterns: [34, 37],
			regex_include: '^((34)|(37))',
			regex_exclude: '',
			format: /(\d{1,4})(\d{1,6})?(\d{1,5})?/,
			length: [15],
			cvcLength: [3, 4],
			luhn: true
		}, {
			type: 'dinersclub',
			typeName: 'Diners',
			patterns: [30, 36, 38, 39],
			regex_include: '^(36)',
			regex_exclude: '',
			format: /(\d{1,4})(\d{1,6})?(\d{1,4})?/,
			length: [14],
			cvcLength: [3],
			luhn: true
		}, {
			type: 'discover',
			typeName: 'Discover',
			patterns: [6011,622,64,65],
			regex_include: '^65[4-9][0-9]{13}|64[4-9][0-9]{13}|6011[0-9]{12}|(622(?:12[6-9]|1[3-9][0-9]|[2-8][0-9][0-9]|9[01][0-9]|92[0-5])[0-9]{10})$',
			regex_exclude: '',
			format: defaultFormat,
			length: [16],
			cvcLength: [3],
			luhn: true
		}, {
			type: 'jcb',
			typeName: 'Jcb',
			patterns: [35],
			regex_include: '',
			regex_exclude: '',
			format: defaultFormat,
			length: [16],
			cvcLength: [3],
			luhn: true
		}, {
			type: 'hiper',
			typeName: 'Hiper',
			patterns: [63],
			regex_include: '',
			regex_exclude: '',
			format: defaultFormat,
			length: [16],
			cvcLength: [3],
			luhn: true
		}, {
			type: 'hipercard',
			typeName: 'Hipercard',
			patterns: [38,60,6062,6370,6375,6376],
			regex_include: '^((606282)|(637095)|(637568)|(637599)|(637609)|(637612))',
			regex_exclude: '',
			format: defaultFormat,
			length: [16],
			cvcLength: [3],
			luhn: true
		}, {
			type: 'aura',
			typeName: 'Aura',
			patterns: [50],
			regex_include: '',
			regex_exclude: '',
			format: defaultFormat,
			length: [16],
			cvcLength: [3],
			luhn: true
        }, {
            type: 'ticket',
			typeName: 'Ticket',
			patterns: [6026, 6033],
			regex_include: '',
			regex_exclude: '',
			format: defaultFormat,
			length: [16],
			cvcLength: [3],
			luhn: true
        }
	];

	return {
		init: function() {
			this.registerCreditCardType();
		},
		cardFromNumber : function(num) {
			var card, p, pattern, _i, _j, _len, _len1, _ref;

			num = (num + '').replace(/\D/g, '');
			for (_i = 0, _len = cards.length; _i < _len; _i++) {
				card = cards[_i];

				let cardTypeFound = false;
				if (card.regex_include != '') {
					let regexIncludePattern = new RegExp(card.regex_include);

					if (regexIncludePattern.test(num)) {
						cardTypeFound = true;
					}
				}

				if (cardTypeFound) {

					if (card.regex_exclude == '') {
						return card;
					}

					let regexExcludePattern = new RegExp(card.regex_exclude);

					if (!regexExcludePattern.test(num)) {
						return card;
					}
				}

				_ref = card.patterns;
				for (_j = 0, _len1 = _ref.length; _j < _len1; _j++) {
					pattern = _ref[_j];
					p = pattern + '';
					if (num.substr(0, p.length) === p) {
						return card;
					}
				}
			}
		},
		registerCreditCardType: function() {
			let self = this;

			$('body').on('keyup', '.braspag-card input[name ="payment[cc_number]"]', function(e) {

				e.preventDefault();
				let cardNumber = $(this).val();
				let card = self.cardFromNumber(cardNumber);

				if (card != undefined) {
					$('.creditcard-type').val("Braspag-"+card.typeName);
				}
			});

			$('body').on('keyup', '.braspag-debitcard input[name ="payment[cc_number]"]', function(e) {
				e.preventDefault();
				let cardNumber = $(this).val();
				let card = self.cardFromNumber(cardNumber);

				if (card != undefined) {
					$('.debitcard-type').val("Braspag-"+card.typeName);
				}
			});

			$('body').on('keyup', '.braspag-card input[name ="payment[cc_number_card2]"]', function(e) {

				e.preventDefault();
				let cardNumber = $(this).val();
				let card = self.cardFromNumber(cardNumber);

				if (card != undefined) {
					$('.creditcard-type-two').val("Braspag-"+card.typeName);
				}
			});

		},

		forceRegisterCreditCardType: function(creditCardNumber, creditCardType) {

			let card = this.cardFromNumber(creditCardNumber);

			if (card === undefined) {
				$('input[name="payment[cc_number]').val('');
			}

			creditCardType.val("Braspag-"+card.typeName);
		},

		forceRegisterDebitCardType: function(debitCardNumber, debitCardType) {

			let card = this.cardFromNumber(debitCardNumber);

			if (card === undefined) {
				$('input[name="payment[cc_number]').val('');
			}

			debitCardType.val("Braspag-"+card.typeName);
		}
	};
} );
