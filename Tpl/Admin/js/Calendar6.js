//调用说明：
//首先应用到页面，然后，实例如下 
// <div>
//            <input type="text" name="txtdate1" /><a href="javascript:show_calendar('form1.txtdate1');"
//                onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img
//                    alt="日期" src="images/calendar.gif" style="border-top-style: none; border-right-style: none;
//                    border-left-style: none; border-bottom-style: none" /></a>
//        </div>



var weekend = [0,6];
var fontsize = 2;
var gNow = new Date();
var ggWinCal;
isNav = (navigator.appName.indexOf("Netscape") != -1) ? true : false;
isIE = (navigator.appName.indexOf("Microsoft") != -1) ? true : false;
// Non-Leap year Month days..
Calendar.DOMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
// Leap year Month days..
Calendar.lDOMonth = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
function Calendar(p_item, p_WinCal, p_month, p_year, p_format) {
        if ((p_month == null) && (p_year == null))        return;
        if (p_WinCal == null)
                this.gWinCal = ggWinCal;
        else
                this.gWinCal = p_WinCal;
        
        if (p_month == null) {
                this.gMonth = null;
                this.gYearly = true;
        } else {
                this.gMonth = new Number(p_month);
                this.gYearly = false;
        }
        this.gYear = p_year;
        this.gFormat = p_format;
        this.gBGColor = "white";
        this.gFGColor = "black";
        this.gTextColor = "black";
        this.gHeaderColor = "black";
        this.gReturnItem = p_item;
}
Calendar.get_daysofmonth = Calendar_get_daysofmonth;
Calendar.calc_month_year = Calendar_calc_month_year;
function Calendar_get_daysofmonth(monthNo, p_year) {
        /* 
        Check for leap year ..
        1.Years evenly divisible by four are normally leap years, except for... 
        2.Years also evenly divisible by 100 are not leap years, except for... 
        3.Years also evenly divisible by 400 are leap years. 
        */
        if ((p_year % 4) == 0) {
                if ((p_year % 100) == 0 && (p_year % 400) != 0)
                        return Calendar.DOMonth[monthNo];
        
                return Calendar.lDOMonth[monthNo];
        } else
                return Calendar.DOMonth[monthNo];
}
function Calendar_calc_month_year(p_Month, p_Year, incr) {
        /* 
        Will return an 1-D array with 1st element being the calculated month 
        and second being the calculated year 
        after applying the month increment/decrement as specified by 'incr' parameter.
        'incr' will normally have 1/-1 to navigate thru the months.
        */
        var ret_arr = new Array();
        
        if (incr == -1) {
                // B A C K W A R D
                if (p_Month == 0) {
                        ret_arr[0] = 11;
                        ret_arr[1] = parseInt(p_Year) - 1;
                }
                else {
                        ret_arr[0] = parseInt(p_Month) - 1;
                        ret_arr[1] = parseInt(p_Year);
                }
        } else if (incr == 1) {
                // F O R W A R D
                if (p_Month == 11) {
                        ret_arr[0] = 0;
                        ret_arr[1] = parseInt(p_Year) + 1;
                }
                else {
                        ret_arr[0] = parseInt(p_Month) + 1;
                        ret_arr[1] = parseInt(p_Year);
                }
        }
        
        return ret_arr;
}
function Calendar_calc_month_year(p_Month, p_Year, incr) {
        /* 
        Will return an 1-D array with 1st element being the calculated month 
        and second being the calculated year 
        after applying the month increment/decrement as specified by 'incr' parameter.
        'incr' will normally have 1/-1 to navigate thru the months.
        */
        var ret_arr = new Array();
        
        if (incr == -1) {
                // B A C K W A R D
                if (p_Month == 0) {
                        ret_arr[0] = 11;
                        ret_arr[1] = parseInt(p_Year) - 1;
                }
                else {
                        ret_arr[0] = parseInt(p_Month) - 1;
                        ret_arr[1] = parseInt(p_Year);
                }
        } else if (incr == 1) {
                // F O R W A R D
                if (p_Month == 11) {
                        ret_arr[0] = 0;
                        ret_arr[1] = parseInt(p_Year) + 1;
                }
                else {
                        ret_arr[0] = parseInt(p_Month) + 1;
                        ret_arr[1] = parseInt(p_Year);
                }
        }
        
        return ret_arr;
}
// This is for compatibility with Navigator 3, we have to create and discard one object before the prototype object exists.
//new Calendar();
Calendar.prototype.show = function() {
        var vCode = ""; 

        this.gWinCal.document.open();
        // Setup the page...
        this.wwrite("<html>");
        this.wwrite("<head><title>Calendar</title>");
        vCode = this.cal_style();
        this.wwrite(vCode);
        this.wwrite("</head>");
        this.wwrite("<body>");
                // Show navigation buttons
        var prevMMYYYY = Calendar.calc_month_year(this.gMonth, this.gYear, -1);
        var prevMM = prevMMYYYY[0];
        var prevYYYY = prevMMYYYY[1];
        var nextMMYYYY = Calendar.calc_month_year(this.gMonth, this.gYear, 1);
        var nextMM = nextMMYYYY[0];
        var nextYYYY = nextMMYYYY[1];
        
        this.wwrite("<table border=0 cellpadding=0 cellspacing=1 class=Calendar>");
        this.wwrite("<thead>");
        this.wwrite("<tr align=\"center\" valign=\"middle\">");
        this.wwrite("<td class=\"Title\" colspan=\"7\">");
        this.wwrite("<A class=\"DayButton\" title=\"上一年\" HREF=\"" +
                "javascript:window.opener.Build(" + 
                "'" + this.gReturnItem + "', '" + this.gMonth + "', '" + (parseInt(this.gYear)-1) + "', '" + this.gFormat + "'" +
                ");" +
                "\">7<\/A>&nbsp;");
        this.wwrite("<A class=\"DayButton\" title=\"上一月\" HREF=\"" +
                "javascript:window.opener.Build(" + 
                "'" + this.gReturnItem + "', '" + prevMM + "', '" + prevYYYY + "', '" + this.gFormat + "'" +
                ");" +
                "\">3<\/A>&nbsp;");
        this.wwrite("<input maxlength=\"4\" name=\"year\" size=\"4\" readonly type=\"text\" value=" + this.gYear + " />年");
        this.wwrite("<input maxlength=\"2\" name=\"month\" size=\"1\" readonly type=\"text\" value=" + (parseInt(this.gMonth) + 1) + " />月");
        this.wwrite("<A class=\"DayButton\" title=\"下一月\" HREF=\"" +
                "javascript:window.opener.Build(" + 
                "'" + this.gReturnItem + "', '" + nextMM + "', '" + nextYYYY + "', '" + this.gFormat + "'" +
                ");" +
                "\">4<\/A>&nbsp;");
        this.wwrite("<A class=\"DayButton\" title=\"下一年\" HREF=\"" +
                "javascript:window.opener.Build(" + 
                "'" + this.gReturnItem + "', '" + this.gMonth + "', '" + (parseInt(this.gYear)+1) + "', '" + this.gFormat + "'" +
                ");" +
                "\">8<\/A>&nbsp;</TD></TR>");        
        
        
        vCode = "";
        vCode = "<TR>";
        vCode = vCode + "<TD class=DaySunTitle>日</FONT></TD>";
        vCode = vCode + "<TD class=DayTitle>一</FONT></TD>";
        vCode = vCode + "<TD class=DayTitle>二</FONT></TD>";
        vCode = vCode + "<TD class=DayTitle>三</FONT></TD>";
        vCode = vCode + "<TD class=DayTitle>四</FONT></TD>";
        vCode = vCode + "<TD class=DayTitle>五</FONT></TD>";
        vCode = vCode + "<TD class=DaySatTitle>六</FONT></TD>";
        vCode = vCode + "</TR></thead>";
        this.wwrite(vCode);
        
        // Get the complete calendar code for the month..
        vCode = this.getMonthlyCalendarCode();
        this.wwrite(vCode);
        
        this.wwrite("</font></body></html>");
        this.gWinCal.document.close();
} 

Calendar.prototype.wwrite = function(wtext) {
        this.gWinCal.document.writeln(wtext);
}
Calendar.prototype.wwriteA = function(wtext) {
        this.gWinCal.document.write(wtext);
}
Calendar.prototype.cal_style = function() {
        var vCode = "";
        
        vCode = vCode + "<Style>";
        vCode = vCode + ("Input {font-family: verdana;font-size: 9pt;text-decoration: none;background-color: #FFFFFF;height: 20px;border: 1px solid #666666;color:#000000;}");
        vCode = vCode + ("a {text-decoration: none;}"); 

        vCode = vCode + (".Calendar {font-family: verdana;text-decoration: none;width: 200px;background-color: #C0D0E8;font-size: 9pt;border:0px dotted #1C6FA5;}");
        vCode = vCode + (".CalendarTD {font-family: verdana;font-size: 7pt;color: #CDCDCD;background-color:#f6f6f6;height: 20px;width:11%;text-align: center;}"); 

        vCode = vCode + (".Title {font-family: verdana;font-size: 11pt;font-weight: normal;height: 24px;text-align: center;color: #333333;text-decoration: none;background-color: #A4B9D7;border-top-width: 1px;border-right-width: 1px;border-bottom-width: 1px;border-left-width: 1px;border-bottom-style:1px;border-top-color: #999999;border-right-color: #999999;border-bottom-color: #999999;border-left-color: #999999;}"); 

        vCode = vCode + (".Day {font-family: verdana;font-size: 7pt;background-color: #E5E9F2;height: 20px;width:11%;text-align: center;}");
        vCode = vCode + (".DaySat {font-family: verdana;font-size: 7pt;background-color:#E5E9F2;text-align: center;height: 18px;width: 12%;}");
        vCode = vCode + (".DaySun {font-family: verdana;font-size: 7pt;background-color:#E5E9F2;text-align: center;height: 18px;width: 12%;}");
        vCode = vCode + (".DayA {color:#243F65;text-decoration: none;}");
        vCode = vCode + (".DaySatA {color:#009933;text-decoration: none;}");
        vCode = vCode + (".DaySunA {color: #FF0000;text-decoration: none;}"); 

        vCode = vCode + (".DayTitle {font-family: verdana;font-size: 9pt;color: #000000;background-color: #C0D0E8;height: 20px;width:11%;text-align: center;}");
        vCode = vCode + (".DaySatTitle {font-family: verdana;font-size: 9pt;color:#009933;text-decoration: none;background-color:#C0D0E8;text-align: center;height: 20px;width: 12%;}");
        vCode = vCode + (".DaySunTitle {font-family: verdana;font-size: 9pt;color: #FF0000;text-decoration: none;background-color: #C0D0E8;text-align: center;height: 20px;width: 12%;}"); 

        vCode = vCode + (".DayButton {font-family: Webdings;font-size: 9pt;font-weight: bold;color: #243F65;cursor:hand;text-decoration: none;}"); 

        vCode = vCode + ("</Style>");        
        return vCode;
}
Calendar.prototype.getMonthlyCalendarCode = function() {
        var vCode = "";
        var vHeader_Code = "";
        var vData_Code = "";
        
        // Begin Table Drawing code here..
        
        //vHeader_Code = this.cal_header();
        vData_Code = this.cal_data();
        vCode = vCode + vHeader_Code + vData_Code;
                
        return vCode;
}
Calendar.prototype.cal_header = function() {
        var vCode = "";
        vCode = "<TR>";
        vCode = vCode + "<TD class=DaySunTitle>日</FONT></TD>";
        vCode = vCode + "<TD class=DayTitle>一</FONT></TD>";
        vCode = vCode + "<TD class=DayTitle>二</FONT></TD>";
        vCode = vCode + "<TD class=DayTitle>三</FONT></TD>";
        vCode = vCode + "<TD class=DayTitle>四</FONT></TD>";
        vCode = vCode + "<TD class=DayTitle>五</FONT></TD>";
        vCode = vCode + "<TD class=DaySatTitle>六</FONT></TD>";
        vCode = vCode + "</TR></thead>"; 

}
Calendar.prototype.cal_data = function() {
        var vDate = new Date();
        vDate.setDate(1);
        vDate.setMonth(this.gMonth);
        vDate.setFullYear(this.gYear);
        var vFirstDay=vDate.getDay();
        var vDay=1;
        var vLastDay=Calendar.get_daysofmonth(this.gMonth, this.gYear);
        var vOnLastDay=0;
        var vCode = "";
        /*
        Get day for the 1st of the requested month/year..
        Place as many blank cells before the 1st day of the month as necessary. 
        */
        vCode = vCode + "<TR style='cursor:hand'>";
        for (i=0; i<vFirstDay; i++) {
                vCode = vCode + "<TD class=CalendarTD>&nbsp;</TD>";
        }
        // Write rest of the 1st week
        for (j=vFirstDay; j<7; j++) {
            if (j == 0){
                 vCode = vCode + "<TD class=DaySun><A class=DaySunA HREF='#' "
            }else{
                if (j == 6){
                     vCode = vCode + "<TD class=DaySat><A class=DaySatA HREF='#' "
                }else{
                    vCode = vCode + "<TD class=Day><A class=DayA HREF='#' "
                }
            }
            vCode = vCode + "onClick=\"self.opener.document." + this.gReturnItem + ".value='" + 
            this.format_data(vDay) + "';window.close();\">" + this.format_day(vDay) + "</A>" + "</TD>";
            vDay=vDay + 1;
        }
        vCode = vCode + "</TR>";
        // Write the rest of the weeks
        for (k=2; k<7; k++) {
                vCode = vCode + "<TR>";
                for (j=0; j<7; j++) {
                    if (j == 0){
                        vCode = vCode + "<TD class=DaySun><A class=DaySunA HREF='#' "
                    }else{
                        if (j == 6){
                            vCode = vCode + "<TD class=DaySat><A class=DaySatA HREF='#' "
                        }else{
                            vCode = vCode + "<TD class=Day><A class=DayA HREF='#' "
                        }
                    }
                vCode = vCode + "onClick=\"self.opener.document." + this.gReturnItem + ".value='" + 
                                        this.format_data(vDay) + 
                                        "';window.close();\">" + 
                                this.format_day(vDay) + 
                                "</A>" + 
                                "</TD>";
                        vDay=vDay + 1;
                        if (vDay > vLastDay) {
                                vOnLastDay = 1;
                                break;
                        }
                }
                if (j == 6)
                        vCode = vCode + "</TR>";
                if (vOnLastDay == 1)
                        break;
        }
        
        // Fill up the rest of last week with proper blanks, so that we get proper square blocks
        for (m=1; m<(7-j); m++) {
                if (this.gYearly)
                        vCode = vCode + "<TD class=CalendarTD>&nbsp;</TD>";
                else
                        vCode = vCode + "<TD class=CalendarTD>" + m + "</TD>";
        }
        
        return vCode;
}
Calendar.prototype.format_day = function(vday) {
        var vNowDay = gNow.getDate();
        var vNowMonth = gNow.getMonth();
        var vNowYear = gNow.getFullYear();
        if (vday == vNowDay && this.gMonth == vNowMonth && this.gYear == vNowYear)
                return ("<FONT COLOR=\"8B0000\"><B>" + vday + "</B></FONT>");
        else
                return (vday);
}
Calendar.prototype.format_data = function(p_day) {
        var vData;
        var vMonth = 1 + this.gMonth;
        vMonth = (vMonth.toString().length < 2) ? "0" + vMonth : vMonth;
        var vY4 = new String(this.gYear);
        var vY2 = new String(this.gYear.substr(2,2));
        var vDD = (p_day.toString().length < 2) ? "0" + p_day : p_day;
        switch (this.gFormat) {
                case "MM\/DD\/YYYY" :
                        vData = vMonth + "\/" + vDD + "\/" + vY4;
                        break;
                case "MM\/DD\/YY" :
                        vData = vMonth + "\/" + vDD + "\/" + vY2;
                        break;
                case "MM-DD-YYYY" :
                        vData = vMonth + "-" + vDD + "-" + vY4;
                        break;
                case "MM-DD-YY" :
                        vData = vMonth + "-" + vDD + "-" + vY2;
                        break;
                case "DD\/MM\/YYYY" :
                        vData = vDD + "\/" + vMonth + "\/" + vY4;
                        break;
                case "DD\/MM\/YY" :
                        vData = vDD + "\/" + vMonth + "\/" + vY2;
                        break;
                case "DD-MM-YYYY" :
                        vData = vDD + "-" + vMonth + "-" + vY4;
                        break;
                case "DD-MM-YY" :
                        vData = vDD + "-" + vMonth + "-" + vY2;
                        break;
                default :
                        vData = vY4 + vMonth + vDD ;
        }
        return vData;
}
function Build(p_item, p_month, p_year, p_format) {
        var p_WinCal = ggWinCal;
        gCal = new Calendar(p_item, p_WinCal, p_month, p_year, p_format);
        // Customize your Calendar here..
        gCal.gBGColor="white";
        gCal.gLinkColor="black";
        gCal.gTextColor="black";
        gCal.gHeaderColor="darkgreen";
        // Choose appropriate show function
        if (gCal.gYearly)        gCal.showY();
        else        gCal.show();
}
function show_calendar() {
        /* 
                p_month : 0-11 for Jan-Dec; 12 for All Months.
                p_year        : 4-digit year
                p_format: Date format (mm/dd/yyyy, dd/mm/yy, ...)
                p_item        : Return Item.
        */
        p_item = arguments[0];
        if (arguments[1] == null)
                p_month = new String(gNow.getMonth());
        else
                p_month = arguments[1];
        if (arguments[2] == "" || arguments[2] == null)
                p_year = new String(gNow.getFullYear().toString());
        else
                p_year = arguments[2];
        if (arguments[3] == null)
                p_format = "YYYYMMDD";
        else
                p_format = arguments[3];
        vWinCal = window.open("", "Calendar", 
                "width=220,height=200,status=no,resizable=no,top=200,left=200");
        vWinCal.opener = self;
        ggWinCal = vWinCal;
        Build(p_item, p_month, p_year, p_format);
}
