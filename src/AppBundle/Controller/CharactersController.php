<?php
/**
 * Created by PhpStorm.
 * User: wilder
 * Date: 19/07/18
 * Time: 15:27
 */

namespace AppBundle\Controller;

use GuzzleHttp\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class CharactersController extends Controller
{
    /**
     * @Route("/twenty", name="twenty")
     */
    public function getTwentyAction()
    {
        // Create a client with a base URI
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://gateway.marvel.com/v1/public/',
            // You can set any number of default request options.

        ]);

        $publicKey = 'a6e6494bfd9e6eb8e4dc0f2545116477';
        $privateKey = '6dfcd0b9f350b569c6c059a2a5ba659805aeabd8';
        $timestamp ='1';
        $hash = md5($timestamp . $privateKey . $publicKey);

        // Send request https://foo.com/api/test?key=maKey&name=toto
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

        //dump($characters[0]['thumbnail']);die;

        return $this->render('default/twenty.html.twig', [
            'characters' => $characters,
        ]);
    }

    /**
     * @Route("/details/{id}", name="details")
     */
    public function getDetailsAction($id)
    {
        // Create a client with a base URI
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://gateway.marvel.com:443/v1/public/characters/',
            // You can set any number of default request options.

        ]);

        $publicKey = 'a6e6494bfd9e6eb8e4dc0f2545116477';
        $privateKey = '6dfcd0b9f350b569c6c059a2a5ba659805aeabd8';
        $timestamp ='1';
        $hash = md5($timestamp . $privateKey . $publicKey);

        // Send request https://foo.com/api/test?key=maKey&name=toto
        $response = $client->request('GET', $id, ['query' => [
                'ts' => $timestamp,
                'apikey' => $publicKey,
                'hash' => $hash,
            ]]
        );

        $body = $response->getBody()->getContents();
        $characterDecode = json_decode($body,true);
        $character = $characterDecode['data']['results'][0];
        //dump($character);die;

        $nbComics = count($character['comics']['items']);
        $nbSeries = count($character['series']['items']);

        if ($nbComics) {
            $comics=[];
            for ($i=0; $i<min(3, $nbComics); $i++) {
                $comics[] = $character['comics']['items'][$i]['name'];
            }
        }
        //dump($comics[2]);die;

        return $this->render('default/detail.html.twig', [
            'character' => $character,
            'nbComics' => $nbComics,
            'nbSeries' => $nbSeries,
            'comics' => $comics
        ]);
    }
}