<table cellspacing="1" class="outer" style="width:100%;">
    <tr>
        <th align="center"><{$last_res_head}></th>
    </tr>
    <tr>
        <td style="padding:0;">
            <table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
                <tr>
                    <{if $xoops_isadmin}>
                        <td class="head"></td>
                    <{/if}>
                    <td align="center" class="head"><{$last_res_head_title}></td>
                    <td align="center" class="head"><{$last_res_head_experience}></td>
                    <td align="center" class="head"><{$last_res_head_date}></td>
                    <td align="center" class="head"><{$last_res_head_local}></td>
                    <td align="center" class="head"><{$last_res_head_views}></td>
                </tr>
            </table>

            <{foreach from=$items item=item name=items}>
                <{if $use_extra_code == 1}>
                    <{if ($smarty.foreach.items.index % $index_code_place == 0) && !($smarty.foreach.items.first)}>
                        <{if $jobs_use_banner == 1}>
                            <table>
                                <tr>
                                    <td align="center"><{$index_banner}></td>
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
                            <td><{$item.admin}></td>
                        <{/if}>
                        <td width="20%"><b><{$item.title}></b> <{$item.new}></td>
                        <td width="20%" align="center"><{$item.exp}></td>
                        <td align="center" width="20%"><{$item.date}></td>
                        <td width="20%" align="center"><{if $item.town}><{$item.town}><{if $item.state}>,
                                <{$item.state}><{/if}><{/if}>
                        </td>
                        <td align="center" width="15%"><{$item.views}></td>
                    </tr>
                </table>
            <{/foreach}>
        </td>
    </tr>
</table>
