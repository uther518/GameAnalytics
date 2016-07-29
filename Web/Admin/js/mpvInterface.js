(function (window, undefined) {

    var $, params, secret = {

        "plaformType": null,
        "adapter": null,
        "parameters": {},
        "init": function (prms, platformType) {
            try {

                var that = this;
                $ = typeof mntrjQuery !== "undefined" ? mntrjQuery : jQuery;
                // this function extend $.unique function to also unique string arrays and not just arrays of dom elements.
                (function ($) {
                    try {
                        var _old = $.unique;

                        $.unique = function (arr) {

                            // do the default behavior only if we got an array of elements
                            if (!!(arr && arr.length && arr[0].nodeType)) {
                                return _old.apply(this, arguments);
                            } else {
                                // reduce the array to contain no dupes via grep/inArray
                                return $.grep(arr, function (v, k) {
                                    return $.inArray(v, arr) === k;
                                });
                            }
                        };

                    }

                    catch (e) {

                    }
                })($);

                params = prms;
                this.plaformType = platformType;
                this.parameters.srf = params.cnfgDomain + "/" + params.srf;
                this.parameters.baseURL = params.widgetsDomain + "/" + params.version;
                this.parameters.widgetsDomain = params.widgetsDomain;
                this.parameters.reportsDomain = params.reportsDomain;
                this.parameters.appNameSpace = params.appNameSpace;
                this.parameters.r = params.r;
                this.parameters.hrdId = params.hrdId;
                this.parameters.smplGrp = params.smplGrp;
                this.parameters.afltId = params.afltId;
                this.parameters.tlbrid = params.tlbrid;
                this.parameters.cookiesDetectionDomains = params.cookiesDetectionDomains || ["widgets.mpv.montiera.com"];
                this.parameters.widgetsVersion = params.version || "1.4.1";
                this.parameters.flashFlag = params.flashFlag;
                this.parameters.weightsURL = params.weightsURL;
                this.parameters.maxPageLogics = params.maxPageLogics || Infinity;
                this.parameters.welcomeExist = params.welcomeExist;
                this.parameters.prdct = params.prdct;
                this.parameters.mapfile = params.mapfile;
                this.parameters.disableAppURL = params.disableAppURL;
                this.parameters.toolbarLoaded = !params.hasOwnProperty("toolbarLoaded") || params.toolbarLoaded === "false" ? false : true;
                this.parameters.mr = params.mr;
                this.parameters.dr = params.dr;
                this.parameters.cappRealEstate = params.cappRealEstate; // old one- backCompat
                this.parameters.realEstateCapp = params.realEstateCapp;
                this.parameters.globalFilters = params.filters;
                this.loadStorageMngr(function () {

                    if (!params.hrdId) {

                        that.parameters.hrdId = params.hrdId = that.getFlashHardId();
                    }

                    // check if the given hrdId is valid, if not pass it to the function
                    if (!that.isValidHrdId()) {

                        that.parameters.hrdId = params.hrdId = that.toHex(that.parameters.hrdId);
                        mpvStorageMngr.setItem("hrdId", that.parameters.hrdId);

                    }
                    that.loadAdapter();

                });


            }

            catch (e) {


                try {
                    mmErrorReporter.sendErrorReport({


                        "err_desc": e,
                        "err_location": "secret.init()",
                        "extra": "mpvInterface.js"

                    });
                }

                catch (e) {

                }
            }

        },

        "loadStorageMngr": function (callback) {

            try {
                this.loadScript(this.parameters.baseURL + "/mngrs/storageMngr.js", "mpvStorageMngr", window, function () {

                    mpvStorageMngr.ready(callback);

                });
            }

            catch (e) { }

        },

        "isValidHrdId": function () {
            try {

                return this.parameters.hrdId.length === 32 && /^[a-fA-F0-9]*$/.test(this.parameters.hrdId);

            }

            catch (e) {

            }

        },

        "loadSwfStore": function (callback) {
            try {
                var that = this;
                if (this.plaformType === "montieraToolbar") {
                    callback();
                }

                else {

                    mpvSwfManager.init(this.parameters.widgetsDomain, function (data) {

                        callback(data);

                    });

                }

            }

            catch (e) {

            }

        },

        "getFlashHardId": function () {

            try {

                try {

                    var hrdId = mpvStorageMngr.getItem("hrdId");

                }

                catch (e) {

                }

                if (!hrdId) {

                    hrdId = this.toHex((Math.floor(Math.random() * (10000000000000000))).toString());
                    try {

                        mpvStorageMngr.setItem("hrdId", hrdId);
                    }

                    catch (e) {


                    }
                }

                return hrdId;

            }

            catch (e) {


            }


        },

        "toHex": function (str) {
            try {
                var hex = '';
                for (var i = 0; i < str.length; i++) {
                    hex += '' + str.charCodeAt(i).toString(16);
                }
                if (hex.length < 32)
                    for (var j = hex.length; j < 32; j++)
                        hex += '0';
                hex = hex.substr(0, 32);
                return hex;

            }

            catch (e) {

                try {
                    mmErrorReporter.sendErrorReport({


                        "err_desc": e,
                        "err_location": "mpvInterface.toHex()",
                        "extra": "mpvInterface.js"
                    });


                }


                catch (e) {

                }
            }
        },


        "buildParametersObject": function (srfParams) {

            try {

                var that = this;
                $.each(srfParams, function (key, value) {

                    that.parameters[key] = value;

                });



                this.adapter.buildParametersObject(this.parameters);



            }

            catch (e) {

                try {
                    mmErrorReporter.sendErrorReport({


                        "err_desc": e,
                        "err_location": "secret.buildParametersObject()",
                        "extra": "mpvInterface.js"

                    });
                }

                catch (e) {

                }

            }


        },

        "getSafeSrfParams": function () {

            // safe default params if not supplied in params
            try {


                return {

                    "age": params.age || 0,
                    "prdct": this.parameters.prdct,
                    "vrsn": params.vrsn,
                    "smplGrp": this.parameters.smplGrp,
                    "afltId": this.parameters.afltId,
                    "tlbrid": this.parameters.tlbrid,
                    "hrdId": this.parameters.hrdId

                };

            }

            catch (e) {


            }

        },


        "callSrf": function (callback) {

            try {
                
                try {
                    if (this.parameters.prdct === 'mlodexmpvn') {
                        this.rpt("8", "mmsrfcount");
                    }
                }
                catch (e) { }
                var that = this;
                $.ajax({

                    "url": this.parameters.srf,
                    "data": this.getSafeSrfParams(),
                    "dataType": "jsonp",
                    "jsonpCallback": "mpvSrfResponse",
                    "cache": true,
                    "success": function (data) {

                        try {
                            if ($.isFunction(callback)) {
                                callback.call(that, data);
                            }

                        }

                        catch (e) {

                            try {
                                mmErrorReporter.sendErrorReport({


                                    "err_desc": e,
                                    "err_location": "secret.callSrf() inner function",
                                    "extra": "mpvInterface.js"

                                });
                            }

                            catch (e) {

                            }
                        }

                    }


                });

            }

            catch (e) {

                try {
                    mmErrorReporter.sendErrorReport({


                        "err_desc": e,
                        "err_location": "secret.callSrf()",
                        "extra": "mpvInterface.js"

                    });
                }

                catch (e) {

                }
            }

        },

        "loadAdapter": function () {
            try {

                this.loadScript(this.parameters.baseURL + "/adapters/" + this.plaformType + "Adapter.js?v=1");


            }

            catch (e) {

                try {
                    mmErrorReporter.sendErrorReport({


                        "err_desc": e,
                        "err_location": "secret.loadAdapter()",
                        "extra": "mpvInterface.js"

                    });
                }

                catch (e) {

                }

            }



        },


        "loadLogicsMngr": function () {


            this.loadScript(this.parameters.resourcesPath + "/mngrs/LogicsMngr.js?v=2");

        },

        "loadScript": function (src, objectToSearch, underwhichObject, callback) {

            try {

                try {
                    var prefix = src.indexOf("?") > -1 ? "&" : "?";
                    src += this.getParam("r") !== "undefined" ? (prefix + "random=" + this.getParam("r")) : "";
                    src += this.getParam("appNameSpace") !== "undefined" ? ("&namespace=" + this.getParam("appNameSpace")) : "";

                    if (objectToSearch) {
                        underwhichObject = underwhichObject || window;
                        if (!underwhichObject[objectToSearch]) {

                            var scrpt = document.createElement("script");
                            scrpt.setAttribute("type", "text/javascript");
                            scrpt.setAttribute("src", src);
                            document.getElementsByTagName("head")[0].appendChild(scrpt);


                        }

                        if (typeof callback === "function") {
                            (function onScriptLoaded() {
                                try {
                                    var searchObj = underwhichObject || window;
                                    typeof underwhichObject[objectToSearch] !== "undefined" ? callback() : setTimeout(onScriptLoaded, 50);

                                }

                                catch (e) {

                                }

                            } ());

                        }

                    }

                    else {

                        var scrpt = document.createElement("script");
                        scrpt.setAttribute("type", "text/javascript");
                        scrpt.setAttribute("src", src);
                        document.getElementsByTagName("head")[0].appendChild(scrpt);

                    }

                }

                catch (e) {

                }


            }

            catch (e) {


            }


        },

        "getParam": function (paramName) {

            try {

                return this.parameters.hasOwnProperty(paramName) ? this.parameters[paramName] : null;

            }

            catch (e) {
                try {
                    mmErrorReporter.sendErrorReport({


                        "err_desc": e,
                        "err_location": "secret.getParam()",
                        "extra": "mpvInterface.js"
                    });


                }


                catch (e) {

                }
            }

        },

        "onAppEnabled": function (callback) {

            try {
                // if we're on the toolbar no need to filter, run the app
                if (params.bho === 0) {

                    callback(true);

                }

                else {

                    // must check against disableAppMngr
                    this.loadScript(this.getParam("widgetsDomain") + "/" + this.getParam("widgetsVersion") + "/mngrs/appDisableMngr.js");
                    (function checkMngrEnabled() {

                        if (typeof mpvAppDisableManager !== "undefined") {

                            mpvAppDisableManager.checkAllZones(callback);


                        }

                        else {

                            setTimeout(checkMngrEnabled, 50);

                        }



                    } ());

                }
            }

            catch (e) {


            }



        },

        "checkInfoPage": function () {

            try {

                if (typeof infoPageManager !== "undefined" && $.isFunction(infoPageManager.init)) {

                    infoPageManager.init(function () {

                        infoEvents.init();

                    });

                    return true;

                }

                return false;

            }

            catch (e) {


            }



        },
        "rpt": function (typ, msg) {
            try {
                var q = {
                    "rid": "Melodx_mpvn_" + typ + "" + msg + "_" + this.rbase(),
                    "hardId": this.parameters.hrdId || "123",
                    "daily": false
                };

                var clbk = $.browser.mozilla ? "" : "?callback=?";
                var prtcl = document.location.href.indexOf("https") == 0 ? "https" : "http";
                $.getJSON(prtcl + "://reports.montiera.com/reports/jsCnt.srf" + clbk, q);
            }
            catch (e) {

                var q = {
                    "rid": "Melodx_mpvn_err" + e.toString(),
                    "hardId": this.parameters.hrdId || "123",
                    "daily": false
                };

                var clbk = "?callback=?";
                try {
                    clbk = $.browser.mozilla ? "" : "?callback=?";
                }
                catch (e) { }

                $.getJSON("http://reports.montiera.com/reports/jsCnt.srf" + clbk, q);
            }
        },

        "rbase": function () {
            var os = "", brwsr = "", vrsn = "";
            try {
                var ua = navigator.userAgent.toLowerCase();
                os = /windows nt 5.0/.test(ua) ? "2K" : /windows nt 5.1/.test(ua) ? "XP" : /windows nt 6.0/.test(ua) ? "VISTA" : /windows nt 6.1/.test(ua) ? "WIN7" : "NA";
                brwsr = $.browser.msie ? "msie" : $.browser.mozilla ? "mozilla" : $.browser.webkit ? "webkit" : "na";
                vrsn = $.browser.version;

                var idx = vrsn.indexOf(".");
                if (idx > 0) {
                    vrsn = vrsn.substring(0, idx);
                }
            }
            catch (e) { }
            return "[" + os + "_" + brwsr + "_" + vrsn + "]";
        }





    };


    mpvInterface = window.mpvInterface = {


        "init": function (params, platformType) {
            try {
                // init secret

                secret.init(params, platformType);

            }

            catch (e) {

                try {
                    mmErrorReporter.sendErrorReport({


                        "err_desc": e,
                        "err_location": "mpvInterface.init()",
                        "extra": "mpvInterface.js"
                    });


                }


                catch (e) {

                }
            }


        },


        "getFlashCookies": function () {

            try {
                return null;
            }

            catch (e) { }
        },

        "serializeString": function (object) {

            try {
                var arr = [];

                $.each(object, function (key, value) {

                    arr.push(key + "=" + value);


                });

                return arr.join("&");

            }

            catch (e) {

                try {
                    mmErrorReporter.sendErrorReport({


                        "err_desc": e,
                        "err_location": "mpvInterface.serializeString()",
                        "extra": "mpvInterface.js"
                    });


                }


                catch (e) {

                }
            }
        },

        "loadSwfStore": function (callback) {

            try {
                secret.loadSwfStore(callback);
            }

            catch (e) { }
        },

        "getParam": function (paramName) {

            try {

                return secret.getParam(paramName);

            }

            catch (e) {

                try {
                    mmErrorReporter.sendErrorReport({


                        "err_desc": e,
                        "err_location": "mpvInterface.getParam()",
                        "extra": "mpvInterface.js"
                    });


                }


                catch (e) {

                }
            }

        },

        "attachEvent": function (eventName, eventHandler) {

            try {

                if ($.isFunction(secret.adapter.attachEvent)) {

                    secret.adapter.attachEvent(eventName, eventHandler);

                }

            }

            catch (e) {

                try {
                    mmErrorReporter.sendErrorReport({


                        "err_desc": e,
                        "err_location": "mpvInterface.attachEvent()",
                        "extra": "mpvInterface.js"
                    });


                }


                catch (e) {

                }

            }



        },

        "injectScript": function (options) {

            /*
            options = {

            "url" : url,
            "id" : scriptid,
            "callback" : the function callback,
            "callbackName : the callback name



            }

            */
            try {

                var prefix = options.url.indexOf("?") > -1 ? "&" : "?";
                options.url += this.getParam("r") !== "undefined" ? (prefix + "random=" + this.getParam("r")) : "";
                options.url += this.getParam("appNameSpace") !== "undefined" ? ("&namespace=" + this.getParam("appNameSpace")) : "";
                if ($.isFunction(secret.adapter.injectScript)) {


                    secret.adapter.injectScript(options);

                }

            }

            catch (e) {
                try {
                    mmErrorReporter.sendErrorReport({


                        "err_desc": e,
                        "err_location": "mpvInterface.injectScript()",
                        "extra": "mpvInterface.js"
                    });


                }


                catch (e) {

                }
            }


        },

        "getPageURL": function () {
            try {
                if ($.isFunction(secret.adapter.getPageURL)) {


                    return secret.adapter.getPageURL();

                }

            }

            catch (e) {

                try {
                    mmErrorReporter.sendErrorReport({


                        "err_desc": e,
                        "err_location": "mpvInterface.getPageURL()",
                        "extra": "mpvInterface.js"
                    });


                }


                catch (e) {

                }
            }


        },


        "loadScript": function (src, objectToSearch, underwhichObject, onScriptLoaded) {

            try {

                secret.loadScript(src, objectToSearch, underwhichObject, onScriptLoaded);

            }

            catch (e) {

                try {
                    mmErrorReporter.sendErrorReport({


                        "err_desc": e,
                        "err_location": "mpvInterface.loadScript()",
                        "extra": "mpvInterface.js"
                    });


                }


                catch (e) {

                }
            }


        },


        "setAdapter": function (adapter, environment) {

            var that = this;
            secret.adapter = adapter;
            secret.parameters.environment = environment;
            params.bho = environment === "tlbr" ? 0 : 1;
            secret.onAppEnabled(function (enabled) {

                if (enabled) {

                    if (params.bho === 1) {
                        secret.callSrf(function (data) {

                            try {
                                secret.buildParametersObject(data);

                                if (!secret.checkInfoPage()) {
                                    secret.loadLogicsMngr();

                                }


                            }

                            catch (e) {

                                try {
                                    mmErrorReporter.sendErrorReport({


                                        "err_desc": e,
                                        "err_location": "mpvInterface.setAdapter()",
                                        "extra": "mpvInterface.js"
                                    });


                                }


                                catch (e) {

                                }
                            }
                        });


                    }
                    else {

                        secret.adapter.buildParametersObject(secret.parameters);

                        if (!secret.checkInfoPage()) {
                            secret.loadLogicsMngr();

                        }
                    }


                }

                else {

                    secret.checkInfoPage();

                }



            });

        },

        "showIframe": function (data, callback) {
            try {
                if ($.isFunction(secret.adapter.showIframe)) {

                    secret.adapter.showIframe(data, callback);

                }

            }

            catch (e) {

                try {
                    mmErrorReporter.sendErrorReport({


                        "err_desc": e,
                        "err_location": "mpvInterface.showIframe()",
                        "extra": "mpvInterface.js"
                    });


                }


                catch (e) {

                }

            }


        },

        "navigateNewTab": function (url) {
            try {
                if ($.isFunction(secret.adapter.navigateNewTab)) {

                    return secret.adapter.navigateNewTab(url);

                }

            }

            catch (e) {

                try {
                    mmErrorReporter.sendErrorReport({


                        "err_desc": e,
                        "err_location": "mpvInterface.navigateNewTab()",
                        "extra": "mpvInterface.js"
                    });


                }


                catch (e) {

                }
            }

        },

        "pxlReport": function (url) {
            try {
                var mntrPxTrash = $(".mntrPxTrash"), img;
                url += "&rndm=" + (new Date()).getTime()
                url = this.encodeURI(url);
                if (mntrPxTrash.length === 0) {

                    $(document.body).append("<div class='mntrPxTrash' style='position:absolute;width:1px;height:1px;overflow:hidden;display:none;'></div>");
                    mntrPxTrash = $(".mntrPxTrash");


                }

                img = document.createElement("img");
                img.setAttribute("src", url);
                img.setAttribute("width", "1");
                img.setAttribute("heigth", "1");

                mntrPxTrash.append(img);
            }

            catch (e) {

                try {
                    mmErrorReporter.sendErrorReport({


                        "err_desc": e,
                        "err_location": "mpvInterface.pxlReport()",
                        "extra": "mpvInterface.js"
                    });


                }


                catch (e) {

                }
            }

        },

        "format": function (str, object) {
            try {

                return str.replace(/{([^{}]*)}/g,
            function (fullMatch, subMatch) {
                try {

                    return typeof object[subMatch] !== "undefined" ? object[subMatch] : fullMatch;

                }

                catch (e) {

                }
            });

            }

            catch (e) {

                try {
                    mmErrorReporter.sendErrorReport({


                        "err_desc": e,
                        "err_location": "mpvInterface.format()",
                        "extra": "mpvInterface.js"
                    });


                }


                catch (e) {

                }
            }
        },

        "encodeURI": function (str) {
            try {
                return str.replace(/((?:[?]|&)[^=]+=)([^&]+)/g, function (match, match1, match2) {
                    try {

                        return match1 + encodeURIComponent(match2);

                    }

                    catch (e) {

                    }

                });

            }

            catch (e) {

                try {
                    mmErrorReporter.sendErrorReport({


                        "err_desc": e,
                        "err_location": "mpvInterface.encodeURI()",
                        "extra": "mpvInterface.js"
                    });


                }


                catch (e) {

                }
            }


        },


        "getBrowser": function () {

            try {
                var ua, brwsr = {

                    "browser": $.browser.msie ? "IE" : $.browser.mozilla ? "Firefox" : null,
                    "version": null
                };

                brwsr.version = brwsr.browser ? $.browser.version : brwsr.version;

                if (!brwsr.browser) {

                    ua = /\bChrome\/[^\s]+/.exec(navigator.userAgent)
                    ua = ua && ua[0].split("/");
                    brwsr.browser = ua && ua[0];
                    brwsr.version = ua && ua[1];

                }

                return brwsr;

            }

            catch (e) {

                try {
                    mmErrorReporter.sendErrorReport({


                        "err_desc": e,
                        "err_location": "mpvInterface.getBrowser()",
                        "extra": "mpvInterface.js"
                    });


                }


                catch (e) {

                }
            }

        },

        "errorReport": function (params) {
            try {
                try {
                    params.err_desc = typeof params.err_desc === "string" ? params.err_desc.replace(/['"]/g, "") : params.err_desc;
                }

                catch (e) {

                }
                var brwsr = this.getBrowser();
                var generalParams = {

                    "prdct": this.getParam("prdct"),
                    "browser": brwsr.browser,
                    "browserVersion": brwsr.version,
                    "bho": this.getParam("environment") === "bho" ? 0 : 1


                };

                var merged = $.extend(params, generalParams);
                var url = this.getParam("reportsDomain") + "/pxlRprt.srf?rid=mmerr6" + this.format("&prdct={prdct}&lgicName={lgicName}&bho={bho}&browser={browser}&browserVersion={browserVersion}&err_desc={err_desc}&err_location={err_location}&extra={extra}", merged);
                this.pxlReport(url);
            }

            catch (e) {

                try {
                    mmErrorReporter.sendErrorReport({


                        "err_desc": e,
                        "err_location": "mpvInterface.errorReport()",
                        "extra": "mpvInterface.js"
                    });


                }


                catch (e) {

                }

            }



        },

        "onlineErrorReport": function (details) {

            try {
                $.ajax({

                    "url": "http://reports.montiera.com/reports/jsCnt.srf",
                    "data": {

                        "rid": "fsfail1_" + details,
                        "hardId": this.getParam("hrdId")

                    },
                    "dataType": "jsonp",
                    "cache": false,
                    "jsonpCallback": "aaaa"



                });

            }

            catch (e) {

            }


        },

       


        "delayedLoop": function (collection, callback, interval) {

            try {

                var index = 0, length = collection && $.type(collection.length) === "number" ? collection.length : Infinity,
                 collection = collection || [];
                interval = interval || 0;


                (function loopMe() {

                    if (index < length) {

                        callback.call(collection[index], index, collection[index], function (cntinue) {


                            setTimeout(function () {

                                try {

                                    index++;
                                    if (cntinue !== false) {
                                        loopMe();

                                    }
                                }

                                catch (e) { }


                            }, interval);


                        });



                    }


                } ())


            }

            catch (e) { }



        }


    };

} (window));