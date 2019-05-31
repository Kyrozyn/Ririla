<?php


namespace Controller;


use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Model\xpModel;

class xp
{
    private $model;
    private $userid;
    private $groupid;
    private $bot;

    /**
     * xp constructor.
     * @param $userid
     * @param $groupid
     * @param LINEBot $bot
     */
    public function __construct($userid, $groupid, LINEBot $bot)
    {
        $this->userid = $userid;
        $this->groupid = $groupid;
        $this->bot = $bot;
        $this->model = new xpModel($userid, $groupid);
    }

    public function isFound()
    {
        if ($this->model->hasXP()) {
            $reply = new TextMessageBuilder('your XP found');
        } else {
            $reply = new TextMessageBuilder('your XP not found');

        }
        return $reply;
    }

    public function getXP()
    {
        $xp = $this->model->getXP();
        $reply = new TextMessageBuilder("XP kamu = " . $xp);
        return $reply;
    }

    public function addXP()
    {
        $this->model->addXP();
    }

    public function getLeaderboard()
    {
        $header = "***Leaderboard***";
        $angka = 0;
        $balas = null;
        foreach ($this->model->getLeaderboard() as $id) {
            $angka = $angka + 1;
            $profile = $this->bot->getProfile($id['userid']);
            $json = $profile->getJSONDecodedBody();
            $nama = $json['displayName'];
            if (empty($nama)) {
                $nama = "????";
            }
            $balas = $balas . $angka . ". " . $nama . " : " . $id["xp"];
            if ($angka < 10) {
                $balas = $balas . "\n";
            }
        }
        $satu = new TextMessageBuilder($header);
        $dua = new TextMessageBuilder($balas);
        $reply = new MultiMessageBuilder();
        $reply->add($satu);
        $reply->add($dua);
        return $reply;
    }
}