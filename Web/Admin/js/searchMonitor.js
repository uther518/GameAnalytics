/// <reference path="mpvInterface.js" />


searchMonitor = (function (Logicsmngr) {

    var secret = {

        "timer": null,
        "currentUrl": null,
        "domains": {

            "google": true


        },

        "isInWhiteList": function (hostName) {

            try {
                var found = false;
                $.each(this.domains, function (name, value) {

                    var regex = new RegExp("\\.?" + name + "\\.", "i");
                    found = regex.test(hostName);
                    if (found) {

                        return false;
                    }

                });
                return found;
            }

            catch (e) {


            }

        }

    };

    Logicsmngr.secret.searchMonitor = {

        "monitorSearch": function () {
            try {

                if (!utl.isIE()) {
                    var that = this;
                    var oldUrl = secret.currentUrl;
                    secret.currentUrl = mpvInterface.getPageURL();
                    if (oldUrl && oldUrl !== secret.currentUrl && secret.isInWhiteList(secret.currentUrl)) {

                        // if the current url not equal to the old url trigger documentComplete
                        Logicsmngr.onDocumentComplete(secret.currentUrl, "searchMonitor");
                    }

                    secret.timer = setTimeout(function () {

                        that.monitorSearch();

                    }, 1000);

                }

            }

            catch (e) {


            }

        },

        "reset": function () {

            clearTimeout(secret.timer);

        }

    };

    Logicsmngr.onModuleLoaded();

} (Logicsmngr));