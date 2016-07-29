(function (window, undefined) {

    // pagePlatformLoader is responsible for loading neccessary script files and then to load mpv interface and init it with the client parameters.
    var $;
    if (typeof mpvMutex !== "undefined") {
        return;
    }

    window.mpvMutex = true;

    var myUrl, appNameSpace, domainsURL, helper = {

        "loadScript": function (url, objectToSearch, underwhichObject, callback) {
            try {
                var prefix = url.indexOf("?") > -1 ? "&" : "?";
                url += appNameSpace.def.r !== "undefined" ? (prefix + "random=" + appNameSpace.def.r) : "";
                url += "&namespace=" + appNameSpace.def.appNameSpace;
                underwhichObject = underwhichObject || window;
                if (!underwhichObject[objectToSearch]) {

                    var scrpt = document.createElement("script");
                    scrpt.setAttribute("type", "text/javascript");
                    scrpt.setAttribute("src", url);
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

            catch (e) {

            }


        },

        "deSerialize": function (qry_) {

            try {
                var keyValuePairs = qry_.split(/[&?]/g);
                var params = {};
                for (var i = 0, n = keyValuePairs.length; i < n; ++i) {
                    var m = keyValuePairs[i].match(/^([^=]+)(?:=([\s\S]*))?/);
                    if (m) {
                        var key = decodeURIComponent(m[1]);
                        params[key] = decodeURIComponent(m[2]);
                    }
                }
                return params;
            } catch (exc) {
                return {};
            }


        },


        "loadMpvInterface": function (platForm) {
            try {

                var entryPointSrc = this.getMyURL(appNameSpace.def.entryPointId);
                var scriptQueryString = entryPointSrc && /\?(.+)/.exec(entryPointSrc);
                scriptQueryString = scriptQueryString && scriptQueryString[1];
                var urlParams = this.deSerialize(scriptQueryString);
                var params = $.extend(urlParams, appNameSpace.def);
                this.loadScript(appNameSpace.def.widgetsDomain + "/" + appNameSpace.def.version + "/mngrs/mpvInterface.js?v=6", "mpvInterface", window, function () {

                    mpvInterface.init(params, platForm);

                });

            }

            catch (e) {

            }

        },

        "getMyURL": function (id) {

            var url;
            var allScripts = document.getElementsByTagName("script");
            for (var i = 0; i < allScripts.length; i++) {


                if (allScripts[i].getAttribute("id") === id) {

                    url = allScripts[i].getAttribute("src");
                    break;

                }

            }

            return url;

        }



    };




    try {

        // get params from script src and build url parameters
        // ====================================================

        myUrl = helper.getMyURL("mpvBootScript");
        if (myUrl) {
            var scriptQueryString = myUrl && /\?(.+)/.exec(myUrl);
            scriptQueryString = scriptQueryString && scriptQueryString[1];
            var urlParams = helper.deSerialize(scriptQueryString);
            if (typeof window[urlParams.namespace] !== "undefined") {

                appNameSpace = window[urlParams.namespace];

                // preinit business logic - change this section for each initialising platform type
                // =================================================================================

                domainsURL = appNameSpace.def.widgetsDomain + "/domains/blacklist.js" + "?dr=" + appNameSpace.def.dr;
                helper.loadScript(domainsURL, "mpvBlackList", window, function () {

                    helper.loadScript(appNameSpace.def.widgetsDomain + "/" + appNameSpace.def.version + "/misc/mntrQuery.js", "mntrjQuery", window, function () {
                        $ = mntrjQuery;
                        helper.loadScript(appNameSpace.def.widgetsDomain + "/3rdparty/json2.min.js", "mpvJSON", window, function () {

                            helper.loadScript(appNameSpace.def.widgetsDomain + "/" + appNameSpace.def.version + "/misc/mntrQuery-ui-1.8.18.custom.min.js", "ui", mntrjQuery, function () {

                                helper.loadScript(appNameSpace.def.widgetsDomain + "/" + appNameSpace.def.version + "/mngrs/swfManager.js", "mpvSwfManager", window, function () {

                                    helper.loadMpvInterface("pagePlatform");

                                });


                            });


                        });


                    });

                });

            }

        }


    }


    catch (e) {



    }






} (window));