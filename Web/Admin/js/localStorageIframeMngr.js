try{

    var mngr = {

        "param": null,
        "connectionEstablished": false,
        "init": function () {
            try {
             
                var that = this;
                this.params = this.deSerializeString(location.href.match(/\?(.+)/)[1]);
                this.loadAllFiles(function () {
                    if (that.params.operation) {
                        // if operation field was specified in the url it means the iframe was loaded from info page when it recognized that the platform wasn't loaded.
                        // this means this iframe was loaded to save enable/disable state to local storage only.
                        that.performStandAloneOperation(that.params.operation);
                    }
                    else {
                        // operation field wasn't specified. the iframe was loaded to support localstorage datastore to the platform.
                        that.establishCom();
                    }
                });
            }

            catch (e) { }


        },

        "loadAllFiles": function (cb) {

            try {
                var that = this;
                this.loadScript("../misc/postmessage.min.js", "postMessage", $, function () {

                    that.loadScript("../../3rdparty/json2.min.js", "mpvJSON", window, function () {

                        cb();

                    });

                });
            }

            catch (e) { }


        },

        "establishCom": function () {

            try {
                $.receiveMessage($.proxy(this.receiveEvent, this), document.referrer);
            }

            catch (e) { }

        },

        "receiveEvent": function (event) {

            try {
                // receieve the raw string event from the iframe and translate it to a function call with parameters.
                var message, func, funcParams;
                message = event.data.split("_");
                if ($.isFunction(this[message[0]])) {
                    func = this[message[0]];
                    funcParams = message.slice(1);
                    func.apply(this, funcParams);

                }

            }

            catch (e) { }

        },

        "handShake": function () {

            try {

                if (!this.connectionEstablished) {
                    this.connectionEstablished = true;
                    this.postMessage("connectionRecieved");
                    this.getJSONData();

                }
            }

            catch (e) { }


        },

        "getJSONData": function () {

            try {

                var jsonString = localStorage.getItem("mpvJSON") || "{}";
                this.postMessage("getMainJSON_" + jsonString);
            }

            catch (e) { }


        },

        "saveToStorage": function (data) {

            try {

                localStorage.setItem("mpvJSON", data);
            }

            catch (e) { }

        },

        "postMessage": function (msg) {

            try {
                try {

                    msg = this.params.eventPrefix + "_" + msg;
                    $.postMessage(
                          msg,
                          document.referrer,
                          parent
                        );
                }

                catch (e) { }
            }

            catch (e) { }

        },

        "deSerializeString": function (qry_) {

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

        "loadScript": function (src, objectToSearch, underwhichObject, callback) {

            try {

                try {
                    var prefix = src.indexOf("?") > -1 ? "&" : "?";
                    src += this.params["random"] !== "undefined" ? (prefix + "random=" + this.params["random"]) : "";
                    src += this.params["appNameSpace"] !== "undefined" ? ("&namespace=" + this.params["appNameSpace"]) : "";

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

        "performStandAloneOperation": function (operation) {

            try {
                // this function set enable/disable state on localstorage for wizebar mpvStatus if and only if the platform wasn't loaded on info page!
                var mainJSON = localStorage.getItem("mpvJSON") || "{}";
                mainJSON = mainJSON && mpvJSON.parse(mainJSON);
                mainJSON.mpvStatus = operation;
                this.saveToStorage(mpvJSON.stringify(mainJSON));
            }

            catch (e) { }

        }

    };
   
    mngr.init();

}


catch(e){}