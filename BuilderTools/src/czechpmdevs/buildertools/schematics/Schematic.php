<?php

/**
 * Copyright (C) 2018-2021  CzechPMDevs
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

namespace czechpmdevs\buildertools\schematics;

use czechpmdevs\buildertools\async\SchematicCreateTask;
use czechpmdevs\buildertools\async\SchematicLoadTask;
use czechpmdevs\buildertools\blockstorage\BlockList;
use czechpmdevs\buildertools\BuilderTools;
use pocketmine\math\Vector3;
use pocketmine\Server;

/**
 * Class Schematic
 * @package czechpmdevs\buildertools\schematics
 */
class Schematic extends SchematicData {

    /** @var string $file */
    public string $file;

    /**
     * Schematic constructor.
     * @param BlockList $blocks
     * @param Vector3 $axisVector
     * @param string $materialType
     */
    public function __construct(BlockList $blocks, Vector3 $axisVector, string $materialType = SchematicData::MATERIALS_BEDROCK) {
        parent::__construct($blocks, $axisVector, $materialType);
        $this->isLoaded = true;
    }

    /**
     * @param string $file
     */
    public function save(string $file) {
        Server::getInstance()->getAsyncPool()->submitTask(new SchematicCreateTask($file, $this->getBlockList()->toThreadSafe(), $this->getAxisVector(), $this->materialType));
    }

    /**
     * @param SchematicLoadTask $task
     * @return Schematic|null
     */
    public static function loadFromAsync(SchematicLoadTask $task): ?Schematic {
        if($task->error !== "") {
            BuilderTools::getInstance()->getLogger()->error($task->error);
            return null;
        }

        BuilderTools::getInstance()->getLogger()->info("Schematics " . basename($task->path, ".schematic") . " loaded!");
        return new Schematic($task->blockList->toBlockList(), $task->axisVector, $task->materials);
    }
}