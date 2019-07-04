define([], function() {
    var bpsd_options = {};
    var bpsd_status = "";
    var bpsd_defaultOptions = {
        authMethods: "",
        log: false,
        windowWidth: 600,
        windowHeight: 550,
        onInitialize: function() {},
        onInvalid: function() {},
        onError: function() {},
        onNotAuthenticable: function() {},
        onAbort: function() {},
        onRedirect: function() {},
        onAuthorize: function() {},
        onNotAuthorize: function() {},
        onFinalize: function() {}
    };

    var braspagSuperDebito = {
        superdebito: function(_1) {
            bpsd_options = bpsd_merge(bpsd_defaultOptions, _1);
            bpsd_raiseEvent("onInitialize", bpsd_options.onInitialize);
            if (!bpsd_validate()) {
                bpsd_raiseEvent("onFinalize", bpsd_options.onFinalize);
                return;
            }
            var _2 = [];
            _2.push("MerchantId=" + bpsd_options.merchantId);
            _2.push("MerchantOrderNumber=" + bpsd_getElementValue(".bp-sd-merchantordernumber"));
            _2.push("CustomerName=" + bpsd_getElementValue(".bp-sd-customername"));
            _2.push("CardHolderName=" + bpsd_getElementValue(".bp-sd-cardholdername"));
            _2.push("CardNumber=" + bpsd_getElementValue(".bp-sd-cardnumber"));
            _2.push("CardExpirationDate=" + bpsd_getElementValue(".bp-sd-cardexpirationdate"));
            _2.push("CardCvv=" + bpsd_getElementValue(".bp-sd-cardcvv"));
            _2.push("Amount=" + bpsd_getElementValue(".bp-sd-amount"));
            _2.push("AuthMethods=" + bpsd_options.authMethods);
            var _3 = _2.join("&");
            var _4 = bpsd_apiUrl();
            bpsd_log(_4 + ": " + _3);
            bpsd_ajax("POST", _4, bpsd_getUrlSuccess, _3);
        },

        bpsd_validate: function() {
            if (!bpsd_validatemandatoryvalue(bpsd_options.merchantId)) {
                bpsd_raiseEvent("onInvalid", bpsd_options.onInvalid, "MerchantId");
                return false;
            }
            if (!bpsd_validatemandatoryvalue(bpsd_getElementValue(".bp-sd-merchantordernumber"))) {
                bpsd_raiseEvent("onInvalid", bpsd_options.onInvalid, "MerchantOrderNumber");
                return false;
            }
            if (!bpsd_validatemandatoryvalue(bpsd_getElementValue(".bp-sd-customername"))) {
                bpsd_raiseEvent("onInvalid", bpsd_options.onInvalid, "CustomerName");
                return false;
            }
            if (!bpsd_validatemandatoryvalue(bpsd_getElementValue(".bp-sd-cardholdername"))) {
                bpsd_raiseEvent("onInvalid", bpsd_options.onInvalid, "CardHolderName");
                return false;
            }
            if (!bpsd_validatecardformat(bpsd_getElementValue(".bp-sd-cardnumber"))) {
                bpsd_raiseEvent("onInvalid", bpsd_options.onInvalid, "CardNumber");
                return false;
            }
            if (!/^(0[1-9]|1[0-2])\/([0-9]{2})$/.test(unescape(bpsd_getElementValue(".bp-sd-cardexpirationdate")))) {
                bpsd_raiseEvent("onInvalid", bpsd_options.onInvalid, "CardExpirationDate");
                return false;
            }
            if (!/^([0-9]{3}|[0-9]{4})$/.test(unescape(bpsd_getElementValue(".bp-sd-cardcvv")))) {
                bpsd_raiseEvent("onInvalid", bpsd_options.onInvalid, "CardCvv");
                return false;
            }
            if (!bpsd_validatemandatoryvalue(bpsd_getElementValue(".bp-sd-amount"))) {
                bpsd_raiseEvent("onInvalid", bpsd_options.onInvalid, "Amount");
                return false;
            }
            return true;
        },

        bpsd_apiUrl: function() {
            var _5 = bpsd_scriptUrl();
            bpsd_log(_5);
            if (_5.indexOf("localhost") > -1) {
                return "http://localhost:15809/Api/V1/DebitAuthentication";
            }
            if (_5.indexOf("dev.braspag.com.br") > -1) {
                return "https://dev.braspag.com.br/SuperDebito/Api/V1/DebitAuthentication";
            }
            if (_5.indexOf("superdebitosandbox.braspag.com.br") > -1) {
                return "https://superdebitosandbox.braspag.com.br/Api/V1/DebitAuthentication";
            }
            if (_5.indexOf("superdebitohomolog.braspag.com.br") > -1) {
                return "https://superdebitohomolog.braspag.com.br/Api/V1/DebitAuthentication";
            }
            return "https://superdebito.braspag.com.br/Api/V1/DebitAuthentication";
        },

        bpsd_scriptUrl: function() {
            if (document.currentScript) {
                return document.currentScript.src;
            }
            var _6 = document.getElementsByTagName("script");
            for (var i = 0; i < _6.length; i++) {
                if (_6[i].src.indexOf("superdebito") > -1) {
                    return _6[i].src;
                }
            }
            return "";
        },

        bpsd_merge: function(_8, _9) {
            var _a = {};
            for (var _b in _8) {
                _a[_b] = _8[_b];
            }
            for (var _b in _9) {
                _a[_b] = _9[_b];
            }
            return _a;
        },

        bpsd_getElement: function(_c) {
            var _d = document.querySelectorAll(_c);
            if (_d && _d.length > 0) {
                return _d[0];
            }
            return null;
        },

        bpsd_getElementValue: function(_e) {
            var _f = bpsd_getElement(_e);
            if (_f) {
                if (_f.nodeName == "INPUT") {
                    return encodeURIComponent(_f.value);
                }
                return encodeURIComponent(_f.textContent || _f.innerText);
            }
            return null;
        },

        bpsd_ajax: function(_10, url, _12, _13) {
            var _14;
            if (window.XMLHttpRequest) {
                _14 = new XMLHttpRequest();
            } else {
                _14 = new ActiveXObject("Microsoft.XMLHTTP");
            }
            _14.onreadystatechange = function() {
                if (_14.readyState == 4) {
                    if (_14.status == 200) {
                        _12(JSON.parse(_14.responseText));
                        console.log(_14.responseText);
                    } else {
                        bpsd_raiseEvent("onError", bpsd_options.onError);
                        bpsd_raiseEvent("onFinalize", bpsd_options.onFinalize);
                    }
                }
            };
            _14.open(_10, url, true);
            _14.setRequestHeader("Accept", "application/json");
            _14.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            _14.send(_13);
        },

        bpsd_getUrlSuccess: function(_15) {
            if (_15.Url) {
                bpsd_raiseEvent("onRedirect", bpsd_options.onRedirect);
                if (_15.AcceptIframe) {
                    bpsd_popupLightbox(_15.Url);
                } else {
                    bpsd_popupCenterDual(_15.Url);
                }
            } else {
                bpsd_raiseEvent("onNotAuthenticable", bpsd_options.onNotAuthenticable);
                bpsd_raiseEvent("onFinalize", bpsd_options.onFinalize);
            }
        },

        bpsd_popupStatusHandler: function(_16) {
            if (_16 == "bpsd_Status_Authorized") {
                bpsd_raiseEvent("onAuthorize", bpsd_options.onAuthorize);
            } else {
                if (_16 == "bpsd_Status_NotAuthorized") {
                    bpsd_raiseEvent("onNotAuthorize", bpsd_options.onNotAuthorize);
                } else {
                    if (_16 == "bpsd_Status_Closed") {
                        document.getElementById("bpsd-lightbox-content").style.display = "none";
                        document.getElementById("bpsd-lightbox-overlay").style.display = "none";
                        if (bpsd_status == "bpsd_Status_Opened") {
                            bpsd_raiseEvent("onAbort", bpsd_options.onAbort);
                        }
                        bpsd_raiseEvent("onFinalize", bpsd_options.onFinalize);
                    }
                }
            }
            bpsd_status = _16;
        },

        bpsd_popupCenterDual: function(url) {
            var self = this;
            var _18 = bpsd_popupLeft();
            var top = bpsd_popupTop();
            var _1a = window.open(url, "", "scrollbars=yes, width=" + bpsd_options.windowWidth + ", height=" + bpsd_options.windowHeight + ", top=" + top + ", left=" + _18);
            if (window.focus) {
                _1a.focus();
            }
            bpsd_status = "bpsd_Status_Opened";
            var _1b = setInterval(function() {
                if (_1a.closed !== false) {
                    clearInterval(_1b);
                    self.bpsd_popupStatusHandler("bpsd_Status_Closed");
                }
            }, 500);
        },

        bpsd_popupLightbox: function(url) {
            document.getElementById("bpsd-lightbox-content").style.cssText = "display: none; position: absolute; top: 50%; left: 50%; width: " + bpsd_options.windowWidth + "px; height: " + bpsd_options.windowHeight + "px; margin: -" + bpsd_options.windowHeight / 2 + "px 0 0 -" + bpsd_options.windowWidth / 2 + "px; border: 5px solid #ffffff; background: #ffffff; z-index: 1000; overflow: hidden;";
            document.getElementById("bpsd-lightbox-iframe").src = url;
            document.getElementById("bpsd-lightbox-overlay").style.display = "block";
            document.getElementById("bpsd-lightbox-content").style.display = "block";
            bpsd_status = "bpsd_Status_Opened";
        },

        bpsd_popupTop: function() {
            var _1d = window.screenTop != undefined ? window.screenTop : screen.top;
            var _1e = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;
            return ((_1e / 2) - (bpsd_options.windowHeight / 2)) + _1d;
        },

        bpsd_popupLeft: function() {
            var _1f = window.screenLeft != undefined ? window.screenLeft : screen.left;
            var _20 = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
            return ((_20 / 2) - (bpsd_options.windowWidth / 2)) + _1f;
        },

        bpsd_hasClass: function(_21, _22) {
            return !!_21.className.match(new RegExp("(\\s|^)" + _22 + "(\\s|$)"));
        },

        bpsd_addClass: function(_23, _24) {
            if (!bpsd_hasClass(_23, _24)) {
                _23.className += " " + _24;
            }
        },

        bpsd_removeClass: function(_25, _26) {
            if (bpsd_hasClass(_25, _26)) {
                var reg = new RegExp("(\\s|^)" + _26 + "(\\s|$)");
                _25.className = _25.className.replace(reg, " ");
            }
        },

        bpsd_validatemandatoryvalue: function(_28) {
            if (!_28) {
                return false;
            }
            var val = unescape(_28);
            if (!val.trim()) {
                return false;
            }
            return true;
        },

        bpsd_validatecardformat: function(_2a) {
            var _2b = unescape(_2a);
            var _2c = parseInt(_2b.substring(_2b.length - 1, _2b.length));
            var _2d = _2b.substring(0, _2b.length - 1);
            if (bpsd_calculateMod10(_2d) != parseInt(_2c)) {
                return false;
            }
            return true;
        },

        bpsd_calculateMod10: function(_2e) {
            var sum = 0;
            for (var i = 0; i < _2e.length; i++) {
                sum += parseInt(_2e.substring(i, i + 1));
            }
            var _31 = new Array(0, 1, 2, 3, 4, -4, -3, -2, -1, 0);
            for (var j = _2e.length - 1; j >= 0; j -= 2) {
                var _33 = parseInt(_2e.substring(j, j + 1));
                var _34 = _31[_33];
                sum += _34;
            }
            var _35 = sum % 10;
            _35 = 10 - _35;
            if (_35 == 10) {
                _35 = 0;
            }
            return _35;
        },

        bpsd_raiseEvent: function(_36, _37, _38) {
            try {
                bpsd_log("'" + _36 + "' raised" + (_38 ? " with parameters: '" + _38 + "'" : ""));
                _37(_38);
            } catch (err) {
                console.log("An error has ocurred on '" + _36 + "' event handler in your application. The error message is: '" + err + "'");
                throw err;
            }
        },

        bpsd_log: function(_39) {
            if (console.log && bpsd_options.log) {
                console.log(_39);
            }
        },

        bpsd_init: function() {
            var self = this;
            var _3a = document.createElement("div");
            _3a.id = "bpsd-lightbox-content";
            var _3b = document.createElement("iframe");
            _3b.id = "bpsd-lightbox-iframe";
            _3b.style.cssText = "border:0; width: 100%; height: 100%; overflow: hidden;";
            var _3c = document.createElement("div");
            _3c.id = "bpsd-lightbox-overlay";
            _3c.style.cssText = "display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: black; z-index: 5; -moz-opacity: 0.8; opacity:.80; filter: alpha(opacity=80);";
            _3a.appendChild(_3b);
            document.body.appendChild(_3a);
            document.body.appendChild(_3c);
            document.addEventListener("click", function(e) {
                if (e.target.id == "bpsd-lightbox-overlay") {
                    self.bpsd_popupStatusHandler("bpsd_Status_Closed");
                }
            });
            window.addEventListener("message", function(e) {
                self.bpsd_popupStatusHandler(e.data);
            }, false);
        }

    }

    if (window.addEventListener) {
        window.addEventListener("load", braspagSuperDebito.bpsd_init(), false);
    } else {
        if (window.attachEvent) {
            window.attachEvent("onload", braspagSuperDebito.bpsd_init());
        } else {
            window.onload = braspagSuperDebito.bpsd_init();
        }
    }

    return braspagSuperDebito;
});


