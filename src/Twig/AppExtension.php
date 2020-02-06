<?php


namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('getUrlId', [$this, 'formatUrl']),
        ];
    }

    public function formatUrl($url)
    {
        $id = null;
        if ( preg_match('/\d+$/', $url) )
        {
            $id = preg_replace('/^.+\/+/', '', $url);
        }
        return $id;
    }
}