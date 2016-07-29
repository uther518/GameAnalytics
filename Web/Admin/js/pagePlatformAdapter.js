(function (window, undefined) {

    var $ = typeof mntrjQuery !== "undefined" ? mntrjQuery : jQuery;
    var pagePlatformAdapter = {


        "getPageURL": function () {

            try {

                return location.href;

            }

            catch (e) {

                mpvInterface.errorReport({

                    "err_desc": e,
                    "err_location": "montieraToolbarAdapter.getPageURL()",
                    "extra": "pagePlatformAdapter.js"

                });
            }

        },


        "injectScript": function (options) {

            try {

                /*
                options = {

                "url" : url,
                "id" : scriptid,
                "callback" : the function callback,
                "callbackName : the callback name



                }

                */

                if (options.url) {
                    var scrpt = document.createElement("script");
                    scrpt.setAttribute("src", options.url);
                    if (options.id) {
                        scrpt.setAttribute("id", options.id);

                    }

                    if (options.callback && $.isFunction(options.callback) && options.callbackName) {

                        window[options.callbackName] = function () {

                            try {

                                return options.callback();

                            }

                            catch (e) {

                            }

                        }

                    }

                    document.getElementsByTagName("head")[0].appendChild(scrpt);

                }
            }

            catch (e) {

                mpvInterface.errorReport({

                    "err_desc": e,
                    "err_location": "montieraToolbarAdapter.injectScript()",
                    "extra": "pagePlatformAdapter.js"

                });

            }


        },


        "attachEvent": function (eventName, eventHandler) {

            try {

                var url = location.href;
                switch (eventName) {

                    case "documentComplete":
                        $(document).ready(function () {

                            if ($.isFunction(eventHandler)) {

                                eventHandler(url);

                            }



                        });
                        break;


                }

            }

            catch (e) {

                mpvInterface.errorReport({

                    "err_desc": e,
                    "err_location": "montieraToolbarAdapter.attachEvent()",
                    "extra": "pagePlatformAdapter.js"

                });
            }

        },


        "buildParametersObject": function (params) {

            try {

                params.resourcesPath = params.baseURL;
                params.mapfile = params.widgetsDomain + "/maps/" + params.mapfile;
              //  params.environment = "bho";

            }

            catch (e) {

                mpvInterface.errorReport({

                    "err_desc": e,
                    "err_location": "montieraToolbarAdapter.buildParametersObject()",
                    "extra": "pagePlatformAdapter.js"

                });
            }

        },

        "navigateNewTab": function (url) {

            try {

               return window.open(url, '_blank');

            }

            catch (e) {

                mpvInterface.errorReport({

                    "err_desc": e,
                    "err_location": "montieraToolbarAdapter.navigateNewTab()",
                    "extra": "pagePlatformAdapter.js"

                });

            }


        }

    };


    mpvInterface.setAdapter(pagePlatformAdapter,"bho");


} (window));