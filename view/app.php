<?php
extendView('base');

beginPartial('body');
?>

<div class="site-wrapper">
    <div class="site-content" data-ui-view></div>

    <footer class="site-footer">
        <div class="st-wrapper">
            <span class="left">&copy; 2013 Cloudnalysis</span>
            <span>&nbsp;</span>
            <span class="right">Contact</span>
        </div>
    </footer>
</div>

<?php
endPartial();