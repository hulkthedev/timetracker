<!DOCTYPE html>
<html>
  <head>
      <title>TimeTracker v1.0</title>
      <meta charset="utf-8">

      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
      <link href="res/favicon.png" type="image/x-icon" rel="shortcut icon">

      <link href="res/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <link href="res/datepicker/bootstrap-datetimepicker.min.css" rel="stylesheet">
      <link href="res/css/styles.css" rel="stylesheet">

      <script src="res/jquery/jquery-3.3.1.min.js" charset="utf-8"></script>
      <script src="res/jquery/jquery.twbsPagination.min.js" charset="utf-8"></script>
      <script src="res/bootstrap/js/bootstrap.min.js" charset="utf-8"></script>
      <script src="res/datepicker/bootstrap-datetimepicker.min.js" charset="utf-8"></script>

      <script src="res/js/modules/Tools.js"></script>
      <script src="res/js/modules/Config.js"></script>
      <script src="res/js/modules/TimeAccount.js"></script>
      <script src="res/js/modules/WorkingList.js"></script>
      <script src="res/js/modules/WorkingEdit.js"></script>
      <script src="res/js/modules/WorkingActions.js"></script>
      <script src="res/js/Init.js"></script>
  </head>
  <body>
    <header class="container">
        <nav class="navbar navbar-default">
            <div class="navbar-header">
                <a class="navbar-brand">TimeTracker</a>
            </div>

            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a><span class="glyphicon glyphicon-th-list"></span> List</a>
                    </li>
                    <li>
                        <a data-toggle="modal" data-target="#modal-working-start"><span class="glyphicon glyphicon-play"></span> Start Working</a>
                    </li>
                    <li>
                        <a data-toggle="modal" data-target="#modal-working-end"><span class="glyphicon glyphicon-stop"></span> End Working</a>
                    </li>
                    <li>
                        <a data-toggle="modal" data-target="#modal-time-account"><span class="glyphicon glyphicon-user"></span> Time Account</a>
                    </li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-option-vertical"></span>
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a href="#" data-toggle="modal" data-target="#modal-config">
                                    <span class="glyphicon glyphicon-cog"></span> Settings
                                </a>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <a href="#"><span class="glyphicon glyphicon-open-file"></span> Export Database</a>
                            </li>
                            <li>
                                <a href="#"><span class="glyphicon glyphicon-erase"></span> Clear Database</a>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <a href="#"><span class="glyphicon glyphicon-refresh"></span> Recalculate Time Differences</a>
                            </li>
                            <li>
                                <a href="#"><span class="glyphicon glyphicon-refresh"></span> Recalculate Time Account</a>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <a href="#"><span class="glyphicon glyphicon-print"></span> Print</a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <ul class="nav navbar-nav navbar-right nav-statistics">
                    <li>
                        <a>Sick total <span class="badge sick">0</span></a>
                    </li>
                    <li>
                        <a>Vacation total <span class="badge vacation">0</span></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-10">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width: 1%"></div>
                </div>

                <table class="table table-condensed working-time hidden">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="align-center">Week</th>
                            <th>Day</th>
                            <th>Date</th>
                            <th>Start</th>
                            <th>End</th>
                            <th class="align-center">Difference</th>
                            <th class="align-center">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8">Database is empty</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-1"></div>
        </div>

        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-10">
                <nav class="align-center working-time-pagination hidden">
                    <ul class="pagination">
                        <li>
                            <a href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li class="active"><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-lg-1"></div>
        </div>
    </main>

    <footer class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <blockquote>
                            <footer><!-- filled by js --></footer>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- modals -->
    <?php include(__DIR__ . '/modals.html'); ?>

  </body>
</html>