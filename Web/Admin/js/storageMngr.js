try {
  
    (function (window, $, undefined) {

        try {
            //#region localStorageMngr
            var lsMngr =
              {
                  "iframe": null,
                  "mainJSON": null,
                  "eventPrefix": "mpvStorageIframe",
                  "eventPrefixRegex": /^mpvStorageIframe_/,
                  "handShakeTimeOut": null,
                  "init": function () {

                      try {

                          var that = this;
                          if (window.postMessage) {
                              // post message is supported. safe to use localstorage
                              root.loadScript(root.baseURL + "/misc/postmessage.min.js", "postMessage", $, function () {
                                  try {
                                      var ifrParams = {
                                          "random": window.mpvInterface && mpvInterface.getParam("r") || 1000,
                                          "eventPrefix": that.eventPrefix,
                                          "appNameSpace": window.mpvInterface && mpvInterface.getParam("appNameSpace") || "storageStdAloneNS"
                                      };
                                      that.iframe = that.createTheIframe(root.baseURL + "/html/storageiframemanager.htm?" + $.param(ifrParams));
                                      that.establishCommunication();
                                  }

                                  catch (e) { }


                              });

                          }

                          else {
                              // post message is not supported. cannot go on with local storage.
                              root.activeMngrComplete(false);
                          }


                      }

                      catch (e) { }

                  },

                  "createTheIframe": function (src) {

                      try {

                          return $("<iframe />", {

                              "src": src,
                              "scrolling": 'no',
                              "frameborder": '0',
                              "framespacing": '0',
                              "allowtransparency": 'true',
                              "marginwidth": '0',
                              "marginheight": '0',
                              "width": "0",
                              "height": "0"

                          })
                          .appendTo(document.body)
                          .css({
                              "display": "none",
                              "width": 0,
                              "height": 0,
                              "background-color": "transparent",
                              "position": "absolute"
                          })
                          .get(0);
                      }

                      catch (e) { }

                  },
                  "establishCommunication": function () {

                      try {
                          // establishes the main communication with the iframe

                          var that = this, handShake = function () {

                              try {
                                  that.postMessage("handShake");
                                  that.handShakeTimeOut = setTimeout(handShake, 1000);
                              }
                              catch (e) { }

                          };
                          $.receiveMessage($.proxy(this.receiveEvent, this), $(this.iframe).attr("src"));
                          handShake();
                      }

                      catch (e) { }


                  },
                  "receiveEvent": function (event) {

                      try {
                          // receieve the raw string event from the iframe and translate it to a function call with parameters.
                          var message, func, funcParams;
                          if (this.eventPrefixRegex.test(event.data)) {
                              message = event.data.replace(this.eventPrefixRegex, "").split("_");
                              if ($.isFunction(this[message[0]])) {
                                  func = this[message[0]];
                                  funcParams = message.slice(1);
                                  func.apply(this, funcParams);

                              }

                          }
                      }

                      catch (e) { }

                  },
                  "postMessage": function (msg) {

                      try {
                          $.postMessage(
                          msg,
                          this.iframe.getAttribute("src"),
                          this.iframe.contentWindow
                        );
                      }

                      catch (e) { }

                  },
                  "connectionRecieved": function () {

                      try {
                          // clear the handShake interval when the communication is established
                          clearTimeout(this.handShakeTimeOut);
                      }
                      catch (e) { }

                  },
                  "getMainJSON": function (data) {

                      try {

                          try {// use only mpvJSON on the main data to avoid sites like cnn.com using corrupted JSON parser and destroys the data.
                              this.mainJSON = mpvJSON.parse(data);
                          }

                          catch (e) {

                              this.mainJSON = {};
                          }
                          root.activeMngrComplete(true);


                      }

                      catch (e) { }

                  },
                  "getItem": function (key) {

                      try {

                          return this.mainJSON[key];
                      }

                      catch (e) { }

                  },
                  "setItem": function (key, value) {

                      try {

                          this.mainJSON[key] = value;
                          this.save();
                      }

                      catch (e) { }

                  },
                  "clearItem": function (key) {

                      try {
                          delete this.mainJSON[key];
                          this.save();
                      }

                      catch (e) { }

                  },
                  "save": function () {

                      try {
                          // use only mpvJSON on the main data to avoid sites like cnn.com using corrupted JSON parser and destroys the data.
                          this.postMessage("saveToStorage_" + mpvJSON.stringify(this.mainJSON));

                      }

                      catch (e) { }

                  }
              },
            //#endregion
            //#region flashMngr
            flashMngr = {
                "flash": null,
                "isMontieraToolbar": function () {

                    try {
                        return window.escrt && $.isFunction(window.escrt.addHndlr);
                    }

                    catch (e) { }

                },
                "init": function (callback) {

                    try {
                        var that = this,
                        callback = callback || $.proxy(root.activeMngrComplete, root), onFlashLoaded = function (data) {

                            try {
                                that.flash = data && data.supported && data.fcookies;
                                callback(data && data.supported);
                            }

                            catch (e) { }

                        };

                        if (window.mpvInterface) {
                            mpvInterface.loadSwfStore(onFlashLoaded);

                        }

                        else {

                            this.loadDependencies(onFlashLoaded)
                        }
                    }

                    catch (e) { }
                },

                "loadDependencies": function (cb) {

                    try {
                        if (this.isMontieraToolbar()) { // we do not load flash into the toolbar. this means the widget will not work on ie <= 7 on the toolbar.

                            return cb(false);
                        }

                        root.loadScript(root.baseURL + "/mngrs/swfManager.js", "mpvSwfManager", window, function () {

                            try {

                                mpvSwfManager.init(root.baseURL + "/..", function (data) {

                                    cb(data);

                                });
                            }

                            catch (e) { };

                        });
                    }

                    catch (e) { }

                },

                "getItem": function (key) {

                    try {

                        if (this.flash) {

                            return this.flash.get(key);
                        }
                    }

                    catch (e) { }
                },
                "setItem": function (key, value) {

                    try {
                        if (this.flash) {
                            if (!/^(?:boolean|number|string)$/.test($.type(value))) {
                                // if value is not one of the primitives assume its an object try stringify it.
                                try {
                                    value = mpvJSON.stringify(value);
                                }

                                catch (e) { }

                            }
                            return this.flash.set(key, value);
                        }
                    }

                    catch (e) { }

                },
                "getAll": function () {

                    try {

                        if (this.flash) {

                            return this.flash.getAll();
                        }
                    }

                    catch (e) { }

                },
                "clearItem": function (key) {

                    try {

                        if (this.flash) {

                            return this.flash.clear(key);
                        }
                    }

                    catch (e) { }

                }
            },
            //#endregion
            //#region externalInterface 
             root = {
                 "disableAppURL": "http://boot.wizebar.com/boot/tools.asmx",
                 "flashBackwardCompat": false,
                 "activeMngr": null,
                 "initialized": false,
                 "readyCallback": null,
                 "mngrs": null,
                 "baseURL": null,
                 "loadScript": function (src, objectToSearch, underwhichObject, callback) {

                     try {

                         try {
                             if (window.mpvInterface) {
                                 var prefix = src.indexOf("?") > -1 ? "&" : "?";
                                 src += mpvInterface.getParam("r") !== "undefined" ? (prefix + "random=" + mpvInterface.getParam("r")) : "";
                                 src += mpvInterface.getParam("appNameSpace") !== "undefined" ? ("&namespace=" + mpvInterface.getParam("appNameSpace")) : "";
                             }
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

                                     }());

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

                 "getBaseURL": function (cb) {

                     try {
                         var searchCount = 200;
                         if (window.mpvInterface) {

                             return cb(mpvInterface.getParam("baseURL"));
                         }

                         else {

                             // grab it from the currentScriptURL
                             (function getBaseURL() {

                                 try {
                                     searchCount--;
                                     var allScripts = document.getElementsByTagName("script"), len = allScripts.length, matchingScriptSrc, baseURL;
                                     for (var i = 0; i < len && !matchingScriptSrc ; i++) {

                                         matchingScriptSrc = allScripts[i] && /storagemngr\.js/i.test(allScripts[i].src) && allScripts[i].src;

                                     }

                                     if (matchingScriptSrc) {

                                         baseURL = /(.+)?\/mngrs\/storagemngr\.js/i.exec(matchingScriptSrc)[1];
                                         return cb(baseURL);

                                     }

                                     else {

                                         searchCount > 0 && setTimeout(getBaseURL, 30);

                                     }


                                 }

                                 catch (e) { }


                             }());
                         }
                     }

                     catch (e) { }

                 },
                 "init": function (readyCallback) {

                     try {
                         var that = this;
                         if (!this.initialized) {

                             this.initialized = true;
                             this.mngrs = this.getMngrsByPriority();
                             this.readyCallback = readyCallback;
                             this.getBaseURL(function (baseURL) {
                                 try {
                                     that.baseURL = baseURL;
                                     that.loadScript(that.baseURL + "/misc/mntrQuery.js", "mntrjQuery", window, function () {

                                         that.loadScript(that.baseURL + "/../3rdparty/json2.min.js", "mpvJSON", window, function () {
                                             $ = mntrjQuery;
                                             that.mngrs[0].init();

                                         });

                                     });

                                 }
                                 catch (e) { }
                             });

                         }
                     }

                     catch (e) { }


                 },
                 "getMngrsByPriority": function () {

                     try {
                         // this function should support future storage enviornments (proxy,3rdpary injectors)..etc.
                         return [lsMngr, flashMngr];
                     }

                     catch (e) { }

                 },
                 "activeMngrComplete": function (success) {

                     try {

                         var that = this, mngr = this.mngrs.shift();
                         if (success) {
                             // the active manager is ready. fire the outer callback.
                             this.activeMngr = mngr;
                             if (this.activeMngr !== flashMngr && this.flashBackwardCompat) {
                                 // if we're in flash compat mode and localstorage mngr loaded successfully load the flash mngr and then run the callback.
                                 this.importFromFlash(function () {

                                     that.readyCallback();
                                 });
                             }

                             else {
                                 // all ready. firecallback
                                 this.readyCallback();
                             }

                         }

                         else {
                             // unable to work with the mngr, try fallback to next storage mngr. if no more mngrs are available giveup and call the callback with false param.
                             this.mngrs[0] ? this.mngrs[0].init() : this.readyCallback(false);
                         }

                     }

                     catch (e) { }
                 },
                 "getItem": function (key) {

                     try {

                         return this.activeMngr.getItem(key);
                     }

                     catch (e) { }

                 },
                 "setItem": function (key, value) {

                     try {

                         return this.activeMngr.setItem(key, value);
                     }

                     catch (e) { }

                 },
                 "clearItem": function (key) {

                     try {
                         return this.activeMngr.clearItem(key);
                     }

                     catch (e) { }
                 },
                 "importFromFlash": function (callback) {

                     try {
                         // this function is deprecated.
                         var that = this;
                         // load the flash only if really cannot find the value for "mpvStatus"
                         if (typeof this.activeMngr.getItem("mpvStatus") === "undefined") {
                             flashMngr.init(function () {

                                 try {
                                     // try get the value from the flash
                                     var mpvStatus = flashMngr.getItem("mpvStatus");
                                     if (mpvStatus) {
                                         // and if it was found save it to local storage.
                                         that.activeMngr.setItem("mpvStatus", mpvStatus);
                                     }

                                     callback();
                                 }

                                 catch (e) { }

                             });

                         }

                         else {
                             // value for mpvStatus was found outside of flash storage. no need to load flash.
                             callback();
                         }
                     }

                     catch (e) { }

                 }
             },
            //#endregion
            //#region API

            api = {
                "storage": {
                   
                    "ready": function (cb) {
                        try {
                            if (!root.initialized) {
                                root.init(cb);
                            }
                            else {
                                cb();
                            }
                        }
                        catch (e) { }
                    },
                    "getItem": function (key) {
                        try {
                            return root.getItem(key);
                        }
                        catch (e) { }
                    },
                    "setItem": function (key, val) {
                        try {
                            return root.setItem(key, val);
                        }
                        catch (e) { }
                    },
                    "clearItem": function (key) {
                        try {
                            return root.clearItem(key);
                        }
                        catch (e) { }
                    }
                },

                "tags": {
                    "allTags": null,
                    "api": {
                        "addTag": function (tagName) {
                            api.tags.allTags[tagName] = true;
                            root.setItem("mmctags", api.tags.allTags);
                        },
                        "removeTag": function (tagName) {
                            delete api.tags.allTags[tagName];
                            root.setItem("mmctags", api.tags.allTags);
                        },
                        "getAllTags": function () {
                            var ret = [];
                            $.each(api.tags.allTags, function (key) {
                                ret.push(key);
                            });
                            return ret;
                        },
                        "isTagExist": function (tagName) {
                            var isExist = api.tags.allTags[tagName] ? true : false;
                            return isExist;
                        }
                    }
                },
                "appState": {
                    "disable": function (hrdId, callback) {
                        if (window.mpvInterface && window.mpvAppDisableManager) {
                            mpvAppDisableManager.disableApp(function (response) {
                                if (typeof callback === "function") {
                                    callback(response && response.status === "OK");
                                }
                            });
                        }
                        else {
                            root.setItem("mpvStatus", "0");
                            var numberOfAttemps = 0;
                            $.when($.ajax({
                                "url":root.disableAppURL + "/SetUserDisabled",
                                "data": { "hrdId": hrdId },
                                "dataType": "jsonp",
                                "jsonpCallback": "mpvSetServerDisableApp",
                                "cache": true
                            })).then(function (response) {
                                try {
                                    numberOfAttemps++;
                                    if (numberOfAttemps >= 3 && (!response || response.status !== "OK")) {
                                        return  api.appState.disable(hrdId,callback);
                                    }
                                    else {
                                        if ($.type(callback) === "function") {
                                            callback(response && response.status === "OK");
                                        }
                                    }
                                }
                                catch (e) {
                                }
                            });
                        }
                    },
                    "isEnabled": function (hrdId, callback) {
                        if (window.mpvInterface && window.mpvAppDisableManager) {
                            mpvAppDisableManager.checkServer(function (response) {
                                if ($.type(callback) === "function") {
                                    callback(response && response.enabled === "1");
                                }
                            });
                        }
                        else {
                            $.when($.ajax({
                                "url": root.disableAppURL + "/IsUserEnabled",
                                "data": { "hrdId": hrdId },
                                "dataType": "jsonp",
                                "jsonpCallback": "mpvServerDisableResponse",
                                "cache": true
                            })).then(function (response) {
                                try {
                                    if ($.type(callback) === "function") {
                                        callback(response && response.enabled === "1");
                                    }
                                }
                                catch (e) {
                                }
                            });
                        }
                    },
                    "enable": function (hrdId, callback) {
                        root.setItem("mpvStatus", "1");
                        $.when($.ajax({
                            "url": root.disableAppURL + "/ActivateUser",
                            "data": { "hrdId": hrdId },
                            "dataType": "jsonp",
                            "jsonpCallback": "mpvServerDisableResponse",
                            "cache": true
                        })).then(function (response) {
                            try {
                                if ($.type(callback) === "function") {
                                    callback(response && response.status === "OK");
                                }
                            }
                            catch (e) {
                            }
                        });
                    }
                }

            };

            //#endregion API
            api.storage.ready(function () {
                try {
                    //expose storage interface to global scope.
                    api.tags.allTags = root.getItem("mmctags") || {};
                    window.mpvStorageMngr = window.mpvStorageMngr || api.storage;
                    window.mmcTagsAPI = window.mmcTagsAPI || api.tags.api;
                    window.mmcAppState = window.mmcAppState || api.appState;
                    ready = true;
                    while(readyCallbacks.length > 0){
                        var currentCb = readyCallbacks.shift();
                        typeof currentCb === "function" && currentCb();
                    }
                }
                catch (e) { }
            });

            var readyCallbacks = [], ready = false;
            window.mmcAPI = function (cb) {
                if (ready) {
                    return cb();
                }
                readyCallbacks.push(cb);
            };
        }
        catch (e) { }
    } (window));

}


catch (e) { }