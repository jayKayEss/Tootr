<?php

namespace Tootr;

class Parser {

    protected $db;
    protected $streamId;

    function __construct($db, $streamName) {
        $this->db = $db;
        $this->streamId = $db->getStreamId($streamName);
    }

    function parse($text) {
//        $words = preg_split('/\s+|([!?.,]+)\b/', $text, null, \PREG_SPLIT_DELIM_CAPTURE);
          $words = preg_split('/\s+/', $text);

//        print_r($words);

        $lastWord = null;
        $endOfSentence = false;

        foreach ($words as $word) {
            if (empty($word)) continue;

//            if (in_array($word, array('.', '!', '?'))) {
            if (preg_match('/\.$/', $word)) {
                $endOfSentence = true;
            }

            if ($lastWord) {
                $this->addEdge($lastWord, $word);
            } else {
                $this->addEdge(null, $word); // headword
            }

            if ($endOfSentence) {
                $lastWord = null;
                $endOfSentence = false;
            } else {
                $lastWord = $word;
            }
        }

    }

    function addEdge($from, $to) {
        if (!empty($from)) {
            $fromId = $this->db->getNodeId($from);
        } else {
            $fromId = 0;
        }
        $toId = $this->db->getNodeId($to);
        $this->db->addEdge($this->streamId, $fromId, $toId);
    }

}
