<?php

namespace Classes\Models;

use Classes\Database;

class ReportModel
{
    private $db;
    private $connection;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->connection = $this->db->getConnection();
    }

    public function getBest3PointShooters()
    {
        $good_shooters = [];
        $sql = "SELECT roster.name, player_totals.3pt, player_totals.3pt_attempted, age, team.name as team_name
                FROM player_totals
                INNER JOIN roster ON (roster.id = player_totals.player_id)
                Right JOIN team on (team.code = roster.team_code)
                Where age >= 30 and 3pt != 0 and 3pt_attempted != 0;";
        $data = $this->connection->query($sql);
        $result = $data->fetch_all(MYSQLI_ASSOC);
        foreach($result as $res) {
            $shooting_percentage = ($res['3pt'] / $res['3pt_attempted']) * 100;
            if(round($shooting_percentage,2) > 35) {
                $good_shooters[] = array_merge($res, ['shooting_percentage' =>  intval(round($shooting_percentage))]);
            }
        }

        return collect($good_shooters)->sortBy('shooting_percentage')->reverse()->values()->all();
    }
}
