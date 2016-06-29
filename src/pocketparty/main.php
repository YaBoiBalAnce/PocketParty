<?php
namespace pocketparty;


use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class main extends PluginBase{
    public $parties = [];
    public $requests = [];
    public function onEnable()
    {
        $this->getLogger()->info("Enabled the Party!");
        //Register /party command
        $this->getServer()->getCommandMap()->register(PartyCommand::class, new PartyCommand($this));
    }

    public function startParty(Player $host,Player $player){
        $this->parties[$host->getName()][$player->getName()] = 0;
        $host->sendMessage("Party Started. Invite others using /party invite [player]");
    }

    public function sendRequest(Player $host,Player $player){
        $time = time() + 60;
        $this->requests[$host->getName()]["Time"] = $time;
        $this->requests[$host->getName()]["Requester"] = $player->getName();
        $this->requests[$host->getName()]["isHost"] = true;
        $this->requests[$player->getName()]["Time"] = $time;
        $this->requests[$player->getName()]["Requester"] = $host->getName();
        $this->requests[$player->getName()]["isHost"] = false;
        $name = $host->getName();
        $player->sendMessage("Player: $name invited you to thier party");
        $player->sendMessage("Do /party accept -  to join!");
    }

    public function isInParty(Player $p){
        foreach ($this->parties as $host => $player){
            if ($player === $p->getName()){
                return true;
            }
        }
        return false;
    }

    public function acceptRequest(Player $p){
        if (isset($this->requests[$p->getName()])){
            if (!$this->requests[$p->getName()]["isHost"]){
                if (!time() > $this->requests[$p->getName()]["Time"]){
                    $p->sendMessage("Accepted party request");
                    $host = $this->requests[$p->getName()]["Requester"];
                    $host = $this->getServer()->getPlayer($host);
                    if ($host) {
                        $this->startParty($host, $p);
                        unset($this->requests[$host->getName()]);
                        unset($this->requests[$p->getName()]);
                        return;
                    }else{
                        $p->sendMessage("Requester seems to be no longer online. Cancelled party");
                        unset($this->requests[$this->requests[$p->getName()]["Requester"]]);
                        unset($this->requests[$p->getName()]);
                        return;
                    }

                }else{
                    $p->sendMessage("Your request seems to have timed out!");
                    unset($this->requests[$this->requests[$p->getName()]["Requester"]]);
                    unset($this->requests[$p->getName()]);
                    return;
                }
            }else{
                $p->sendMessage("Your the host of the party silly!");
                return;
            }
        }
    }

    public function getHost(Player $p){
        foreach ($this->parties as $host => $pblah){
            foreach ($this->parties[$host] as $player => $dummyval){
                if ($player === $p->getName()){
                    return $host;
                }
            }
        }
        return false;
    }

    public function removePlayerFromParty(Player $player){
        if ($this->isInParty($player)){
            if ($this->getHost($player) === $player->getName()){
                //code shit ehrvsjaerqhaerfwevfwrtop[rHWGF
            }
        }
    }
}