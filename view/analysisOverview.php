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
                   class="table table-bordered table-hover table-striped tablesorter">
                <thead>
                    <tr class="unselectable handCursor">
                        <th style="width:40%"
                            data-ng-click="sortBy('name')">Account Name</th>
                        <th style="width:15%"
                            data-ng-click="sortBy('cost')">Cost</th>
                        <th style="width:15%"
                            data-ng-click="sortBy('daily.value')">Daily</th>
                        <th style="width:15%"
                            data-ng-click="sortBy('weekly.value')">Weekly</th>
                        <th style="width:15%"
                            data-ng-click="sortBy('monthly.value')">Monthly</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-ng-repeat="account in accounts | orderBy:sort.property:sort.reverse">
                        <td><a href="/analysis/totals/{{account.id}}">{{account.name}}</a></td>
                        <td>{{account.cost}}</td>
                        <td dd-overview-td="{{account.daily.value}}"></td>
                        <td dd-overview-td="{{account.weekly.value}}"></td>
                        <td dd-overview-td="{{account.monthly.value}}"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

<?php
endPartial();