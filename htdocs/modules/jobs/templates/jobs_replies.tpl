<table border="1" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td class="head"><b><{$nav_main}></b></td>
    </tr>
    <tr>
        <td class="head"><b><{$title_head}><{$title}></b></td>
    </tr>
</table>
<br><br><{$nav_page}><br><br>

<{foreach item=item from=$items}>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td class="head"><{$submitter_head}>&nbsp;&nbsp;<{$submitter}><br><br></td>
        </tr>
        <tr>
            <td class="head"><{$date_head}>&nbsp;&nbsp;<{$item.date}><br><br></td>
        </tr>
        <tr>
            <td class="head"><{$email_head}>&nbsp;&nbsp;<{$email}><br><br></td>
        </tr>
        <tr>
            <td class="head"><{$tele_head}>&nbsp;&nbsp;<{$item.tele}><br><br></td>
        </tr>
        <tr>
            <td class="head"><{$resume_head}>&nbsp;&nbsp;<{if $item.resume}><a
                href="<{$item.resume}>"><{$view_resume}></a><{else}><{$no_resume}><{/if}><br><br>
            </td>
        </tr>
    </table>
<table border="1" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td class="head" align="center"><{$message_head}></td>
    </tr>
    <tr>
        <td class="even"><br><{$item.message}><br><br><{$del_reply}></td>
    </tr>
    <{/foreach}>
</table><br><{$nav_page}><br><br>
