<?php
#
# functions.php    version 2.12
#
# functions for administrative scripts and reports
#
# Copyright (C) 2016  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
#
# CHANGES:
# 90524-1503 - First Build
# 110708-1723 - Added HF precision option
# 111222-2124 - Added max stats bar chart function
# 120125-1235 - Small changes to max stats function to allow for total system stats
# 120213-1417 - Changes to allow for ra stats
# 120713-2137 - Added download function for max stats
# 130615-2111 - Added user authentication function and login lockout for 15 minutes after 10 failed login
# 130705-1957 - Added password encryption compatibility
# 130831-0919 - Changed to mysqli PHP functions
# 140319-1924 - Added MathZDC function
# 140918-1609 - Added admin QXZ print/echo function with length padding
# 141118-0109 - Added options for up to 9 ordered variables within QXZ function output
# 141229-1535 - Added code to QXZ allowing for on-the-fly mysql phrase lookups
# 150210-1358 - Added precision S default to 0 in sec_convert
# 150216-1528 - Fixed non-latin problem, issue #828
# 150514-1522 - Added lookup_gmt function, copied from agc/functions.php
# 150516-1206 - Added missing TZCODE segment to gmt_lookup function
# 160802-1149 - Added hex2rgb function
# 161102-0039 - Patched sec_convert function to display hours
#

##### BEGIN validate user login credentials, check for failed lock out #####
session_start();
function user_authorization($user, $pass, $user_option, $user_update)
{
    if (!empty($_SESSION['PHP_AUTH_USER']) && !empty($_SESSION['PHP_AUTH_PW'])) {
        $user = $_SESSION['PHP_AUTH_USER'];
        $pass = $_SESSION['PHP_AUTH_PW'];
    } else {
        if (sizeof($_POST) > 0) {
            $user = $_POST['username'];
            $pass = $_POST['password'];
        } else {
            $user = $_SESSION['PHP_AUTH_USER'];
            $pass = $_SESSION['PHP_AUTH_PW'];
        }
    }

    global $link;
    # require("dbconnect_mysqli.php");

    #############################################
    ##### START SYSTEM_SETTINGS LOOKUP #####
    $stmt = "SELECT use_non_latin,webroot_writable,pass_hash_enabled,pass_key,pass_cost FROM system_settings;";
    $rslt = mysql_to_mysqli($stmt, $link);
    if ($DB) {
        echo "$stmt\n";
    }
    $qm_conf_ct = mysqli_num_rows($rslt);
    if ($qm_conf_ct > 0) {
        $row                 = mysqli_fetch_row($rslt);
        $non_latin           = $row[0];
        $SSwebroot_writable  = $row[1];
        $SSpass_hash_enabled = $row[2];
        $SSpass_key          = $row[3];
        $SSpass_cost         = $row[4];
    }
    ##### END SETTINGS LOOKUP #####
    ###########################################

    $STARTtime             = date("U");
    $TODAY                 = date("Y-m-d");
    $NOW_TIME              = date("Y-m-d H:i:s");
    $ip                    = getenv("REMOTE_ADDR");
    $browser               = getenv("HTTP_USER_AGENT");
    $LOCK_over             = ($STARTtime - 900); # failed login lockout time is 15 minutes(900 seconds)
    $LOCK_trigger_attempts = 10;

    $user = preg_replace("/\'|\"|\\\\|;/", "", $user);
    $pass = preg_replace("/\'|\"|\\\\|;/", "", $pass);

    $passSQL = "pass='$pass'";

    if ($SSpass_hash_enabled > 0) {
        if (file_exists("../agc/bp.pl")) {
            $pass_hash = exec("../agc/bp.pl --pass=$pass");
        } else {
            $pass_hash = exec("../../agc/bp.pl --pass=$pass");
        }
        $pass_hash = preg_replace("/PHASH: |\n|\r|\t| /", '', $pass_hash);
        $passSQL   = "pass_hash='$pass_hash'";
    }

    $stmt = "SELECT count(*) from vicidial_users where user='$user' and $passSQL and user_level > 7 and active='Y' and ( (failed_login_count < $LOCK_trigger_attempts) or (UNIX_TIMESTAMP(last_login_date) < $LOCK_over) );";

    if ($user_option == 'REPORTS') {
        $stmt = "SELECT count(*) from vicidial_users where user='$user' and $passSQL and user_level > 6 and active='Y' and ( (failed_login_count < $LOCK_trigger_attempts) or (UNIX_TIMESTAMP(last_login_date) < $LOCK_over) );";
    }
    if ($user_option == 'REMOTE') {
        $stmt = "SELECT count(*) from vicidial_users where user='$user' and $passSQL and user_level > 3 and active='Y' and ( (failed_login_count < $LOCK_trigger_attempts) or (UNIX_TIMESTAMP(last_login_date) < $LOCK_over) );";
    }
    if ($user_option == 'QC') {
        $stmt = "SELECT count(*) from vicidial_users where user='$user' and $passSQL and user_level > 1 and active='Y' and ( (failed_login_count < $LOCK_trigger_attempts) or (UNIX_TIMESTAMP(last_login_date) < $LOCK_over) );";
    }
    if ($DB) {
        echo "|$stmt|\n";
    }
    if ($non_latin > 0) {
        $rslt = mysql_to_mysqli("SET NAMES 'UTF8'", $link);
    }


    $rslt = mysql_to_mysqli($stmt, $link);
    $row  = mysqli_fetch_row($rslt);
    $auth = $row[0];

    if ($auth < 1) {
        $auth_key = 'BAD';
        $stmt     = "SELECT failed_login_count,UNIX_TIMESTAMP(last_login_date) from vicidial_users where user='$user';";
        if ($non_latin > 0) {
            $rslt = mysql_to_mysqli("SET NAMES 'UTF8'", $link);
        }
        $rslt       = mysql_to_mysqli($stmt, $link);
        $cl_user_ct = mysqli_num_rows($rslt);
        if ($cl_user_ct > 0) {
            $row                = mysqli_fetch_row($rslt);
            $failed_login_count = $row[0];
            $last_login_date    = $row[1];

            if ($failed_login_count < $LOCK_trigger_attempts) {
                $stmt = "UPDATE vicidial_users set failed_login_count=(failed_login_count+1),last_ip='$ip' where user='$user';";
                $rslt = mysql_to_mysqli($stmt, $link);
            } else {
                if ($LOCK_over > $last_login_date) {
                    $stmt = "UPDATE vicidial_users set last_login_date=NOW(),failed_login_count=1,last_ip='$ip' where user='$user';";
                    $rslt = mysql_to_mysqli($stmt, $link);
                } else {
                    $auth_key = 'LOCK';
                }
            }
        }
        if ($SSwebroot_writable > 0) {
            $fp = fopen("./project_auth_entries.txt", "a");
            fwrite($fp, "ADMIN|FAIL|$NOW_TIME|$user|$auth_key|$ip|$browser|\n");
            fclose($fp);
        }
        unset($_SESSION['PHP_AUTH_USER']);
        unset($_SESSION['PHP_AUTH_PW']);
    } else {
        if ($user_update > 0) {
            $stmt = "UPDATE vicidial_users set last_login_date=NOW(),last_ip='$ip',failed_login_count=0 where user='$user';";
            $rslt = mysql_to_mysqli($stmt, $link);
        }
        $auth_key                  = 'GOOD';
        $_SESSION['PHP_AUTH_USER'] = $user;
        $_SESSION['PHP_AUTH_PW']   = $pass;
    }
    return $auth_key;
}
##### END validate user login credentials, check for failed lock out #####


##### BEGIN reformat seconds into HH:MM:SS or MM:SS #####
function sec_convert($sec, $precision)
{
    $sec = round($sec, 0);

    if ($sec < 1) {
        if ($precision == 'HF' || $precision == 'H') {
            return "0:00:00";
        } else {
            if ($precision == 'S') {
                return "0";
            } else {
                return "0:00";
            }

        }
    } else {
        if ($precision == 'HF') {
            $precision = 'H';
        } else {
            # if ( ($sec < 3600) and ($precision != 'S') ) {$precision='M';}
        }

        if ($precision == 'H') {
            $Fhours_H     = MathZDC($sec, 3600);
            $Fhours_H_int = floor($Fhours_H);
            $Fhours_H_int = intval("$Fhours_H_int");
            $Fhours_M     = ($Fhours_H - $Fhours_H_int);
            $Fhours_M     = ($Fhours_M * 60);
            $Fhours_M_int = floor($Fhours_M);
            $Fhours_M_int = intval("$Fhours_M_int");
            $Fhours_S     = ($Fhours_M - $Fhours_M_int);
            $Fhours_S     = ($Fhours_S * 60);
            $Fhours_S     = round($Fhours_S, 0);
            if ($Fhours_S < 10) {
                $Fhours_S = "0$Fhours_S";
            }
            if ($Fhours_M_int < 10) {
                $Fhours_M_int = "0$Fhours_M_int";
            }
            $Ftime = "$Fhours_H_int:$Fhours_M_int:$Fhours_S";
        }
        if ($precision == 'M') {
            $Fminutes_M     = MathZDC($sec, 60);
            $Fminutes_M_int = floor($Fminutes_M);
            $Fminutes_M_int = intval("$Fminutes_M_int");
            $Fminutes_S     = ($Fminutes_M - $Fminutes_M_int);
            $Fminutes_S     = ($Fminutes_S * 60);
            $Fminutes_S     = round($Fminutes_S, 0);
            if ($Fminutes_S < 10) {
                $Fminutes_S = "0$Fminutes_S";
            }
            $Ftime = "$Fminutes_M_int:$Fminutes_S";
        }
        if ($precision == 'S') {
            $Ftime = $sec;
        }
        return "$Ftime";
    }
}
##### END reformat seconds into HH:MM:SS or MM:SS #####


##### BEGIN counts like elements in an array, optional sort asc desc #####
function array_group_count($array, $sort = false)
{
    $tally_array = array();

    $i = 0;
    foreach (array_unique($array) as $value) {
        $count = 0;
        foreach ($array as $element) {
            if ($element == "$value") {
                $count++;
            }
        }

        $count           = sprintf("%010s", $count);
        $tally_array[$i] = "$count $value";
        $i++;
    }

    if ($sort == 'desc') {
        rsort($tally_array);
    } elseif ($sort == 'asc') {
        sort($tally_array);
    }

    return $tally_array;
}
##### END counts like elements in an array, optional sort asc desc #####


##### BEGIN bar chart using max stats data #####
function horizontal_bar_chart($campaign_id, $days_graph, $title, $link, $metric, $metric_name, $more_link, $END_DATE, $download_link)
{
    $stats_start_time = time();
    if ($END_DATE) {
        $Bstats_date[0] = $END_DATE;
    } else {
        $Bstats_date[0] = date("Y-m-d");
    }
    $Btotal_calls[0] = 0;
    $link_text       = '';
    $max_count       = 0;
    $i               = 0;
    $NWB             = "$download_link &nbsp; <a href=\"javascript:openNewWindow('help.php?ADD=99999";
    $NWE             = "')\"><IMG SRC=\"help.gif\" WIDTH=20 HEIGHT=20 BORDER=0 ALT=\"HELP\" ALIGN=TOP></A>";


    ### get stats for last X days
    $stmt = "SELECT stats_date,$metric from vicidial_daily_max_stats where campaign_id='$campaign_id' and stats_flag='OPEN' and stats_date<='$Bstats_date[0]';";
    if ($metric == 'total_calls_inbound_all') {
        $stmt = "SELECT stats_date,sum(total_calls) from vicidial_daily_max_stats where stats_type='INGROUP' and stats_flag='OPEN' and stats_date<='$Bstats_date[0]' group by stats_date;";
    }
    if ($metric == 'total_calls_outbound_all') {
        $stmt = "SELECT stats_date,sum(total_calls) from vicidial_daily_max_stats where stats_type='CAMPAIGN' and stats_flag='OPEN' and stats_date<='$Bstats_date[0]' group by stats_date;";
    }
    if ($metric == 'ra_total_calls') {
        $stmt = "SELECT stats_date,total_calls from vicidial_daily_ra_stats where stats_flag='OPEN' and stats_date<='$Bstats_date[0]' and user='$campaign_id';";
    }
    if ($metric == 'ra_concurrent_calls') {
        $stmt = "SELECT stats_date,max_calls from vicidial_daily_ra_stats where stats_flag='OPEN' and stats_date<='$Bstats_date[0]' and user='$campaign_id';";
    }
    $rslt            = mysql_to_mysqli($stmt, $link);
    $Xstats_to_print = mysqli_num_rows($rslt);
    if ($Xstats_to_print > 0) {
        $rowx            = mysqli_fetch_row($rslt);
        $Bstats_date[0]  = $rowx[0];
        $Btotal_calls[0] = $rowx[1];
        if ($max_count < $Btotal_calls[0]) {
            $max_count = $Btotal_calls[0];
        }
    }
    $stats_date_ARRAY = explode("-", $Bstats_date[0]);
    $stats_start_time = mktime(10, 10, 10, $stats_date_ARRAY[1], $stats_date_ARRAY[2], $stats_date_ARRAY[0]);
    while ($i <= $days_graph) {
        $Bstats_date[$i]  = date("Y-m-d", $stats_start_time);
        $Btotal_calls[$i] = 0;
        $stmt             = "SELECT stats_date,$metric from vicidial_daily_max_stats where campaign_id='$campaign_id' and stats_date='$Bstats_date[$i]';";
        if ($metric == 'total_calls_inbound_all') {
            $stmt = "SELECT stats_date,sum(total_calls) from vicidial_daily_max_stats where stats_date='$Bstats_date[$i]' and stats_type='INGROUP' group by stats_date;";
        }
        if ($metric == 'total_calls_outbound_all') {
            $stmt = "SELECT stats_date,sum(total_calls) from vicidial_daily_max_stats where stats_date='$Bstats_date[$i]' and stats_type='CAMPAIGN' group by stats_date;";
        }
        if ($metric == 'ra_total_calls') {
            $stmt = "SELECT stats_date,total_calls from vicidial_daily_ra_stats where stats_date='$Bstats_date[$i]' and user='$campaign_id';";
        }
        if ($metric == 'ra_concurrent_calls') {
            $stmt = "SELECT stats_date,max_calls from vicidial_daily_ra_stats where stats_date='$Bstats_date[$i]' and user='$campaign_id';";
        }
        echo "<!-- $i) $stmt \\-->\n";
        $rslt            = mysql_to_mysqli($stmt, $link);
        $Ystats_to_print = mysqli_num_rows($rslt);
        if ($Ystats_to_print > 0) {
            $rowx             = mysqli_fetch_row($rslt);
            $Btotal_calls[$i] = $rowx[1];
            if ($max_count < $Btotal_calls[$i]) {
                $max_count = $Btotal_calls[$i];
            }
        }
        $i++;
        $stats_start_time = ($stats_start_time - 86400);
    }
    if ($max_count < 1) {
        echo "<!-- no max stats cache summary information available -->";
    } else {
        if ($title == 'campaign') {
            $out_in_type = ' outbound';
        }
        if ($title == 'in-group') {
            $out_in_type = ' inbound';
        }
        if ($more_link > 0) {
            $link_text = "<a href=\"$PHP_SELF?ADD=999993&campaign_id=$campaign_id&stage=$title\"><font size=1>more summary stats...</font></a>";
        }
        echo "<table cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"white\" summary=\"Multiple day $metric_name.\" style=\"background-image:url(images/bg_fade.png); background-repeat:repeat-x; background-position:left top; width: 33em;\">\n";
        echo "<caption align=\"top\">$days_graph Day $out_in_type $metric_name for this $title &nbsp; $link_text  &nbsp; $NWB#max_stats$NWE<br /></caption>\n";
        echo "<tr>\n";
        echo "<th scope=\"col\" style=\"text-align: left; vertical-align:top;\"><span class=\"auraltext\">date</span> </th>\n";
        echo "<th scope=\"col\" style=\"text-align: left; vertical-align:top;\"><span class=\"auraltext\">$metric_name</span> </th>\n";
        echo "</tr>\n";

        $max_multi = MathZDC(400, $max_count);
        $i         = 0;
        while ($i < $days_graph) {
            $bar_width = intval($max_multi * $Btotal_calls[$i]);
            if ($Btotal_calls[$i] < 1) {
                $Btotal_calls[$i] = "-none-";
            }
            echo "<tr>\n";
            echo "<td class=\"chart_td\"><font style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 60%;\">$Bstats_date[$i] </font></td>\n";
            echo "<td class=\"chart_td\"><img src=\"images/bar.png\" alt=\"\" width=\"$bar_width\" height=\"10\" style=\"vertical-align: middle; margin: 2px 2px 2px 0;\"/><font style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 60%;\"> $Btotal_calls[$i]</font></td>\n";
            echo "</tr>\n";
            $i++;
        }
        echo "</table>\n";
    }
}
##### END bar chart using max stats data #####


##### BEGIN download max stats data #####
function download_max_system_stats($campaign_id, $days_graph, $title, $metric, $metric_name, $END_DATE)
{
    global $CSV_text, $link;
    $stats_start_time = time();
    if ($END_DATE) {
        $Bstats_date[0] = $END_DATE;
    } else {
        $Bstats_date[0] = date("Y-m-d");
    }
    $Btotal_calls[0] = 0;
    $link_text       = '';
    $i               = 0;

    ### get stats for last X days
    $stmt = "SELECT stats_date,$metric from vicidial_daily_max_stats where campaign_id='$campaign_id' and stats_flag='OPEN' and stats_date<='$Bstats_date[0]';";
    if ($metric == 'total_calls_inbound_all') {
        $stmt = "SELECT stats_date,sum(total_calls) from vicidial_daily_max_stats where stats_type='INGROUP' and stats_flag='OPEN' and stats_date<='$Bstats_date[0]' group by stats_date;";
    }
    if ($metric == 'total_calls_outbound_all') {
        $stmt = "SELECT stats_date,sum(total_calls) from vicidial_daily_max_stats where stats_type='CAMPAIGN' and stats_flag='OPEN' and stats_date<='$Bstats_date[0]' group by stats_date;";
    }
    if ($metric == 'ra_total_calls') {
        $stmt = "SELECT stats_date,total_calls from vicidial_daily_ra_stats where stats_flag='OPEN' and stats_date<='$Bstats_date[0]' and user='$campaign_id';";
    }
    if ($metric == 'ra_concurrent_calls') {
        $stmt = "SELECT stats_date,max_calls from vicidial_daily_ra_stats where stats_flag='OPEN' and stats_date<='$Bstats_date[0]' and user='$campaign_id';";
    }
    $rslt            = mysql_to_mysqli($stmt, $link);
    $Xstats_to_print = mysqli_num_rows($rslt);
    if ($Xstats_to_print > 0) {
        $rowx            = mysqli_fetch_row($rslt);
        $Bstats_date[0]  = $rowx[0];
        $Btotal_calls[0] = $rowx[1];
        if ($max_count < $Btotal_calls[0]) {
            $max_count = $Btotal_calls[0];
        }
    }
    $stats_date_ARRAY = explode("-", $Bstats_date[0]);
    $stats_start_time = mktime(10, 10, 10, $stats_date_ARRAY[1], $stats_date_ARRAY[2], $stats_date_ARRAY[0]);
    while ($i <= $days_graph) {
        $Bstats_date[$i]  = date("Y-m-d", $stats_start_time);
        $Btotal_calls[$i] = 0;
        $stmt             = "SELECT stats_date,$metric from vicidial_daily_max_stats where campaign_id='$campaign_id' and stats_date='$Bstats_date[$i]';";
        if ($metric == 'total_calls_inbound_all') {
            $stmt = "SELECT stats_date,sum(total_calls) from vicidial_daily_max_stats where stats_date='$Bstats_date[$i]' and stats_type='INGROUP' group by stats_date;";
        }
        if ($metric == 'total_calls_outbound_all') {
            $stmt = "SELECT stats_date,sum(total_calls) from vicidial_daily_max_stats where stats_date='$Bstats_date[$i]' and stats_type='CAMPAIGN' group by stats_date;";
        }
        if ($metric == 'ra_total_calls') {
            $stmt = "SELECT stats_date,total_calls from vicidial_daily_ra_stats where stats_date='$Bstats_date[$i]' and user='$campaign_id';";
        }
        if ($metric == 'ra_concurrent_calls') {
            $stmt = "SELECT stats_date,max_calls from vicidial_daily_ra_stats where stats_date='$Bstats_date[$i]' and user='$campaign_id';";
        }
        $rslt            = mysql_to_mysqli($stmt, $link);
        $Ystats_to_print = mysqli_num_rows($rslt);
        if ($Ystats_to_print > 0) {
            $rowx             = mysqli_fetch_row($rslt);
            $Btotal_calls[$i] = $rowx[1];
            if ($max_count < $Btotal_calls[$i]) {
                $max_count = $Btotal_calls[$i];
            }
        }
        $i++;
        $stats_start_time = ($stats_start_time - 86400);
    }

    if ($title == 'campaign') {
        $out_in_type = ' outbound';
    }
    if ($title == 'in-group') {
        $out_in_type = ' inbound';
    }
    $CSV_text .= "\"$days_graph Day $out_in_type $metric_name for this $title\"\n";

    if ($max_count < 1) {
        $CSV_text .= "\"no max stats cache summary information available\"\n";
    } else {
        $CSV_text .= "\"DATE\",\"$metric_name\"\n";

        $i = 0;
        while ($i < $days_graph) {
            $bar_width = intval($max_multi * $Btotal_calls[$i]);
            if ($Btotal_calls[$i] < 1) {
                $Btotal_calls[$i] = "-none-";
            }
            $CSV_text .= "\"$Bstats_date[$i]\",\"$Btotal_calls[$i]\"\n";
            $i++;
        }

        $CSV_text .= "\n\n";
    }
}
##### END download max stats data #####


##### LOOKUP GMT, FINDS THE CURRENT GMT OFFSET FOR A PHONE NUMBER #####

function lookup_gmt($phone_code, $USarea, $state, $LOCAL_GMT_OFF_STD, $Shour, $Smin, $Ssec, $Smon, $Smday, $Syear, $postalgmt, $postal_code, $owner, $USprefix)
{
    global $link;

    $postalgmt_found = 0;
    if ((preg_match("/POSTAL/i", $postalgmt)) && (strlen($postal_code) > 4)) {
        if (preg_match('/^1$/', $phone_code)) {
            $stmt    = "select postal_code,state,GMT_offset,DST,DST_range,country,country_code from vicidial_postal_codes where country_code='$phone_code' and postal_code LIKE \"$postal_code%\";";
            $rslt    = mysql_to_mysqli($stmt, $link);
            $pc_recs = mysqli_num_rows($rslt);
            if ($pc_recs > 0) {
                $row        = mysqli_fetch_row($rslt);
                $gmt_offset = $row[2];
                $gmt_offset = preg_replace("/\+/i", "", $gmt_offset);
                $dst        = $row[3];
                $dst_range  = $row[4];
                $PC_processed++;
                $postalgmt_found++;
                $post++;
            }
        }
    }
    if (($postalgmt == "TZCODE") && (strlen($owner) > 1)) {
        $dst_range  = '';
        $dst        = 'N';
        $gmt_offset = 0;

        $stmt    = "select GMT_offset from vicidial_phone_codes where tz_code='$owner' and country_code='$phone_code' limit 1;";
        $rslt    = mysql_to_mysqli($stmt, $link);
        $pc_recs = mysqli_num_rows($rslt);
        if ($pc_recs > 0) {
            $row        = mysqli_fetch_row($rslt);
            $gmt_offset = $row[0];
            $gmt_offset = preg_replace("/\+/i", "", $gmt_offset);
            $PC_processed++;
            $postalgmt_found++;
            $post++;
        }

        $stmt    = "select distinct DST_range from vicidial_phone_codes where tz_code='$owner' and country_code='$phone_code' order by DST_range desc limit 1;";
        $rslt    = mysql_to_mysqli($stmt, $link);
        $pc_recs = mysqli_num_rows($rslt);
        if ($pc_recs > 0) {
            $row       = mysqli_fetch_row($rslt);
            $dst_range = $row[0];
            if (strlen($dst_range) > 2) {
                $dst = 'Y';
            }
        }
    }
    if ((preg_match("/NANPA/i", $tz_method)) && (strlen($USarea) > 2) && (strlen($USprefix) > 2)) {
        $stmt    = "select GMT_offset,DST from vicidial_nanpa_prefix_codes where areacode='$USarea' and prefix='$USprefix';";
        $rslt    = mysql_to_mysqli($stmt, $link);
        $pc_recs = mysqli_num_rows($rslt);
        if ($pc_recs > 0) {
            $row        = mysqli_fetch_row($rslt);
            $gmt_offset = $row[0];
            $gmt_offset = preg_replace("/\+/i", "", $gmt_offset);
            $dst        = $row[1];
            $dst_range  = '';
            if ($dst == 'Y') {
                $dst_range = 'SSM-FSN';
            }
            $PC_processed++;
            $postalgmt_found++;
            $post++;
        }
    }
    if ($postalgmt_found < 1) {
        $PC_processed = 0;
        ### UNITED STATES ###
        if ($phone_code == '1') {
            $stmt    = "select country_code,country,areacode,state,GMT_offset,DST,DST_range,geographic_description from vicidial_phone_codes where country_code='$phone_code' and areacode='$USarea';";
            $rslt    = mysql_to_mysqli($stmt, $link);
            $pc_recs = mysqli_num_rows($rslt);
            if ($pc_recs > 0) {
                $row        = mysqli_fetch_row($rslt);
                $gmt_offset = $row[4];
                $gmt_offset = preg_replace("/\+/i", "", $gmt_offset);
                $dst        = $row[5];
                $dst_range  = $row[6];
                $PC_processed++;
            }
        }
        ### MEXICO ###
        if ($phone_code == '52') {
            $stmt    = "select country_code,country,areacode,state,GMT_offset,DST,DST_range,geographic_description from vicidial_phone_codes where country_code='$phone_code' and areacode='$USarea';";
            $rslt    = mysql_to_mysqli($stmt, $link);
            $pc_recs = mysqli_num_rows($rslt);
            if ($pc_recs > 0) {
                $row        = mysqli_fetch_row($rslt);
                $gmt_offset = $row[4];
                $gmt_offset = preg_replace("/\+/i", "", $gmt_offset);
                $dst        = $row[5];
                $dst_range  = $row[6];
                $PC_processed++;
            }
        }
        ### AUSTRALIA ###
        if ($phone_code == '61') {
            $stmt    = "select country_code,country,areacode,state,GMT_offset,DST,DST_range,geographic_description from vicidial_phone_codes where country_code='$phone_code' and state='$state';";
            $rslt    = mysql_to_mysqli($stmt, $link);
            $pc_recs = mysqli_num_rows($rslt);
            if ($pc_recs > 0) {
                $row        = mysqli_fetch_row($rslt);
                $gmt_offset = $row[4];
                $gmt_offset = preg_replace("/\+/i", "", $gmt_offset);
                $dst        = $row[5];
                $dst_range  = $row[6];
                $PC_processed++;
            }
        }
        ### ALL OTHER COUNTRY CODES ###
        if (!$PC_processed) {
            $PC_processed++;
            $stmt    = "select country_code,country,areacode,state,GMT_offset,DST,DST_range,geographic_description from vicidial_phone_codes where country_code='$phone_code';";
            $rslt    = mysql_to_mysqli($stmt, $link);
            $pc_recs = mysqli_num_rows($rslt);
            if ($pc_recs > 0) {
                $row        = mysqli_fetch_row($rslt);
                $gmt_offset = $row[4];
                $gmt_offset = preg_replace("/\+/i", "", $gmt_offset);
                $dst        = $row[5];
                $dst_range  = $row[6];
                $PC_processed++;
            }
        }
    }

    ### Find out if DST to raise the gmt offset ###
    $AC_GMT_diff  = ($gmt_offset - $LOCAL_GMT_OFF_STD);
    $AC_localtime = mktime(($Shour + $AC_GMT_diff), $Smin, $Ssec, $Smon, $Smday, $Syear);
    $hour         = date("H", $AC_localtime);
    $min          = date("i", $AC_localtime);
    $sec          = date("s", $AC_localtime);
    $mon          = date("m", $AC_localtime);
    $mday         = date("d", $AC_localtime);
    $wday         = date("w", $AC_localtime);
    $year         = date("Y", $AC_localtime);
    $dsec         = ((($hour * 3600) + ($min * 60)) + $sec);

    $AC_processed = 0;
    if ((!$AC_processed) and ($dst_range == 'SSM-FSN')) {
        if ($DBX) {
            print "     " . _QXZ("Second Sunday March to First Sunday November") . "\n";
        }
        #**********************************************************************
        # SSM-FSN
        #     This is returns 1 if Daylight Savings Time is in effect and 0 if
        #       Standard time is in effect.
        #     Based on Second Sunday March to First Sunday November at 2 am.
        #     INPUTS:
        #       mm              INTEGER       Month.
        #       dd              INTEGER       Day of the month.
        #       ns              INTEGER       Seconds into the day.
        #       dow             INTEGER       Day of week (0=Sunday, to 6=Saturday)
        #     OPTIONAL INPUT:
        #       timezone        INTEGER       hour difference UTC - local standard time
        #                                      (DEFAULT is blank)
        #                                     make calculations based on UTC time,
        #                                     which means shift at 10:00 UTC in April
        #                                     and 9:00 UTC in October
        #     OUTPUT:
        #                       INTEGER       1 = DST, 0 = not DST
        #
        # S  M  T  W  T  F  S
        # 1  2  3  4  5  6  7
        # 8  9 10 11 12 13 14
        #15 16 17 18 19 20 21
        #22 23 24 25 26 27 28
        #29 30 31
        #
        # S  M  T  W  T  F  S
        #    1  2  3  4  5  6
        # 7  8  9 10 11 12 13
        #14 15 16 17 18 19 20
        #21 22 23 24 25 26 27
        #28 29 30 31
        #
        #**********************************************************************

        $USACAN_DST = 0;
        $mm         = $mon;
        $dd         = $mday;
        $ns         = $dsec;
        $dow        = $wday;

        if ($mm < 3 || $mm > 11) {
            $USACAN_DST = 0;
        } elseif ($mm >= 4 and $mm <= 10) {
            $USACAN_DST = 1;
        } elseif ($mm == 3) {
            if ($dd > 13) {
                $USACAN_DST = 1;
            } elseif ($dd >= ($dow + 8)) {
                if ($timezone) {
                    if ($dow == 0 and $ns < (7200 + $timezone * 3600)) {
                        $USACAN_DST = 0;
                    } else {
                        $USACAN_DST = 1;
                    }
                } else {
                    if ($dow == 0 and $ns < 7200) {
                        $USACAN_DST = 0;
                    } else {
                        $USACAN_DST = 1;
                    }
                }
            } else {
                $USACAN_DST = 0;
            }
        } elseif ($mm == 11) {
            if ($dd > 7) {
                $USACAN_DST = 0;
            } elseif ($dd < ($dow + 1)) {
                $USACAN_DST = 1;
            } elseif ($dow == 0) {
                if ($timezone) { # UTC calculations
                    if ($ns < (7200 + ($timezone - 1) * 3600)) {
                        $USACAN_DST = 1;
                    } else {
                        $USACAN_DST = 0;
                    }
                } else { # local time calculations
                    if ($ns < 7200) {
                        $USACAN_DST = 1;
                    } else {
                        $USACAN_DST = 0;
                    }
                }
            } else {
                $USACAN_DST = 0;
            }
        } # end of month checks
        if ($DBX) {
            print "     DST: $USACAN_DST\n";
        }
        if ($USACAN_DST) {
            $gmt_offset++;
        }
        $AC_processed++;
    }

    if ((!$AC_processed) and ($dst_range == 'FSA-LSO')) {
        if ($DBX) {
            print "     " . _QXZ("First Sunday April to Last Sunday October") . "\n";
        }
        #**********************************************************************
        # FSA-LSO
        #     This is returns 1 if Daylight Savings Time is in effect and 0 if
        #       Standard time is in effect.
        #     Based on first Sunday in April and last Sunday in October at 2 am.
        #**********************************************************************

        $USA_DST = 0;
        $mm      = $mon;
        $dd      = $mday;
        $ns      = $dsec;
        $dow     = $wday;

        if ($mm < 4 || $mm > 10) {
            $USA_DST = 0;
        } elseif ($mm >= 5 and $mm <= 9) {
            $USA_DST = 1;
        } elseif ($mm == 4) {
            if ($dd > 7) {
                $USA_DST = 1;
            } elseif ($dd >= ($dow + 1)) {
                if ($timezone) {
                    if ($dow == 0 and $ns < (7200 + $timezone * 3600)) {
                        $USA_DST = 0;
                    } else {
                        $USA_DST = 1;
                    }
                } else {
                    if ($dow == 0 and $ns < 7200) {
                        $USA_DST = 0;
                    } else {
                        $USA_DST = 1;
                    }
                }
            } else {
                $USA_DST = 0;
            }
        } elseif ($mm == 10) {
            if ($dd < 25) {
                $USA_DST = 1;
            } elseif ($dd < ($dow + 25)) {
                $USA_DST = 1;
            } elseif ($dow == 0) {
                if ($timezone) { # UTC calculations
                    if ($ns < (7200 + ($timezone - 1) * 3600)) {
                        $USA_DST = 1;
                    } else {
                        $USA_DST = 0;
                    }
                } else { # local time calculations
                    if ($ns < 7200) {
                        $USA_DST = 1;
                    } else {
                        $USA_DST = 0;
                    }
                }
            } else {
                $USA_DST = 0;
            }
        } # end of month checks

        if ($DBX) {
            print "     DST: $USA_DST\n";
        }
        if ($USA_DST) {
            $gmt_offset++;
        }
        $AC_processed++;
    }

    if ((!$AC_processed) and ($dst_range == 'LSM-LSO')) {
        if ($DBX) {
            print "     " . _QXZ("Last Sunday March to Last Sunday October") . "\n";
        }
        #**********************************************************************
        #     This is s 1 if Daylight Savings Time is in effect and 0 if
        #       Standard time is in effect.
        #     Based on last Sunday in March and last Sunday in October at 1 am.
        #**********************************************************************

        $GBR_DST = 0;
        $mm      = $mon;
        $dd      = $mday;
        $ns      = $dsec;
        $dow     = $wday;

        if ($mm < 3 || $mm > 10) {
            $GBR_DST = 0;
        } elseif ($mm >= 4 and $mm <= 9) {
            $GBR_DST = 1;
        } elseif ($mm == 3) {
            if ($dd < 25) {
                $GBR_DST = 0;
            } elseif ($dd < ($dow + 25)) {
                $GBR_DST = 0;
            } elseif ($dow == 0) {
                if ($timezone) { # UTC calculations
                    if ($ns < (3600 + ($timezone - 1) * 3600)) {
                        $GBR_DST = 0;
                    } else {
                        $GBR_DST = 1;
                    }
                } else { # local time calculations
                    if ($ns < 3600) {
                        $GBR_DST = 0;
                    } else {
                        $GBR_DST = 1;
                    }
                }
            } else {
                $GBR_DST = 1;
            }
        } elseif ($mm == 10) {
            if ($dd < 25) {
                $GBR_DST = 1;
            } elseif ($dd < ($dow + 25)) {
                $GBR_DST = 1;
            } elseif ($dow == 0) {
                if ($timezone) { # UTC calculations
                    if ($ns < (3600 + ($timezone - 1) * 3600)) {
                        $GBR_DST = 1;
                    } else {
                        $GBR_DST = 0;
                    }
                } else { # local time calculations
                    if ($ns < 3600) {
                        $GBR_DST = 1;
                    } else {
                        $GBR_DST = 0;
                    }
                }
            } else {
                $GBR_DST = 0;
            }
        } # end of month checks
        if ($DBX) {
            print "     DST: $GBR_DST\n";
        }
        if ($GBR_DST) {
            $gmt_offset++;
        }
        $AC_processed++;
    }
    if ((!$AC_processed) and ($dst_range == 'LSO-LSM')) {
        if ($DBX) {
            print "     " . _QXZ("Last Sunday October to Last Sunday March") . "\n";
        }
        #**********************************************************************
        #     This is s 1 if Daylight Savings Time is in effect and 0 if
        #       Standard time is in effect.
        #     Based on last Sunday in October and last Sunday in March at 1 am.
        #**********************************************************************

        $AUS_DST = 0;
        $mm      = $mon;
        $dd      = $mday;
        $ns      = $dsec;
        $dow     = $wday;

        if ($mm < 3 || $mm > 10) {
            $AUS_DST = 1;
        } elseif ($mm >= 4 and $mm <= 9) {
            $AUS_DST = 0;
        } elseif ($mm == 3) {
            if ($dd < 25) {
                $AUS_DST = 1;
            } elseif ($dd < ($dow + 25)) {
                $AUS_DST = 1;
            } elseif ($dow == 0) {
                if ($timezone) { # UTC calculations
                    if ($ns < (3600 + ($timezone - 1) * 3600)) {
                        $AUS_DST = 1;
                    } else {
                        $AUS_DST = 0;
                    }
                } else { # local time calculations
                    if ($ns < 3600) {
                        $AUS_DST = 1;
                    } else {
                        $AUS_DST = 0;
                    }
                }
            } else {
                $AUS_DST = 0;
            }
        } elseif ($mm == 10) {
            if ($dd < 25) {
                $AUS_DST = 0;
            } elseif ($dd < ($dow + 25)) {
                $AUS_DST = 0;
            } elseif ($dow == 0) {
                if ($timezone) { # UTC calculations
                    if ($ns < (3600 + ($timezone - 1) * 3600)) {
                        $AUS_DST = 0;
                    } else {
                        $AUS_DST = 1;
                    }
                } else { # local time calculations
                    if ($ns < 3600) {
                        $AUS_DST = 0;
                    } else {
                        $AUS_DST = 1;
                    }
                }
            } else {
                $AUS_DST = 1;
            }
        } # end of month checks
        if ($DBX) {
            print "     DST: $AUS_DST\n";
        }
        if ($AUS_DST) {
            $gmt_offset++;
        }
        $AC_processed++;
    }

    if ((!$AC_processed) and ($dst_range == 'FSO-LSM')) {
        if ($DBX) {
            print "     " . _QXZ("First Sunday October to Last Sunday March") . "\n";
        }
        #**********************************************************************
        #   TASMANIA ONLY
        #     This is s 1 if Daylight Savings Time is in effect and 0 if
        #       Standard time is in effect.
        #     Based on first Sunday in October and last Sunday in March at 1 am.
        #**********************************************************************

        $AUST_DST = 0;
        $mm       = $mon;
        $dd       = $mday;
        $ns       = $dsec;
        $dow      = $wday;

        if ($mm < 3 || $mm > 10) {
            $AUST_DST = 1;
        } elseif ($mm >= 4 and $mm <= 9) {
            $AUST_DST = 0;
        } elseif ($mm == 3) {
            if ($dd < 25) {
                $AUST_DST = 1;
            } elseif ($dd < ($dow + 25)) {
                $AUST_DST = 1;
            } elseif ($dow == 0) {
                if ($timezone) { # UTC calculations
                    if ($ns < (3600 + ($timezone - 1) * 3600)) {
                        $AUST_DST = 1;
                    } else {
                        $AUST_DST = 0;
                    }
                } else { # local time calculations
                    if ($ns < 3600) {
                        $AUST_DST = 1;
                    } else {
                        $AUST_DST = 0;
                    }
                }
            } else {
                $AUST_DST = 0;
            }
        } elseif ($mm == 10) {
            if ($dd > 7) {
                $AUST_DST = 1;
            } elseif ($dd >= ($dow + 1)) {
                if ($timezone) {
                    if ($dow == 0 and $ns < (7200 + $timezone * 3600)) {
                        $AUST_DST = 0;
                    } else {
                        $AUST_DST = 1;
                    }
                } else {
                    if ($dow == 0 and $ns < 3600) {
                        $AUST_DST = 0;
                    } else {
                        $AUST_DST = 1;
                    }
                }
            } else {
                $AUST_DST = 0;
            }
        } # end of month checks
        if ($DBX) {
            print "     DST: $AUST_DST\n";
        }
        if ($AUST_DST) {
            $gmt_offset++;
        }
        $AC_processed++;
    }

    if ((!$AC_processed) and ($dst_range == 'FSO-FSA')) {
        if ($DBX) {
            print "     " . _QXZ("Sunday in October to First Sunday in April") . "\n";
        }
        #**********************************************************************
        # FSO-FSA
        #   2008+ AUSTRALIA ONLY (country code 61)
        #     This is returns 1 if Daylight Savings Time is in effect and 0 if
        #       Standard time is in effect.
        #     Based on first Sunday in October and first Sunday in April at 1 am.
        #**********************************************************************

        $AUSE_DST = 0;
        $mm       = $mon;
        $dd       = $mday;
        $ns       = $dsec;
        $dow      = $wday;

        if ($mm < 4 or $mm > 10) {
            $AUSE_DST = 1;
        } elseif ($mm >= 5 and $mm <= 9) {
            $AUSE_DST = 0;
        } elseif ($mm == 4) {
            if ($dd > 7) {
                $AUSE_DST = 0;
            } elseif ($dd >= ($dow + 1)) {
                if ($timezone) {
                    if ($dow == 0 and $ns < (3600 + $timezone * 3600)) {
                        $AUSE_DST = 1;
                    } else {
                        $AUSE_DST = 0;
                    }
                } else {
                    if ($dow == 0 and $ns < 7200) {
                        $AUSE_DST = 1;
                    } else {
                        $AUSE_DST = 0;
                    }
                }
            } else {
                $AUSE_DST = 1;
            }
        } elseif ($mm == 10) {
            if ($dd >= 8) {
                $AUSE_DST = 1;
            } elseif ($dd >= ($dow + 1)) {
                if ($timezone) {
                    if ($dow == 0 and $ns < (7200 + $timezone * 3600)) {
                        $AUSE_DST = 0;
                    } else {
                        $AUSE_DST = 1;
                    }
                } else {
                    if ($dow == 0 and $ns < 3600) {
                        $AUSE_DST = 0;
                    } else {
                        $AUSE_DST = 1;
                    }
                }
            } else {
                $AUSE_DST = 0;
            }
        } # end of month checks
        if ($DBX) {
            print "     DST: $AUSE_DST\n";
        }
        if ($AUSE_DST) {
            $gmt_offset++;
        }
        $AC_processed++;
    }

    if ((!$AC_processed) and ($dst_range == 'FSO-TSM')) {
        if ($DBX) {
            print "     " . _QXZ("First Sunday October to Third Sunday March") . "\n";
        }
        #**********************************************************************
        #     This is s 1 if Daylight Savings Time is in effect and 0 if
        #       Standard time is in effect.
        #     Based on first Sunday in October and third Sunday in March at 1 am.
        #**********************************************************************

        $NZL_DST = 0;
        $mm      = $mon;
        $dd      = $mday;
        $ns      = $dsec;
        $dow     = $wday;

        if ($mm < 3 || $mm > 10) {
            $NZL_DST = 1;
        } elseif ($mm >= 4 and $mm <= 9) {
            $NZL_DST = 0;
        } elseif ($mm == 3) {
            if ($dd < 14) {
                $NZL_DST = 1;
            } elseif ($dd < ($dow + 14)) {
                $NZL_DST = 1;
            } elseif ($dow == 0) {
                if ($timezone) { # UTC calculations
                    if ($ns < (3600 + ($timezone - 1) * 3600)) {
                        $NZL_DST = 1;
                    } else {
                        $NZL_DST = 0;
                    }
                } else { # local time calculations
                    if ($ns < 3600) {
                        $NZL_DST = 1;
                    } else {
                        $NZL_DST = 0;
                    }
                }
            } else {
                $NZL_DST = 0;
            }
        } elseif ($mm == 10) {
            if ($dd > 7) {
                $NZL_DST = 1;
            } elseif ($dd >= ($dow + 1)) {
                if ($timezone) {
                    if ($dow == 0 and $ns < (7200 + $timezone * 3600)) {
                        $NZL_DST = 0;
                    } else {
                        $NZL_DST = 1;
                    }
                } else {
                    if ($dow == 0 and $ns < 3600) {
                        $NZL_DST = 0;
                    } else {
                        $NZL_DST = 1;
                    }
                }
            } else {
                $NZL_DST = 0;
            }
        } # end of month checks
        if ($DBX) {
            print "     DST: $NZL_DST\n";
        }
        if ($NZL_DST) {
            $gmt_offset++;
        }
        $AC_processed++;
    }

    if ((!$AC_processed) and ($dst_range == 'LSS-FSA')) {
        if ($DBX) {
            print "     " . _QXZ("Last Sunday in September to First Sunday in April") . "\n";
        }
        #**********************************************************************
        # LSS-FSA
        #   2007+ NEW ZEALAND (country code 64)
        #     This is returns 1 if Daylight Savings Time is in effect and 0 if
        #       Standard time is in effect.
        #     Based on last Sunday in September and first Sunday in April at 1 am.
        #**********************************************************************

        $NZLN_DST = 0;
        $mm       = $mon;
        $dd       = $mday;
        $ns       = $dsec;
        $dow      = $wday;

        if ($mm < 4 || $mm > 9) {
            $NZLN_DST = 1;
        } elseif ($mm >= 5 && $mm <= 9) {
            $NZLN_DST = 0;
        } elseif ($mm == 4) {
            if ($dd > 7) {
                $NZLN_DST = 0;
            } elseif ($dd >= ($dow + 1)) {
                if ($timezone) {
                    if ($dow == 0 && $ns < (3600 + $timezone * 3600)) {
                        $NZLN_DST = 1;
                    } else {
                        $NZLN_DST = 0;
                    }
                } else {
                    if ($dow == 0 && $ns < 7200) {
                        $NZLN_DST = 1;
                    } else {
                        $NZLN_DST = 0;
                    }
                }
            } else {
                $NZLN_DST = 1;
            }
        } elseif ($mm == 9) {
            if ($dd < 25) {
                $NZLN_DST = 0;
            } elseif ($dd < ($dow + 25)) {
                $NZLN_DST = 0;
            } elseif ($dow == 0) {
                if ($timezone) { # UTC calculations
                    if ($ns < (3600 + ($timezone - 1) * 3600)) {
                        $NZLN_DST = 0;
                    } else {
                        $NZLN_DST = 1;
                    }
                } else { # local time calculations
                    if ($ns < 3600) {
                        $NZLN_DST = 0;
                    } else {
                        $NZLN_DST = 1;
                    }
                }
            } else {
                $NZLN_DST = 1;
            }
        } # end of month checks
        if ($DBX) {
            print "     DST: $NZLN_DST\n";
        }
        if ($NZLN_DST) {
            $gmt_offset++;
        }
        $AC_processed++;
    }

    if ((!$AC_processed) and ($dst_range == 'TSO-LSF')) {
        if ($DBX) {
            print "     " . _QXZ("Third Sunday October to Last Sunday February") . "\n";
        }
        #**********************************************************************
        # TSO-LSF
        #     This is returns 1 if Daylight Savings Time is in effect and 0 if
        #       Standard time is in effect. Brazil
        #     Based on Third Sunday October to Last Sunday February at 1 am.
        #**********************************************************************

        $BZL_DST = 0;
        $mm      = $mon;
        $dd      = $mday;
        $ns      = $dsec;
        $dow     = $wday;

        if ($mm < 2 || $mm > 10) {
            $BZL_DST = 1;
        } elseif ($mm >= 3 and $mm <= 9) {
            $BZL_DST = 0;
        } elseif ($mm == 2) {
            if ($dd < 22) {
                $BZL_DST = 1;
            } elseif ($dd < ($dow + 22)) {
                $BZL_DST = 1;
            } elseif ($dow == 0) {
                if ($timezone) { # UTC calculations
                    if ($ns < (3600 + ($timezone - 1) * 3600)) {
                        $BZL_DST = 1;
                    } else {
                        $BZL_DST = 0;
                    }
                } else { # local time calculations
                    if ($ns < 3600) {
                        $BZL_DST = 1;
                    } else {
                        $BZL_DST = 0;
                    }
                }
            } else {
                $BZL_DST = 0;
            }
        } elseif ($mm == 10) {
            if ($dd < 22) {
                $BZL_DST = 0;
            } elseif ($dd < ($dow + 22)) {
                $BZL_DST = 0;
            } elseif ($dow == 0) {
                if ($timezone) { # UTC calculations
                    if ($ns < (3600 + ($timezone - 1) * 3600)) {
                        $BZL_DST = 0;
                    } else {
                        $BZL_DST = 1;
                    }
                } else { # local time calculations
                    if ($ns < 3600) {
                        $BZL_DST = 0;
                    } else {
                        $BZL_DST = 1;
                    }
                }
            } else {
                $BZL_DST = 1;
            }
        } # end of month checks
        if ($DBX) {
            print "     DST: $BZL_DST\n";
        }
        if ($BZL_DST) {
            $gmt_offset++;
        }
        $AC_processed++;
    }

    if (!$AC_processed) {
        if ($DBX) {
            print "     " . _QXZ("No DST Method Found") . "\n";
        }
        if ($DBX) {
            print "     DST: 0\n";
        }
        $AC_processed++;
    }

    return $gmt_offset;
}


function mysql_to_mysqli($stmt, $link)
{
    $rslt = mysqli_query($link, $stmt);
    return $rslt;
}


function MathZDC($dividend, $divisor, $quotient = 0)
{
    if ($divisor == 0) {
        return $quotient;
    } else if ($dividend == 0) {
        return 0;
    } else {
        return ($dividend / $divisor);
    }
}

function use_archive_table($table_name)
{
    global $link;
    $tbl_stmt = "show tables like '" . $table_name . "_archive'";
    $tbl_rslt = mysql_to_mysqli($tbl_stmt, $link);
    if (mysqli_num_rows($tbl_rslt) > 0) {
        $tbl_row = mysqli_fetch_row($tbl_rslt);
        return $tbl_row[0];
    } else {
        return $table_name;
    }
}

# function to print/echo content, options for length, alignment and ordered internal variables are included
function _QXZ($English_text, $sprintf = 0, $align = "l", $v_one = '', $v_two = '', $v_three = '', $v_four = '', $v_five = '', $v_six = '', $v_seven = '', $v_eight = '', $v_nine = '')
{
    global $SSenable_languages, $SSlanguage_method, $VUselected_language, $link;

    // navy code for language translation
    /*$English_text_value = trim($English_text,"-/.,:%");
    $English_text_value = trim($English_text_value);
    if(!empty($English_text_value)) {
        $English_text_value = strtoupper($English_text_value);
        $stmtA     = "SELECT en from tbl_lang where en='$English_text_value';";
        $rsltA     = mysql_to_mysqli($stmtA, $link);
        $lang_rows = mysqli_num_rows($rsltA);
        if ($lang_rows > 0) {
        } else {
            $stmtL  = "INSERT INTO tbl_lang (en) VALUES ('$English_text_value');";
            $rsltL  = mysql_to_mysqli($stmtL, $link);
        }
    }*/

    if ($SSenable_languages == '1') {
        if ($SSlanguage_method != 'DISABLED') {
            if ((strlen($VUselected_language) > 0) and ($VUselected_language != 'default English')) {
                if ($SSlanguage_method == 'MYSQL') {
                    $stmt  = "SELECT translated_text from vicidial_language_phrases where english_text='$English_text' and language_id='$VUselected_language';";
                    $rslt  = mysql_to_mysqli($stmt, $link);
                    $sl_ct = mysqli_num_rows($rslt);
                    if ($sl_ct > 0) {
                        $row          = mysqli_fetch_row($rslt);
                        $English_text = $row[0];
                    }
                }
            }
        }
    }

    if (preg_match("/%\ds/", $English_text)) {
        $English_text = preg_replace("/%1s/", $v_one, $English_text);
        $English_text = preg_replace("/%2s/", $v_two, $English_text);
        $English_text = preg_replace("/%3s/", $v_three, $English_text);
        $English_text = preg_replace("/%4s/", $v_four, $English_text);
        $English_text = preg_replace("/%5s/", $v_five, $English_text);
        $English_text = preg_replace("/%6s/", $v_six, $English_text);
        $English_text = preg_replace("/%7s/", $v_seven, $English_text);
        $English_text = preg_replace("/%8s/", $v_eight, $English_text);
        $English_text = preg_replace("/%9s/", $v_nine, $English_text);
    }
    ### uncomment to test output
    #   $English_text = str_repeat('*', strlen($English_text));
    if ($sprintf > 0) {
        if ($align == "r") {
            $fmt = "%" . $sprintf . "s";
        } else {
            $fmt = "%-" . $sprintf . "s";
        }
        $English_text = sprintf($fmt, $English_text);
    }
    return $English_text;
}

function hex2rgb($hex)
{
    $hex = str_replace("#", "", $hex);

    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    $rgb = array(
        $r,
        $g,
        $b
    );
    return $rgb; // returns an array with the rgb values
}

function createDateRangeArray($strDateFrom, $strDateTo)
{
    $aryRange = array();

    $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
    $iDateTo   = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

    if ($iDateTo >= $iDateFrom) {
        array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
        while ($iDateFrom < $iDateTo) {
            $iDateFrom += 86400; // add 24 hours
            array_push($aryRange, date('Y-m-d', $iDateFrom));
        }
    }
    return $aryRange;
}


function convertToTimezone($timestamp, $fromTimezone, $toTimezone, $format = 'Y-m-d H:i:s')
{
    $datetime = is_numeric($timestamp) ? DateTime::createFromFormat('U', $timestamp, new DateTimeZone($fromTimezone)) : new DateTime($timestamp, new DateTimeZone($fromTimezone));

    $datetime->setTimezone(new DateTimeZone($toTimezone));

    return $datetime->format($format);
}

/**
 * [primo_get_time_options description]
 * @return [type] [description]
 */
function primo_get_time_options()
{
    $time_options = "";
    for ($hh = 0; $hh <= 23; $hh++) {
        if (strlen($hh) == 1) {
            $hh = "0" . $hh;
        }
        for ($mm = 0; $mm <= 59; $mm += 15) {
            if (strlen($mm) == 1) {
                $mm = "0" . $mm;
            }
            /*
            if($hh == '00' && $mm == '00'){
            $mm = $mm + 15;
            }
            */
            if ($hh < 12) {
                $ampm = 'AM';
            } else {
                $ampm = 'PM';
            }
            $time_options .= "<option value=$hh:$mm:00>$hh:$mm:00 $ampm</option>";
        }
    }

    return $time_options;
}

/**
 * Executes the cURL GET Request.
 * Use this GET cURL Request to execute the GET Endpoints.
 * @param  string       $url        API URL
 * @param  array        $param      Post variables
 * @return              $resp       resultset returned by api
 */
function execute_get_curl($url, $param = array())
{
    // Get cURL resource
    $curl = curl_init();
    // Set some options - we are passing in a useragent too here
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_SSL_VERIFYPEER => false
    ));
    // Send the request & save response to $resp
    $resp = curl_exec($curl);
    if (!$resp) {
        die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
    }
    $info = curl_getinfo($curl);
    // Close request to clear up some resources
    curl_close($curl);

    return $resp;
}

/**
 * [get_permission_state description]
 * @param  string $user       [description]
 * @param  string $permission [description]
 * @return array             [description]
 */
function get_permission_state($user, $permission)
{
    global $link;

    $permissions = array();
    $stmt        = "SELECT * FROM tbl_permissions WHERE user='" . $user . "' LIMIT 1;";
    $rslt        = mysql_to_mysqli($stmt, $link);

    $counter = mysqli_num_rows($rslt);
    if ($counter > 0) {
        $row         = mysqli_fetch_assoc($rslt);
        $permissions = explode(',', $row['permissions']);
         //print_r($permissions);//exit;
    }

    if (in_array(strtolower(str_replace(' ', '_', $permission)), $permissions)) {
        return true;
    } else {
        return false;
    }
}

/**
 * [get_backbone description]
 * @param  [type] $user [description]
 * @return [type]       [description]
 */
function get_backbone($user)
{
    ////////////////
    // users Menu //
    ////////////////
    $users_menu = array(
        array(
            "text" => "users",
            "icon" => "icon-user",
            "state" => array(
                "selected" => get_permission_state($user, 'users')
            )
        ),
        array(
            "text" => "User Group",
            "icon" => "icon-users",
            "state" => array(
                "selected" => get_permission_state($user, 'User Group')
            )
        ),
        array(
            "text" => "Phones",
            "icon" => "fa fa-phone",
            "state" => array(
                "selected" => get_permission_state($user, 'Phones')
            )
        ),
        array(
            "text" => "Remote Agents",
            "icon" => "fa fa-user-secret",
            "state" => array(
                "selected" => get_permission_state($user, 'Remote Agents')
            )
        )
    );

    ///////////////
    // Data Menu //
    ///////////////

    // lead Tools Sub Menu
    $lead_tools_submenu = array(
        array(
            "text" => "Add And Modify Lead",
            "icon" => "fa fa-database",
            "state" => array(
                "selected" => get_permission_state($user, 'Add And Modify Lead')
            )
        ),
        array(
            "text" => "Basic Lead Tool",
            "icon" => "fa fa-bullseye",
            "state" => array(
                "selected" => get_permission_state($user, 'Basic Lead Tool')
            )
        ),
        array(
            "text" => "Copy Leads Tool",
            "icon" => "fa fa-cc",
            "state" => array(
                "selected" => get_permission_state($user, 'Copy Leads Tool')
            )
        ),
        array(
            "text" => "Advanced Lead Tool",
            "icon" => "fa fa-dot-circle-o",
            "state" => array(
                "selected" => get_permission_state($user, 'Advanced Lead Tool')
            )
        ),
        array(
            "text" => "Advanced Lead Tool2",
            "icon" => "fa fa-crop",
            "state" => array(
                "selected" => get_permission_state($user, 'Advanced Lead Tool2')
            )
        ),
        array(
            "text" => "Move Leads Tool",
            "icon" => "fa fa-random",
            "state" => array(
                "selected" => get_permission_state($user, 'Move Leads Tool')
            )
        )
    );

    $data_menu = array(
        array(
            "text" => "Lists",
            "icon" => "fa fa-list-ul",
            "state" => array(
                "selected" => get_permission_state($user, 'Lists')
            )
        ),
        array(
            "text" => "Import Leads",
            "icon" => "fa fa-upload",
            "state" => array(
                "selected" => get_permission_state($user, 'Import Leads')
            )
        ),
        array(
            "text" => "Data Search",
            "icon" => "fa fa-search",
            "state" => array(
                "selected" => get_permission_state($user, 'Data Search')
            )
        ),
        array(
            "text" => "Advanced Lead Search",
            "icon" => "fa fa-asterisk",
            "state" => array(
                "selected" => get_permission_state($user, 'Advanced Lead Search')
            )
        ),
        array(
            "text" => "Custom Lead Search",
            "icon" => "fa fa-futbol-o",
            "state" => array(
                "selected" => get_permission_state($user, 'Custom Lead Search')
            )
        ),
        array(
            "text" => "Lead Tools",
            "icon" => "fa fa-wrench",
            "state" => array(
                "selected" => false
            ),
            "children" => $lead_tools_submenu
        ),
        array(
            "text" => "Splitting Lists",
            "icon" => "fa fa-expand",
            "state" => array(
                "selected" => get_permission_state($user, 'Splitting Lists')
            )
        ),
        array(
            "text" => "Merging Lists",
            "icon" => "fa fa-compress",
            "state" => array(
                "selected" => get_permission_state($user, 'Merging Lists')
            )
        ),
        array(
            "text" => "Manage DNC",
            "icon" => "fa fa-ban",
            "state" => array(
                "selected" => get_permission_state($user, 'Manage DNC')
            )
        ),
        array(
            "text" => "Import DNC",
            "icon" => "fa fa-ban",
            "state" => array(
                "selected" => get_permission_state($user, 'Import DNC')
            )
        ),
        array(
            "text" => "CallBacks Bulk Change",
            "icon" => "fa-headphones",
            "state" => array(
                "selected" => get_permission_state($user, 'CallBacks Bulk Change')
            )
        ),
        array(
            "text" => "Move Callback",
            "icon" => "fa fa-phone-square",
            "state" => array(
                "selected" => get_permission_state($user, 'Move Callback')
            )
        ),
        array(
            "text" => "Copy Custom Fields",
            "icon" => "fa fa-files-o",
            "state" => array(
                "selected" => get_permission_state($user, 'Copy Custom Fields')
            )
        )
    );

    ////////////////////
    // Campaigns Menu //
    ////////////////////

    $campaigns_menu = array(
        array(
            "text" => "Campaigns",
            "icon" => "fa fa-bullhorn",
            "state" => array(
                "selected" => get_permission_state($user, 'Campaigns')
            )
        ),
        array(
            "text" => "AC-CID",
            "icon" => "fa fa-bolt",
            "state" => array(
                "selected" => get_permission_state($user, 'AC-CID')
            )
        ),
        array(
            "text" => "CID Group",
            "icon" => "fa fa-bolt",
            "state" => array(
                "selected" => get_permission_state($user, 'CID Group')
            )
        ),
        array(
            "text" => "Campaign Statuses",
            "icon" => "fa fa-puzzle-piece",
            "state" => array(
                "selected" => get_permission_state($user, 'Campaign Statuses')
            )
        ),
        array(
            "text" => "Agent Form Labels",
            "icon" => "fa fa-book",
            "state" => array(
                "selected" => get_permission_state($user, 'Agent Form Labels')
            )
        ),
        array(
            "text" => "Scripts",
            "icon" => "fa fa-file-code-o",
            "state" => array(
                "selected" => get_permission_state($user, 'Scripts')
            )
        ),
        array(
            "text" => "Reset Campaign Lists",
            "icon" => "fa fa-refresh",
            "state" => array(
                "selected" => get_permission_state($user, 'Reset Campaign Lists')
            )
        )
    );



    $numbers_submenu = array(
        array(
            "text" => "Inbound DIDs",
            "icon" => "fa fa-phone-square",
            "state" => array(
                "selected" => get_permission_state($user, 'Inbound DIDs')
            )
        ),
        array(
            "text" => "Order DIDs",
            "icon" => "fa fa-user-md",
            "state" => array(
                "selected" => get_permission_state($user, 'Order DIDs')
            )
        ),
        array(
            "text" => "Order History",
            "icon" => "fa fa-phone-square",
            "state" => array(
                "selected" => get_permission_state($user, 'Order History')
            )
        )
    );

    //////////////////
    // Inbound Menu //
    //////////////////

    $inbound_menu = array(
         array(
            "text" => "Numbers",
            "icon" => "fa fa-phone-square",
            "state" => array(
                "selected" => false
            ),
            "children" => $numbers_submenu
        ),array(
            "text" => "Inbound Group",
            "icon" => "fa fa-user-md",
            "state" => array(
                "selected" => get_permission_state($user, 'Inbound Group')
            )
        ),
        array(
            "text" => "Call Menus",
            "icon" => "fa fa-phone-square",
            "state" => array(
                "selected" => get_permission_state($user, 'Call Menus')
            )
        ),
        array(
            "text" => "Copy Call Menu",
            "icon" => "fa fa-phone-square",
            "state" => array(
                "selected" => get_permission_state($user, 'Copy Call Menu')
            )
        ),
        array(
            "text" => "Add Call Menu",
            "icon" => "fa fa-phone-square",
            "state" => array(
                "selected" => get_permission_state($user, 'Add Call Menu')
            )
        ),
        array(
            "text" => "Social Media",
            "icon" => "fa fa-phone-square",
            "state" => array(
                "selected" => get_permission_state($user, 'Social Media')
            )
        ),
        array(
            "text" => "Website Feeds",
            "icon" => "fa fa-phone-square",
            "state" => array(
                "selected" => get_permission_state($user, 'Website Feeds')
            )
        ),
        array(
            "text" => "Dynamic Script Builder",
            "icon" => "fa fa-phone-square",
            "state" => array(
                "selected" => get_permission_state($user, 'Dynamic Script Builder')
            )
        ),
        array(
            "text" => "Filter Phone Group",
            "icon" => "fa fa-phone-square",
            "state" => array(
                "selected" => get_permission_state($user, 'Filter Phone Group')
            )
        ),
        array(
            "text" => "Add Delete FPG Number",
            "icon" => "fa fa-phone-square",
            "state" => array(
                "selected" => get_permission_state($user, 'Add Delete FPG Number')
            )
        )
    );

    /////////////////
    // Avatar Menu //
    /////////////////

    $avatar_menu = array(
        array(
            "text" => "Avatar Names",
            "icon" => "fa fa-indent",
            "state" => array(
                "selected" => get_permission_state($user, 'Avatar Names')
            )
        ),
        array(
            "text" => "Sound Files",
            "icon" => "fa fa-indent",
            "state" => array(
                "selected" => get_permission_state($user, 'Sound Files')
            )
        ),
        array(
            "text" => "Manage Avatar",
            "icon" => "fa fa-indent",
            "state" => array(
                "selected" => get_permission_state($user, 'Manage Avatar')
            )
        )
    );

    /////////////////////
    // SQL Filter Menu //
    /////////////////////

    $sql_filter_menu = array(
        array(
            "text" => "Manage SQL Filter",
            "icon" => "fa fa-filter",
            "state" => array(
                "selected" => get_permission_state($user, 'Manage SQL Filter')
            )
        )
    );

    /////////////////////
    // IVR Builder Menu //
    /////////////////////

    $ivr_builder_menu = array(
        array(
            "text" => "Manage IVR Builder",
            "icon" => "fa fa-tasks",
            "state" => array(
                "selected" => get_permission_state($user, 'Manage IVR Builder')
            )
        )
    );

    /////////////////////
    // Report Builder Menu //
    /////////////////////

    $report_builder_menu = array(
        array(
            "text" => "Manage Report Builder",
            "icon" => "fa fa-volume-up",
            "state" => array(
                "selected" => get_permission_state($user, 'Manage Report Builder')
            )
        )
    );

    //////////////////
    // Reports Menu //
    //////////////////

    // Inbound Reports Sub Menu
    $inbound_reports = array(
        array(
            "text" => "Inbound Report",
            "icon" => "fa fa-sign-in",
            "state" => array(
                "selected" => get_permission_state($user, 'Inbound Report')
            )
        ),
        array(
            "text" => "Inbound Service Level Report",
            "icon" => "fa fa-sign-in",
            "state" => array(
                "selected" => get_permission_state($user, 'Inbound Service Level Report')
            )
        ),
        array(
            "text" => "Inbound Summary Hourly Report",
            "icon" => "fa fa-sign-in",
            "state" => array(
                "selected" => get_permission_state($user, 'Inbound Summary Hourly Report')
            )
        ),
        array(
            "text" => "Inbound Daily Report",
            "icon" => "fa fa-sign-in",
            "state" => array(
                "selected" => get_permission_state($user, 'Inbound Daily Report')
            )
        ),
        array(
            "text" => "Inbound DID Report",
            "icon" => "fa fa-sign-in",
            "state" => array(
                "selected" => get_permission_state($user, 'Inbound DID Report')
            )
        ),
        array(
            "text" => "Inbound SMS Report",
            "icon" => "fa fa-compress",
            "state" => array(
                "selected" => get_permission_state($user, 'Inbound SMS Report')
            )
        )
    );

    // Outbound Reports Sub Menu
    $outbound_reports_submenu = array(
        array(
            "text" => "Outbound Calling Report",
            "icon" => "fa fa-sign-out",
            "state" => array(
                "selected" => get_permission_state($user, 'Outbound Calling Report')
            )
        ),
        array(
            "text" => "Recent Sales Report",
            "icon" => "fa fa-hand-peace",
            "state" => array(
                "selected" => get_permission_state($user, 'Recent Sales Report')
            )
        ),
        array(
            "text" => "Outbound Summary Interval Report",
            "icon" => "fa fa-hand-peace",
            "state" => array(
                "selected" => get_permission_state($user, 'Outbound Summary Interval Report')
            )
        )
    );

    // User Reports Sub Menu
    $user_reports_submenu = array(
        array(
            "text" => "User Time Clock Report",
            "icon" => "fa fa-clock-o",
            "state" => array(
                "selected" => get_permission_state($user, 'User Time Clock Report')
            )
        ),
        array(
            "text" => "User Timesheet Report",
            "icon" => "fa fa-calendar",
            "state" => array(
                "selected" => get_permission_state($user, 'User Timesheet Report')
            )
        )
    );

    // Agent Reports Sub Menu
    $agent_reports_submenu = array(
        array(
            "text" => "Live Agents Report",
            "icon" => "fa fa-clock-o",
            "state" => array(
                "selected" => get_permission_state($user, 'Live Agents Report')
            )
        ),
        array(
            "text" => "Agent Performance Report",
            "icon" => "fa fa-user",
            "state" => array(
                "selected" => get_permission_state($user, 'Agent Performance Report')
            )
        ),
        array(
            "text" => "Agent Time Detail Report",
            "icon" => "fa fa-calendar",
            "state" => array(
                "selected" => get_permission_state($user, 'Agent Time Detail Report')
            )
        ),
        array(
            "text" => "QA Report",
            "icon" => "fa fa-twitch",
            "state" => array(
                "selected" => get_permission_state($user, 'QA Report')
            )
        ),
        array(
            "text" => "Agent DID Report",
            "icon" => "fa fa-soccer-ball-o",
            "state" => array(
                "selected" => get_permission_state($user, 'Agent DID Report')
            )
        ),
        array(
            "text" => "User Group Login Report",
            "icon" => "fa fa-soccer-ball-o",
            "state" => array(
                "selected" => get_permission_state($user, 'User Group Login Report')
            )
        ),
        array(
            "text" => "Weekly Login Report",
            "icon" => "fa fa-soccer-ball-o",
            "state" => array(
                "selected" => get_permission_state($user, 'Weekly Login Report')
            )
        ),
        array(
            "text" => "Agent Daily and Stats",
            "icon" => "fa fa-soccer-ball-o",
            "state" => array(
                "selected" => get_permission_state($user, 'Agent Daily and Stats')
            )
        ),
        array(
            "text" => "Agent Lag Report",
            "icon" => "fa fa-soccer-ball-o",
            "state" => array(
                "selected" => get_permission_state($user, 'Agent Lag Report')
            )
        )
    );

    // Export Reports Sub Menu
    $export_reports_submenu = array(
        array(
            "text" => "Export Leads Report",
            "icon" => "fa fa-empire",
            "state" => array(
                "selected" => get_permission_state($user, 'Export Leads Report')
            )
        ),
        array(
            "text" => "Export Calls Report",
            "icon" => "fa fa-empire",
            "state" => array(
                "selected" => get_permission_state($user, 'Export Calls Report')
            )
        )
    );

    $custom_reports_submenu = array(
        array(
            "text" => "Dispo Report",
            "icon" => "fa fa-bar-chart",
            "state" => array(
                "selected" => get_permission_state($user, 'Dispo Report')
            )
        ),
    );

    $reports_menu = array(
        array(
            "text" => "Inbound Reports",
            "icon" => "fa fa-indent",
            "state" => array(
                "selected" => get_permission_state($user, 'Inbound Reports')
            ),
            "children" => $inbound_reports
        ),
        array(
            "text" => "Outbound Reports",
            "icon" => "fa fa-outdent",
            "state" => array(
                "selected" => get_permission_state($user, 'Outbound Reports')
            ),
            "children" => $outbound_reports_submenu
        ),
        array(
            "text" => "User Reports",
            "icon" => "fa fa-area-chart",
            "state" => array(
                "selected" => get_permission_state($user, 'User Reports')
            ),
            "children" => $user_reports_submenu
        ),
        array(
            "text" => "Agent Reports",
            "icon" => "fa fa-line-chart",
            "state" => array(
                "selected" => get_permission_state($user, 'Agent Reports')
            ),
            "children" => $agent_reports_submenu
        ),
        array(
            "text" => "Export Reports",
            "icon" => "fa fa-download",
            "state" => array(
                "selected" => get_permission_state($user, 'Export Reports')
            ),
            "children" => $export_reports_submenu
        ),
        array(
            "text" => "Custom Reports",
            "icon" => "fa fa-line-chart",
            "state" => array(
                "selected" => get_permission_state($user, 'Custom Reports')
            ),
            "children" => $custom_reports_submenu
        ),
        array(
            "text" => "Search Callbacks",
            "icon" => "fa fa-clock-o",
            "state" => array(
                "selected" => get_permission_state($user, 'Search Callbacks')
            )
        ),
        array(
            "text" => "Today Due Callbacks",
            "icon" => "fa fa-hourglass",
            "state" => array(
                "selected" => get_permission_state($user, 'Today Due Callbacks')
            )
        ),
        array(
            "text" => "System Carrier Logs",
            "icon" => "fa fa-certificate",
            "state" => array(
                "selected" => get_permission_state($user, 'System Carrier Logs')
            )
        ),
        array(
            "text" => "Carrier Summary Report",
            "icon" => "fa fa-certificate",
            "state" => array(
                "selected" => get_permission_state($user, 'Carrier Summary Report')
            )
        )
    );

    //////////////////////
    // System Setttings //
    //////////////////////

    // Call Times Sub Menu
    $calltimes_submenu = array(
        array(
            "text" => "Call Times",
            "icon" => "fa fa-clock-o",
            "state" => array(
                "selected" => get_permission_state($user, 'Call Times')
            )
        ),
        array(
            "text" => "State Call Times",
            "icon" => "fa fa-building",
            "state" => array(
                "selected" => get_permission_state($user, 'State Call Times')
            )
        ),
        array(
            "text" => "Holidays",
            "icon" => "fa fa-archive",
            "state" => array(
                "selected" => get_permission_state($user, 'Holidays')
            )
        )
    );

    $salesforce_menu = array(
        array(
            "text" => "Salesforce Log",
            "icon" => "fa fa-globe",
            "state" => array(
                "selected" => get_permission_state($user, 'Salesforce Log')
            )
        )
    );

    $settings_menu = array(
        array(
            "text" => "Connectivity",
            "icon" => "fa fa-globe",
            "state" => array(
                "selected" => get_permission_state($user, 'Connectivity')
            )
        ),
        array(
            "text" => "Global Statuses",
            "icon" => "fa fa-globe",
            "state" => array(
                "selected" => get_permission_state($user, 'Global Statuses')
            )
        ),
        array(
            "text" => "Dialler Branding",
            "icon" => "fa fa-television",
            "state" => array(
                "selected" => get_permission_state($user, 'Dialler Branding')
            )
        ),
        array(
            "text"     => "Audio Store",
            "icon"     => "fa fa-volume-up",
            "state"    => array(
                "selected" => get_permission_state($user, 'Audio Store')
            )
        ),
        array(
            "text" => "Call Times Menu",
            "icon" => "fa fa-file-code-o",
            "state" => array(
                "selected" => false
            ),
            "children" => $calltimes_submenu
        ),
        array(
            "text" => "SMS Templates",
            "icon" => "fa fa-paper-plane",
            "state" => array(
                "selected" => get_permission_state($user, 'SMS Templates')
            )
        ),
        array(
            "text" => "Email Templates",
            "icon" => "fa fa-envelope",
            "state" => array(
                "selected" => get_permission_state($user, 'Email Templates')
            )
        ),
        array(
            "text" => "Firewall Manager",
            "icon" => "fa fa-globe",
            "state" => array(
                "selected" => get_permission_state($user, 'Firewall Manager')
            )
        ),
        array(
            "text" => "Prefix Trimmer",
            "icon" => "fa fa-eraser",
            "state" => array(
                "selected" => get_permission_state($user, 'Prefix Trimmer')
            )
        ),
        array(
            "text" => "Dialler Settings",
            "icon" => "fa fa-globe",
            "state" => array(
                "selected" => get_permission_state($user, 'Dialler Settings')
            )
        ),
        array(
            "text" => "Order CLI",
            "icon" => "fa fa-globe",
            "state" => array(
                "selected" => get_permission_state($user, 'Order CLI')
            )
        ),
        array(
            "text" => "CRM Integration",
            "icon" => "fa fa-globe",
            "state" => array(
                "selected" => get_permission_state($user, 'CRM Integration')
            )
        ),
        array(
            "text" => "Agent Options",
            "icon" => "fa fa-globe",
            "state" => array(
                "selected" => get_permission_state($user, 'Agent Options')
            )
        ),
        array(
            "text" => "Peer IP",
            "icon" => "fa fa-globe",
            "state" => array(
                "selected" => get_permission_state($user, 'Peer IP')
            )
        ),
        array(
            "text" => "IPS",
            "icon" => "fa fa-globe",
            "state" => array(
                "selected" => get_permission_state($user, 'IPS')
            )
        ),
        array(
            "text" => "Carrier Logs",
            "icon" => "fa fa-indent",
            "state" => array(
                "selected" => get_permission_state($user, 'Carrier Logs')
            )
        ),
        array(
            "text" => "QA Questions",
            "icon" => "fa fa-question",
            "state" => array(
                "selected" => get_permission_state($user, 'QA Questions')
            )
        ),
        array(
            "text" => "QA Portal",
            "icon" => "fa fa-balance-scale",
            "state" => array(
                "selected" => get_permission_state($user, 'QA Portal')
            )
        ),
        array(
            "text" => "Recording Portal",
            "icon" => "fa fa-microphone",
            "state" => array(
                "selected" => get_permission_state($user, 'Recording Portal')
            )
        ),
        array(
            "text" => "Change Language",
            "icon" => "fa fa-language",
            "state" => array(
                "selected" => get_permission_state($user, 'Change Language')
            )
        ),
        array(
            "text" => "Manager Chat",
            "icon" => "fa fa-comment",
            "state" => array(
                "selected" => get_permission_state($user, 'Manager Chat')
            )
        ),
        array(
            "text" => "Wallboard",
            "icon" => "fa fa-laptop",
            "state" => array(
                "selected" => get_permission_state($user, 'Wallboard')
            )
        )
    );

    ///////////////
    // All Menus //
    ///////////////
    $all_menus = array(
        array(
            "text" => "users Menu",
            "icon" => "fa fa-users",
            "state" => array(
                "selected" => false
            ),
            "children" => $users_menu
        ),
        array(
            "text" => "Data",
            "icon" => "fa fa-database",
            "state" => array(
                "selected" => get_permission_state($user, 'Data')
            ),
            "children" => $data_menu
        ),
        array(
            "text" => "Campaigns Menu",
            "icon" => "fa fa-bullhorn",
            "state" => array(
                "selected" => false
            ),
            "children" => $campaigns_menu
        ),
        array(
            "text" => "Inbound",
            "icon" => "fa fa-fast-forward",
            "state" => array(
                "selected" => get_permission_state($user, 'Inbound')
            ),
            "children" => $inbound_menu
        ),
        array(
            "text" => "Avatar",
            "icon" => "fa fa-fast-forward",
            "state" => array(
                "selected" => get_permission_state($user, 'Avatar')
            ),
            "children" => $avatar_menu
        ),
        array(
            "text" => "SQL Filter Menu",
            "icon" => "fa fa-fast-forward",
            "state" => array(
                "selected" => get_permission_state($user, 'SQL Filter')
            ),
            "children" => $sql_filter_menu
        ),
        array(
            "text" => "IVR Builder Menu",
            "icon" => "fa fa-file-text-o",
            "state" => array(
                "selected" => get_permission_state($user, 'IVR Builder')
            ),
            "children" => $ivr_builder_menu
        ),
        array(
            "text" => "Report Builder Menu",
            "icon" => "fa fa-fast-forward",
            "state" => array(
                "selected" => get_permission_state($user, 'Report Builder')
            ),
            "children" => $report_builder_menu
        ),
        array(
            "text" => "Reports",
            "icon" => "fa-bar-chart",
            "state" => array(
                "selected" => get_permission_state($user, 'Reports')
            ),
            "children" => $reports_menu
        ),
         array(
            "text" => "CRM Integration",
            "icon" => "icon-settings",
            "state" => array(
                "selected" => get_permission_state($user, 'CRM Integration')
            ),
            "children" => $salesforce_menu
        ),
        array(
            "text" => "Settings",
            "icon" => "icon-settings",
            "state" => array(
                "selected" => get_permission_state($user, 'Settings')
            ),
            "children" => $settings_menu
        )
    );

    //////////
    // Tree //
    //////////
    $backbone[] = array(
        "text" => "admin Interface",
        "state" => array(
            "selected" => false
        ),
        "children" => $all_menus
    );
    return $backbone;
}

/**
 * [get_custom_fields_header description]
 * @param  [type] $link       [description]
 * @param  [type] $CF_list_id [description]
 * @return [type]             [description]
 */
function get_custom_fields_header($link, $CF_list_id)
{
    $custom_fields_header = array();

    $stmt = "DESC custom_$CF_list_id";
    $rslt = mysql_to_mysqli($stmt, $link);
    while ($row = mysqli_fetch_assoc($rslt)) {
        if ($row['Field'] != "lead_id") {
            $custom_fields_header[] = $row['Field'];
        }
    }
    $custom_fields_header = array_unique(array_filter($custom_fields_header));

    return $custom_fields_header;
}

/**
 * [get_allowed_campaigns description]
 * @param  [string] $user_group [description]
 * @param  [resource] $link       [description]
 * @param  string $where_clause [description]
 * @return [string]   $allowed_campaigns_csv          [description]
 */
function get_allowed_campaigns($link, $user_group, $active = '', $qc_enabled = '', $where_clause = '', $mode = '') {
    $qc_enabled_sql       = '';
    $where_qc_enabled_sql = '';
    if($qc_enabled == 'Y') {
        $qc_enabled_sql = " AND qc_enabled='Y' AND active='Y'";
        $where_qc_enabled_sql = " WHERE qc_enabled='Y' AND active='Y'";
    }

    $stmt = "SELECT allowed_campaigns,admin_viewable_groups FROM vicidial_user_groups WHERE user_group='$user_group';";
    $rslt = mysql_to_mysqli($stmt, $link);
    $row  = mysqli_fetch_row($rslt);

    $allowed_campaigns     = $row[0];
    $admin_viewable_groups = $row[1];

    // Allowed admin viewable groups
    $raw_admin_viewable_groups   = '';
    $admin_viewable_groups_csv   = '';
    $where_admin_viewable_groups = '';
    if ((!preg_match('/\-\-ALL\-\-/i', $admin_viewable_groups)) and (strlen($admin_viewable_groups) > 3)) {
        $raw_admin_viewable_groups = preg_replace("/ -/", '', $admin_viewable_groups);
        $admin_viewable_groups_csv = preg_replace("/ /", "','", $raw_admin_viewable_groups);
        $admin_viewable_groups_csv = "'---ALL---','$admin_viewable_groups_csv'";
        $where_admin_viewable_groups = "WHERE user_group IN($admin_viewable_groups_csv)";
    } else {
        $where_admin_viewable_groups = '';
        $qc_enabled_sql = $where_qc_enabled_sql;
    }

    // $where_admin_viewable_groups = preg_replace("/,''/", '', $where_admin_viewable_groups);

    $active_sql = '';
    if($qc_enabled == '' && $active != '' ) {
        $active_sql = "AND active='".$active."'";
    }

    $allowed_campaigns_csv = '';
    $admin_viewable_campaigns_csv = '';
    $admin_viewable_campaigns_arr = array();
    $stmt = "SELECT campaign_id,campaign_name,active FROM vicidial_campaigns $where_admin_viewable_groups $qc_enabled_sql $active_sql ORDER BY campaign_id;";
    $rslt = mysql_to_mysqli($stmt, $link);
    while($row  = mysqli_fetch_row($rslt)) {
        $admin_viewable_campaigns_csv .= $row[0] . ",";
        $admin_viewable_campaigns_arr['campaign_id'][]   = $row[0];
        $admin_viewable_campaigns_arr['campaign_name'][] = $row[1];
        $admin_viewable_campaigns_arr['active'][]        = $row[2];
    }
    $admin_viewable_campaigns_csv = rtrim($admin_viewable_campaigns_csv, ',');

    // Allowed Campaigns
    $final_allowed_campaigns_csv = '';
    $raw_allowed_campaigns = '';
    if ((!preg_match('/\-ALL/i', $allowed_campaigns))) {

        $raw_allowed_campaigns = preg_replace("/ -/", '', $allowed_campaigns);
        $raw_allowed_campaigns = preg_replace("/ /", ",", $raw_allowed_campaigns);

    } elseif(preg_match('/\-ALL/i', $allowed_campaigns)) {

        if($mode == 'array') {
            return get_all_campaigns($link, $active, $qc_enabled, $mode);
        }

        // Remove single quotes from prepared csv
        // 'UKinjur','UKenergy' => UKinjur,UKenergy
        $raw_allowed_campaigns = str_replace("'", '', get_all_campaigns($link, $active, $qc_enabled));
    } else {
        // Do Nothing
    }

    $exploded_admin_viewable_campaigns = explode(',', $admin_viewable_campaigns_csv);
    $exploded_allowed_campaigns = explode(',', $raw_allowed_campaigns);

    $final_allowed_campaigns_arr = array();
    foreach($exploded_admin_viewable_campaigns as $camp) {
        if(in_array($camp, $exploded_allowed_campaigns)) {
            $final_allowed_campaigns_arr[] = $camp;
        }
    }
    $final_allowed_campaigns_csv = "'";
    $final_allowed_campaigns_csv .= implode("','", $final_allowed_campaigns_arr) . "'";

    if ($where_clause == 'where') {
        if($qc_enabled == 'Y') {
            $qc_enabled_sql = " AND qc_enabled='Y' AND active='Y'";
        }

        $where_allowed_campaigns = "WHERE campaign_id IN($final_allowed_campaigns_csv) $qc_enabled_sql";
        return $where_allowed_campaigns;
    }

    return $final_allowed_campaigns_csv;
}

/**
 * [get_all_campaigns description]
 * @param  [type] $link [description]
 * @return [type]       [description]
 */
function get_all_campaigns($link, $active = '', $qc_enabled = '', $mode = '') {
    $all_campaigns = '';

    $qc_enabled_sql = '';
    $where_qc_enabled_sql = '';
    if($qc_enabled == 'Y') {
        $qc_enabled_sql       = " AND qc_enabled='Y' AND active='Y'";
        $where_qc_enabled_sql = " WHERE qc_enabled='Y' AND active='Y'";
    }
    $qc_enabled_sql = $where_qc_enabled_sql;

    $active_sql = '';
    if($qc_enabled == '' && $active != '' ) {
        $active_sql = "WHERE active='".$active."'";
    }

    $stmt     = "SELECT campaign_id,campaign_name,active FROM vicidial_campaigns $qc_enabled_sql $active_sql ORDER BY campaign_id;";
    $rslt     = mysql_to_mysqli($stmt, $link);
    $num_rows = mysqli_num_rows($rslt);
    $admin_viewable_campaigns_arr = array();
    if ($num_rows > 0) {
        while ($row = mysqli_fetch_assoc($rslt)) {
            $all_campaigns .= "'".$row['campaign_id']."',";
            $admin_viewable_campaigns_arr['campaign_id'][]   = $row['campaign_id'];
            $admin_viewable_campaigns_arr['campaign_name'][] = $row['campaign_name'];
            $admin_viewable_campaigns_arr['active'][]        = $row['active'];
        }
        $all_campaigns = rtrim($all_campaigns, ',');
    }

    if($mode == 'array') {
        return $admin_viewable_campaigns_arr;
    }

    return $all_campaigns;
}

/**
 * [get_all_user_groups description]
 * @param  [type] $link [description]
 * @return [type]       [description]
 */
function get_all_user_groups($link) {
    $all_user_groups = "'---ALL---',";

    $stmt     = "SELECT user_group FROM vicidial_user_groups ORDER BY user_group;";
    $rslt     = mysql_to_mysqli($stmt, $link);
    $num_rows = mysqli_num_rows($rslt);
    if ($num_rows > 0) {
        while ($row = mysqli_fetch_assoc($rslt)) {
            $all_user_groups .= "'".$row['user_group']."',";
        }
    }
    $all_user_groups = rtrim($all_user_groups, ',');

    return $all_user_groups;
}

/**
 * [get_allowed_user_groups description]
 * @param  [string] $user_group   [description]
 * @param  [resource] $link         [description]
 * @param  string $where_clause [description]
 * @return [string] $admin_viewable_groups_csv [description]
 */
function get_allowed_user_groups($link, $user_group, $where_clause = '') {
    $stmt = "SELECT admin_viewable_groups FROM vicidial_user_groups WHERE user_group='$user_group';";
    $rslt = mysql_to_mysqli($stmt, $link);
    $row  = mysqli_fetch_row($rslt);
    $admin_viewable_groups = $row[0];

    // Allowed admin viewable groups
    $raw_admin_viewable_groups = '';
    $admin_viewable_groups_csv = '';
    if ((!preg_match('/\-\-ALL\-\-/i', $admin_viewable_groups)) and (strlen($admin_viewable_groups) > 3)) {
        $raw_admin_viewable_groups = preg_replace("/ -/", '', $admin_viewable_groups);
        $admin_viewable_groups_csv = preg_replace("/ /", "','", $raw_admin_viewable_groups);
        $admin_viewable_groups_csv = "'---ALL---','$admin_viewable_groups_csv'";
    } elseif(preg_match('/\-ALL/i', $admin_viewable_groups)) {
        $admin_viewable_groups_csv = get_all_user_groups($link);
    } else {
        // Do Nothing
    }

    if ($where_clause == 'where') {
        $where_admin_viewable_groups = "WHERE user_group IN($admin_viewable_groups_csv)";
        return $where_admin_viewable_groups;
    }

    return $admin_viewable_groups_csv;
}

/**
 * [user_group description]
 * @param  [type] $where_allowed_user_groups   [description]
 * @param  [type] $link         [description]
 * @param [string] $mode       csv|array
 * @param [string] $qc_enabled       ''|Y
 * @return [string]
 */
function get_allowed_statuses($link, $user_group, $qc_enabled = '', $mode = 'csv') {
    //$where_allowed_user_groups = get_allowed_user_groups($link, $user_group, 'where');

    $where_allowed_campaigns = get_allowed_campaigns($link, $user_group, '', $qc_enabled, 'where');

    $stmt = "SELECT status,status_name FROM vicidial_statuses UNION SELECT status,status_name FROM vicidial_campaign_statuses $where_allowed_campaigns GROUP BY status;";
    $rslt = mysql_to_mysqli($stmt, $link);

    $allowed_statuses = '';
    $allowed_statuses_array = array();
    while ($row = mysqli_fetch_assoc($rslt)) {
        $allowed_statuses .= "'" . $row['status'] . "'" . ',';
        $allowed_statuses_array['status'][] = $row['status'];
        $allowed_statuses_array['status_name'][] = $row['status_name'];
    }
    $allowed_statuses = rtrim($allowed_statuses, ',');

    if($mode == 'array') {
        return $allowed_statuses_array;
    }

    return $allowed_statuses;
}

/**
 * [get_allowed_users description]
 * @param  [type] $user_group [description]
 * @param  [type] $link       [description]
 * @return [type]             [description]
 */
function get_allowed_users($link, $user_group, $vdad_vdcl = 'Y') {
    $where_allowed_user_groups = get_allowed_user_groups($link, $user_group, 'where');

    $and_clause = '';
    if($vdad_vdcl == 'N') {
        $and_clause = " AND user NOT IN('VDAD','VDCL')";
    }
    $stmt          = "SELECT user FROM vicidial_users $where_allowed_user_groups $and_clause ORDER BY user;";
    $rslt          = mysql_to_mysqli($stmt, $link);
    $allowed_users = '';
    while ($row = mysqli_fetch_assoc($rslt)) {
        $allowed_users .= "'" . $row['user'] . "'" . ',';
    }
    $allowed_users = rtrim($allowed_users, ',');

    return $allowed_users;
}

/**
 * [get_inbound_groups description]
 * @param  [type] $link       [description]
 * @param  [type] $and_clause [description]
 * @return [type]             [description]
 */
function get_inbound_groups($link, $user_group) {
    $admin_viewable_groups_csv = get_allowed_user_groups($link, $user_group);

    $stmt = "SELECT group_id,group_name FROM vicidial_inbound_groups WHERE user_group IN($admin_viewable_groups_csv) ORDER BY group_id;";
    $rslt = mysql_to_mysqli($stmt, $link);

    $num_rows  = mysqli_num_rows($rslt);
    $vi_groups = '';
    if ($num_rows > 0) {
        while ($row = mysqli_fetch_assoc($rslt)) {
            $vi_groups .= "'".$row['group_id']."',";
        }
        $vi_groups = rtrim($vi_groups, ",");
    }

    return $vi_groups;
}

/**
 * [get_allowed_lists description]
 * @param  [string] $user_group   [description]
 * @param  [type] $link         [description]
 * @param  string $where_clause where or empty
 * @param  string $active       Y|N
 * @param  string $mode         array
 * @return [allowed_lists_csv|allowed_lists_array] returns csv or array
 */
function get_allowed_lists($link, $user_group, $active = '', $where_clause = '', $mode = 'array') {
    $allowed_campaigns_csv = get_allowed_campaigns($link, $user_group);
    $active_clause = '';
    if($active != '') {
        $active_clause = "AND active='".$active."'";
    }
    $stmt = "SELECT list_id,list_name FROM vicidial_lists WHERE campaign_id IN ($allowed_campaigns_csv) $active_clause;";
    $rslt = mysql_to_mysqli($stmt, $link);

    $num_rows  = mysqli_num_rows($rslt);
    $allowed_lists_csv = '';
    if ($num_rows > 0) {
        while ($row = mysqli_fetch_assoc($rslt)) {
            $allowed_lists_csv .= "'".$row['list_id']."',";
            $allowed_lists_array['list_id'][]   = $row['list_id'];
            $allowed_lists_array['list_name'][] = $row['list_name'];
        }
        $allowed_lists_csv = rtrim($allowed_lists_csv, ',');

        if($mode == 'array') {
            return $allowed_lists_array;
        }
    }

    $where_allowed_lists = '';
    if ($where_clause == 'where') {
        $where_allowed_lists = "WHERE list_id IN($allowed_lists_csv)";
        return $where_allowed_lists;
    }

    return $allowed_lists_csv;
}

/**
 * [password_generator description]
 * @param  [string] $user_group   [description]
 * @param  [type] $link         [description]
 * @return [] returns string
 */
function password_generator($len = 6) {
    $autogen_pass = "";
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for($i=0; $i < $len; $i++) {
        $autogen_pass .= substr($chars, rand(0, strlen($chars)), 1);
    }

    return $autogen_pass;
}

##### CALCULATE DIALABLE LEADS #####
function get_dialable_leads($DB, $link, $local_call_time, $dial_statuses, $camp_lists, $drop_lockout_time, $call_count_limit, $single_status, $fSQL)
{
    ##### BEGIN calculate what gmt_offset_now values are within the allowed local_call_time setting ###
    if (isset($camp_lists)) {
        if (strlen($camp_lists) > 1) {
            if (strlen($dial_statuses) > 2) {
                $g           = 0;
                $p           = '13';
                $GMT_gmt[0]  = '';
                $GMT_hour[0] = '';
                $GMT_day[0]  = '';
                $YMD         = date("Y-m-d");
                while ($p > -13) {
                    $pzone        = 3600 * $p;
                    $pmin         = (gmdate("i", time() + $pzone));
                    $phour        = ((gmdate("G", time() + $pzone)) * 100);
                    $pday         = gmdate("w", time() + $pzone);
                    $tz           = sprintf("%.2f", $p);
                    $GMT_gmt[$g]  = "$tz";
                    $GMT_day[$g]  = "$pday";
                    $GMT_hour[$g] = ($phour + $pmin);
                    $p            = ($p - 0.25);
                    $g++;
                }

                $stmt = "SELECT call_time_id,call_time_name,call_time_comments,ct_default_start,ct_default_stop,ct_sunday_start,ct_sunday_stop,ct_monday_start,ct_monday_stop,ct_tuesday_start,ct_tuesday_stop,ct_wednesday_start,ct_wednesday_stop,ct_thursday_start,ct_thursday_stop,ct_friday_start,ct_friday_stop,ct_saturday_start,ct_saturday_stop,ct_state_call_times FROM vicidial_call_times where call_time_id='$local_call_time';";
                if ($DB) {
                    echo "$stmt\n";
                }
                $rslt                 = mysql_to_mysqli($stmt, $link);
                $rowx                 = mysqli_fetch_row($rslt);
                $Gct_default_start    = $rowx[3];
                $Gct_default_stop     = $rowx[4];
                $Gct_sunday_start     = $rowx[5];
                $Gct_sunday_stop      = $rowx[6];
                $Gct_monday_start     = $rowx[7];
                $Gct_monday_stop      = $rowx[8];
                $Gct_tuesday_start    = $rowx[9];
                $Gct_tuesday_stop     = $rowx[10];
                $Gct_wednesday_start  = $rowx[11];
                $Gct_wednesday_stop   = $rowx[12];
                $Gct_thursday_start   = $rowx[13];
                $Gct_thursday_stop    = $rowx[14];
                $Gct_friday_start     = $rowx[15];
                $Gct_friday_stop      = $rowx[16];
                $Gct_saturday_start   = $rowx[17];
                $Gct_saturday_stop    = $rowx[18];
                $Gct_state_call_times = $rowx[19];
                $Gct_holidays         = $rowx[20];

                ### BEGIN Check for outbound holiday ###
                $holiday_id = '';
                if (strlen($Gct_holidays) > 2) {
                    $Gct_holidaysSQL = preg_replace("/\|/", "','", "$Gct_holidays");
                    $Gct_holidaysSQL = "'" . $Gct_holidaysSQL . "'";

                    $stmt = "SELECT holiday_id,holiday_date,holiday_name,ct_default_start,ct_default_stop from vicidial_call_time_holidays where holiday_id IN($Gct_holidaysSQL) and holiday_status='ACTIVE' and holiday_date='$YMD' order by holiday_id;";
                    $rslt = mysql_to_mysqli($stmt, $link);
                    if ($DB) {
                        echo "$stmt\n";
                    }
                    $sthCrows = mysqli_num_rows($rslt);
                    if ($sthCrows > 0) {
                        $aryC         = mysqli_fetch_row($rslt);
                        $holiday_id   = $aryC[0];
                        $holiday_date = $aryC[1];
                        $holiday_name = $aryC[2];
                        if ($Gct_default_start < $aryC[3]) {
                            $Gct_default_start = $aryC[3];
                        }
                        if ($Gct_default_stop > $aryC[4]) {
                            $Gct_default_stop = $aryC[4];
                        }
                        if ($Gct_sunday_start < $aryC[3]) {
                            $Gct_sunday_start = $aryC[3];
                        }
                        if ($Gct_sunday_stop > $aryC[4]) {
                            $Gct_sunday_stop = $aryC[4];
                        }
                        if ($Gct_monday_start < $aryC[3]) {
                            $Gct_monday_start = $aryC[3];
                        }
                        if ($Gct_monday_stop > $aryC[4]) {
                            $Gct_monday_stop = $aryC[4];
                        }
                        if ($Gct_tuesday_start < $aryC[3]) {
                            $Gct_tuesday_start = $aryC[3];
                        }
                        if ($Gct_tuesday_stop > $aryC[4]) {
                            $Gct_tuesday_stop = $aryC[4];
                        }
                        if ($Gct_wednesday_start < $aryC[3]) {
                            $Gct_wednesday_start = $aryC[3];
                        }
                        if ($Gct_wednesday_stop > $aryC[4]) {
                            $Gct_wednesday_stop = $aryC[4];
                        }
                        if ($Gct_thursday_start < $aryC[3]) {
                            $Gct_thursday_start = $aryC[3];
                        }
                        if ($Gct_thursday_stop > $aryC[4]) {
                            $Gct_thursday_stop = $aryC[4];
                        }
                        if ($Gct_friday_start < $aryC[3]) {
                            $Gct_friday_start = $aryC[3];
                        }
                        if ($Gct_friday_stop > $aryC[4]) {
                            $Gct_friday_stop = $aryC[4];
                        }
                        if ($Gct_saturday_start < $aryC[3]) {
                            $Gct_saturday_start = $aryC[3];
                        }
                        if ($Gct_saturday_stop > $aryC[4]) {
                            $Gct_saturday_stop = $aryC[4];
                        }
                        if ($DB) {
                            echo "CALL TIME HOLIDAY FOUND!   $local_call_time|$holiday_id|$holiday_date|$holiday_name|$Gct_default_start|$Gct_default_stop|\n";
                        }
                    }
                }
                ### END Check for outbound holiday ###

                $ct_states        = '';
                $ct_state_gmt_SQL = '';
                $ct_srs           = 0;
                $b                = 0;
                if (strlen($Gct_state_call_times) > 2) {
                    $state_rules = explode('|', $Gct_state_call_times);
                    $ct_srs      = ((count($state_rules)) - 2);
                }
                while ($ct_srs >= $b) {
                    if (strlen($state_rules[$b]) > 1) {

                        $stmt = "SELECT state_call_time_id,state_call_time_state,state_call_time_name,state_call_time_comments,sct_default_start,sct_default_stop,sct_sunday_start,sct_sunday_stop,sct_monday_start,sct_monday_stop,sct_tuesday_start,sct_tuesday_stop,sct_wednesday_start,sct_wednesday_stop,sct_thursday_start,sct_thursday_stop,sct_friday_start,sct_friday_stop,sct_saturday_start,sct_saturday_stop from vicidial_state_call_times where state_call_time_id='$state_rules[$b]';";
                        $rslt = mysql_to_mysqli($stmt, $link);
                        $row  = mysqli_fetch_row($rslt);

                        $Gstate_call_time_id    = $row[0];
                        $Gstate_call_time_state = $row[1];
                        $Gsct_default_start     = $row[4];
                        $Gsct_default_stop      = $row[5];
                        $Gsct_sunday_start      = $row[6];
                        $Gsct_sunday_stop       = $row[7];
                        $Gsct_monday_start      = $row[8];
                        $Gsct_monday_stop       = $row[9];
                        $Gsct_tuesday_start     = $row[10];
                        $Gsct_tuesday_stop      = $row[11];
                        $Gsct_wednesday_start   = $row[12];
                        $Gsct_wednesday_stop    = $row[13];
                        $Gsct_thursday_start    = $row[14];
                        $Gsct_thursday_stop     = $row[15];
                        $Gsct_friday_start      = $row[16];
                        $Gsct_friday_stop       = $row[17];
                        $Gsct_saturday_start    = $row[18];
                        $Gsct_saturday_stop     = $row[19];
                        $Sct_holidays           = $row[20];
                        $ct_states .= "'$Gstate_call_time_state',";

                        ### BEGIN Check for outbound state holiday ###
                        $Sholiday_id = '';
                        if ((strlen($Sct_holidays) > 2) or ((strlen($holiday_id) > 2) and (strlen($Sholiday_id) < 2))) {
                            # Apply state holiday
                            if (strlen($Sct_holidays) > 2) {
                                $Sct_holidaysSQL = preg_replace("/\|/", "','", "$Sct_holidays");
                                $Sct_holidaysSQL = "'" . $Sct_holidaysSQL . "'";
                                $stmt            = "SELECT holiday_id,holiday_date,holiday_name,ct_default_start,ct_default_stop from vicidial_call_time_holidays where holiday_id IN($Sct_holidaysSQL) and holiday_status='ACTIVE' and holiday_date='$YMD' order by holiday_id;";
                                $holidaytype     = "STATE CALL TIME HOLIDAY FOUND!   ";
                            }
                            # Apply call time wide holiday
                            elseif ((strlen($holiday_id) > 2) and (strlen($Sholiday_id) < 2)) {
                                $stmt        = "SELECT holiday_id,holiday_date,holiday_name,ct_default_start,ct_default_stop from vicidial_call_time_holidays where holiday_id='$holiday_id' and holiday_status='ACTIVE' and holiday_date='$YMD' order by holiday_id;";
                                $holidaytype = "NO STATE HOLIDAY APPLYING CALL TIME HOLIDAY!   ";
                            }
                            $rslt = mysql_to_mysqli($stmt, $link);
                            if ($DB) {
                                echo "$stmt\n";
                            }
                            $sthCrows = mysqli_num_rows($rslt);
                            if ($sthCrows > 0) {
                                $aryC          = mysqli_fetch_row($rslt);
                                $Sholiday_id   = $aryC[0];
                                $Sholiday_date = $aryC[1];
                                $Sholiday_name = $aryC[2];
                                if (($Gsct_default_start < $aryC[3]) && ($Gsct_default_stop > 0)) {
                                    $Gsct_default_start = $aryC[3];
                                }
                                if (($Gsct_default_stop > $aryC[4]) && ($Gsct_default_stop > 0)) {
                                    $Gsct_default_stop = $aryC[4];
                                }
                                if (($Gsct_sunday_start < $aryC[3]) && ($Gsct_sunday_stop > 0)) {
                                    $Gsct_sunday_start = $aryC[3];
                                }
                                if (($Gsct_sunday_stop > $aryC[4]) && ($Gsct_sunday_stop > 0)) {
                                    $Gsct_sunday_stop = $aryC[4];
                                }
                                if (($Gsct_monday_start < $aryC[3]) && ($Gsct_monday_stop > 0)) {
                                    $Gsct_monday_start = $aryC[3];
                                }
                                if (($Gsct_monday_stop > $aryC[4]) && ($Gsct_monday_stop > 0)) {
                                    $Gsct_monday_stop = $aryC[4];
                                }
                                if (($Gsct_tuesday_start < $aryC[3]) && ($Gsct_tuesday_stop > 0)) {
                                    $Gsct_tuesday_start = $aryC[3];
                                }
                                if (($Gsct_tuesday_stop > $aryC[4]) && ($Gsct_tuesday_stop > 0)) {
                                    $Gsct_tuesday_stop = $aryC[4];
                                }
                                if (($Gsct_wednesday_start < $aryC[3]) && ($Gsct_wednesday_stop > 0)) {
                                    $Gsct_wednesday_start = $aryC[3];
                                }
                                if (($Gsct_wednesday_stop > $aryC[4]) && ($Gsct_wednesday_stop > 0)) {
                                    $Gsct_wednesday_stop = $aryC[4];
                                }
                                if (($Gsct_thursday_start < $aryC[3]) && ($Gsct_thursday_stop > 0)) {
                                    $Gsct_thursday_start = $aryC[3];
                                }
                                if (($Gsct_thursday_stop > $aryC[4]) && ($Gsct_thursday_stop > 0)) {
                                    $Gsct_thursday_stop = $aryC[4];
                                }
                                if (($Gsct_friday_start < $aryC[3]) && ($Gsct_friday_stop > 0)) {
                                    $Gsct_friday_start = $aryC[3];
                                }
                                if (($Gsct_friday_stop > $aryC[4]) && ($Gsct_friday_stop > 0)) {
                                    $Gsct_friday_stop = $aryC[4];
                                }
                                if (($Gsct_saturday_start < $aryC[3]) && ($Gsct_saturday_stop > 0)) {
                                    $Gsct_saturday_start = $aryC[3];
                                }
                                if (($Gsct_saturday_stop > $aryC[4]) && ($Gsct_saturday_stop > 0)) {
                                    $Gsct_saturday_stop = $aryC[4];
                                }
                                if ($DB) {
                                    echo "$holidaytype   |$Gstate_call_time_id|$Gstate_call_time_state|$Sholiday_id|$Sholiday_date|$Sholiday_name|$Gsct_default_start|$Gsct_default_stop|\n";
                                }
                            }
                        }
                        $r         = 0;
                        $state_gmt = '';
                        while ($r < $g) {
                            if ($GMT_day[$r] == 0) #### Sunday local time
                                {
                                if (($Gsct_sunday_start == 0) and ($Gsct_sunday_stop == 0)) {
                                    if (($GMT_hour[$r] >= $Gsct_default_start) and ($GMT_hour[$r] < $Gsct_default_stop)) {
                                        $state_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                } else {
                                    if (($GMT_hour[$r] >= $Gsct_sunday_start) and ($GMT_hour[$r] < $Gsct_sunday_stop)) {
                                        $state_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                }
                            }
                            if ($GMT_day[$r] == 1) #### Monday local time
                                {
                                if (($Gsct_monday_start == 0) and ($Gsct_monday_stop == 0)) {
                                    if (($GMT_hour[$r] >= $Gsct_default_start) and ($GMT_hour[$r] < $Gsct_default_stop)) {
                                        $state_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                } else {
                                    if (($GMT_hour[$r] >= $Gsct_monday_start) and ($GMT_hour[$r] < $Gsct_monday_stop)) {
                                        $state_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                }
                            }
                            if ($GMT_day[$r] == 2) #### Tuesday local time
                                {
                                if (($Gsct_tuesday_start == 0) and ($Gsct_tuesday_stop == 0)) {
                                    if (($GMT_hour[$r] >= $Gsct_default_start) and ($GMT_hour[$r] < $Gsct_default_stop)) {
                                        $state_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                } else {
                                    if (($GMT_hour[$r] >= $Gsct_tuesday_start) and ($GMT_hour[$r] < $Gsct_tuesday_stop)) {
                                        $state_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                }
                            }
                            if ($GMT_day[$r] == 3) #### Wednesday local time
                                {
                                if (($Gsct_wednesday_start == 0) and ($Gsct_wednesday_stop == 0)) {
                                    if (($GMT_hour[$r] >= $Gsct_default_start) and ($GMT_hour[$r] < $Gsct_default_stop)) {
                                        $state_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                } else {
                                    if (($GMT_hour[$r] >= $Gsct_wednesday_start) and ($GMT_hour[$r] < $Gsct_wednesday_stop)) {
                                        $state_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                }
                            }
                            if ($GMT_day[$r] == 4) #### Thursday local time
                                {
                                if (($Gsct_thursday_start == 0) and ($Gsct_thursday_stop == 0)) {
                                    if (($GMT_hour[$r] >= $Gsct_default_start) and ($GMT_hour[$r] < $Gsct_default_stop)) {
                                        $state_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                } else {
                                    if (($GMT_hour[$r] >= $Gsct_thursday_start) and ($GMT_hour[$r] < $Gsct_thursday_stop)) {
                                        $state_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                }
                            }
                            if ($GMT_day[$r] == 5) #### Friday local time
                                {
                                if (($Gsct_friday_start == 0) and ($Gsct_friday_stop == 0)) {
                                    if (($GMT_hour[$r] >= $Gsct_default_start) and ($GMT_hour[$r] < $Gsct_default_stop)) {
                                        $state_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                } else {
                                    if (($GMT_hour[$r] >= $Gsct_friday_start) and ($GMT_hour[$r] < $Gsct_friday_stop)) {
                                        $state_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                }
                            }
                            if ($GMT_day[$r] == 6) #### Saturday local time
                                {
                                if (($Gsct_saturday_start == 0) and ($Gsct_saturday_stop == 0)) {
                                    if (($GMT_hour[$r] >= $Gsct_default_start) and ($GMT_hour[$r] < $Gsct_default_stop)) {
                                        $state_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                } else {
                                    if (($GMT_hour[$r] >= $Gsct_saturday_start) and ($GMT_hour[$r] < $Gsct_saturday_stop)) {
                                        $state_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                }
                            }
                            $r++;
                        }
                        $state_gmt = "$state_gmt'99'";
                        $ct_state_gmt_SQL .= "or (state='$Gstate_call_time_state' and gmt_offset_now IN($state_gmt)) ";
                    }

                    $b++;
                }
                if (strlen($ct_states) > 2) {
                    $ct_states    = preg_replace('/,$/i', '', $ct_states);
                    $ct_statesSQL = "and state NOT IN($ct_states)";
                } else {
                    $ct_statesSQL = "";
                }

                $r           = 0;
                $default_gmt = '';
                while ($r < $g) {
                    if ($GMT_day[$r] == 0) #### Sunday local time
                        {
                        if (($Gct_sunday_start == 0) and ($Gct_sunday_stop == 0)) {
                            if (($GMT_hour[$r] >= $Gct_default_start) and ($GMT_hour[$r] < $Gct_default_stop)) {
                                $default_gmt .= "'$GMT_gmt[$r]',";
                            }
                        } else {
                            if (($GMT_hour[$r] >= $Gct_sunday_start) and ($GMT_hour[$r] < $Gct_sunday_stop)) {
                                $default_gmt .= "'$GMT_gmt[$r]',";
                            }
                        }
                    }
                    if ($GMT_day[$r] == 1) #### Monday local time
                        {
                        if (($Gct_monday_start == 0) and ($Gct_monday_stop == 0)) {
                            if (($GMT_hour[$r] >= $Gct_default_start) and ($GMT_hour[$r] < $Gct_default_stop)) {
                                $default_gmt .= "'$GMT_gmt[$r]',";
                            }
                        } else {
                            if (($GMT_hour[$r] >= $Gct_monday_start) and ($GMT_hour[$r] < $Gct_monday_stop)) {
                                $default_gmt .= "'$GMT_gmt[$r]',";
                            }
                        }
                    }
                    if ($GMT_day[$r] == 2) #### Tuesday local time
                        {
                        if (($Gct_tuesday_start == 0) and ($Gct_tuesday_stop == 0)) {
                            if (($GMT_hour[$r] >= $Gct_default_start) and ($GMT_hour[$r] < $Gct_default_stop)) {
                                $default_gmt .= "'$GMT_gmt[$r]',";
                            }
                        } else {
                            if (($GMT_hour[$r] >= $Gct_tuesday_start) and ($GMT_hour[$r] < $Gct_tuesday_stop)) {
                                $default_gmt .= "'$GMT_gmt[$r]',";
                            }
                        }
                    }
                    if ($GMT_day[$r] == 3) #### Wednesday local time
                        {
                        if (($Gct_wednesday_start == 0) and ($Gct_wednesday_stop == 0)) {
                            if (($GMT_hour[$r] >= $Gct_default_start) and ($GMT_hour[$r] < $Gct_default_stop)) {
                                $default_gmt .= "'$GMT_gmt[$r]',";
                            }
                        } else {
                            if (($GMT_hour[$r] >= $Gct_wednesday_start) and ($GMT_hour[$r] < $Gct_wednesday_stop)) {
                                $default_gmt .= "'$GMT_gmt[$r]',";
                            }
                        }
                    }
                    if ($GMT_day[$r] == 4) #### Thursday local time
                        {
                        if (($Gct_thursday_start == 0) and ($Gct_thursday_stop == 0)) {
                            if (($GMT_hour[$r] >= $Gct_default_start) and ($GMT_hour[$r] < $Gct_default_stop)) {
                                $default_gmt .= "'$GMT_gmt[$r]',";
                            }
                        } else {
                            if (($GMT_hour[$r] >= $Gct_thursday_start) and ($GMT_hour[$r] < $Gct_thursday_stop)) {
                                $default_gmt .= "'$GMT_gmt[$r]',";
                            }
                        }
                    }
                    if ($GMT_day[$r] == 5) #### Friday local time
                        {
                        if (($Gct_friday_start == 0) and ($Gct_friday_stop == 0)) {
                            if (($GMT_hour[$r] >= $Gct_default_start) and ($GMT_hour[$r] < $Gct_default_stop)) {
                                $default_gmt .= "'$GMT_gmt[$r]',";
                            }
                        } else {
                            if (($GMT_hour[$r] >= $Gct_friday_start) and ($GMT_hour[$r] < $Gct_friday_stop)) {
                                $default_gmt .= "'$GMT_gmt[$r]',";
                            }
                        }
                    }
                    if ($GMT_day[$r] == 6) #### Saturday local time
                        {
                        if (($Gct_saturday_start == 0) and ($Gct_saturday_stop == 0)) {
                            if (($GMT_hour[$r] >= $Gct_default_start) and ($GMT_hour[$r] < $Gct_default_stop)) {
                                $default_gmt .= "'$GMT_gmt[$r]',";
                            }
                        } else {
                            if (($GMT_hour[$r] >= $Gct_saturday_start) and ($GMT_hour[$r] < $Gct_saturday_stop)) {
                                $default_gmt .= "'$GMT_gmt[$r]',";
                            }
                        }
                    }
                    $r++;
                }

                $default_gmt = "$default_gmt'99'";
                $all_gmtSQL  = "(gmt_offset_now IN($default_gmt) $ct_statesSQL) $ct_state_gmt_SQL";

                $dial_statuses = preg_replace("/ -$/", "", $dial_statuses);
                $Dstatuses     = explode(" ", $dial_statuses);
                $Ds_to_print   = (count($Dstatuses) - 0);
                $Dsql          = '';
                $o             = 0;
                while ($Ds_to_print > $o) {
                    $o++;
                    $Dsql .= "'$Dstatuses[$o]',";
                }
                $Dsql = preg_replace("/,$/", "", $Dsql);
                if (strlen($Dsql) < 2) {
                    $Dsql = "''";
                }

                $DLTsql = '';
                if ($drop_lockout_time > 0) {
                    $DLseconds = ($drop_lockout_time * 3600);
                    $DLseconds = floor($DLseconds);
                    $DLseconds = intval("$DLseconds");
                    $DLTsql    = "and ( ( (status IN('DROP','XDROP')) and (last_local_call_time < CONCAT(DATE_ADD(NOW(), INTERVAL -$DLseconds SECOND),' ',CURTIME()) ) ) or (status NOT IN('DROP','XDROP')) )";
                }

                $CCLsql = '';
                if ($call_count_limit > 0) {
                    $CCLsql = "and (called_count < $call_count_limit)";
                }

                $EXPsql        = '';
                $expired_lists = '';
                $REPORTdate    = date("Y-m-d");
                $stmt          = "SELECT list_id FROM vicidial_lists where list_id IN($camp_lists) and (active='Y') and (expiration_date < \"$REPORTdate\");";
                #$DB=1;
                if ($DB) {
                    echo "$stmt\n";
                }
                $rslt      = mysql_to_mysqli($stmt, $link);
                $rslt_rows = mysqli_num_rows($rslt);
                $f         = 0;
                while ($rslt_rows > $f) {
                    $rowx = mysqli_fetch_row($rslt);
                    $expired_lists .= "'$rowx[0]',";
                    $f++;
                }
                $expired_lists = preg_replace("/,$/", '', $expired_lists);
                if (strlen($expired_lists) < 2) {
                    $expired_lists = "''";
                }
                $EXPsql = "and list_id NOT IN($expired_lists)";


                #################################
                # Camp List
                $stmt         = "SELECT list_id FROM vicidial_lists where list_id IN($camp_lists) and active='Y';";
                $rslt_list    = mysql_to_mysqli($stmt, $link);
                $camplists_ct = mysqli_num_rows($rslt_list);
                if ($DB) {
                    echo "$camplists_ct|$stmt\n";
                }
                $k          = 0;
                $camp_lists = '';
                while ($camplists_ct > $k) {
                    $rowA = mysqli_fetch_row($rslt_list);
                    $camp_lists .= "'$rowA[0]',";

                    ##### Get Call List call time settings
                    ##### BEGIN calculate what gmt_offset_now values are within the allowed local_call_time setting ###
                    $g           = 0;
                    $p           = '13';
                    $GMT_gmt[0]  = '';
                    $GMT_hour[0] = '';
                    $GMT_day[0]  = '';
                    $YMD         = date("Y-m-d");
                    while ($p > -13) {
                        $pzone        = 3600 * $p;
                        $pmin         = (gmdate("i", time() + $pzone));
                        $phour        = ((gmdate("G", time() + $pzone)) * 100);
                        $pday         = gmdate("w", time() + $pzone);
                        $tz           = sprintf("%.2f", $p);
                        $GMT_gmt[$g]  = "$tz";
                        $GMT_day[$g]  = "$pday";
                        $GMT_hour[$g] = ($phour + $pmin);
                        $p            = ($p - 0.25);
                        $g++;
                    }

                    # Set List ID Variable
                    $cur_list_id          = $rowA[0];
                    $list_local_call_time = "";

                    # Pull the call times for the lists
                    $stmt    = "SELECT local_call_time FROM vicidial_lists where list_id='$cur_list_id';";
                    $rslt    = mysql_to_mysqli($stmt, $link);
                    $rslt_ct = mysqli_num_rows($rslt);
                    if ($rslt_ct > 0) {
                        #set Cur call_time
                        $row           = mysqli_fetch_row($rslt);
                        $cur_call_time = $row[0];
                    }

                    # check that call time exists
                    if ($cur_call_time != "campaign") {
                        $stmt             = "SELECT count(*) from vicidial_call_times where call_time_id='$cur_call_time';";
                        $rslt             = mysql_to_mysqli($stmt, $link);
                        $row              = mysqli_fetch_row($rslt);
                        $call_time_exists = $row[0];
                        if ($call_time_exists < 1) {
                            $cur_call_time = 'campaign';
                        }
                    }

                    ##### BEGIN local call time for list set different than campaign #####
                    if ($cur_call_time != "campaign") {
                        ##### BEGIN calculate what gmt_offset_now values are within the allowed local_call_time setting ###
                        $stmt  = "SELECT call_time_id,call_time_name,call_time_comments,ct_default_start,ct_default_stop,ct_sunday_start,ct_sunday_stop,ct_monday_start,ct_monday_stop,ct_tuesday_start,ct_tuesday_stop,ct_wednesday_start,ct_wednesday_stop,ct_thursday_start,ct_thursday_stop,ct_friday_start,ct_friday_stop,ct_saturday_start,ct_saturday_stop,ct_state_call_times,ct_holidays FROM vicidial_call_times where call_time_id='$cur_call_time';";
                        if ($DB) {
                        echo "$stmt\n";
                        }
                        $rsltD = mysql_to_mysqli($stmt, $link);
                        $aryD  = mysqli_fetch_row($rsltD);

                        $Gct_default_start    = $aryD[3];
                        $Gct_default_stop     = $aryD[4];
                        $Gct_sunday_start     = $aryD[5];
                        $Gct_sunday_stop      = $aryD[6];
                        $Gct_monday_start     = $aryD[7];
                        $Gct_monday_stop      = $aryD[8];
                        $Gct_tuesday_start    = $aryD[9];
                        $Gct_tuesday_stop     = $aryD[10];
                        $Gct_wednesday_start  = $aryD[11];
                        $Gct_wednesday_stop   = $aryD[12];
                        $Gct_thursday_start   = $aryD[13];
                        $Gct_thursday_stop    = $aryD[14];
                        $Gct_friday_start     = $aryD[15];
                        $Gct_friday_stop      = $aryD[16];
                        $Gct_saturday_start   = $aryD[17];
                        $Gct_saturday_stop    = $aryD[18];
                        $Gct_state_call_times = $aryD[19];
                        $Gct_holidays         = $aryD[20];

                        ### BEGIN Check for outbound holiday ###
                        $holiday_id = '';
                        if (strlen($Gct_holidays) > 2) {
                            $Gct_holidaysSQL = preg_replace("/\|/", "','", "$Gct_holidays");
                            $Gct_holidaysSQL = "'" . $Gct_holidaysSQL . "'";

                            $stmt     = "SELECT holiday_id,holiday_date,holiday_name,ct_default_start,ct_default_stop from vicidial_call_time_holidays where holiday_id IN($Gct_holidaysSQL) and holiday_status='ACTIVE' and holiday_date='$YMD' order by holiday_id;";
                            $rslt     = mysql_to_mysqli($stmt, $link);
                            $sthCrows = mysqli_num_rows($rslt);
                            if ($sthCrows > 0) {
                                $aryC         = mysqli_fetch_row($rslt);
                                $holiday_id   = $aryC[0];
                                $holiday_date = $aryC[1];
                                $holiday_name = $aryC[2];
                                if (($Gct_default_start < $aryC[3]) && ($Gct_default_stop > 0)) {
                                    $Gct_default_start = $aryC[3];
                                }
                                if (($Gct_default_stop > $aryC[4]) && ($Gct_default_stop > 0)) {
                                    $Gct_default_stop = $aryC[4];
                                }
                                if (($Gct_sunday_start < $aryC[3]) && ($Gct_sunday_stop > 0)) {
                                    $Gct_sunday_start = $aryC[3];
                                }
                                if (($Gct_sunday_stop > $aryC[4]) && ($Gct_sunday_stop > 0)) {
                                    $Gct_sunday_stop = $aryC[4];
                                }
                                if (($Gct_monday_start < $aryC[3]) && ($Gct_monday_stop > 0)) {
                                    $Gct_monday_start = $aryC[3];
                                }
                                if (($Gct_monday_stop > $aryC[4]) && ($Gct_monday_stop > 0)) {
                                    $Gct_monday_stop = $aryC[4];
                                }
                                if (($Gct_tuesday_start < $aryC[3]) && ($Gct_tuesday_stop > 0)) {
                                    $Gct_tuesday_start = $aryC[3];
                                }
                                if (($Gct_tuesday_stop > $aryC[4]) && ($Gct_tuesday_stop > 0)) {
                                    $Gct_tuesday_stop = $aryC[4];
                                }
                                if (($Gct_wednesday_start < $aryC[3]) && ($Gct_wednesday_stop > 0)) {
                                    $Gct_wednesday_start = $aryC[3];
                                }
                                if (($Gct_wednesday_stop > $aryC[4]) && ($Gct_wednesday_stop > 0)) {
                                    $Gct_wednesday_stop = $aryC[4];
                                }
                                if (($Gct_thursday_start < $aryC[3]) && ($Gct_thursday_stop > 0)) {
                                    $Gct_thursday_start = $aryC[3];
                                }
                                if (($Gct_thursday_stop > $aryC[4]) && ($Gct_thursday_stop > 0)) {
                                    $Gct_thursday_stop = $aryC[4];
                                }
                                if (($Gct_friday_start < $aryC[3]) && ($Gct_friday_stop > 0)) {
                                    $Gct_friday_start = $aryC[3];
                                }
                                if (($Gct_friday_stop > $aryC[4]) && ($Gct_friday_stop > 0)) {
                                    $Gct_friday_stop = $aryC[4];
                                }
                                if (($Gct_saturday_start < $aryC[3]) && ($Gct_saturday_stop > 0)) {
                                    $Gct_saturday_start = $aryC[3];
                                }
                                if (($Gct_saturday_stop > $aryC[4]) && ($Gct_saturday_stop > 0)) {
                                    $Gct_saturday_stop = $aryC[4];
                                }
                                if ($DB) {
                                    echo "LIST CALL TIME HOLIDAY FOUND!   $local_call_time|$holiday_id|$holiday_date|$holiday_name|$Gct_default_start|$Gct_default_stop|\n";
                                }
                            }
                        }
                        ### END Check for outbound holiday ###

                        $ct_states        = '';
                        $ct_state_gmt_SQL = '';
                        $ct_srs           = 0;
                        $b                = 0;
                        if (strlen($Gct_state_call_times) > 2) {
                            $state_rules = explode('|', $Gct_state_call_times);
                            $ct_srs      = ((count($state_rules)) - 2);
                        }
                        while ($ct_srs >= $b) {
                            if (strlen($state_rules[$b]) > 1) {
                                $stmt     = "SELECT state_call_time_id,state_call_time_state,state_call_time_name,state_call_time_comments,sct_default_start,sct_default_stop,sct_sunday_start,sct_sunday_stop,sct_monday_start,sct_monday_stop,sct_tuesday_start,sct_tuesday_stop,sct_wednesday_start,sct_wednesday_stop,sct_thursday_start,sct_thursday_stop,sct_friday_start,sct_friday_stop,sct_saturday_start,sct_saturday_stop,ct_holidays from vicidial_state_call_times where state_call_time_id='$state_rules[$b]';";
                                $rslt     = mysql_to_mysqli($stmt, $link);
                                $sthCrows = mysqli_num_rows($rslt);
                                if ($sthCrows > 0) {
                                    $aryC                   = mysqli_fetch_row($rslt);
                                    $Gstate_call_time_id    = $aryC[0];
                                    $Gstate_call_time_state = $aryC[1];
                                    $Gsct_default_start     = $aryC[4];
                                    $Gsct_default_stop      = $aryC[5];
                                    $Gsct_sunday_start      = $aryC[6];
                                    $Gsct_sunday_stop       = $aryC[7];
                                    $Gsct_monday_start      = $aryC[8];
                                    $Gsct_monday_stop       = $aryC[9];
                                    $Gsct_tuesday_start     = $aryC[10];
                                    $Gsct_tuesday_stop      = $aryC[11];
                                    $Gsct_wednesday_start   = $aryC[12];
                                    $Gsct_wednesday_stop    = $aryC[13];
                                    $Gsct_thursday_start    = $aryC[14];
                                    $Gsct_thursday_stop     = $aryC[15];
                                    $Gsct_friday_start      = $aryC[16];
                                    $Gsct_friday_stop       = $aryC[17];
                                    $Gsct_saturday_start    = $aryC[18];
                                    $Gsct_saturday_stop     = $aryC[19];
                                    $Sct_holidays           = $aryC[20];
                                    $ct_states .= "'$Gstate_call_time_state',";
                                }

                                ### BEGIN Check for outbound state holiday ###
                                $Sholiday_id = '';
                                if ((strlen($Sct_holidays) > 2) or ((strlen($holiday_id) > 2) and (strlen($Sholiday_id) < 2))) {
                                    #Apply state holiday
                                    if (strlen($Sct_holidays) > 2) {
                                        $Sct_holidaysSQL = preg_replace("/\|/", "','", "$Sct_holidays");
                                        $Sct_holidaysSQL = "'" . $Sct_holidaysSQL . "'";
                                        $stmt            = "SELECT holiday_id,holiday_date,holiday_name,ct_default_start,ct_default_stop from vicidial_call_time_holidays where holiday_id IN($Sct_holidaysSQL) and holiday_status='ACTIVE' and holiday_date='$YMD' order by holiday_id;";
                                        $holidaytype     = "LIST STATE CALL TIME HOLIDAY FOUND!   ";
                                    }
                                    #Apply call time wide holiday
                                    elseif ((strlen($holiday_id) > 2) and (strlen($Sholiday_id) < 2)) {
                                        $stmt        = "SELECT holiday_id,holiday_date,holiday_name,ct_default_start,ct_default_stop from vicidial_call_time_holidays where holiday_id='$holiday_id' and holiday_status='ACTIVE' and holiday_date='$YMD' order by holiday_id;";
                                        $holidaytype = "LIST NO STATE HOLIDAY APPLYING CALL TIME HOLIDAY!   ";
                                    }
                                    $rslt = mysql_to_mysqli($stmt, $link);
                                    if ($DB) {
                                        echo "$stmt\n";
                                    }
                                    $sthCrows = mysqli_num_rows($rslt);
                                    if ($sthCrows > 0) {
                                        $aryC          = mysqli_fetch_row($rslt);
                                        $Sholiday_id   = $aryC[0];
                                        $Sholiday_date = $aryC[1];
                                        $Sholiday_name = $aryC[2];
                                        if (($Gsct_default_start < $aryC[3]) && ($Gsct_default_stop > 0)) {
                                            $Gsct_default_start = $aryC[3];
                                        }
                                        if (($Gsct_default_stop > $aryC[4]) && ($Gsct_default_stop > 0)) {
                                            $Gsct_default_stop = $aryC[4];
                                        }
                                        if (($Gsct_sunday_start < $aryC[3]) && ($Gsct_sunday_stop > 0)) {
                                            $Gsct_sunday_start = $aryC[3];
                                        }
                                        if (($Gsct_sunday_stop > $aryC[4]) && ($Gsct_sunday_stop > 0)) {
                                            $Gsct_sunday_stop = $aryC[4];
                                        }
                                        if (($Gsct_monday_start < $aryC[3]) && ($Gsct_monday_stop > 0)) {
                                            $Gsct_monday_start = $aryC[3];
                                        }
                                        if (($Gsct_monday_stop > $aryC[4]) && ($Gsct_monday_stop > 0)) {
                                            $Gsct_monday_stop = $aryC[4];
                                        }
                                        if (($Gsct_tuesday_start < $aryC[3]) && ($Gsct_tuesday_stop > 0)) {
                                            $Gsct_tuesday_start = $aryC[3];
                                        }
                                        if (($Gsct_tuesday_stop > $aryC[4]) && ($Gsct_tuesday_stop > 0)) {
                                            $Gsct_tuesday_stop = $aryC[4];
                                        }
                                        if (($Gsct_wednesday_start < $aryC[3]) && ($Gsct_wednesday_stop > 0)) {
                                            $Gsct_wednesday_start = $aryC[3];
                                        }
                                        if (($Gsct_wednesday_stop > $aryC[4]) && ($Gsct_wednesday_stop > 0)) {
                                            $Gsct_wednesday_stop = $aryC[4];
                                        }
                                        if (($Gsct_thursday_start < $aryC[3]) && ($Gsct_thursday_stop > 0)) {
                                            $Gsct_thursday_start = $aryC[3];
                                        }
                                        if (($Gsct_thursday_stop > $aryC[4]) && ($Gsct_thursday_stop > 0)) {
                                            $Gsct_thursday_stop = $aryC[4];
                                        }
                                        if (($Gsct_friday_start < $aryC[3]) && ($Gsct_friday_stop > 0)) {
                                            $Gsct_friday_start = $aryC[3];
                                        }
                                        if (($Gsct_friday_stop > $aryC[4]) && ($Gsct_friday_stop > 0)) {
                                            $Gsct_friday_stop = $aryC[4];
                                        }
                                        if (($Gsct_saturday_start < $aryC[3]) && ($Gsct_saturday_stop > 0)) {
                                            $Gsct_saturday_start = $aryC[3];
                                        }
                                        if (($Gsct_saturday_stop > $aryC[4]) && ($Gsct_saturday_stop > 0)) {
                                            $Gsct_saturday_stop = $aryC[4];
                                        }
                                        if ($DB) {
                                            echo "$holidaytype   |$Gstate_call_time_id|$Gstate_call_time_state|$Sholiday_id|$Sholiday_date|$Sholiday_name|$Gsct_default_start|$Gsct_default_stop|\n";
                                        }
                                    }
                                }
                                ### END Check for outbound state holiday ###

                                $r             = 0;
                                $state_gmt     = '';
                                $del_state_gmt = '';
                                while ($r < $g) {
                                    if ($GMT_day[$r] == 0) #### Sunday local time
                                        {
                                        if (($Gsct_sunday_start == 0) && ($Gsct_sunday_stop == 0)) {
                                            if (($GMT_hour[$r] >= $Gsct_default_start) && ($GMT_hour[$r] < $Gsct_default_stop)) {
                                                $state_gmt .= "'$GMT_gmt[$r]',";
                                            }
                                        } else {
                                            if (($GMT_hour[$r] >= $Gsct_sunday_start) && ($GMT_hour[$r] < $Gsct_sunday_stop)) {
                                                $state_gmt .= "'$GMT_gmt[$r]',";
                                            }
                                        }
                                    }
                                    if ($GMT_day[$r] == 1) #### Monday local time
                                        {
                                        if (($Gsct_monday_start == 0) && ($Gsct_monday_stop == 0)) {
                                            if (($GMT_hour[$r] >= $Gsct_default_start) && ($GMT_hour[$r] < $Gsct_default_stop)) {
                                                $state_gmt .= "'$GMT_gmt[$r]',";
                                            }
                                        } else {
                                            if (($GMT_hour[$r] >= $Gsct_monday_start) && ($GMT_hour[$r] < $Gsct_monday_stop)) {
                                                $state_gmt .= "'$GMT_gmt[$r]',";
                                            }
                                        }
                                    }
                                    if ($GMT_day[$r] == 2) #### Tuesday local time
                                        {
                                        if (($Gsct_tuesday_start == 0) && ($Gsct_tuesday_stop == 0)) {
                                            if (($GMT_hour[$r] >= $Gsct_default_start) && ($GMT_hour[$r] < $Gsct_default_stop)) {
                                                $state_gmt .= "'$GMT_gmt[$r]',";
                                            }
                                        } else {
                                            if (($GMT_hour[$r] >= $Gsct_tuesday_start) && ($GMT_hour[$r] < $Gsct_tuesday_stop)) {
                                                $state_gmt .= "'$GMT_gmt[$r]',";
                                            }
                                        }
                                    }
                                    if ($GMT_day[$r] == 3) #### Wednesday local time
                                        {
                                        if (($Gsct_wednesday_start == 0) && ($Gsct_wednesday_stop == 0)) {
                                            if (($GMT_hour[$r] >= $Gsct_default_start) && ($GMT_hour[$r] < $Gsct_default_stop)) {
                                                $state_gmt .= "'$GMT_gmt[$r]',";
                                            }
                                        } else {
                                            if (($GMT_hour[$r] >= $Gsct_wednesday_start) && ($GMT_hour[$r] < $Gsct_wednesday_stop)) {
                                                $state_gmt .= "'$GMT_gmt[$r]',";
                                            }
                                        }
                                    }
                                    if ($GMT_day[$r] == 4) #### Thursday local time
                                        {
                                        if (($Gsct_thursday_start == 0) && ($Gsct_thursday_stop == 0)) {
                                            if (($GMT_hour[$r] >= $Gsct_default_start) && ($GMT_hour[$r] < $Gsct_default_stop)) {
                                                $state_gmt .= "'$GMT_gmt[$r]',";
                                            }
                                        } else {
                                            if (($GMT_hour[$r] >= $Gsct_thursday_start) && ($GMT_hour[$r] < $Gsct_thursday_stop)) {
                                                $state_gmt .= "'$GMT_gmt[$r]',";
                                            }
                                        }
                                    }
                                    if ($GMT_day[$r] == 5) #### Friday local time
                                        {
                                        if (($Gsct_friday_start == 0) && ($Gsct_friday_stop == 0)) {
                                            if (($GMT_hour[$r] >= $Gsct_default_start) && ($GMT_hour[$r] < $Gsct_default_stop)) {
                                                $state_gmt .= "'$GMT_gmt[$r]',";
                                            }
                                        } else {
                                            if (($GMT_hour[$r] >= $Gsct_friday_start) && ($GMT_hour[$r] < $Gsct_friday_stop)) {
                                                $state_gmt .= "'$GMT_gmt[$r]',";
                                            }
                                        }
                                    }
                                    if ($GMT_day[$r] == 6) #### Saturday local time
                                        {
                                        if (($Gsct_saturday_start == 0) && ($Gsct_saturday_stop == 0)) {
                                            if (($GMT_hour[$r] >= $Gsct_default_start) && ($GMT_hour[$r] < $Gsct_default_stop)) {
                                                $state_gmt .= "'$GMT_gmt[$r]',";
                                            }
                                        } else {
                                            if (($GMT_hour[$r] >= $Gsct_saturday_start) && ($GMT_hour[$r] < $Gsct_saturday_stop)) {
                                                $state_gmt .= "'$GMT_gmt[$r]',";
                                            }
                                        }
                                    }
                                    $r++;
                                }
                                $state_gmt = "$state_gmt'99'";

                                $del_list_state_gmt_SQL .= "or (List_id=\"$cur_list_id\" and state='$Gstate_call_time_state' and gmt_offset_now NOT IN($state_gmt)) ";
                                $list_state_gmt_SQL .= "or (List_id=\"$cur_list_id\" and state='$Gstate_call_time_state' and gmt_offset_now IN($state_gmt)) ";
                            }

                            $b++;
                        }
                        if (strlen($ct_states) > 2) {
                            $ct_states    = preg_replace("/,$/i", '', $ct_states);
                            $ct_statesSQL = "and state NOT IN($ct_states)";
                        } else {
                            $ct_statesSQL = "";
                        }

                        $r                = 0;
                        $dgA              = 0;
                        $list_default_gmt = '';
                        while ($r < $g) {
                            if ($DB > 0) {
                                echo "LCT_gmt: $r|$GMT_day[$r]|$GMT_gmt[$r]|$Gct_sunday_start|$Gct_sunday_stop|$GMT_hour[$r]|$Gct_default_start|$Gct_default_stop\n";
                            }

                            if ($GMT_day[$r] == 0) #### Sunday local time
                                {
                                if (($Gct_sunday_start == 0) && ($Gct_sunday_stop == 0)) {
                                    if (($GMT_hour[$r] >= $Gct_default_start) && ($GMT_hour[$r] < $Gct_default_stop)) {
                                    } else {
                                        $list_default_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                } else {
                                    if (($GMT_hour[$r] >= $Gct_sunday_start) && ($GMT_hour[$r] < $Gct_sunday_stop)) {
                                    } else {
                                        $list_default_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                }
                            }
                            if ($GMT_day[$r] == 1) #### Monday local time
                                {
                                if (($Gct_monday_start == 0) && ($Gct_monday_stop == 0)) {
                                    if (($GMT_hour[$r] >= $Gct_default_start) && ($GMT_hour[$r] < $Gct_default_stop)) {
                                    } else {
                                        $list_default_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                } else {
                                    if (($GMT_hour[$r] >= $Gct_monday_start) && ($GMT_hour[$r] < $Gct_monday_stop)) {
                                    } else {
                                        $list_default_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                }
                            }
                            if ($GMT_day[$r] == 2) #### Tuesday local time
                                {
                                if (($Gct_tuesday_start == 0) && ($Gct_tuesday_stop == 0)) {
                                    if (($GMT_hour[$r] >= $Gct_default_start) && ($GMT_hour[$r] < $Gct_default_stop)) {
                                    } else {
                                        $list_default_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                } else {
                                    if (($GMT_hour[$r] >= $Gct_tuesday_start) && ($GMT_hour[$r] < $Gct_tuesday_stop)) {
                                    } else {
                                        $list_default_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                }
                            }
                            if ($GMT_day[$r] == 3) #### Wednesday local time
                                {
                                if (($Gct_wednesday_start == 0) && ($Gct_wednesday_stop == 0)) {
                                    if (($GMT_hour[$r] >= $Gct_default_start) && ($GMT_hour[$r] < $Gct_default_stop)) {
                                    } else {
                                        $list_default_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                } else {
                                    if (($GMT_hour[$r] >= $Gct_wednesday_start) && ($GMT_hour[$r] < $Gct_wednesday_stop)) {
                                    } else {
                                        $list_default_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                }
                            }
                            if ($GMT_day[$r] == 4) #### Thursday local time
                                {
                                if (($Gct_thursday_start == 0) && ($Gct_thursday_stop == 0)) {
                                    if (($GMT_hour[$r] >= $Gct_default_start) && ($GMT_hour[$r] < $Gct_default_stop)) {
                                    } else {
                                        $list_default_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                } else {
                                    if (($GMT_hour[$r] >= $Gct_thursday_start) && ($GMT_hour[$r] < $Gct_thursday_stop)) {
                                    } else {
                                        $list_default_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                }
                            }
                            if ($GMT_day[$r] == 5) #### Friday local time
                                {
                                if (($Gct_friday_start == 0) && ($Gct_friday_stop == 0)) {
                                    if (($GMT_hour[$r] >= $Gct_default_start) && ($GMT_hour[$r] < $Gct_default_stop)) {
                                    } else {
                                        $list_default_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                } else {
                                    if (($GMT_hour[$r] >= $Gct_friday_start) && ($GMT_hour[$r] < $Gct_friday_stop)) {
                                    } else {
                                        $list_default_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                }
                            }
                            if ($GMT_day[$r] == 6) #### Saturday local time
                                {
                                if (($Gct_saturday_start == 0) && ($Gct_saturday_stop == 0)) {
                                    if (($GMT_hour[$r] >= $Gct_default_start) && ($GMT_hour[$r] < $Gct_default_stop)) {
                                    } else {
                                        $list_default_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                } else {
                                    if (($GMT_hour[$r] >= $Gct_saturday_start) && ($GMT_hour[$r] < $Gct_saturday_stop)) {
                                    } else {
                                        $list_default_gmt .= "'$GMT_gmt[$r]',";
                                    }
                                }
                            }
                            $r++;
                        }

                        $list_default_gmt = "$list_default_gmt'99'";
                        $LCTlist_id_sql .= " or ((list_id='$cur_list_id' and gmt_offset_now NOT IN($list_default_gmt) $ct_statesSQL) $list_state_gmt_SQL)";
                    }
                    ##### END local call time for list set different than campaign #####

                    else {
                        if (strlen($list_id_sql) < 3) {
                            $list_id_sql = "(list_id IN('$cur_list_id'";
                        } else {
                            $list_id_sql .= ",'$cur_list_id'";
                        }
                    }

                    $k++;
                }
                $camp_lists = preg_replace("/.$/i", "", $camp_lists);
                if (strlen($camp_lists) < 4) {
                    $camp_lists = "''";
                }
                if (strlen($list_id_sql) < 4) {
                    $list_id_sql = "list_id=''";
                } else {
                    $list_id_sql .= '))';
                }
                if (strlen($LCTlist_id_sql) > 1) {
                    $list_id_sql .= "$LCTlist_id_sql";
                }


                $stmt = "SELECT count(*) FROM vicidial_list where called_since_last_reset='N' and status IN($Dsql) and list_id IN($camp_lists) and ($list_id_sql) and ($all_gmtSQL) $CCLsql $DLTsql $fSQL $EXPsql";
                #$DB=1;
                if ($DB) {
                    echo "$stmt\n";
                }
                $rslt      = mysql_to_mysqli($stmt, $link);
                $rslt_rows = mysqli_num_rows($rslt);
                if ($rslt_rows) {
                    $rowx         = mysqli_fetch_row($rslt);
                    $active_leads = "$rowx[0]";
                } else {
                    $active_leads = '0';
                }

                if ($DB > 0) {
                    echo "|$DB|\n";
                }
                if ($single_status > 0) {
                    return $active_leads;
                } else {
                    echo _QXZ("This campaign has") . " $active_leads " . _QXZ("leads to be dialed in those lists") . "\n";
                }
            } else {
                return _QXZ("no dial statuses selected for this campaign");
            }
        } else {
            return _QXZ("no active lists selected for this campaign");
        }
    } else {
        return _QXZ("no active lists selected for this campaign");
    }
    ##### END calculate what gmt_offset_now values are within the allowed local_call_time setting ###
}

function CHECK_USER_LEVEL($PHP_AUTH_USER,$user_level,$link)
{

    $current_level=getCurrentUserLevel($PHP_AUTH_USER,$link);
    if(strval($user_level)>strval($current_level))
    {
        require('./permission.php');
        exit();
    }
}

function getCurrentUserLevel($current_user,$link){
        $current_level=0;
        $stmt="SELECT user_level FROM vicidial_users WHERE user='$current_user' LIMIT 1;";
        $rslt=mysql_to_mysqli($stmt, $link);
        if (mysqli_num_rows($rslt)>0)
        {
            $row=mysqli_fetch_row($rslt);
            $current_level=$row[0];
        }
        return $current_level;
}

function allow_open($current_user,$source_user,$link){

        $current_level=0;
        $source_level=0;
        $stmt="SELECT user_level FROM vicidial_users WHERE user='$current_user' LIMIT 1;";
        $rslt=mysql_to_mysqli($stmt, $link);
        if (mysqli_num_rows($rslt)>0)
        {
        $row=mysqli_fetch_row($rslt);
        $current_level=$row[0];
        }
        $stmt="SELECT user_level FROM vicidial_users WHERE user='$source_user' LIMIT 1;";
        $rslt=mysql_to_mysqli($stmt, $link);
        if (mysqli_num_rows($rslt)>0)
        {
        $row=mysqli_fetch_row($rslt);
        $source_level=$row[0];
        }

        if( strval($current_level) < strval($source_level) ){
            require('./permission.php');
            exit();
        }
}
