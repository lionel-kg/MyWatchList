<?php

namespace App\Service;
use App\Entity\Manga as MangaEntity;
use App\Entity\Genre as GenreEntity;
use App\Service\Tool\Manga as MangaTool;
use App\Service\Genre as GenreService;

class Manga extends MangaTool
{

    /**
     * @return array
     */
    public function getMangas() :array
    {
        $errorDebug = "";
        $response = ["error"=>"","errorDebug"=>"","mangas"=>[]];
        try {
            $mangas = $this->findAll();
            if ($mangas === null) {
                $response["error"] = "Erreur lors de la recuperation des mangas";
               return $response;
            }
            //$response["mangas"] = $this->getInfoSerialize($mangas,["info_manga"]);
            $response["mangas"] = $mangas;
        } catch (\Exception $e) {
            $errorDebug = sprintf("Exception : %s",$e->getMessage());
        }
        if($errorDebug !== ""){
            $response["errorDebug"] = $errorDebug;
            $response["error"] = "Erreur lors de la récuperation du manga";
        }
        dd($response);
        return $response;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getManga(int $id): array
    {
        $errorDebug = "";
        $response = ["error"=>"","errorDebug"=>"","manga"=>[]];
        try{
            $manga = $this->findById($id);
            if ($manga === null) {
               $response["error"] = "Erreur lors de la récuperation du manga";
                return $response;
            }
            //$response["manga"] = $this->getInfoSerialize([$manga], ["info_manga"]);
            $response["manga"] = $manga;
        } catch (\Exception $e) {
            $errorDebug = sprintf("Exception : %s",$e->getMessage());
        }
        if($errorDebug !== ""){
            $response["errorDebug"] = $errorDebug;
            $response["error"] = "Erreur lors de la récuperation du manga";
        }
        dd($manga);
        return $response;
    }

    public function add(array $mangas,GenreService $genreService)
    {
        $errorDebug = "";
        $response = ["error" => "", "errorDebug" => "","mangas"=> []];
        try {
            foreach ($mangas as $m)
            {
                $manga =  $this->createEntity(MangaEntity::class,$m);
                foreach ($m["genres"] as $genre) {
                    $newGenre = $genreService->createEntity(GenreEntity::class,$genre);
                    $this->em->persist($newGenre);
                    $manga->addGenre($newGenre);
                }
                $this->em->persist($manga);
            }
            $this->em->flush();
        } catch (\Exception $e) {
            $errorDebug = sprintf("Exception : %s",$e->getMessage());
        }
        if($errorDebug !== ""){
            $response["errorDebug"] = $errorDebug;
            $response["error"] = "Erreur lors de l'ajout de l'utilisateur";
        }

    }
}