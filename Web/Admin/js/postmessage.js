
(function ($) {
    '$:nomunge';

    var interval_id,
    last_hash,
    cache_bust = 1,

    rm_callback,

    window = this,
    FALSE = !1,

    postMessage = 'postMessage',
    addEventListener = 'addEventListener',

    p_receiveMessage,
    has_postMessage = window[postMessage] && !$.browser.opera;



    $[postMessage] = function (message, target_url, target, noPostMessageMap) {
        //if (!target_url) { return; }

        message = typeof message === 'string' ? message : $.param(message);

        target = target || parent;

        if (has_postMessage) {
          
            //target[postMessage](message, target_url.replace(/([^:]+:\/\/[^\/]+).*/, '$1'));
            target[postMessage](message, '*');

        }
        else {
            if (!noPostMessageMap) {
                // no post message support old browser,no noPostMessageMap supplied,use the hash  
                try {
                    target.location = target_url.replace(/#.*$/, '') + '#' + (+new Date) + (cache_bust++) + '&' + message;
                }
                catch (e) {

                }
            }

            else {
                try {
                    // no post message support old browser,noPostMessageMap supplied,use inner iframes length mechanism.
                    // $("#" + noPostMessageMap.cntnrId).find("iframe").first().attr("id", msg);
                    $("#" + noPostMessageMap.cntnrId).find("iframe").attr("id", message);

                }

                catch (e) {
                   // alert(e);
                }

            }

        }
    };


    $.receiveMessage = p_receiveMessage = function (callback, source_origin, noPostMessageMap) {
        if (has_postMessage) {

            if (callback) {
                var old_rm_callback = rm_callback;

                rm_callback = function (e) {
                    //if ((typeof source_origin === 'string' && e.origin !== source_origin) || ($.isFunction(source_origin) && source_origin(e.origin) === FALSE)) {
                    //return FALSE;
                    //}
               
                    if (typeof old_rm_callback === "function") {

                        old_rm_callback(e);

                    }
                    callback(e);
                };
            }

            if (window[addEventListener]) {
                if (typeof old_rm_callback === "function") {

                    window.removeEventListener('message', old_rm_callback, FALSE);

                }

                window[callback ? addEventListener : 'removeEventListener']('message', rm_callback, FALSE);
            } else {
                if (typeof old_rm_callback === "function") {

                    window.detachEvent('onmessage', old_rm_callback);

                }

                window[callback ? 'attachEvent' : 'detachEvent']('onmessage', rm_callback);
            }

        } else {

            if (callback) {
                if (!noPostMessageMap) {
                    // if noPostMessageMap object has been supplied use the hash transport mechanism - default
                    var interval_id = null;
                    interval_id = setInterval(function () {
                        var hash = document.location.hash,
                        re = /^#?\d+&/;
                        if (hash !== last_hash && re.test(hash)) {
                            last_hash = hash;
                            callback({ data: hash.replace(re, '') });
                        }
                    }, 100);

                }

                else {
                    // if noPostMessageMap object supplied, use the numOfInnerFrames=>Event mechanism
                    var lastEvent;
                    setInterval(function () {
                        try {

                            $(noPostMessageMap.events).each(function () {

                                try {
                                    var eventName = noPostMessageMap.eventPrefix + this.toString();
                                    if (noPostMessageMap.ifr.contentWindow.frames[eventName] && lastEvent !== eventName) {
                                        lastEvent = eventName;
                                        callback({ "data": eventName });

                                    }

                                }

                                catch (e) {

                                   // alert(e);
                                }


                            });
                        }

                        catch (e) {

                          //  alert(e);
                        }


                    }, 300);

                }
            }
        }
    };

})(typeof mntrjQuery !== "undefined" ? mntrjQuery : jQuery);