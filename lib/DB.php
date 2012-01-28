<?php

namespace Tootr;

class DB {

    const USER = "tootr";
    const PASS = "rainbowsponiesunicorns";

    const GET_STREAM = "SELECT id FROM stream WHERE name=?";
    const NEW_STREAM = "INSERT INTO stream (name) VALUES (?)";

    const GET_NODE = "SELECT id FROM node WHERE term=?";
    const GET_NODE_BY_ID = "SELECT * FROM node WHERE id=?";
    const NEW_NODE = "INSERT INTO node (term) VALUES (?)";

    const NEW_EDGE = <<<'EOL'
INSERT INTO edge (stream_id, from_id, to_id, rank, myrand) VALUES (?, ?, ?, 1, cast(rand()*10000 as unsigned)) 
ON DUPLICATE KEY UPDATE rank=rank+1
EOL;

    const GET_EDGES = "SELECT * FROM edge WHERE stream_id=? AND from_id=?";

    protected $dbh;

    function __construct() {
       $this->dbh = new \PDO('mysql:host=localhost;dbname=tootr', self::USER, self::PASS); 
    }

    function getStreamId($name) {
        $query = $this->dbh->prepare(self::GET_STREAM);
        $query->execute(array($name));

        if ($rec = $query->fetch(\PDO::FETCH_ASSOC)) {
            return $rec['id'];
        }

        $query = $this->dbh->prepare(self::NEW_STREAM);
        $query->execute(array($name));

        return $this->dbh->lastInsertId();
    }

    function getNodeId($term) {
        $query = $this->dbh->prepare(self::GET_NODE);
        $query->execute(array($term));

        if ($rec = $query->fetch(\PDO::FETCH_ASSOC)) {
            return $rec['id'];
        }

        $query = $this->dbh->prepare(self::NEW_NODE);
        $query->execute(array($term));

        return $this->dbh->lastInsertId();
    }

    function getNode($id) {
        $query = $this->dbh->prepare(self::GET_NODE_BY_ID);
        $query->execute(array($id));

        if ($rec = $query->fetch(\PDO::FETCH_ASSOC)) {
            return $rec;
        }
    }

    function addEdge($streamId, $fromId, $toId) {
        $query = $this->dbh->prepare(self::NEW_EDGE);
        $query->execute(array($streamId, $fromId, $toId));
    }

    function getRandomEdge($streamId, $fromId) {
        $query = $this->dbh->prepare(self::GET_EDGES);
        $query->execute(array($streamId, $fromId));

        $picked = null;
        $count = 0;

        while ($rec = $query->fetch(\PDO::FETCH_ASSOC)) {
            for ($i=0; $i<$rec['rank']; $i++) {
                $count++;
                $myRand = rand(1, $count);

//                error_log("EDGE: FROM {$rec['from_id']}, TO {$rec['to_id']}, C $count, R $myRand, I $i");

                if ($myRand == $count) {
                    $picked = $rec;
                }
            }
        }

        if (isset($picked)) {
            return $picked;
        } else if (isset($rec)) {
            return $rec;
        } else {
            return null;
        }
    }

}
