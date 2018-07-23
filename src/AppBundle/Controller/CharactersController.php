<?php
/**
 * Created by PhpStorm.
 * User: wilder
 * Date: 19/07/18
 * Time: 15:27
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Favorite;
use GuzzleHttp\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class CharactersController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function getTwentyAction()
    {
        // Create a Guzzle client with a base URI
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://gateway.marvel.com/v1/public/',
            // You can set any number of default request options.

        ]);

        $publicKey = 'a6e6494bfd9e6eb8e4dc0f2545116477';
        $privateKey = '6dfcd0b9f350b569c6c059a2a5ba659805aeabd8';
        $timestamp ='1';
        $hash = md5($timestamp . $privateKey . $publicKey);


        $response = $client->request('GET','characters', ['query' => [
                'ts' => $timestamp,
                'apikey' => $publicKey,
                'hash' => $hash,
                'orderBy' => 'name',
                'limit' => '20',
                'offset' => '99.json',
            ]]
        );

        $body = $response->getBody()->getContents();
        $characterDecode = json_decode($body,true);
        $characters = $characterDecode['data']['results'];

            //dump($characters);die;

        return $this->render('default/indexhtml.twig', [
            'characters' => $characters,
        ]);
    }

    /**
     * @Route("/details/{id}", name="details")
     */
    public function getDetailsAction($id)
    {
        // Create a Guzzle client with a base URI
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://gateway.marvel.com:443/v1/public/characters/',
            // You can set any number of default request options.

        ]);

        $publicKey = 'a6e6494bfd9e6eb8e4dc0f2545116477';
        $privateKey = '6dfcd0b9f350b569c6c059a2a5ba659805aeabd8';
        $timestamp ='1';
        $hash = md5($timestamp . $privateKey . $publicKey);

        $response = $client->request('GET', $id, ['query' => [
            'ts' => $timestamp,
            'apikey' => $publicKey,
            'hash' => $hash,
            ]]
        );

        $body = $response->getBody()->getContents();
        $characterDecode = json_decode($body,true);
        $character = $characterDecode['data']['results'][0];

        $nbComics = count($character['comics']['items']);
        $nbSeries = count($character['series']['items']);
        $comics=[];
        if ($nbComics) {
            for ($i=0; $i<3; $i++) {
                $comics[] = $character['comics']['items'][$i]['name'];
            }
        }

        //check if favorite object  already exists and is set true
        $CharacterAlreadyFavorite = $this->getDoctrine()
            ->getRepository(Favorite::class)
            ->findOneBy([
                'charactherId' =>$id
            ]);

        //check if favorite is set true
        if ($CharacterAlreadyFavorite) {
            if ($CharacterAlreadyFavorite->getisSet()) {
                $heart = 1;
            } else {
                $heart = 0;
            }
        } else {
            $heart = 0;
        }


        return $this->render('default/detail.html.twig', [
            'character' => $character,
            'nbComics' => $nbComics,
            'nbSeries' => $nbSeries,
            'comics' => $comics,
            'heart' => $heart,
        ]);
    }

    /**
     * @Route("/favorite/{id}", name="favorite")
     */
    public function favoriteAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        //check if number of favorites Characters is less than 5
        $FavoriteNumbers = $this->getDoctrine()
            ->getRepository(Favorite::class)
            ->findBy(
                ['isSet' => true]
            );
        $countFavorite = count($FavoriteNumbers);


        $CharacterAlreadyFavorite = $this->getDoctrine()
            ->getRepository(Favorite::class)
            ->findOneBy([
                'charactherId' =>$id
            ]);

        //if object favorite doesn't exists, create one
        if (!$CharacterAlreadyFavorite) {
            $favorite = new favorite();
            $favorite->setCharactherId($id);
            if ($countFavorite >4) {
                $this->addFlash('danger', 'Désolé, vous ne pouvez pas avoir plus de 5 favoris. Vous devez d\'abord en supprimer un');

            } else {
                $favorite->setIsSet(true);

                $em->persist($favorite);
                $em->flush();
                $this->addFlash('success', 'Le personnage a été ajouté à vos favoris avec succès');
            }


        //if not, set true or false
        } else {
            if ($CharacterAlreadyFavorite->getisSet() == false ) {

                if ($countFavorite >4) {
                    $this->addFlash('danger', 'Désolé, vous ne pouvez pas avoir plus de 5 favoris. Vous devez d\'abord en supprimer un');
                } else {
                        $CharacterAlreadyFavorite->setisSet(true);
                        $em->flush();
                        $this->addFlash('success', 'Le personnage a été ajouté à vos favoris avec succès');
                }


            } else $CharacterAlreadyFavorite->setisSet(false);
                $em->flush();
                $this->addFlash('success', 'Le personnage a été supprimé de vos favoris avec succès');
            }


        return $this->redirectToRoute('details', array (
            'id' => $id,
            ));
    }


    /**
     * @Route("/favorites", name="favoriteAll")
     */
    public function favoriteAllAction()
    {
        //check all in favorite entity
        $allFavoritesCharacters = $this->getDoctrine()
            ->getRepository(Favorite::class)
            ->findBy(
                ['isSet' => true]
            );
            //dump($allFavoritesCharacters);die;


        for ($i=0; $i<count($allFavoritesCharacters); $i++) {

            $favoriteId = $allFavoritesCharacters[$i]->getcharactherId();
            settype ($favoriteId,  "string");



            // Create a Guzzle client with a base URI
            $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => 'https://gateway.marvel.com:443/v1/public/characters/',
                // You can set any number of default request options.

            ]);

            $publicKey = 'a6e6494bfd9e6eb8e4dc0f2545116477';
            $privateKey = '6dfcd0b9f350b569c6c059a2a5ba659805aeabd8';
            $timestamp ='1';
            $hash = md5($timestamp . $privateKey . $publicKey);

            $response = $client->request('GET', $favoriteId, ['query' => [
                    'ts' => $timestamp,
                    'apikey' => $publicKey,
                    'hash' => $hash,
                ]]
            );


            $body = $response->getBody()->getContents();
            $characterDecode = json_decode($body,true);

            $character = $characterDecode['data']['results'][0];


        }

        return $this->render('default/favorite.html.twig', [
            'allFavoritesCharacters' => $allFavoritesCharacters
        ]);
    }
}