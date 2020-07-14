<?php
include(dirname(__FILE__).'/simple_html_dom.php');
include(dirname(__FILE__).'/__fn.php');

// PROFILE
$PROFILE = '';

// START ID
$ID = '';

while(true){
    echo "load ".$ID;
    $page = getpage('https://'.$PROFILE.'.soup.io/since/'.$ID.'?mode=own');
    echo "\n";
    $html = str_get_html($page['content']);

    $post = $html->find('div.post');

    foreach($post as $item){
        $postid = $item->id;
        $abbr = $item->find('abbr', 0);
        $time = strtotime( $abbr->title );
        echo "\t - post: ".date("Y_m_d_H-i-s", $time);
        $filename = date("Y_m_d_H-i-s", $time).'_'.$postid;

        $img = $item->find('.lightbox', 0);
        if(isset($img)){
            saveImg( $img->href, $filename);

        }else{
            $img = $item->find('.imagecontainer', 0);
            if(isset($img)){
                $img = $img->find('img', 0);
                saveImg( $img->src, $filename);

            }else{
                $blockquote = $item->find('blockquote', 0);
                if(isset($blockquote)){
                    $credits = $item->find('cite', 0);
                    $text = clearCytat( $blockquote->plaintext )."\n"
                            .clearCytat( $credits->plaintext )."\n";
                    saveFile( $text, $filename);

                }else{
                    $body = $item->find('.body', 0);
                    if(isset($body)){
                        $text = clearCytat( $body->plaintext );
                        saveFile( $text, $filename);
                    }

                }
            }
        }
        // break;
        echo "\n";
    }

    $ID = $page['next'];

}
