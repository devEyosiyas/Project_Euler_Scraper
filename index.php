<?php

include_once('simple_html_dom.php');

set_time_limit(0);

get_the_problem(1, 720);

function get_the_problem($start, $end)
{
    $json = array();
    for ($i = $start; $i <= $end; $i++) {
        $html = file_get_html('https://projecteuler.net/problem=' . $i);
        $data['ID'] = $html->find('h3', 0)->plaintext;
        $data['Title'] = $html->find('h2', 0)->plaintext;
        $meta = $html->find('.info span style="left:-250px;width:250px;top:30px;"', 1)->innertext;
        $meta = explode(';', $meta);
        $data['Published On'] = substr($meta[0], 13);
        $data['Solved By'] = substr($meta[1], 11);
        $data['Difficulty'] = substr($meta[2], 23);
        foreach ($html->find('div.problem_content') as $content) {
            $data['Problem'] = $content->plaintext;
            foreach ($content->find('img') as $image)
                $data['Image'][] = $image->src;
        }
        $json[] = $data;
    }
    write_json($json);
}

function write_json($content)
{
    file_put_contents('problems.json', json_encode($content, true));
}