<?php 

namespace Classes\Models;

use Classes\Database;

class PlayerModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->con = $this->db->getConnection();
    }

    public function getPlayerStats($search)
    {
        $where = [];
        if ($search->has('playerId')) $where[] = "roster.id = '" . $search['playerId'] . "'";
        if ($search->has('player')) $where[] = "roster.name = '" . $search['player'] . "'";
        if ($search->has('team')) $where[] = "roster.team_code = '" . $search['team']. "'";
        if ($search->has('position')) $where[] = "roster.pos = '" . $search['position'] . "'";
        if ($search->has('country')) $where[] = "roster.nationality = '" . $search['country'] . "'";
        $where = implode(' AND ', $where);
        $sql = "
            SELECT roster.name, player_totals.*
            FROM player_totals
                INNER JOIN roster ON (roster.id = player_totals.player_id)
            WHERE $where";
        $data = $this->con->query($sql) ?: [];
        // calculate totals
        foreach ($data as $row) {
            unset($row['player_id']);
            $row['total_points'] = ($row['3pt'] * 3) + ($row['2pt'] * 2) + $row['free_throws'];
            $row['field_goals_pct'] = $row['field_goals_attempted'] ? (round($row['field_goals'] / $row['field_goals_attempted'], 2) * 100) . '%' : 0;
            $row['3pt_pct'] = $row['3pt_attempted'] ? (round($row['3pt'] / $row['3pt_attempted'], 2) * 100) . '%' : 0;
            $row['2pt_pct'] = $row['2pt_attempted'] ? (round($row['2pt'] / $row['2pt_attempted'], 2) * 100) . '%' : 0;
            $row['free_throws_pct'] = $row['free_throws_attempted'] ? (round($row['free_throws'] / $row['free_throws_attempted'], 2) * 100) . '%' : 0;
            $row['total_rebounds'] = $row['offensive_rebounds'] + $row['defensive_rebounds'];
        }
        return collect($data);
    }

    public function getPlayers($search)
    {
        $where = [];
        if ($search->has('playerId')) $where[] = "roster.id = '" . $search['playerId'] . "'";
        if ($search->has('player')) $where[] = "roster.name = '" . $search['player'] . "'";
        if ($search->has('team')) $where[] = "roster.team_code = '" . $search['team']. "'";
        if ($search->has('position')) $where[] = "roster.position = '" . $search['position'] . "'";
        if ($search->has('country')) $where[] = "roster.nationality = '" . $search['country'] . "'";
        $where = implode(' AND ', $where);
        $sql = "
            SELECT roster.*
            FROM roster
            WHERE $where";
        return collect($this->con->query($sql))
            ->map(function($item, $key) {
                unset($item['id']);
                return $item;
            });
    }
}