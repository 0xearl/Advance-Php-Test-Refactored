<?php 

use Classes\Models\ReportModel;

$report_model = new ReportModel();

$report_model->getBest3PointShooters();

//SELECT * from (select 3pt, 3pt_attempted, age from player_totals left join roster on player_totals.player_id = roster.id)  as result;
