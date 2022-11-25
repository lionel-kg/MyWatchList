<?php

namespace App\Service\Tool;

use App\Service\CustomAbstractService;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Manga as MangaEntity;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class Manga extends CustomAbstractService
{
    protected $em;

    private $params;
    public function __construct(EntityManagerInterface $em, ParameterBagInterface $params, SerializerInterface $serializer, SluggerInterface $slugger)
    {
        $this->em = $em;
        $this->params = $params;
        parent::__construct($em, $params, $serializer, $slugger);
    }

    /**
     * @return array
     */
    public function findAll() :array
    {

        return $this->em->getRepository(MangaEntity::class)->findAll();
    }

    /**
     * @param int $id
     * @return MangaEntity
     */
    public function findById(int $id): MangaEntity
    {
        return $this->em->getRepository(MangaEntity::class)->find($id);
    }

    public function createEntity(string $entityName, array $parameters): object
    {
        $fields = ["originalName","synopsis","duration","season","background","type","year"];
        return $this->createSimpleEntity($entityName, $fields, $parameters);
    }


}