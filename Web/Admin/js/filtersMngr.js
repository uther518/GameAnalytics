-(function (Logicsmngr) {
    var $ = typeof mntrjQuery !== "undefined" ? mntrjQuery : jQuery;
    Logicsmngr.secret.filtersMngr = (function () {


        var secret = {

            "isNewDay": function (lastTimeShown) {

                var now = new Date();
                try {
                    // if month or year or day aren't equal than we're 100% in new day.
                    return ((lastTimeShown.getFullYear() !== now.getFullYear()) || (lastTimeShown.getMonth() !== now.getMonth()) || (lastTimeShown.getDate() !== now.getDate()));
                }

                catch (e) {

                    // in case of exception always return true to enable possible impression
                    return true;
                }
            },

            "finalDecision": function (found, filterType) {

                return /^white$/i.test(filterType) ? found ? true : false : found ? false : true;

            },
            "isInList": function (object, value) {

                var found = false;
                var regex = new RegExp("^" + value + "$", "i");
                $.each(object, function (property, value) {
                    if (regex.test(property)) {
                        found = true;
                        return false;
                    }
                });
                return found;
            },

            "countries": {

                "currentCountry": mpvInterface.getParam("cntry"),
                "isAllowedByCountry": function (filterObject) {
                    try {
                        var isAllowed;
                        var filterType = filterObject.type;
                        var found = secret.isInList(filterObject.values, this.currentCountry) || secret.isInList(filterObject.values, "all");
                        return secret.finalDecision(found, filterType);

                    }

                    catch (e) {

                        try {
                            mpvInterface.errorReport({

                                "err_desc": e,
                                "err_location": "filtersMngr.countries.isAllowedByCountry()",
                                "extra": "filtersMngr.js"

                            });

                        }

                        catch (e) {

                        }
                    }

                }


            },

            "isAllowedByTlbrid": function (filterObject) {
                try {
                    var filterType = filterObject.type;
                    var found = secret.isInList(filterObject.values, mpvInterface.getParam("tlbrid")) || secret.isInList(filterObject.values, "all");
                    return secret.finalDecision(found, filterType);
                }
                catch (e) {
                    try {
                        mpvInterface.errorReport({
                            "err_desc": e,
                            "err_location": "filtersMngr.isAllowedByTlbrid()",
                            "extra": "filtersMngr.js"
                        });
                    }
                    catch (e) {
                    }
                }
            },

            "isAllowedBySmplGrp": function (filterObject) {
                try {
                    var filterType = filterObject.type;
                    var found = secret.isInList(filterObject.values, mpvInterface.getParam("smplGrp")) || secret.isInList(filterObject.values, "all");
                    return secret.finalDecision(found, filterType);
                }
                catch (e) {
                    try {
                        mpvInterface.errorReport({
                            "err_desc": e,
                            "err_location": "filtersMngr.isAllowedBySmplGrp()",
                            "extra": "filtersMngr.js"
                        });
                    }
                    catch (e) {
                    }
                }

            },

            "isAllowedByEnvironment": function (filterObject) {
                try {
                    var currentEnvrmnt = Logicsmngr.getCurrentEnvironment();
                    return $.inArray(currentEnvrmnt, filterObject.values) > -1;
                }
                catch (e) {
                    try {
                        mpvInterface.errorReport({
                            "err_desc": e,
                            "err_location": "filtersMngr.isAllowedByEnvironment()",
                            "extra": "filtersMngr.js"
                        });
                    }
                    catch (e) {
                    }
                }
            },

            "isAllowedByWelcomeScreen": function (filterObject, logicName) {
                if (typeof Logicsmngr.gotWelcomeMessage !== "undefined") {
                    if (!filterObject && Logicsmngr.gotWelcomeMessage && (logicName === "mntrMMConfirm") ||
                        filterObject && !Logicsmngr.gotWelcomeMessage && (logicName !== "mntrMMConfirm") ||
                        filterObject && Logicsmngr.gotWelcomeMessage && (logicName === "mntrMMConfirm"))
                        return false;
                    return true;
                }
                return true;
            },


            "isAllowedByTags": function (filterObject) {
                try {
                    var filterType = filterObject.type;
                    var userTags = mmcTagsAPI.getAllTags();
                    var found = false;
                    for (var i = 0; i < userTags.length; i++) {
                        if (filterObject.values[userTags[i]]) {
                            found = true;
                            break;
                        }
                    }
                    return secret.finalDecision(found, filterType);
                }
                catch (e) {
                    try {
                        mpvInterface.errorReport({
                            "err_desc": e,
                            "err_location": "filtersMngr.isAllowedByTags()",
                            "extra": "filtersMngr.js"
                        });
                    }
                    catch (e) {
                    }
                }
            },


            "isAllowedByCapping": function (logic) {
                try {
                    return cappMgr.isAllowedByCapping(logic);
                }
                catch (e) {
                }
            },

            "isAllowedByUser": function (logic) {
                try {
                    // this function was used to check the flash optout when the opt out was in logic level. 
                    // it is no longer active and replace by global optout check. Do not use this function anymore.
                    return true;
                }
                catch (e) {
                }
            },

            "isAllowedByLimitNumber": function (logic) {
                try {
                    // random lottery on limit number. if no limit number or the lottery result on the limit number is between 0 and 1 
                    // the the logics is allowed.
                    var isAllowed = true;
                    if (logic.filters.levelTwo && typeof logic.filters.levelTwo.limitNumber === "number") {
                        var random = Math.random() * logic.filters.levelTwo.limitNumber;
                        isAllowed = random >= 0 && random <= 1 ? true : false;
                    }
                }
                catch (e) {
                }
                return isAllowed;
            }

        },

        cappMgr = {

            "filterRealEstate": function (logicList) {

                try {
                    var that = this, logicByRealEstate = lottery.splitByField("cap_loc", logicList), now = new Date(), minDate = new Date(null), filteredList = [];
                    $.each(logicByRealEstate, function (realEstateType, collectionOfLogics) {
                        try {
                            var diffMin, cappRealEstateMinDiff = that.getCappRealEstateValue(realEstateType, "mindiff"),
                            cappRealEstateDaily = that.getCappRealEstateValue(realEstateType, "daily"), lastDisplayDate = that.getLastDisplayDate(collectionOfLogics),
                            sumOfDailyCapps = that.getSumOfDisplays(collectionOfLogics);
                            lastDisplayDate = lastDisplayDate && new Date(lastDisplayDate) || minDate;
                            diffMin = (now - lastDisplayDate) / 1000 / 60;
                            $.merge(filteredList, diffMin > cappRealEstateMinDiff && cappRealEstateDaily > sumOfDailyCapps ? collectionOfLogics : []);

                        }

                        catch (e) { }
                    });
                    return $.grep(logicList, function (logic) {

                        return $.inArray(logic, filteredList) > -1;

                    });
                }

                catch (e) { }
            },

            "getCappRealEstateValue": function (capLoc /* location family */, capType /*can be either daily or mindiff*/) {

                try {
                    var capVal, cappConfig = $.extend(true, { "default": { "mindiff": 0, "daily": 99999} }, mpvInterface.getParam("realEstateCapp") || {});
                    capVal = cappConfig[capLoc] && !/^null|undefined$/i.test($.type(cappConfig[capLoc][capType])) ? cappConfig[capLoc][capType] : cappConfig["default"][capType];
                    try {
                        capVal = parseFloat(capVal);
                    }
                    catch (e) { }
                    capVal = $.type(capVal) === "number" && !isNaN(capVal) ? capVal : capType === "mindiff" ? 0 : 9999;
                    return capVal;
                }

                catch (e) { }

            },

            "getLastDisplayDate": function (collection) {

                try {
                    var defaultMinDate = new Date(null); // 1/1/1970
                    return Math.max.apply(null, $.map(collection, function (logic) {
                        try {
                            var logicFlashObject = logic.flashObjectName && Logicsmngr.getLogicFlashObject(logic.flashObjectName), lastDisplayDate;
                            lastDisplayDate = logicFlashObject && logicFlashObject.capping && logicFlashObject.capping.lastTimeShown;
                            lastDisplayDate = lastDisplayDate && new Date(lastDisplayDate) || defaultMinDate;
                            return lastDisplayDate.getTime();
                        }
                        catch (e) { }
                    }));
                }

                catch (e) { }

            },

            "getSumOfDisplays": function (collection) {

                try {
                    var sumOfDisplays = 0;
                    $.each(collection, function () {

                        try {
                            var logic = this, logicFlashObject = logic.flashObjectName && Logicsmngr.getLogicFlashObject(logic.flashObjectName), numOfDisplays, lastDisplayDate;
                            numOfDisplays = logicFlashObject && logicFlashObject.capping && logicFlashObject.capping.numberOfTimesShown || 0;
                            try {
                                numOfDisplays = parseInt(numOfDisplays);
                            }
                            catch (e) { }
                            numOfDisplays = $.type(numOfDisplays) === "number" && !isNaN(numOfDisplays) ? numOfDisplays : 0;
                            lastDisplayDate = logicFlashObject && logicFlashObject.capping && $.type(logicFlashObject.capping.lastTimeShown) === "string" && new Date(logicFlashObject.capping.lastTimeShown) || new Date(null);
                            numOfDisplays = secret.isNewDay(lastDisplayDate) ? 0 : numOfDisplays;
                            sumOfDisplays += numOfDisplays;

                        }
                        catch (e) { }
                    });
                    return sumOfDisplays;
                }

                catch (e) { }

            },

            "isAllowedByCapping": function (logic) {

                var shouldShow = true, now = new Date(), lastTimeShown, saveToStorage = false, minDiffCapping;
                try {

                    if (logic.flashObjectName) {
                        var logicFlashObject = Logicsmngr.getLogicFlashObject(logic.flashObjectName);
                        // if flash object not found then always assume the logic is allowed to be injected
                        if (logicFlashObject) {
                            lastTimeShown = new Date(logicFlashObject.capping.lastTimeShown || null);
                            // handle daily capping here - if we have reached the maximum daily capping allowed by the map
                            if (logicFlashObject.capping.numberOfTimesShown >= logic.filters.levelTwo.capping) {
                                // then check if we're on a new date
                                if (secret.isNewDay(lastTimeShown)) {

                                    // if it is the reset the number of impressions to zero and save the object
                                    logicFlashObject.capping.numberOfTimesShown = 0;
                                    saveToStorage = true;
                                }

                                else {

                                    // if it is not a new day then the logic is not allowed by capping
                                    shouldShow = false;
                                }

                            }

                            if (shouldShow) {
                                // handle minDiff capping here...
                                try {
                                    minDiffCapping = parseFloat(logic.filters.levelTwo.mindiff);
                                }

                                catch (e) {

                                }
                                minDiffCapping = $.type(minDiffCapping) === "number" && !isNaN(minDiffCapping) ? minDiffCapping : 0;
                                var diffMin = ((now - lastTimeShown) / 1000) / 60
                                shouldShow = diffMin > minDiffCapping;

                            }

                            if (saveToStorage) {

                                mpvStorageMngr.setItem(logic.flashObjectName, logicFlashObject);
                            }
                        }
                    }

                }

                catch (e) {

                }

                return shouldShow;



            }


        },


    lottery = {

        "getFinalWinners": function (candidates) {


            try {

                // split the candidates into types and get winner of each type
                var that = this, winners = this.getWinnersByField(candidates, "location");

                // check if the winners are conflicted by location. if so make lottery and get the final winners and return it.
                if (winners && winners.length > 1) {


                    winners = this.getWinnersByField(winners, "type");

                }

                return winners;

            }

            catch (e) {


                try {
                    mpvInterface.errorReport({

                        "err_desc": e,
                        "err_location": "filtersMngr.getFinalWinners()",
                        "extra": "filtersMngr.js"

                    });

                }

                catch (e) {

                }
            }


        },

        "getWinnersByField": function (candidates, type) {
            // 1. split the candidates to groups according to the specified field (type/location).
            // 2. enumerate on each group :  if the group name has the field of "no-"+type, it means that the members don't have the specified field (the field can be optional).
            //    so if they don't have the specified field they are considered winners so add them all to winners array. 
            //    else, if the members DO have the specified field, make lottery between them and add the winner.
            try {
                var tree = this.splitByField(type, candidates), winners = [], that = this;
                $.each(tree, function (ftype, collectionOfType) {
                    try {
                        var winner;
                        if (collectionOfType) {


                            if (collectionOfType.length > 1) {


                                winner = (ftype === "no-" + type) ? collectionOfType : that.makeLotto(collectionOfType);


                            }

                            else {

                                winner = collectionOfType[0];

                            }

                            $.isArray(winner) ? winners = winners.concat(winner) : winners.push(winner)

                        }

                    }

                    catch (e) {

                    }

                });

                return winners;

            }

            catch (e) {

                try {
                    mpvInterface.errorReport({

                        "err_desc": e,
                        "err_location": "filtersMngr.getWinnersByField()",
                        "extra": "filtersMngr.js"

                    });

                }

                catch (e) {

                }
            }

        },


        "splitByField": function (fieldName, originalCollection) {
            try {
                // this function takes a collection and split the collection into groups based on the value of the fieldName property in each member in the collection.

                var fieldTree = {};
                if (this.isPopulatedArray(originalCollection)) {

                    $(originalCollection).each(function () {
                        try {

                            var logic = this, field = logic[fieldName] || ("no-" + fieldName);
                            fieldTree[field] = fieldTree[field] || [];
                            fieldTree[field].push(logic);

                        }

                        catch (e) {

                        }


                    });

                }

                return fieldTree;

            }

            catch (e) {

                try {
                    mpvInterface.errorReport({

                        "err_desc": e,
                        "err_location": "filtersMngr.splitByField()",
                        "extra": "filtersMngr.js"

                    });

                }

                catch (e) {

                }
            }
        },

        "isPopulatedArray": function (array) {

            return array && array.length > 0;


        },

        "makeLotto": function (candidates) {

            try {
                // this function makes the actual lottery based on the scoresObject it recieved. it takes a random number between 0 to the scale (sum of scores)
                // and asks each of the scores object method if the number is in its range
                var scoresObject, randomNumber;

                $(candidates).each(function () {
                    // check if score field exist in the element in all element ,if it doesn't exist specify 1 as the default score
                    this.score = typeof this.score !== "undefined" ? this.score : 1;

                });

                scoresObject = this.getScoresObject(candidates);
                // get the random number for the range
                randomNumber = Math.random() * scoresObject.scale;

                // enumrate the methods that were dynamica
                $.each(scoresObject, function (propName, propVal) {
                    try {

                        if ($.isFunction(propVal)) {

                            // check if the randomNumber is in the range of the current method
                            propVal(randomNumber);
                            // if it was in the range then the method set the winner field to the winner logic so the loop will break
                            return scoresObject.winner === null;

                        }

                    }

                    catch (e) {

                    }


                });
                // return the winner
                return scoresObject.winner;

            }

            catch (e) {

                try {
                    mpvInterface.errorReport({

                        "err_desc": e,
                        "err_location": "filtersMngr.makeLotto()",
                        "extra": "filtersMngr.js"

                    });

                }

                catch (e) {

                }
            }

        },

        "getScoresObject": function (arr) {
            try {
                // this method is the heart of the lottery operation:
                // it creates a scoresObject that has to fields : winner -> the final winner logic will be set to this property, scale -> a field representing the sum of all scores
                // it finds the winner based on the methods in adds to the object at runtime.
                var scoresObject = { "winner": null, "scale": 0 };
                // sort the original array ascending according to the scores (lowest to highest)
                arr.sort(function (logic1, logic2) {

                    return logic1.score - logic2.score;

                });

                $(arr).each(function (index) {

                    try {
                        // loop through all the logics, calc the start and end pos for the current logic and add a method that accepts a random number as a parameter and checks
                        // if the number is in the range. if it is in the range set winner property to the final logic.
                        var logic = this, logicScore = Number(logic.score), startPos = scoresObject.scale, endPos = startPos + logicScore, isFirstElement = index === 0;
                        scoresObject[logic.name] = function (number) {

                            var isInRange = isFirstElement ? (number >= startPos && number <= endPos) : (number > startPos && number <= endPos);
                            scoresObject.winner = isInRange ? logic : scoresObject.winner;


                        };

                        scoresObject.scale = endPos;

                    }

                    catch (e) {


                    }

                });

                return scoresObject;

            }

            catch (e) {

                try {
                    mpvInterface.errorReport({

                        "err_desc": e,
                        "err_location": "filtersMngr.getScoresObject()",
                        "extra": "filtersMngr.js"

                    });

                }

                catch (e) {

                }
            }

        }



    };


        return {


            "filterOnLevel1": function (logicsList) {
                try {
                    var levelOneFiltered = [];
                    $(logicsList).each(function () {

                        var currentLogic = this;
                        var shouldAdd = true;
                        $.each(currentLogic.filters.levelOne, function (filterName, filterObject) {
                            switch (filterName) {
                                case "countries":
                                    if (!secret.countries.isAllowedByCountry(filterObject)) {
                                        shouldAdd = false;
                                        return false;
                                    }
                                    break;
                                case "environment":
                                    if (!secret.isAllowedByEnvironment(filterObject)) {
                                        shouldAdd = false;
                                        return false;
                                    }
                                    break;
                                case "smplGrp":
                                    if (!secret.isAllowedBySmplGrp(filterObject)) {
                                        shouldAdd = false;
                                        return false;
                                    }
                                    break;
                                    
                                case "tlbrId":
                                    if (!secret.isAllowedByTlbrid(filterObject)) {
                                        shouldAdd = false;
                                        return false;
                                    }
                                    break;
                                case "tags":
                                    if (!secret.isAllowedByTags(filterObject)) {
                                        shouldAdd = false;
                                        return false;
                                    }
                                    break;
                                case "waitForWelcome":      /* Idan */
                                    if (!secret.isAllowedByWelcomeScreen(filterObject, currentLogic.name)) {
                                        shouldAdd = false;
                                        return false;
                                    }
                                    break;
                                case "isActive":
                                    if (filterObject === false) {
                                        shouldAdd = false;
                                        return false;
                                    }
                                    break;
                            }
                        });

                        if (shouldAdd) {
                            levelOneFiltered.push(currentLogic);
                        }

                    });


                    return levelOneFiltered;

                }

                catch (e) {

                    try {
                        mpvInterface.errorReport({

                            "err_desc": e,
                            "err_location": "filtersMngr.filterOnLevel1()",
                            "extra": "filtersMngr.js"

                        });

                    }

                    catch (e) {

                    }
                }

            },

            "filterOnLevel2": function (logicsList, currentUrl) {

                // this function is applied on every documentComplete event.
                // its role is to filter the logics based on generic filters and then based on specific filters 
                try {
                    var levelTwoFiltered = [], logicsList = cappMgr.filterRealEstate(logicsList);

                    try {

                        currentUrl = decodeURIComponent(currentUrl);

                    }

                    catch (e) {


                    }

                    $(logicsList).each(function () {

                        var currentLogic = this;
                        var shouldAdd = true;

                        // if the current logic doesnt have levelTwo filter property dont add it (xpe)
                        if (!currentLogic.filters.levelTwo) {

                            return true;
                        }

                        // filter base on generic params like capping ,and widget approval through the welcome screen dialog or widget settings
                        $.each(currentLogic.filters.levelTwo, function (filterName, filterObject) {

                            switch (filterName) {

                                case "capping":
                                    if (!secret.isAllowedByCapping(currentLogic)) {
                                        shouldAdd = false;
                                        return false;

                                    }
                                    break;

                                case "limitNumber":
                                    if (!secret.isAllowedByLimitNumber(currentLogic)) {

                                        shouldAdd = false;
                                        return false;

                                    }
                                    break;


                            }

                        });


                        //                        if (shouldAdd && !secret.isAllowedByUser(currentLogic)) {

                        //                            shouldAdd = false;
                        //                        }


                        // if we reached this level then the logic is allowed by the capping and the user.
                        // so check on based on the logic specific filters.
                        if (shouldAdd && $.isFunction(window[currentLogic.name].isAllowedByFilter) && window[currentLogic.name].isAllowedByFilter(currentUrl)) {
                            levelTwoFiltered.push(currentLogic);

                        }


                    });

                    // TODO: the lottery based on conflicting logic types and scores.
                    return this.getFinalLogics(levelTwoFiltered);
                    // return levelTwoFiltered;

                }

                catch (e) {

                    try {
                        mpvInterface.errorReport({

                            "err_desc": e,
                            "err_location": "filtersMngr.filterOnLevel2()",
                            "extra": "filtersMngr.js"

                        });

                    }

                    catch (e) {

                    }
                }



            },

            "filterOnLevel3": function (logics) {

                try {
                    // filter base on the maximum logics per page configuration.
                    var newLogicsList, allTheRest, lotteryWinners, maxPageLogics = mpvInterface.getParam("maxPageLogics");
                    maxPageLogics = typeof maxPageLogics !== "undefined" ? maxPageLogics : Infinity;
                    // the new logics list should include all the logics that doesn't support level3 or has level3 filtering turned off. 
                    newLogicsList = $.grep(logics, function (logic) {

                        return !logic.filters.pageLevelThree || !logic.filters.pageLevelThree.on;

                    }) || [];
                    // all the rest -> all the other that does have level3 filtering turned on.
                    allTheRest = $.grep(logics, function (logic) {

                        return $.inArray(logic, newLogicsList) === -1;

                    }) || [];

                    // do the lottery only if we exceed the limit number of page logics (the number comes from the entry point config).
                    if (allTheRest.length > maxPageLogics) {
                        lotteryWinners = [];
                        while (lotteryWinners.length < maxPageLogics && allTheRest.length > 0) {

                            var winner = lottery.makeLotto(allTheRest);
                            lotteryWinners.push(winner);
                            allTheRest.splice($.inArray(winner, allTheRest), 1);

                        }

                        newLogicsList = newLogicsList.concat(lotteryWinners);

                    }

                    else {

                        newLogicsList = newLogicsList.concat(allTheRest);
                    }

                    return newLogicsList;

                }

                catch (e) {


                }



            },


            "isAllowedByFilter": function (logic) {
                try {
                    var isAllowed = true;
                    $.each(logic.filters, function (filterName, filterObject) {

                        switch (filterName) {

                            case "countries":
                                isAllowed = secret.countries.isAllowedByCountry(filterObject);
                                break;

                        }

                        return isAllowed;


                    });

                    return isAllowed;

                }

                catch (e) {

                    try {
                        mpvInterface.errorReport({

                            "err_desc": e,
                            "err_location": "filtersMngr.isAllowedByFilter()",
                            "extra": "filtersMngr.js"

                        });

                    }

                    catch (e) {

                    }
                }

            },

            "getFinalLogics": function (collection) {
                try {
                    return lottery.getFinalWinners(collection);

                }

                catch (e) {

                    mpvInterface.errorReport({

                        "err_desc": e,
                        "err_location": "filtersMngr.getFinalLogics()",
                        "extra": "filtersMngr.js"

                    });
                }
            }


        }


    })();

    Logicsmngr.onModuleLoaded();

})(Logicsmngr);