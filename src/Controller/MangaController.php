<?php

namespace App\Controller;

use App\Service\Genre as GenreService;
use App\Service\Manga as MangaService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/manga", name="app_manga")
 */
class MangaController extends CustomAbstractController
{

    /**
     * @Route ("/all", methods={"GET"} ,name="_all")
     * @param MangaService $mangaService
     * @return JsonResponse
     */
    public function getAll(MangaService $mangaService, Request $request): JsonResponse
    {
        $errorDebug = "";
        $connected = @fsockopen($request->getUriForPath($request->getPathInfo()), 80);
        //website, port  (try 80 or 443)
        if ($connected){
            $is_conn = true; //action when connected
            fclose($connected);
        }else{
            $is_conn = false; //action in connection failure
        }
        [
            "error"=>$error,
            "errorDebug"=>$errorDebug,
            "mangas"=>$mangas
        ] = $mangaService->getMangas();
        if($errorDebug !== ""){
            return $this->sendError($error,$errorDebug);
        }
        return $this->sendSuccess("recover manga successfull", ["mangas" => $mangas], response::HTTP_OK);
    }

    /**
     * @Route("/{id}" , methods={"GET"}, name="_info")
     */
   public function getById(int $id, MangaService $mangaService){
        $errorDebug = "";
           [
               "error" => $error,
               "errorDebug" => $errorDebug,
               "manga" => $manga
           ] = $mangaService->getManga($id);

       if ($errorDebug !== "") {
           return $this->sendError($errorDebug,$errorDebug);
       }
       return $this->sendSuccess("recover manga successfull", ["manga" => $manga], response::HTTP_OK);
   }

    /**
     * @Route("/add", methods={"GET"},name="_add")
     * @return void
     * @throws GuzzleException
     */
    public function  add(MangaService $mangaService, GenreService $genreService): JsonResponse
    {
        $client = new Client();
        $res = $client->request( "GET","https://api.jikan.moe/v4/top/anime",['verify' => false]);
        $resJson = json_decode($res->getBody()->getContents(),true);
        $arrayManga = [];
        foreach ($resJson["data"] as $manga)
        {
            $arrayParams = [];
            $arrayParams["manga"]["originalName"] = $manga["titles"][0]["title"];
            $arrayParams["manga"]["year"] = $manga["year"];
            //dump($manga["demographics"][0]["name"]);
            if(isset($manga["demographics"][0])) {
                $arrayParams["manga"]["type"] = $manga["demographics"][0]["name"];
            }
            $arrayParams["manga"]["synopsis"] = $manga["synopsis"];
            $arrayParams["manga"]["duration"] = $manga["duration"];
            $arrayParams["manga"]["season"] = $manga["season"];
            $arrayParams["manga"]["background"] = $manga["images"]["jpg"]["large_image_url"];
            $arrayParams["manga"]["genres"] = $manga["genres"];
            $newManga = $mangaService->add($arrayParams,$genreService);
        }
        return $this->sendSuccess("create manga successful",[],response::HTTP_CREATED);
        }
}
