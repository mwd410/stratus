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


<div id="modalOverlay" data-ng-show="modalDialog">

    <div id="modalDialog">
        <header>{{ modalDialog.title }}</header>
        <div>
            <div class="message">{{ modalDialog.message }}</div>
            <button class="st-button bg"
                    data-ng-repeat="button in modalDialog.buttons"
                    data-ng-click="modalDialog.onClick(button.text)">
                <i class="icon-large"
                   data-ng-show="button.iconCls"
                   data-ng-class="button.iconCls"></i>
                {{ button.text }}
            </button>
        </div>
    </div>
</div>

<?php
endPartial();
