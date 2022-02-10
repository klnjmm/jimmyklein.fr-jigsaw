<?php

use Illuminate\Support\Str;

return [
    'baseUrl' => '',
    'production' => false,
    'siteName' => 'Jimmy KLEIN',
    'siteDescription' => 'Generate an elegant blog with Jigsaw',
    'siteAuthor' => 'Jimmy KLEIN',
    'links' => [
        'twitter' => 'https://bit.ly/klnjmmtwitter',
        'youtube' => 'https://bit.ly/klnjmmyoutube',
        'github' => 'https://bit.ly/klnjmmgithub',
        'devletter' => 'https://bit.ly/klnjmmdevletter',
    ],
    // collections
    'collections' => [
        'posts' => [
            'author' => 'Jimmy KLEIN',
            'sort' => '-date',
            'path' => '{filename}',
        ],
        'categories' => [
            'path' => '/categories/{filename}',
            'posts' => function ($page, $allPosts) {
                return $allPosts->filter(function ($post) use ($page) {
                    return $post->categories ? in_array($page->getFilename(), $post->categories, true) : false;
                });
            },
        ],
    ],

    // helpers
    'getDate' => function ($page) {
        return Datetime::createFromFormat('U', $page->date);
    },
    'getDateInFrench' => function ($page) {
        $fmt = new IntlDateFormatter('fr_FR',          IntlDateFormatter::MEDIUM,
            IntlDateFormatter::NONE,
            'Europe/Paris',
            IntlDateFormatter::GREGORIAN,
        );

        return $fmt->format($page->date);
    },
    'getExcerpt' => function ($page, $length = 255) {
        if ($page->excerpt) {
            return $page->excerpt;
        }

        $content = preg_split('/<!-- more -->/m', $page->getContent(), 2);
        $cleaned = trim(
            strip_tags(
                preg_replace(['/<pre>[\w\W]*?<\/pre>/', '/<h\d>[\w\W]*?<\/h\d>/'], '', $content[0]),
                '<code>'
            )
        );

        if (count($content) > 1) {
            return $cleaned;
        }

        $truncated = substr($cleaned, 0, $length);

        if (substr_count($truncated, '<code>') > substr_count($truncated, '</code>')) {
            $truncated .= '</code>';
        }

        return strlen($cleaned) > $length
            ? preg_replace('/\s+?(\S+)?$/', '', $truncated) . '...'
            : $cleaned;
    },
    'isActive' => function ($page, $path) {
        return Str::endsWith(trimPath($page->getPath()), trimPath($path));
    },
];
