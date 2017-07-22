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
        <td class="head" align="center" style="padding:10px 10px 10px 10px;"><{$add_resume}></td>
    </tr>
    <tr>
        <td class="newstitle" height="18"><{$category_path}></td>
    </tr>
    <tr>
        <td class="even" align="left"><{$availability}></td>
    </tr>
    <{if $moderated}>
        <{if $xoops_isadmin}>
            <tr>
                <td align="center" class="even">
                    <table class="outer" cellspacing="0" style="width:50%;">
                        <tr>
                            <td class="head" align="center"><{$admin_block}></td>
                        </tr>
                        <tr>
                            <td class="odd" align="center"><{$confirm_resume}></td>
                        </tr>

                    </table>
                </td>
            </tr>
        <{/if}>
    <{/if}>


    <{if $subcategories}>
        <tr>
            <td class="odd">
                <table border="0" style="width:100%;">
                    <{foreach item=category from=$subcategories}>
                    <td align="left">
                        <table class="outer" cellspacing='5' cellpadding='0' align="center">
                            <tr>
                                <td class="head" valign="top" align="center">
                                    <{if $category.image != ""}>
                                        <{$category.image}>
                                    <{/if}>
                                    <br><a href="<{$xoops_url}>/modules/jobs/resumecat.php?cid=<{$category.id}>"><b><{$category.title}></b></a><br>(<{$category.totallisting}>
                                    )&nbsp;<{$category.new}>
                                </td>
                            </tr>
                            <tr>
                                <td align="center"><{$category.subcategories}></td>
                            </tr>
                        </table>
                    </td>
                    <{if $category.count % 3 == 0}>
                    </tr>
                    <tr>
                        <{/if}>
                        <{/foreach}>
                </table>
            </td>
        </tr>
    <{/if}>
    <tr>

        <td class="even" align="center"><{$total_resumes}><br><{$total_confirm}></td>
    </tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td><br>

            <{if $show_nav == true}><{assign var='imgstyle' value='border:0; '}>
                <div align="center"><{$lang_sortby}> <{$lang_title}>
                    (<a href="resumecat.php?cid=<{$cid}>&orderby=titleA"><img title="<{$lang_titleatoz}>"
                                                                              style="<{$imgstyle}>" src=
                                                                              <{if $sort_active == 'titleA'}>"assets/images/up_active.gif""
                        /><{else}>"images/up.gif"
                        /><{/if}></a><a href="resumecat.php?cid=<{$cid}>&orderby=titleD"><img
                                title="<{$lang_titleztoa}>" style="<{$imgstyle}>" src=
                        <{if $sort_active == 'titleD'}>"images/down_active.gif"" /><{else}>"images/down.gif"
                        /><{/if}></a>)<{$lang_exp}>
                    (<a href="resumecat.php?cid=<{$cid}>&orderby=expA"><img title="<{$lang_expltoh}>"
                                                                            style="<{$imgstyle}>" src=
                                                                            <{if $sort_active == 'expA'}>"images/up_active.gif""
                        /><{else}>"images/up.gif"
                        /><{/if}></a><a href="resumecat.php?cid=<{$cid}>&orderby=expD"><img title="<{$lang_exphtol}>"
                                                                                            style="<{$imgstyle}>" src=
                                                                                            <{if $sort_active == 'expD'}>"images/down_active.gif""
                        /><{else}>"images/down.gif" /><{/if}></a>)<{$lang_date}>
                    (<a href="resumecat.php?cid=<{$cid}>&orderby=dateA"><img title="<{$lang_dateold}>"
                                                                             style="<{$imgstyle}>" src=
                                                                             <{if $sort_active == 'dateA' || $show_active == 'dateA'}>"images/up_active.gif""
                        /><{else}>"images/up.gif"
                        /><{/if}></a><a href="resumecat.php?cid=<{$cid}>&orderby=dateD"><img title="<{$lang_datenew}>"
                                                                                             style="<{$imgstyle}>" src=
                                                                                             <{if $sort_active == 'dateD' || $show_active == 'dateD'}>"images/down_active.gif""
                        /><{else}>"images/down.gif" /><{/if}></a>)<{$lang_local}>
                    (<a href="resumecat.php?cid=<{$cid}>&orderby=townA"><img title="<{$lang_localatoz}>"
                                                                             style="<{$imgstyle}>" src=
                                                                             <{if $sort_active == 'townA' || $show_active == 'townA'}>"images/up_active.gif""
                        /><{else}>"images/up.gif"
                        /><{/if}></a><a href="resumecat.php?cid=<{$cid}>&orderby=townD"><img title="<{$lang_localztoa}>"
                                                                                             style="<{$imgstyle}>" src=
                                                                                             <{if $sort_active == 'town' || $show_active == 'townD'}>"images/down_active.gif""
                        /><{else}>"images/down.gif" /><{/if}></a>)<{$lang_popularity}>
                    (<a href="resumecat.php?cid=<{$cid}>&orderby=viewA"><img title="<{$lang_popularityleast}>"
                                                                             style="<{$imgstyle}>" src=
                                                                             <{if $sort_active == 'viewA'}>"images/up_active.gif""
                        /><{else}>"images/up.gif"
                        /><{/if}></a><a href="resumecat.php?cid=<{$cid}>&orderby=viewD"><img
                                title="<{$lang_popularitymost}>" style="<{$imgstyle}>" src=
                        <{if $sort_active == 'viewD'}>"images/down_active.gif"" /><{else}>"images/down.gif"
                        /><{/if}></a>)
                    <br><b><{$lang_cursortedby}></b>
                    <hr
                    / width="97%">
                </div>
            <{/if}>

            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td width="100%" align="center" valign="top"><h2><b><{$resumes_all}> <{$cat_title}>
                                <{$all_resumes}></b></h2></td>
                </tr>
            </table>

            <div align="center"><{$nav_page}><br></div>

            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <{if $no_resumes_to_show}>
                <tr>
                    <td align="center"><p><{$no_resumes_to_show}></p></td>
                </tr>
            </table>
            <{else}>

            <{if $show_nav == true}><{assign var='imgstyle' value='border:0; '}>
            <{else}><{assign var='imgstyle' value='border:0; display:none;'}>
            <{/if}>
    <tr>
        <{if $xoops_isadmin}>
            <td class="head"></td>
        <{/if}>
        <td class="head" align="center" width="20%">
            <{$last_res_head_title}><br>
            <a href="resumecat.php?cid=<{$cid}>&orderby=titleA">
                <img title="<{$lang_titleatoz}>" style="<{$imgstyle}>" src=
                <{if $sort_active == 'titleA' || $show_active == 'titleA'}>"assets/images/up_active.gif""
                /><{else}>"assets/images/up.gif" /><{/if}></a>
            <a href="resumecat.php?cid=<{$cid}>&orderby=titleD">
                <img title="<{$lang_titleztoa}>" style="<{$imgstyle}>" src=
                <{if $sort_active == 'titleD' || $show_active == 'titleD'}>"assets/images/down_active.gif""
                /><{else}>"assets/images/down.gif" /><{/if}></a></td>

        <td class="head" width="20%" align="center">
            <{$last_res_head_exp}><br>
            <a href="resumecat.php?cid=<{$cid}>&orderby=expA">
                <img title="<{$lang_expltoh}>" style="<{$imgstyle}>" src=
                <{if $sort_active == 'expA' || $show_active == 'expA'}>"assets/images/up_active.gif""
                /><{else}>"assets/images/up.gif" /><{/if}></a>
            <a href="resumecat.php?cid=<{$cid}>&orderby=expD">
                <img title="<{$lang_exphtol}>" style="<{$imgstyle}>" src=
                <{if $sort_active == 'expD' || $show_active == 'expD'}>"assets/images/down_active.gif""
                /><{else}>"assets/images/down.gif" /><{/if}></a></td>

        <td class="head" width="20%" align="center">
            <{$last_res_head_date}><br>
            <a href="resumecat.php?cid=<{$cid}>&orderby=dateA">
                <img title="<{$lang_dateold}>" style="<{$imgstyle}>" src=
                <{if $sort_active == 'dateA' || $show_active == 'dateA'}>"assets/images/up_active.gif""
                /><{else}>"assets/images/up.gif" /><{/if}></a>
            <a href="resumecat.php?cid=<{$cid}>&orderby=dateD">
                <img title="<{$lang_datenew}>" style="<{$imgstyle}>" src=
                <{if $sort_active == 'dateD' || $show_active == 'dateD'}>"assets/images/down_active.gif""
                /><{else}>"assets/images/down.gif" /><{/if}></a></td>


        <td class="head" width="20%" align="center">
            <{$last_res_head_local}><br>
            <a href="resumecat.php?cid=<{$cid}>&orderby=townA">
                <img title="<{$lang_localatoz}>" style="<{$imgstyle}>" src=
                <{if $sort_active == 'townA' || $show_active == 'townA'}>"assets/images/up_active.gif""
                /><{else}>"assets/images/up.gif" /><{/if}></a>
            <a href="resumecat.php?cid=<{$cid}>&orderby=townD">
                <img title="<{$lang_localztoa}>" style="<{$imgstyle}>" src=
                <{if $sort_active == 'townD' || $show_active == 'townD'}>"assets/images/down_active.gif""
                /><{else}>"assets/images/down.gif" /><{/if}></a></td>

        <td class="head" width="15%" align="center">
            <{$last_res_head_views}><br>
            <a href="resumecat.php?cid=<{$cid}>&orderby=viewA">
                <img title="<{$lang_popularityleast}>" style="<{$imgstyle}>" src=
                <{if $sort_active == 'viewA' || $show_active == 'viewA'}>"assets/images/up_active.gif""
                /><{else}>"assets/images/up.gif" /><{/if}></a>
            <a href="resumecat.php?cid=<{$cid}>&orderby=viewD">
                <img title="<{$lang_popularitymost}>" style="<{$imgstyle}>" src=
                <{if $sort_active == 'viewD' || $show_active == 'viewD'}>"assets/images/down_active.gif""
                /><{else}>"assets/images/down.gif" /><{/if}></a>
        </td>
    </tr>

</table>

<{if $use_extra_code == 1}>

    <{foreach from=$items item=item name=items}>
        <{if ($smarty.foreach.items.index % $index_code_place == 0) && !($smarty.foreach.items.first)}>

            <{if $jobs_use_banner == 1}>
                <table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
                    <tr>
                        <td align="center"><{$resume_banner}></td>
                    </tr>
                </table>
            <{else}>
                <table>
                    <tr>
                        <td align="center"><{$index_extra_code}></td>
                    </tr>
                </table>
            <{/if}><{/if}>
        <table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
            <tr class="<{cycle values=" odd,even"}>">
                <{if $xoops_isadmin}>
                    <td width="5%"><{$item.admin}></td>
                <{/if}>
                <td width="20%"><b><{$item.title}></b> <{$item.new}><br><{$item.type}></td>
                <td width="20%" align="center"><{$item.exp}></td>
                <td width="20%" align="center"><{$item.date}></td>
                <td width="20%" align="center"><{$item.town}><{if $item.state}>, <{$item.state}><{/if}></td>
                <td width="15%" align="center"><{$item.views}></td>
            </tr>
        </table>
    <{/foreach}><{/if}>
</td></tr> <{/if}></table>


<br><br><{$nav_page}><br><br>
<{include file='db:system_notification_select.tpl'}>
