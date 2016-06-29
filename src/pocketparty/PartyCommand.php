<?php
namespace pocketparty;


use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\utils\TextFormat;
use synapse\Player;

class PartyCommand extends PluginCommand{
    public function __construct( main $owner)
    {
        parent::__construct("party", $owner);
        $this->main = $owner;
    }

    public function execute(CommandSender $sender, $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if (empty($args)){
                $sender->sendMessage(TextFormat::GOLD."Party Help:");
                $sender->sendMessage("/party [Player]");
                $sender->sendMessage("/party invite [Player]");
                $sender->sendMessage("/party remove [Player]");
            }
            switch ($args[1]){
                case "invite":
                    $player = $this->main->getServer()->getPlayer($args[2]);
                    if ($this->main->isInParty($sender)){
                        if ($this->main->isInParty($player)){
                            $sender->sendMessage("Player is already in a party");
                            return true;
                        }
                        $this->main->sendRequest($sender,$player);
                        $sender->sendMessage("Party request sent to ".$player->getName());
                        return true;
                    }
                    $sender->sendMessage("You must first start a party with /party [playername]");
                    return true;
                break;
                case "accept":
                    $this->main->acceptRequest($sender);
                break;
                case "remove":
                    if (isset($args[2])){
                        $player = $this->main->getServer()->getPlayer($args[2]);
                        if ($player) {
                            $this->main->removePlayerFromParty($player);
                        }
                    }
                break;
                default:
                    $start = $this->main->getServer()->getPlayer($args[0]);
                    if (!$start){
                        if ($this->main->isInParty($sender)){
                            $sender->sendMessage("You cant start a party you already are in one!");
                            return true;
                        }
                        if ($this->main->isInParty($start)){
                            $sender->sendMessage("This player is already in a party!");
                            return true;
                        }
                        $sender->sendMessage("Party request sent to ".$start->getName());
                        $this->main->sendRequest($sender,$start);
                        return true;
                    }else{
                        $sender->sendMessage(TextFormat::GOLD."Party Help:");
                        $sender->sendMessage("/party [Player]");
                        $sender->sendMessage("/party invite [Player]");
                        $sender->sendMessage("/party remove [Player]");
                    }
            }
        }
    }


}