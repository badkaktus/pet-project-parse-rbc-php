<?php
namespace App\Command;

use Pimple\Container;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Curl\Curl;
use Doctrine\DBAL;


class ParseNewsCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'parse:latest-news';
    protected $di;

    public function __construct(Container $di, string $name = null)
    {
        parent::__construct($name);
        $this->di = $di;
    }

    protected function configure()
    {
        // ...
        $this->setDescription('Получить последние новости с сайта rbc.ru');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $curl = new Curl();

        $curl->get('https://www.rbc.ru');

        // регулярка для получения списка новостей из блока слева
        $re = '/<a href="([\S]*)"[\s\d\w\=\"\-]*>\s*<span class="news-feed__item__title[\s\w\-\_]*">\s*([А-Яа-яЁё\s\d\W]*)<\/span>/m';

        // если что-то пошло не так
        if ($curl->getHttpStatus() !== 200) {
            $output->write('Something goes wrong');
            return 0;
        }

        $news = [];
        preg_match_all($re, $curl->getResponse(),$matches);

        // регулярка для парсинга тела новости
        $fullNewsRegex = '/<div class="article__text[\s\w_\-]*"\s+itemprop="articleBody">([\d\s\SA-Za-zА-Яа-яёЁ]+)<div class="(article__clear|article__tags|subscribe-infographic|article__authors)[\s\w\-_]*">/m';
        // регулярка для парсинга текста новости
        $fullTextRegex = '/<p>(.*?)<\/p>/m';
        // регулярка для парсинка картинки для новости
        $imgRegex = '/<img src="([\S]+)"[\s\w="\-_]*class="article__main-image[\s\w\-_]*"[\s\w="\-_]*\/>/m';

        $curlNews = new Curl();
        $curlNews->setOpt(CURLOPT_FOLLOWLOCATION, 1);

        // обходим все новости
        foreach ($matches[1] as $i => $v) {

            $news[] = [
                    'url' => trim($v),
                    'title' => trim($matches[2][$i])
            ];


            $curlNews->get($news[$i]['url']);
            preg_match($fullNewsRegex, $curlNews->getResponse(), $textMatch);

            // очищаем от лишних тегов
            $clearText = strip_tags($textMatch[0], '<p>');

            preg_match($imgRegex, $curlNews->getResponse(), $imgUrl);

            preg_match_all($fullTextRegex, $clearText, $clearTextMatch);

            $news[$i]['cleartext'] = (is_array($clearTextMatch[1])) ? implode(" ", $clearTextMatch[1]) : "";

            $insertData = [
                'title' => $news[$i]['title'],
                'news' => $news[$i]['cleartext']
            ];

            if(isset($imgUrl[1])) {
                $insertData['img'] = $imgUrl[1];
            }

            $this->di['db']->insert(
                'latest_news', $insertData
            );


        }

        $curlNews->close();

        $curl->close();
        return 0;
    }
}