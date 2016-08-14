<?php
namespace SmpBundle\Service;

use SmpBundle\Entity\User;
use SmpBundle\Entity\Material;
use SmpBundle\Entity\UsersMaterials;

interface Linker
{
    function link(User $user, Material $material);
}
