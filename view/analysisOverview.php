<?php
extendView('analysis');

beginPartial('analysisContent');
?>

    <script>
        var overviewGridData = <?php echo json_encode($totals); ?>;
    </script>

    <div class="container" data-ng-controller="AnalysisOverviewController">
        <div class="row">
            <table id="tbl_overview"
                   class="table table-bordered table-striped tablesorter">
                <thead>
                    <tr>
                        <th class="header">Account Name</th>
                        <th>Cost</th>
                        <th>Daily</th>
                        <th>Weekly</th>
                        <th>Monthly</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-ng-repeat="account in accounts">
                        <td><a href="/analysis/totals/{{account.id}}">{{account.name}}</a></td>
                        <td>{{account.cost}}</td>
                        <td dd-overview-td data-value="{{account.daily.value}}"></td>
                        <td dd-overview-td data-value="{{account.weekly.value}}"></td>
                        <td dd-overview-td data-value="{{account.monthly.value}}"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

<?
endPartial();