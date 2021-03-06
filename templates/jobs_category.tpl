<script language="JavaScript" type="text/JavaScript">
    <!--
    function CLA(CLA) {
        var MainWindow = window.open(CLA, "_blank", "width=500,height=300,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no");
    }

    //-->
</script>
<table cellspacing="1" class="outer" style="width:100%;">
    <tr>
        <th align="center"><{$add_from_sitename}> <{$add_from_title}></th>
    </tr>
    <tr>
        <td class="newstitle" height="18"><{$category_path}></td>
    </tr>
    <{if $jobs_submitter}>
        <tr>
            <td class="head" align="center" style="padding:10px 10px 10px 10px;"><{$add_listing}></td>
        </tr>
    <{/if}>
</table>

<{if $moderated}>
    <{if $xoops_isadmin}>
        <table cellspacing="1" class="outer" style="width:100%;">
            <tr>
                <td align="center" class="even">
                    <table class="outer" cellspacing="0" style="width:50%;">
                        <tr>
                            <td class="head" align="center"><{$admin_block}></td>
                        </tr>
                        <tr>
                            <td class="odd" align="center"><{$confirm_ads}></td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>
    <{/if}>
<{/if}>

<{if $subcategories}>
    <table cellspacing="1" class="outer" style="width:100%;">
        <tr>
            <td class="odd">
                <table cellspacing="1" class="outer" style="width:100%;">
                    <tr>
                        <{foreach item=subcat from=$subcategories}>
                        <td align="left"><b><a href="jobscat.php?cid=<{$subcat.id}>"><{$subcat.title}></a></b>
                            (<{$subcat.totallistings}>)<br>
                            <!-- <font class="subcategories"><{$subcat.infercategories}></font> -->
                        </td>
                        <{if $subcat.count % 4 == 0}>
                    </tr>
                    <tr>
                        <{/if}>
                        <{/foreach}>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
<{/if}>

<{if $premrows}>
    <table width="100%" cellspacing="1" cellpadding="0" border="0" align="center">
        <tr>
            <td width="100%" align="center" valign="top" bgcolor="#FFFF66"><h2><b><{$sponsored}> <{$cat_title}>
                        <{$all_listings}></b></h2></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="20px" width="100%">
        <tr>
            <{if $xoops_isadmin}>
                <td bgcolor="#FFFF66" width="5%"></td>
            <{/if}>
            <td bgcolor="#FFFF66" width="20%"><b><{$last_head_title}></b></td>
            <{if $show_company == '1'}>
                <td bgcolor="#FFFF66" width="20%"><b><{$last_head_company}></b></td>
            <{/if}>
            <td bgcolor="#FFFF66" align="center" width="20%"><b><{$last_head_date}></b></td>
            <td bgcolor="#FFFF66" align="center" width="20%"><b><{$last_head_local}></b></td>
            <td bgcolor="#FFFF66" align="center" width="15%"><b><{$last_head_views}></b></td>
        </tr>
    </table>
    <hr
    / width="97%">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <!-- Start link loop -->
        <{section name=i loop=$items}>
            <tr>
                <td valign="top" bgcolor="#ffff66">
                    <{include file="db:jobs_premium.tpl" item=$items[i]}>
                </td>
            </tr>
        <{/section}>
        <!-- End link loop -->
    </table>
<{/if}>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td><br>
            <{if $show_nav == true}><{assign var='imgstyle' value='border:0; '}>
                <div align="center"><{$lang_sortby}> <{$lang_title}>
                    (<a href="jobscat.php?cid=<{$cid}>&orderby=titleA"><img title="<{$lang_titleatoz}>"
                                                                            style="<{$imgstyle}>" src=
                                                                            <{if $sort_active == 'titleA'}>"assets/images/up_active.gif""
                       ><{else}>"assets/images/up.gif"
                       ><{/if}></a><a href="jobscat.php?cid=<{$cid}>&orderby=titleD"><img title="<{$lang_titleztoa}>"
                                                                                            style="<{$imgstyle}>" src=
                                                                                            <{if $sort_active == 'titleD'}>"assets/images/down_active.gif""
                       ><{else}>"assets/images/down.gif"
                       ><{/if}></a>)

                    <{if $show_company}>
                        <{$lang_company}>
                        (
                        <a href="jobscat.php?cid=<{$cid}>&orderby=companyA"><img title="<{$lang_companyatoz}>"
                                                                                 style="<{$imgstyle}>" src=
                                                                                 <{if $sort_active == 'companyA'}>"assets/images/up_active.gif""
                           ><{else}>"assets/images/up.gif"><{/if}></a>
                        <a href="jobscat.php?cid=<{$cid}>&orderby=companyD"><img title="<{$lang_companyztoa}>"
                                                                                 style="<{$imgstyle}>" src=
                                                                                 <{if $sort_active == 'companyD'}>"assets/images/down_active.gif""
                           ><{else}>"assets/images/down.gif"><{/if}></a>
                        )
                    <{/if}>

                    <{$lang_date}>
                    (<a href="jobscat.php?cid=<{$cid}>&orderby=dateA"><img title="<{$lang_dateold}>"
                                                                           style="<{$imgstyle}>" src=
                                                                           <{if $sort_active == 'dateA' || $show_active == 'dateA'}>"assets/images/up_active.gif""
                       ><{else}>"assets/images/up.gif"
                       ><{/if}></a><a href="jobscat.php?cid=<{$cid}>&orderby=dateD"><img title="<{$lang_datenew}>"
                                                                                           style="<{$imgstyle}>" src=
                                                                                           <{if $sort_active == 'dateD' || $show_active == 'dateD'}>"assets/images/down_active.gif""
                       ><{else}>"assets/images/down.gif"><{/if}></a>)

                    <{if $use_state}>

                        <{$lang_state}>
                        (
                        <a href="jobscat.php?cid=<{$cid}>&orderby=stateA"><img title="<{$lang_stateatoz}>"
                                                                               style="<{$imgstyle}>" src=
                                                                               <{if $sort_active == 'stateA' || $show_active == 'stateA'}>"assets/images/up_active.gif""
                           ><{else}>"assets/images/up.gif"
                           ><{/if}></a>
                        <a href="jobscat.php?cid=<{$cid}>&orderby=stateD"><img title="<{$lang_stateztoa}>"
                                                                               style="<{$imgstyle}>" src=
                                                                               <{if $sort_active == 'stateD' || $show_active == 'stateD'}>"assets/images/down_active.gif""
                           ><{else}>"assets/images/down.gif"><{/if}></a>
                        )

                    <{else}>
                        <{$lang_local}>
                        (
                        <a href="jobscat.php?cid=<{$cid}>&orderby=townA"><img title="<{$lang_localatoz}>"
                                                                              style="<{$imgstyle}>" src=
                                                                              <{if $sort_active == 'townA' || $show_active == 'townA'}>"assets/images/up_active.gif""
                           ><{else}>"assets/images/up.gif"
                           ><{/if}></a>
                        <a href="jobscat.php?cid=<{$cid}>&orderby=townD"><img title="<{$lang_localztoa}>"
                                                                              style="<{$imgstyle}>" src=
                                                                              <{if $sort_active == 'townD' || $show_active == 'townD'}>"assets/images/down_active.gif""
                           ><{else}>"assets/images/down.gif"><{/if}></a>
                        )
                    <{/if}>
                    <{$lang_popularity}>
                    (<a href="jobscat.php?cid=<{$cid}>&orderby=viewA"><img title="<{$lang_popularityleast}>"
                                                                           style="<{$imgstyle}>" src=
                                                                           <{if $sort_active == 'viewA'}>"assets/images/up_active.gif""
                       ><{else}>"assets/images/up.gif"
                       ><{/if}></a><a href="jobscat.php?cid=<{$cid}>&orderby=viewD"><img
                                title="<{$lang_popularitymost}>" style="<{$imgstyle}>" src=
                        <{if $sort_active == 'viewD'}>"assets/images/down_active.gif""><{else}>
                        "assets/images/down.gif"
                       ><{/if}></a>)
                    <br><b><{$lang_cursortedby}></b>
                    <hr
                    / width="97%">
                </div>
            <{/if}>
            <{if $there_are_listings}>
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td width="100%" align="center" valign="top"><h2><b><{$jobs_all}> <{$cat_title}>
                                    <{$all_listings}></b></h2></td>
                    </tr>
                </table>
            <{/if}>
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <{if $no_jobs_to_show}>
                    <tr>
                        <td align="center"><p><{$no_jobs_to_show}></p></td>
                    </tr>
                <{else}>
                    <br>
                    <{$nav_page}>
                    <br>
                    <{if $show_nav == true}><{assign var='imgstyle' value='border:0; '}>
                    <{else}><{assign var='imgstyle' value='border:0; display:none;'}>
                    <{/if}>
                    <tr>
                        <td class="head" align="center" width="20%">
                            <{$last_head_title}><br>
                            <a href="jobscat.php?cid=<{$cid}>&orderby=titleA">
                                <img title="<{$lang_titleatoz}>" style="<{$imgstyle}>" src=
                                <{if $sort_active == 'titleA' || $show_active == 'titleA'}>"assets/images/up_active.gif""
                               ><{else}>"assets/images/up.gif"><{/if}></a>
                            <a href="jobscat.php?cid=<{$cid}>&orderby=titleD">
                                <img title="<{$lang_titleztoa}>" style="<{$imgstyle}>" src=
                                <{if $sort_active == 'titleD' || $show_active == 'titleD'}>"assets/images/down_active.gif""
                               ><{else}>"assets/images/down.gif"><{/if}></a></td>
                        <{if $show_company}>
                            <td class="head" width="20%" align="center">
                                <{$last_head_company}><br>
                                <a href="jobscat.php?cid=<{$cid}>&orderby=companyA">
                                    <img title="<{$lang_companyatoz}>" style="<{$imgstyle}>" src=
                                    <{if $sort_active == 'companyA' || $show_active ==
                                    'companyA'}>"assets/images/up_active.gif""><{else}>"assets/images/up.gif"
                                   ><{/if}></a>
                                <a href="jobscat.php?cid=<{$cid}>&orderby=companyD">
                                    <img title="<{$lang_companyztoa}>" style="<{$imgstyle}>" src=
                                    <{if $sort_active == 'companyD' || $show_active ==
                                    'companyD'}>"assets/images/down_active.gif""><{else}>"assets/images/down.gif"
                                   ><{/if}></a></td>
                        <{/if}>
                        <td class="head" width="20%" align="center">
                            <{$last_head_date}><br>
                            <a href="jobscat.php?cid=<{$cid}>&orderby=dateA">
                                <img title="<{$lang_dateold}>" style="<{$imgstyle}>" src=
                                <{if $sort_active == 'dateA' || $show_active == 'dateA'}>"assets/images/up_active.gif""
                               ><{else}>"assets/images/up.gif"><{/if}></a>
                            <a href="jobscat.php?cid=<{$cid}>&orderby=dateD">
                                <img title="<{$lang_datenew}>" style="<{$imgstyle}>" src=
                                <{if $sort_active == 'dateD' || $show_active == 'dateD'}>"assets/images/down_active.gif""
                               ><{else}>"assets/images/down.gif"><{/if}></a></td>
                        <{if $use_state}>
                            <td class="head" width="20%" align="center">
                                <{$last_head_state}><br>
                                <a href="jobscat.php?cid=<{$cid}>&orderby=stateA">
                                    <img title="<{$lang_localatoz}>" style="<{$imgstyle}>" src=
                                    <{if $sort_active == 'stateA' || $show_active == 'stateA'}>"assets/images/up_active.gif""
                                   ><{else}>"assets/images/up.gif"><{/if}></a>
                                <a href="jobscat.php?cid=<{$cid}>&orderby=stateD">
                                    <img title="<{$lang_localztoa}>" style="<{$imgstyle}>" src=
                                    <{if $sort_active == 'stateD' || $show_active == 'stateD'}>"assets/images/down_active.gif""
                                   ><{else}>"assets/images/down.gif"><{/if}></a></td>
                        <{else}>
                            <td class="head" width="20%" align="center">
                                <{$last_head_local}><br>
                                <a href="jobscat.php?cid=<{$cid}>&orderby=townA">
                                    <img title="<{$lang_localatoz}>" style="<{$imgstyle}>" src=
                                    <{if $sort_active == 'townA' || $show_active == 'townA'}>"assets/images/up_active.gif""
                                   ><{else}>"assets/images/up.gif"><{/if}></a>
                                <a href="jobscat.php?cid=<{$cid}>&orderby=townD">
                                    <img title="<{$lang_localztoa}>" style="<{$imgstyle}>" src=
                                    <{if $sort_active == 'townD' || $show_active == 'townD'}>"assets/images/down_active.gif""
                                   ><{else}>"assets/images/down.gif"><{/if}></a></td>
                        <{/if}>
                        <td class="head" width="15%" align="center">
                            <{$last_head_views}><br>
                            <a href="jobscat.php?cid=<{$cid}>&orderby=viewA">
                                <img title="<{$lang_popularityleast}>" style="<{$imgstyle}>" src=
                                <{if $sort_active == 'viewA' || $show_active == 'viewA'}>"assets/images/up_active.gif""
                               ><{else}>"assets/images/up.gif"><{/if}></a>
                            <a href="jobscat.php?cid=<{$cid}>&orderby=viewD">
                                <img title="<{$lang_popularitymost}>" style="<{$imgstyle}>" src=
                                <{if $sort_active == 'viewD' || $show_active == 'viewD'}>"assets/images/down_active.gif""
                               ><{else}>"assets/images/down.gif"><{/if}></a>
                        </td>
                    </tr>
                <{/if}>
            </table>

            <{foreach from=$items item=item name=items}>
                <{if $use_extra_code == 1}>
                    <{if ($index_code_place != 0 && $smarty.foreach.items.index % $index_code_place == 0) && !($smarty.foreach.items.first)}>

                        <{if $jobs_use_banner == 1}>
                            <table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
                                <tr>
                                    <td align="center"><{$banner}></td>
                                </tr>
                            </table>
                        <{else}>
                            <table>
                                <tr>
                                    <td align="center"><{$index_extra_code}></td>
                                </tr>
                            </table>
                        <{/if}><{/if}><{/if}>
                <table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
                    <tr class="<{cycle values=" odd,even"}>">
                        <{if $xoops_isadmin}>
                            <td width="5%"><{$item.admin}></td>
                        <{/if}>
                        <td width="20%" align="left"><b><{$item.title}></b> <{$item.new}><br><{$item.type}></td>
                        <{if $show_company}>
                            <td width="20%"><{$item.company}></td>
                        <{/if}>
                        <td width="20%" align="center"><{$item.date}></td>
                        <td width="20%" align="center"><{$item.town}><{if $item.state}>, <{$item.state}><{/if}></td>
                        <td width="15%" align="center"><{$item.views}></td>
                    </tr>
                </table>
            <{/foreach}>
        </td>
    </tr>
</table>
<br><{$nav_page}><br><br>
<{include file='db:system_notification_select.tpl'}>
