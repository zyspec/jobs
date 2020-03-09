
<div id="help-template" class="outer">
    <{include file=$smarty.const._MI_JOBS_HELP_HEADER}>

    <!-- -----Help Content ---------- -->
    <h4 class="odd">DESCRIPTION</h4>
    <div class="even marg10 boxshadow1"><p>
        This module is for listing jobs and resumes<br><br>
        Features:<br><br>
        <ol>
            <li>Notifications</li>
            <li>Group Permissions</li>
            <li>Premium users can set how long their listing will last</li>
            <li>Users can view all listings for a company, and if that user is the submitter, they can administer their
                listings from there. they will also be shown how many replies they have had for each listing and have a link to
                show them the replies</li>
            <li>Users can sort the Job Listings by job title, date, company, popularity. Users can sort the Resume Listings
                by job title, date, experience, popularity</li>
            <li>Users can now create their resume if they don't have one to upload. For now it is just a one field form using
                a wywiwyg editor. In the future it will get more involved, hopefully</li>
            <li>Users can add their resume as .doc or .pdf, they can also create one from scratch if they don't have
                one.(Uses a wysiwyg editor) Resumes can be set to private, using a password or key that the submitter creates
                when adding their resume, no one can view the resume without the key, if the submitter adds a key. If no key is
                added the listing will be public</li>
        </ol>
    </p></div>

    <h4 class="odd">INSTALL/UNINSTALL</h4>
    <div class="even marg10 boxshadow1"><p>
        No special measures necessary, follow the standard installation process - extract the module folder into the 
        ./modules directory. Install the module through Admin -> System Module -> Modules.
        <br><br>
        Detailed instructions on installing modules are available in the
        <a href="https://www.gitbook.com/book/xoops/xoops-operations-guide/" target="_blank">Chapter 2.12 of the XOOPS
            Operations Manual</a>
    </p></div>

    <h4 class="odd">OPERATING INSTRUCTIONS</h4>
    <div class="even marg10 boxshadow1"><p>
        This module and its operations are very simple.<br> <br>
        Detailed instructions on configuring the access rights for user groups are available in the
        <a href="https://www.gitbook.com/book/xoops/xoops-operations-guide/" target="_blank">Chapter 2.8 of our XOOPS
        Operations Manual</a><br>
    </p></div>

    <h4 class="odd">Things you MUST do before using this module</h4>
    <div class="even marg10 boxshadow1"><p>
        Before using this module there are some things you NEED TO DO FIRST.<br><br>
        <ol>
            <li>You MUST go to JOBS PREFERENCES FIRST and set it up the way you want to. Once you set it to use a Company or
                not, and add listings, you can not change it later, or it will mess up the way the module works.</li>
            <li>Then you need to go to JOBS ADMIN and set up categories, permissions, regions, type management.</li>
        </ol>
    </p></div>

    <h4 class="odd">Companies</h4>
    <div class="even marg10 boxshadow1"><p>
        <ol>
            <li>If 'show company' is set to yes in the preferences, when a user tries to add a listing they will be redirected to
                add company first. After they finish with their company info they will be redirected to the add listing page, all
                the info from their company will be automatically imported into the add listing page.</li>
            <li>After a user adds their company there will be a new link on the Jobs front page 'view your listings' with their
                company listed below.</li>
            <li>To edit their company info they will click on their company link mentioned above and on the next page there will
                be a link 'Modify your Company Information' they can edit their info there.</li>
            <li>On the add company page the user can add other users that can modify their listings.</li>
        </ol>
    </p></div>

    <h4 class="odd">TUTORIAL</h4>
    <div class="even marg10 boxshadow1"><p>
        There is no tutorial available - <i><strong>YET</strong></i>.
    </p></div>
</div>
