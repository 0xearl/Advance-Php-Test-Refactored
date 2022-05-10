<?php 

use Classes\Models\ReportModel;

$report_model = new ReportModel();

$threept_shooters = $report_model->getBest3PointShooters();
echo "<h1>Best Three Point Shooter Players</h1>";
echo asTable($threept_shooters);

