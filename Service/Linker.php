<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Entity\Material;
use AppBundle\Entity\UsersMaterials;

interface Linker
{
    function link(User $user, Material $material);
}
