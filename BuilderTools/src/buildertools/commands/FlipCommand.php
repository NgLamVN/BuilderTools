<?php

/**
 * Copyright 2018 CzechPMDevs
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

namespace buildertools\commands;

use buildertools\BuilderTools;
use buildertools\editors\Copier;
use buildertools\editors\Editor;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

/**
 * Class FlipCommand
 * @package buildertools\commands
 */
class FlipCommand extends Command implements PluginIdentifiableCommand {

    /**
     * FlipCommand constructor.
     */
    public function __construct() {
        parent::__construct("/flip", "Flip selected area", null, []);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if(!$sender instanceof Player) {
            return;
        }

        if(!$sender->hasPermission("bt.cmd.flip")) {
            $sender->sendMessage("§cYou do not have permissions to use this command.");
            return;
        }

        /** @var Copier $copier */
        $copier = BuilderTools::getEditor(Editor::COPIER);
        $copier->flip($sender);
    }

    /**
     * @return Plugin|BuilderTools $plugin
     */
    public function getPlugin(): Plugin {
        return BuilderTools::getInstance();
    }
}
