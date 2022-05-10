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

    public function getBest3PointShooters() {
        $good_shooters = [];
        $sql = "SELECT id, name, team_code, pos, number FROM roster";
        $data = $this->connection->query($sql);
        $players = $data->fetch_all(MYSQLI_ASSOC);
        foreach($players as $player) {
            $sql = "SELECT 3pt, 3pt_attempted, age FROM player_totals WHERE player_id = '{$player['id']}' and 3pt_attempted > 0 and 3pt > 0";
            $team_sql = "SELECT name from team WHERE code = '{$player['team_code']}'";
            $team_sql = $this->connection->query($team_sql);
            $team = $team_sql->fetch_assoc()['name'];
            $data = $this->connection->query($sql);
            $data = $data->fetch_assoc();
            $data = $data != null ? array_merge($data, ['team' => $team]) : [];
            //insert the team to the player_totals array
            if($data['age'] >= 30) {
                $shoot_percentage = ($data['3pt'] / $data['3pt_attempted']) * 100;
                if(round($shoot_percentage) >= 35) {
                    $good_shooters[] = $data;
                }
            }
        }
        dd($good_shooters);
        
    }

    private function getTotalStats($id) {
        $sql = "SELECT * FROM player_totals WHERE player_id = '{$id}'";
        $data = $this->connection->query($sql);
    }

}