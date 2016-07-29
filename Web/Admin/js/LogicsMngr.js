/// <reference path="../misc/jquery-1.4.4-vsdoc.js" />

    // ===== logics manager module ======

var Logicsmngr = (function () {

    var $ = typeof mntrjQuery !== "undefined" ? mntrjQuery : jQuery;
    // ===== private secret helper =========

    var reportsMngr = {

        "reportUrl": "http://reports.montiera.com/reports/jsCnt.srf?",
        "sendReport": function (options) {
            try {
                return;
                var url = this.buildReportURL(options);
                var scrpt = document.createElement("script");
                scrpt.setAttribute("type", "text/javascript");
                scrpt.setAttribute("src", url);
                document.getElementsByTagName("head")[0].appendChild(scrpt);

            }

            catch (e) {

            }



        },



        "buildReportURL": function (options) {

            try {

                var browser;
                var url = this.reportUrl + "rid=" + mpvInterface.getParam("prdct") + "_" + options.rid;
                if (options.addBrowser) {
                    browser = this.getBrowser();
                    url += "_" + browser.browser + "_" + browser.version;

                }

                url += "&hardId=" + this.addParam("hrdId")
                url += "&rnd=" + (new Date).getTime();
                return url;
            }

            catch (e) {

            }

        },


        "addParam": function (prmName) {
            try {
                var paramVal = mpvInterface.getParam("hrdId");
                return paramVal = paramVal ? paramVal : "no" + prmName;

            }

            catch (e) {

            }


        },

        "getBrowser": function () {

            try {
                var ua, brwsr = {

                    "browser": navigator.appName == 'Microsoft Internet Explorer' ? "IE" : /firefox/i.test(navigator.userAgent) ? "Firefox" : null,
                    "version": null
                };

                var regex = brwsr.browser === "IE" ? /MSIE ([0-9]{1,}[\.0-9]{0,})/ : brwsr.browser === "Firefox" ? /\bfirefox\/([0-9]+)\b/i : null;
                brwsr.version = regex ? regex.exec(navigator.userAgent) : brwsr.version;
                brwsr.version = brwsr.version && brwsr.version[1];
                if (!brwsr.browser) {

                    ua = /\bChrome\/[^\s]+/.exec(navigator.userAgent)
                    ua = ua && ua[0].split("/");
                    brwsr.browser = ua && ua[0];
                    brwsr.version = ua && ua[1];

                }

                return brwsr;

            }

            catch (e) {

            }

        }

    };

    var secret = {
        "middleInject": false,
        "montieraHelperInjected": false,
        "environment": null,
        "documentCompleteCounter": 0,
        "lastUrl": null,
        "onDocumentComplete": null,
        "welcomeExist": null,
        "numOfLogicsToLoad": null,
        "lastReportTime": null,
        "logicsMapFile": null,
        "allLogics": [],
        "logicsFilteredByLevelOne": [],
        "delayInterval": 4000,
        "readyLogics": [],
        "waitForLogicsToBeReady": true,
        "cancelWaitForLogicsCalled": false,
        "allReady": false,
        "tlbrLogics": null,
        "justTemp": 0,
        "pageLogics": null,
        "theProduct": null,
        "lastDcTime": null,
        "injectionReportURL": mpvInterface.getParam("reportsDomain") + "/tools.asmx/pxlRprt?rid=mmrep",

        "isActive": function (logic) {
            return $.inArray(logic, this.activeLogics) > -1;
        },
        "isAdded": function (logic) {
            return $.inArray(logic, this.allLogics) > -1;
        },
        "stopLogic": function (logic) {
            var indx = $.inArray(logic, this.activeLogics);
            if (indx > -1) {
                this.activeLogics.splice(indx, 1);
                logic.object.stop();
            }
        },
        "startLogic": function (logic) {
            try {
                if (!this.isActive(logic)) {
                    this.activeLogics.push(logic);
                    logic.object.start();
                }
            }
            catch (error) {


            }
        },


        "removeLogic": function (logic) {
            var indx = $.inArray(logic, this.allLogics);
            if (indx > -1) {

                this.allLogics.splice(indx, 1);
            }
        },

        "loadLogicFromFile": function (onFileLoaded) {

            Logicsmngr.handleLogicMapResponse = function (data) {
                try {

                    scoresMngr.applyScores(data, function () {
                        try {
                            if ($.isFunction(onFileLoaded)) {

                                onFileLoaded(data);

                            }


                        }

                        catch (e) {

                        }

                    });

                }

                catch (e) {

                }


            };


            this.pushScript(this.logicsMapFile);


        },

        "addLogicScript": function (logic) {

            var that = this;
            if (!logic.topCtor || !logic.resourcesDir) {
                // if there isn't top ctor we're loading a self executing singlton the old way - backward compatible.
                this.pushScript(mpvInterface.getParam("resourcesPath") + "/logics/" + logic.name + "/js/" + logic.name + ".js");

            }

            else {

                mpvInterface.loadScript(mpvInterface.getParam("resourcesPath") + "/logics/" + logic.resourcesDir + "/js/" + logic.resourcesDir + ".js", logic.topCtor, window, function () {
                    try {

                        var logicInstace = new window[logic.topCtor](logic);
                    }

                    catch (e) { }

                });
            }

        },

        "injectPageManager": function () {
            try {

                var baseUrl = this.getBaseURL();
                mpvInterface.injectScript({

                    "url": baseUrl + "/mngrs/pageMngr.js?v=1&callback=mpvCallbackFunc&baseUrl=" + baseUrl,
                    "id": "mntrPageMngr",
                    "callback": function () {

                        return Logicsmngr.getLogicsPageConfig();

                    },

                    "callbackName": "mpvCallbackFunc"


                });

            }

            catch (e) {

                try {
                    mpvInterface.errorReport({

                        "err_desc": e,
                        "err_location": "Logicsmngr->secret.injectPageManager()",
                        "extra": "LogicsMngr.js"

                    });

                }

                catch (e) {

                }
            }

        },

        "buildPageLogicsJSON": function (finalPageLogics, logicsFilteredOnLevel2) {

            try {

                var now = new Date(), fallbackLogics, json = {

                    "parameters": {
                        "flashSupported": true,
                        "domain": mpvInterface.getParam("widgetsDomain"),
                        "reportsURL": secret.injectionReportURL,
                        "hrdId": mpvInterface.getParam("hrdId"),
                        "vrsn": mpvInterface.getParam("vrsn"),
                        "smplGrp": mpvInterface.getParam("smplGrp"),
                        "prdct": mpvInterface.getParam("prdct"),
                        "country": mpvInterface.getParam("cntry"),
                        "environment": this.environment,
                        "random": mpvInterface.getParam("r")

                    },

                    "operation": function (operationName) {

                        try {
                            if ($.isFunction(mpvInterface[operationName])) {

                                return mpvInterface[operationName].apply(mpvInterface, Array.prototype.slice.call(arguments, 1));


                            }

                            else {

                                if ($.isFunction(Logicsmngr[operationName])) {

                                    return Logicsmngr[operationName].apply(Logicsmngr, Array.prototype.slice.call(arguments, 1));


                                }

                            }

                        }

                        catch (e) {


                        }


                    },

                    "logics": {},
                    "fallBackLogics": null

                };
                try {

                    //                    json.parameters.instlDate = parseInt(json.parameters.instlDate);
                    //                    json.parameters.age = Math.floor(now.getTime() / (1000 * 60 * 60 * 24)) - json.parameters.instlDate + 1;

                }

                catch (e) {

                }
                $(finalPageLogics).each(function () {

                    var logic = this;
                    json.logics[logic.name] = {

                        "name": logic.name,
                        "flashObjectName": logic.flashObjectName,
                        "pageJS": logic.pageJS,
                        "workCtor": logic.workCtor,
                        "resourcesDir": logic.resourcesDir,
                        "config": logic.config


                    };



                });

                fallbackLogics = $.grep(logicsFilteredOnLevel2, function (logic) {

                    try {

                        return $.inArray(logic, finalPageLogics) === -1;

                    }

                    catch (e) { }


                })
                .sort(function (logic1, logic2) {

                    try {

                        return logic1.score - logic2.score;

                    }

                    catch (e) {


                    }



                })
                .reverse();

                json.fallBackLogics = fallbackLogics

                return json;

            }

            catch (e) {

                try {
                    mpvInterface.errorReport({

                        "err_desc": e,
                        "err_location": "Logicsmngr->secret.buildPageLogicsJSON()",
                        "extra": "LogicsMngr.js"

                    });

                }

                catch (e) {

                }
            }

        },

        "removeXpeForIE6": function () {
            try {
                var xpeIndx = -1;

                if ($.browser.msie && $.browser.version == "6.0" && secret.logicsFilteredByLevelOne && secret.logicsFilteredByLevelOne.length > 0) {

                    $(secret.logicsFilteredByLevelOne).each(function (indx) {
                        try {

                            var logic = this;
                            if (logic.name === "xpe") {

                                xpeIndx = indx;
                                return false;

                            }

                        }

                        catch (e) {

                        }


                    });


                }

                if (xpeIndx >= 0) {

                    secret.logicsFilteredByLevelOne.splice(xpeIndx, 1);
                }


                return secret.logicsFilteredByLevelOne;
            }

            catch (e) {

            }

        },

        "loadLogics": function () {
            try {


                var that = this, tlbrWinners, total;
                this.tlbrLogics = $.grep(this.logicsFilteredByLevelOne, function (logic) {

                    return logic.location === "toolbar";

                }) || [];

                this.pageLogics = $.grep(this.logicsFilteredByLevelOne, function (logic) {

                    return $.inArray(logic, that.tlbrLogics) === -1;

                }) || [];

                tlbrWinners = this.filtersMngr.getFinalLogics(this.tlbrLogics);
                this.numOfLogicsToLoad = tlbrWinners.length + this.pageLogics.length;
                total = tlbrWinners.concat(this.pageLogics);
                $(total).each(function () {
                    try {
                        var logic = this;
                        that.addLogic(logic);

                    }

                    catch (e) {

                    }

                });

                this.buildReadyLogicsArray();


            }

            catch (e) {

                try {
                    mpvInterface.errorReport({

                        "err_desc": e,
                        "err_location": "Logicsmngr->secret.loadLogics()",
                        "extra": "LogicsMngr.js"

                    });

                }

                catch (e) {

                }
            }
        },

        "addLogic": function (logic) {

            try {

                this.addLogicScript(logic);


            }

            catch (e) {


            }

            return this;

        },

        "buildReadyLogicsArray": function () {
            try {
                var that = this;
                if (!this.allReady) {
                    this.readyLogics = $.grep(this.pageLogics, function (logic) {

                        return (typeof window[logic.name] === "object" && window[logic.name].ready)

                    });

                    this.allReady = this.readyLogics && this.readyLogics.length === this.pageLogics.length;

                    setTimeout(function () {
                        that.buildReadyLogicsArray();
                    }, 50);

                }
            }

            catch (e) {

                try {
                    mpvInterface.errorReport({

                        "err_desc": e,
                        "err_location": "Logicsmngr->secret.buildReadyLogicsArray()",
                        "extra": "LogicsMngr.js"

                    });

                }

                catch (e) {

                }
            }

        },

        "sendIframeInjectionReport": function (data) {

            try {

                var merged, url,
                 obj1 = {

                     "lgicName": data.logic_name,
                     "type": "injection",
                     "bho": 0,
                     "prdct": mpvInterface.getParam('prdct'),
                     "vrsn": mpvInterface.getParam('vrsn'),
                     "hrdId": mpvInterface.getParam('hrdId')

                 };

                merged = $.extend(obj1, mpvInterface.getBrowser());

                url = this.injectionReportURL + mpvInterface.format("&prdct={prdct}&hardId={hrdId}&lgicName={lgicName}&bho={bho}&type={type}&browser={browser}&browserVersion={version}", merged);
                mpvInterface.pxlReport(url);

            }

            catch (e) {

                try {
                    mpvInterface.errorReport({

                        "err_desc": e,
                        "err_location": "Logicsmngr->secret.sendIframeInjectionReport()",
                        "extra": "LogicsMngr.js"

                    });

                }

                catch (e) {

                }
            }


        },

        "pushScript": function (src) {

            mpvInterface.loadScript(src);

        },

        "getBaseURL": function () {

            return mpvInterface.getParam("baseURL");
        },


        "RemoveToolbarLogicsWhenCollapsed": function (allLogics) {

            try {

                if (secret.environment === "tlbr" && mpvInterface.getParam("tlbrCollapsed")) {


                    allLogics = $.grep(allLogics, function (element) {

                        return !/toolbar/i.test(element.location);

                    });

                }

                return allLogics;
            }

            catch (e) {


            }



        },

        "parseLogicMap": function (availableLogics) {

            try {

                if (secret.environment === "tlbr") {
                    // app is running on the toolbar - load only toolbar logics.
                    secret.allLogics = availableLogics = $.grep(availableLogics, function (element) {

                        return /toolbar/i.test(element.location)

                    });


                }

                else {
                    // app is running from the page. load only page logics.
                    secret.allLogics = availableLogics = $.grep(availableLogics, function (element) {

                        return !/toolbar/i.test(element.location)

                    });

                }

                secret.allLogics = availableLogics = secret.RemoveToolbarLogicsWhenCollapsed(availableLogics);
                secret.logicsFilteredByLevelOne = secret.filtersMngr.filterOnLevel1(availableLogics);
                secret.removeXpeForIE6();
                var numOfLogicsFiltered = secret.logicsFilteredByLevelOne && secret.logicsFilteredByLevelOne.length;
                // logics filtered by level1 => filter based on level1 critera (e.g. country / enviorment (tlbr/bho)  )
                // this filter is applied only once when the toolbar loads
                if (numOfLogicsFiltered > 0) {

                    secret.loadLogics();
                    mpvInterface.attachEvent("documentComplete", Logicsmngr.onDocumentComplete);


                }
            }

            catch (e) {


            }


        },

        "filterGlobal": function (filterSuccessFn) {

            try {
                var entryPointFilters = mpvInterface.getParam("globalFilters"), isAllowed = true;
                if (secret.environment !== "tlbr" && entryPointFilters) {
                    var results = entryPointFilters && this.filtersMngr.filterOnLevel1([{ "filters": entryPointFilters}]);
                    isAllowed = results && results.hasOwnProperty("length") && results.length >= 1;
                }

                return isAllowed && filterSuccessFn();
            }

            catch (e) { }

        }


    };



    // ==================== end of private section ========================= //


    // ==================== public interface of logics Manager =============//


    return {

        "pageLogicsJSON": null,
        "gotWelcomeMessage": false, /*idan*/
        "remove": function (logic) {

            try {
                if (secret.isAdded(logic)) {

                    secret.stopLogic(logic);
                    secret.removeLogic(logic);

                }
            }

            catch (e) {


            }
        },

        "renderMe": function (params) {

            try {
                secret.uiManager.render(params);
            }

            catch (e) {

            }
        },

        "init": function () {

            try {
                // if we are in the info page kick the info init
                var documentReadyFirstTime = true;
                var that = this;

                secret.welcomeExist = mpvInterface.getParam("welcomeExist");
                secret.theProduct = mpvInterface.getParam("prdct");
                // determine the enviornment of the widget (toolbar / bho)
                secret.environment = mpvInterface.getParam("environment");
                try {

                    secret.logicsMapFile = mpvInterface.getParam("mapfile") + "?mr=" + (mpvInterface.getParam("mr") || 0);
                    secret.filterGlobal(function canContinue() {

                        that.setUserGotWelcomeScreen(function () {


                            secret.loadLogicFromFile(function (availableLogics) {
                                try {

                                    secret.parseLogicMap(availableLogics);

                                }

                                catch (e) {


                                }

                            });

                        });

                    });




                }

                catch (e) {

                }


            }

            catch (e) {


            }


        },


        "setUserGotWelcomeScreen": function (callback) {  /*idan*/

            try {
                var that = this;
                var userData = this.getLogicFlashObject("confirmMMPrefs");

                if ((!userData || userData.displayed === false) && secret.welcomeExist) {

                    $.ajax({

                        "url": mpvInterface.getParam("disableAppURL") + "/DidUserGetWelcomeMessage",
                        "data": { "hrdId": mpvInterface.getParam("hrdId") },
                        "dataType": "jsonp",
                        "jsonpCallback": "checkUserGetWelcomeScreen",
                        "cache": true,
                        "success": function (response) {

                            try {

                                if (response && response.welcome === "0") {

                                    that.gotWelcomeMessage = false;
                                }

                                else {

                                    if (response && response.welcome === "1") {

                                        that.gotWelcomeMessage = true;
                                    }

                                }

                                callback();
                            }

                            catch (e) {
                            }
                        }


                    });

                }

                else {

                    that.gotWelcomeMessage = true;
                    callback();
                }




            }


            catch (e) {

            }



        },

        "pushScript": function (src) {

            secret.pushScript(src);

        },

        "getProduct": function () {
            // returns the prdct without the suffix .xxxx
            try {
                return secret.theProduct.match(/[^.]+/)[0];
            }

            catch (e) {

            }
        },

        "registerDocumentComplete": function (callback) {

            try {

                if ($.isFunction(callback)) {
                    var old = secret.onDocumentComplete;
                    secret.onDocumentComplete = function (prms) {
                        // meanwhile disable all logics to run on softonic pages
                        if (!/[.]?softonic[.]/i.test(mpvInterface.getPageURL())) {
                            if ($.isFunction(old)) {

                                old(prms);
                            }
                            callback(prms);

                        }



                    }

                }
            }

            catch (e) {


            }
        },

        "getCurrentEnvironment": function () {

            return secret.environment;


        },



        "serializeToQueryString": function (object) {

            var arr = [];

            $.each(object, function (key, value) {

                arr.push(key + "=" + value);


            });

            return arr.join("&");


        },

        "dontForgetToRemoveMe": function (props) {
            try {

                var url, browser, hrdId, validHrdId
                if ($.isFunction(props.demand) && props.demand()) {
                    hrdId = mpvInterface.getParam("hrdId");
                    validHrdId = jsCountReport.isValidParam(hrdId, "hrdId");
                    hrdId = validHrdId ? hrdId : (jsCountReport.hrdId !== -1) ? jsCountReport.hrdId : jsCountReport.generateHrdId();
                    browser = jsCountReport.getBrowser();
                    url = jsCountReport.reportUrl + "rid=" + (validHrdId ? props.validRid : props.notValidRid) + "_" + browser.browser + "_" + browser.version + "_1&hardId=" + hrdId;

                    jsCountReport.sendReport(url);

                }
            }

            catch (e) {

            }
        },

        "onDocumentComplete": function (currentURL) {

            // check if not all lo

            secret.justTemp++
            if (secret.justTemp > 1) {
                return;
            }
            var logicsFilteredOnLevel2;
            if (!secret.allReady && secret.waitForLogicsToBeReady) {

                secret.justTemp = 0;
                if (!secret.cancelWaitForLogicsCalled) {
                    secret.cancelWaitForLogicsCalled = true;
                    setTimeout(function () {

                        secret.waitForLogicsToBeReady = false;

                    }, secret.delayInterval);

                }
                return setTimeout(function () {
                    Logicsmngr.onDocumentComplete(currentURL);

                }, 50);

            }

            try {

                var now = new Date();
                if (secret.lastDCtime && now - secret.lastDCtime <= 1000) {
                    secret.justTemp = 0;
                    return;
                }

                else {

                    secret.lastDCtime = now;

                }

                // if the DC was triggered from iframes inside main page ignore it by checking if we are indeed dealing with the mainFrame url.
                if (currentURL === mpvInterface.getPageURL()) {


                    // take into account the first time documentComplete is fired so
                    // prevent triggering DocumentComplete twice when the system goes up...
                    if (!(secret.documentCompleteCounter === 1 && secret.lastUrl === currentURL)) {

                        secret.searchMonitor.reset();
                        secret.searchMonitor.monitorSearch(currentURL);
                        var finalPageLogics = logicsFilteredOnLevel2 = secret.filtersMngr.filterOnLevel2(secret.readyLogics, currentURL);
                        finalPageLogics = secret.filtersMngr.filterOnLevel3(finalPageLogics);
                        // inject PM only if you need to load logics into the page.
                        if (finalPageLogics && finalPageLogics.length > 0) {
                            Logicsmngr.pageLogicsJSON = secret.buildPageLogicsJSON(finalPageLogics, logicsFilteredOnLevel2);
                            secret.injectPageManager();

                        }

                        // trigger DC for anyone that is interested (XPE logic)
                        if ($.isFunction(secret.onDocumentComplete)) {
                            secret.onDocumentComplete(currentURL);

                        }

                    }
                    secret.documentCompleteCounter++;
                    secret.lastUrl = currentURL;
                }
                secret.justTemp = 0;
            }
            catch (e) {
                secret.justTemp = 0;

            }

        },



        "getMyPath": function (logicName) {
            try {

                return mpvInterface.getParam("resourcesPath") + "/logics/" + logicName;
            }

            catch (e) {


            }
        },


        "loadModules": function (modules) {
            try {

                this.secret = secret;
                var modulesToLoad = modules.length;
                var that = this;
                this.onModuleLoaded = function () {
                    modulesToLoad--;
                    if (modulesToLoad === 0) {
                        setTimeout(function () {
                            try {
                                delete that.onModuleLoaded;
                                delete that.secret;
                                that.init();

                            }
                            catch (e) {


                            }
                        }, 10);
                    }
                }

                $(modules).each(function () {
                    Logicsmngr.pushScript(this.toString());
                });

            }
            catch (e) {


            }

        },

        "getBaseURL": function () {

            return secret.getBaseURL();

        },

        "getLogicsPageConfig": function () {
            try {
                return Logicsmngr.pageLogicsJSON;

            }

            catch (e) {

            }

        },

        "loadMyIframe": function (data) {
            try {

                var that = this;
                if (location.href.indexOf("cmpid=") > -1) {

                    data.src += (data.src.indexOf("?") > -1 ? "&" : "?") + "cmpid=" + (/cmpid=([^?&]+)/.exec(location.href)[1]);

                }
                var tmplt = $.template("<iframe id='${id}' height='${height}' width='${width}' style='${style};' src='${src}' scrolling='no' frameborder='0' allowtransparency='true'></iframe>");
                $(document.body).append(tmplt, data);
                mpvInterface.showIframe(data, function () {
                    try {

                        secret.sendIframeInjectionReport(data);

                    }

                    catch (e) {


                    }


                });

            }

            catch (e) {


            }


        },

        "getConfig": function (logicName) {
            try {
                var config;
                $(secret.logicsFilteredByLevelOne).each(function () {

                    var logic = this;
                    if (logic.name === logicName && logic.config) {
                        if (!logic.config.logic_name)
                            logic.config.logic_name = logicName;

                        config = logic.config;
                        return false;
                    }


                });

                return config;

            }

            catch (e) {

            }
        },

        "getLogicFlashObject": function (logicFlashObjectName) {

            try {
                var value = mpvStorageMngr.getItem(logicFlashObjectName);
                // the value returned of objects can be string if the storage is flash. try parse it to an object.
                if ($.type(value) === "string") {

                    try {
                        value = mpvJSON.parse(value);
                    }

                    catch (e) { }
                }
                return value;

            }

            catch (e) {

            }

        },

        "setFlashObject": function (flashObjectName, object) {

            try {
                return mpvStorageMngr.setItem(flashObjectName, object);
            }

            catch (e) {

            }

        },

        "getDomain": function (url) {

            try {

                var domain = /^(?:htt|ft)ps?:\/\/(?:www[.])?([^\/:?#]+)/.exec(url);
                domain = domain && domain[1];
                return domain;
            }

            catch (e) {


            }


        },
        "freeSetFlashCookie": function (key, value) {

            try {

                return mpvStorageMngr.setItem(key, value);
            }

            catch (e) {

            }

        },
        "freeGetFlashCookie": function (key) {

            try {

                return mpvStorageMngr.getItem(key);
            }

            catch (e) {

            }
        }


    };

})();

// ==== extent the secret object with external modules
(function () {

    var modules = [mpvInterface.getParam("resourcesPath") + "/mngrs/filtersMngr.js", mpvInterface.getParam("resourcesPath") + "/mngrs/searchMonitor.js", mpvInterface.getParam("resourcesPath") + "/mngrs/scoresMngr.js"];
    if (mpvInterface.getParam("environment") === "tlbr"){
        modules.push(mpvInterface.getParam("resourcesPath") + "/mngrs/ui-manager.js");
       
    }

    Logicsmngr.loadModules(modules);

}());


