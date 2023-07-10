<?php

class ThreadsApi {
    const ENDPOINT = "https://threads.com/api/public/";
    private $api_key=null;
  
    public function __construct($api_key) {
        $this->api_key = $api_key;
    }
  
    public function request($method, $query, $data) {
        $url = self::ENDPOINT . "?" . $query;
        $ch = curl_init($url);
        if ($method == "POST") {
            $json = json_encode($data);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        } else if ($method == "GET") {
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $this->api_key,
            'Content-Type: application/json'
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function ping() {
        return $this->request("GET", "ping", null);
    }

    public function postThread($channel, $channelID, $blocks) {
        if ($channel || $channelID) {
                        $data = array();
            if ($channel) {
                $data["channel"] = $channel;
            }
            if ($channelID) {
                $data["channelID"] = $channelID;
            }
            if ($blocks) {
                $data["blocks"] = $blocks;
            }
                        return $this->request("POST", "postThread", $data);
        } else {
                        return "Please provide either channel or channelID";
        }
    }

    public function postComment($threadID, $blocks, $parentID) {
            if ($threadID && $blocks) {
                        $data = array();
            $data["threadID"] = $threadID;
            $data["blocks"] = $blocks;
            if ($parentID) {
            $data["parentID"] = $parentID;
            }
                        return $this->request("POST", "postComment", $data);
        } else {
                        return "Please provide threadID and blocks";
        }
    }

    public function deleteThread($threadID) {
        if ($threadID) {
            $data = array("threadID" => $threadID);
            return $this->request("POST", "deleteThread", $data);
        } else {
            return "Please provide threadID";
        }
    }

    public function channels() {
        return $this->request("POST", "channels", null);
    }

    public function postChatMessage($chat, $chatID, $body) {
        if (($chat || $chatID) && $body) {
            $data = array();
            if ($chat) {
                $data["chat"] = $chat;
            }
            if ($chatID) {
                $data["chatID"] = $chatID;
            }
            if ($body) {
                $data["body"] = $body;
            }
            return $this->request("POST", "postChatMessage", $data);
        } else {
            return "Please provide either chat or chatID and body";
        }
    }

    public function deleteChatMessage($messageID) {
        if ($messageID) {
            $data = array("messageID" => $messageID);
            return $this->request("POST", "deleteChatMessage", $data);
        } else {
            return "Please provide messageID";
        }
    }

    public function uploadFile($data) {
        if ($data) {
            $url = self::ENDPOINT . "?uploadFile";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array("data" => "@$data"));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $this->api_key,
            ));
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
        } else {
            return "Please provide file data";
        }
    }

}
?>
