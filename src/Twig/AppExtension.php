<?php


namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('getUrlIdFromTyp', [$this, 'formatUrlTyp']),
            new TwigFilter('getUrlIdFromCat', [$this, 'formatUrlCat'])
        ];
    }

    public function formatUrlTyp($url)
    {
        $id = null;

        if ( preg_match('/\d+$/', $url) )
        {
            $id = preg_replace('/^.+\/+/', '', $url);
        }
        return $id;
    }

    public function formatUrlCat($url)
    {
        $id = [];
        $temp = preg_replace('/^.+\/+/', '', $url);

        if ( preg_match('/(and)/', $temp) ) {
            $id = explode('and', $temp);
        } else {
            $id[] = $temp;
        }
        return $id;
    }
}