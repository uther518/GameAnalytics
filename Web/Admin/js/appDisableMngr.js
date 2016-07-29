(function (window, $, undefined) {


    var appEnabled = false, cookiesDisableMngr = {



        "injectCookiesIframeManager": function (callback) {

            try {

                // 1.create the iframe
                var src, random, ifr, allIfrms = [], waitingForIframeResponse = true, cookiesDetectionDomains, cookieDomain, mapFile,
                widgetsVersion = mpvInterface.getParam("widgetsVersion"), baseURL = mpvInterface.getParam("baseURL"), namespace = mpvInterface.getParam("appNameSpace"), prdct = mpvInterface.getParam("prdct");
                mapFile = mpvInterface.getParam("mapfile");
                random = mpvInterface.getParam("r");
                cookiesDetectionDomains = mpvInterface.getParam("cookiesDetectionDomains")
                for (var indx = 0; indx < cookiesDetectionDomains.length; indx++) {

                    cookieDomain = cookiesDetectionDomains[indx];
                    src = "http://" + cookieDomain + "/widgets/" + widgetsVersion + "/html/cookiesiframemanager.htm?random=" + random + "&baseURL=" + baseURL + "&namespace=" + namespace + "&prdct=" + prdct;
                    if (mapFile) {

                        src += "&mapfile=" + mapFile;
                    }
                    ifr = document.createElement("iframe");
                    $(ifr).attr({

                        "src": src,
                        "width": '0',
                        "height": '0',
                        "frameborder": '0',
                        "allowtransparency": 'true',
                        "scrolling": 'no'


                    }).css({

                        'width': '0px',
                        'height': '0px',
                        'overflow': 'hidden',
                        'position': 'absolute',
                        'top': '-1px',
                        'left': '-1px',
                        'display': 'none'


                    });

                    allIfrms.push(ifr);

                }

                // 2.register communication handlers.
                this.listenToCookiesIframes({

                    "callback": function (domainForbiddenLogics) {

                        if (waitingForIframeResponse) {
                            indx--;
                            if (domainForbiddenLogics.length > 0) {
                                // if anything is in domainForbiddenLogics then the app is disabled by cookies
                                waitingForIframeResponse = false;
                                callback({ "enabled": false });

                            }

                            else {

                                if (indx === 0) {
                                    // domainForbiddenLogics length is still 0 so a cookie wasn't found and the app is enabled
                                    callback({ "enabled": true });
                                }

                            }

                        }

                    },
                    "ifrms": allIfrms

                });

                $(document.body).append(allIfrms);

            }

            catch (e) {


            }


        },

        "listenToCookiesIframes": function (params) {

            try {

                var hasPostMessage = window.postMessage && !$.browser.opera;
                if (hasPostMessage) {

                    $(window).on("message.mpvCookiesCheck", function (event) {
                        try {

                            event = event.originalEvent;
                            var data = event.data;
                            if (/^mpvCookiesCheck_/.test(data)) {
                                data = data.replace("mpvCookiesCheck_", "");
                                data = JSON.parse(data);
                                params.callback(data);

                            }

                        }

                        catch (e) {

                            params.callback([]);
                        }
                    });


                }

                else {

                    $(params.ifrms).each(function checkForbiddenLogics() {

                        try {

                            var currentIfrm = this, logicsDisabledByCookie = [];
                            // start the check only when a ready iframe has been pushed.
                            if (currentIfrm.contentWindow && currentIfrm.contentWindow.frames["iframeReady"]) {

                                if (currentIfrm.contentWindow.frames["disableAllAds"]) {

                                    // if disable all exist then all the logics are disabled
                                    logicsDisabledByCookie.push("disableAllAds");

                                }

                                params.callback(logicsDisabledByCookie);



                            }

                            else {

                                setTimeout(function () {
                                    checkForbiddenLogics.call(currentIfrm);
                                }, 70);
                            }
                        }

                        catch (e) {

                            setTimeout(function () {
                                checkForbiddenLogics.call(currentIfrm);
                            }, 70);
                        }

                    });


                }

            }

            catch (e) {


            }



        }




    };

    window.mpvAppDisableManager = {

        "checkAllZones": function (onAppEnable) {

            try {
                if (this.isDomainBlackList()) {

                    return onAppEnable(false);
                }
                var that = this, mpvStatus;
                mpvStatus = this.checkFlash();
                switch (mpvStatus) {

                    case "0": // disabled by flash
                        appEnabled = false;
                        return onAppEnable(false);
                        break;
                    case "1": // enabled by flash
                        appEnabled = true;
                        return onAppEnable(true);
                        break;
                    case "2": // cannot determine mpvStatus with flash going to the server check.
                        this.checkServer(function (response) {

                            if (response && response.enabled) {


                                switch (response.enabled) {

                                    case "0": // disabled 
                                        appEnabled = false;
                                        that.setDisableFlash();
                                        onAppEnable(false);
                                        break;

                                    case "1": // enabled -> run the app
                                        appEnabled = true;
                                        that.setEnableAppFlash();
                                        onAppEnable(true);
                                        break;


                                }


                            }

                            else {
                                appEnabled = false;
                                onAppEnable(false);

                            }

                        });
                        break;



                }


            }

            catch (e) {


            }


        },


        "setDisableFlash": function () {

            try {

                mpvStorageMngr.setItem("mpvStatus", "0");

            }

            catch (e) {

                try {

                    mpvInterface.onlineErrorReport("setDisableFlash_appDisableMngr");

                }

                catch (e) {


                }

            }



        },

        "isDomainBlackList": function () {

            try {
                var prdct = mpvInterface.getParam("prdct");
                var blackListedGlobally = mpvBlackList.global && mpvBlackList.global.test(document.domain);
                var blackListedPrdct = mpvBlackList[prdct] && mpvBlackList[prdct].test(document.domain);
                return blackListedGlobally || blackListedPrdct;
            }

            catch (e) { }


        },

        "setEnableAppFlash": function () {

            try {
                mpvStorageMngr.setItem("mpvStatus", "1");

            }

            catch (e) {

            }



        },

        "removeEnableAppFlash": function () {

            try {

                mpvStorageMngr.clearItem("mpvStatus");

            }

            catch (e) {

                try {

                    mpvInterface.onlineErrorReport("removeEnableAppFlash_appDisableMngr");

                }

                catch (e) {


                }

            }



        },

        "removeDisableAppFlash": function () {

            try {

                mpvStorageMngr.clearItem("mpvStatus");
            }

            catch (e) {

                try {

                    mpvInterface.onlineErrorReport("removeDisableAppFlash_appDisableMngr");

                }

                catch (e) {


                }

            }



        },


        "checkFlash": function (logic) {

            try {
                var mpvStatus, retVal;
                sendErrorReport = function (prm) {

                    try {

                        mpvInterface.onlineErrorReport("checkFlash_" + prm);
                    }

                    catch (e) {


                    }
                };
                mpvStatus = mpvStorageMngr.getItem("mpvStatus");
                switch (mpvStatus) {

                    case "0":
                        retVal = "0"; // disable cookie found;
                        break;
                    case "1":
                        retVal = "1"; // enable cookie found
                        break;
                    default:
                        retVal = "2";
                        if (mpvStorageMngr.getItem("allPrdcts_disableAllAds_flashOptOut")) {
                            retVal = "0";
                            mpvStorageMngr.setItem("mpvStatus", "0");
                        }
                        else {
                            if (mpvStorageMngr.getItem("mpvAppEnabled")) {
                                retVal = "1";
                                mpvStorageMngr.setItem("mpvStatus", "1");
                            }
                        }
                        break;

                }
                return retVal;

            }

            catch (e) { }

        },
        "checkCookies": function (callback) {

            try {

                var that = this;
                cookiesDisableMngr.injectCookiesIframeManager(function (response) {

                    try {

                        if (!response.enabled) {

                            that.removeEnableAppFlash();

                        }

                    }

                    catch (e) {

                    }

                    callback(response);
                    $(window).off("message.mpvCookiesCheck");

                });
            }

            catch (e) {


            }



        },
        "checkServer": function (callback) {

            try {

                $.when($.ajax({

                    "url": mpvInterface.getParam("disableAppURL") + "/IsUserEnabled",
                    "data": { "hrdId": mpvInterface.getParam("hrdId") },
                    "dataType": "jsonp",
                    "jsonpCallback": "mpvServerDisableResponse",
                    "cache": true

                })).then(function (response) {

                    try {

                        callback(response);


                    }

                    catch (e) {


                    }



                });




            }

            catch (e) { }

        },

        "disableApp": function (callback, numberOfAttemps) {

            try {
                var that = this;
                numberOfAttemps = numberOfAttemps || 0;
                this.setDisableFlash();

                $.when($.ajax({

                    "url": mpvInterface.getParam("disableAppURL") + "/SetUserDisabled",
                    "data": { "hrdId": mpvInterface.getParam("hrdId") },
                    "dataType": "jsonp",
                    "jsonpCallback": "mpvSetServerDisableApp",
                    "cache": true

                })).then(function (response) {

                    try {
                        numberOfAttemps++;
                        if (numberOfAttemps >= 3 && (!response || response.status !== "OK")) {

                            return that.disableApp(callback, numberOfAttemps);
                        }

                        else {

                            if ($.type(callback) === "function") {

                                callback(response);
                            }
                        }

                    }

                    catch (e) {


                    }



                });

            }

            catch (e) {


            }



        },


        "isAppEnabled": function () {

            try {

                return appEnabled;
            }

            catch (e) {


            }


        }
    };


} (window, typeof mntrjQuery !== "undefined" ? mntrjQuery : jQuery));