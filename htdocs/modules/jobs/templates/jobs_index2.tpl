<table cellspacing="1" class="outer" style="width:100%;">
    <tr>
        <th align="center"><{$add_from_sitename}> <{$add_from_title}></th>
    </tr>
    <{if $res_moderated}>
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
                        </td>
                    </table>
                </td>
            </tr>
        <{/if}>
    <{/if}>
</table>

<table border="1" class="even" width="80%" align="center">
    <tr>
        <td class="odd"><{$back_to_jobs}></td>
    </tr>
    <tr>

        <{if $istheirs}>
            <td class="odd" valign="top"><br><b><{$your_resume}></b><br>

                <{foreach item=res from=$your_resumes}>
                    <a href="<{$res.your_resumes}>"><{$res.title}></a>
                    <br>
                <{/foreach}>
            </td>
        <{/if}>

    </tr>
    <tr>
        <td class="odd" align="center"><{$intro_resume}></td>
    </tr>
</table>

<{if $resume_search}>
    <table border="1" class="even" width="80%" align="center">
        <tr>
            <td class="even" align="center">
                <hr/>
                <table border="0">
                    <form name='search' id='search' action='search.php' method='post'
                          onsubmit='return xoopsFormValidate_search();'>
                        <input type='hidden' name='mids[]' value='<{$xmid}>'/>
                        <input type='hidden' name='issearch' value='1'/>
                        <td width='15%'><b><{$search_listings}></b></td>
                        <td>
                            <{$keywords}><br><input type='text' name='query' id='query' size='15' maxlength='255'
                                                    value=''/>
                        </td>

                        <td> <{$bycategory}><br><{$by_cat}></td>
                        </tr>
                        <tr>
                            <td width='15%'><br>&nbsp;</td>
                            <td> <{$bystate}><br><{$by_state}></td>

                            <td><br><select size='1' name='andor' id='andor'>
                                    <option value='AND' selected='selected'><{$all_words}></option>
                                    <option value='OR'><{$any_words}></option>
                                    <option value='exact'><{$exact_match}></option>
                                </select></td>
                            <td><input type='submit' class='formButton' name='submit' id='submit' value='Search'/></td>
                            <input type='hidden' name='action' id='action' value='results'/>
                            <input type='hidden' name='is_resume' id='is_resume' value='<{$is_resume}>'/>
                    </form>
                </table>
            </td>
        </tr>
    </table>
    <script type='text/javascript'>
        <!--
        function xoopsFormValidate_search() {
        }

        //-->
    </script>
<{/if}>

<table border="1" class="even" align="center" style="width:100%;">
    <tr>
        <td class="head" valign="top" align="center">
            <{$total_listing}>
        </td>
    </tr>
    <tr>
        <td class="odd">
            <table border="0" style="width:100%;">
                <{foreach item=category from=$categories}>
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
</table>
<table>
    <tr>
        <td class="even" align="center"><{$total_listings}><br><{if $moderated}><{$total_confirm}><br><{/if}></td>
    </tr>
</table>

<{include file="db:jobs_res_adlist.tpl" comment=$comment}>
<{include file='db:system_notification_select.tpl'}>


