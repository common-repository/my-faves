<?php

function myfaves_add_fave(){

     $options = get_option( 'myfaves_custom_text' );
     $hidetags = get_option( 'myfaves_hide_tags' );
     $faveCustomText = $options['myfaves_custom_text_txt'];
     $unfaveCustomText = $options['myfaves_unfave_custom_text_txt'];
     $hidetagsopt = $hidetags['myfaves_hide_tags_slct'];
      if ($faveCustomText=='') { $faveCustomText = 'Fave it';} 
      if ($unfaveCustomText=='') { $unfaveCustomText = 'Unfave it';} 
      if ($hidetagsopt==''){ $hidetagsopt='no'; }

        $pid=get_the_ID();
		$title= get_the_title();
        $ptype=get_post_type();
        $uid = get_current_user_id();
        $args=array(
        'post_type'=> 'myfaves',
        'author'=>  $uid,
        'post_status' =>'publish',              
         'meta_query' => array(
             array(
                 'key'   => 'myfaves_postid',
                 'value' => $pid,
                 )
                )
         );
        if (is_user_logged_in()) {
            $faved = get_posts($args) ;
            if ($faved) {
                $str = "<div id='myfaves-formdiv'><form id='myfaves-unfaveform'><a id='myfaves-unfavebtn' class='myfaves-remove-btn'><i class='fas fa-minus-circle'></i> ";
                $str .= $unfaveCustomText."</a><br>";
                $str .= "<input type='hidden' class='myfaves-favepid' name='myfaves-favepid' value='".$pid."'>";
                $str .= "<input type='hidden' class='myfaves-pgtitle' name='myfaves-pgtitle' value='".$title."'><div class='myfaves-unfavemsg'></div></form></div>";
                return $str;
            }  elseif (($faved ==false) && ($ptype=='myfaves')) {
                return;
            } elseif ($pid ==null) {
                return;
            } else { 
        		$str = "<div id='myfaves-formdiv'><form id='myfaves-addfaveform'><a id='myfaves-addfavebtn' class='myfaves-add-btn'><i class='fas fa-heart'></i> ";
                $str .= $faveCustomText."</a><br>";
                $str .= "<input type='hidden' class='myfaves-favepid' name='myfaves-favepid' value='".$pid."'>";
                $str .= "<input type='hidden' class='myfaves-pgtitle' name='myfaves-pgtitle' value='".$title."'>";
                if ($hidetagsopt=="no") {
                    $str .= "<input type='text' id='myfaves-favetags' name='myfaves-favetags' class='myfaves-favetags' placeholder='Tags seperated by ,' value='' minlength='0' maxlength='100'>";
                }
                $str .= "<div class='myfaves-addfavemsg'></div></form></div>";
                return $str;
            }
        }
    }



function myfaves_get_author_post_tags(){
	$author_id = get_current_user_id();
	$taxonomy = 'myfaves_tags';
    if (is_user_logged_in()) {
        //get author's posts
        $posts = get_posts(array(
            'author' => $author_id,
            'numberposts' => -1,
            'fields' => 'ids',
            'post_type'=>'myfaves',
            'post_status' => 'publish',
            )
        );

        $ts = array();

        //loop over the post and count the tags
        foreach ((array)$posts as $p_id) {
            $tags = wp_get_post_terms( $p_id, $taxonomy);
            foreach ((array)$tags as $tag) {
                if (isset($tag->term_id)){ //if its already set just increment the count
                    $ts[$tag->term_id]['count'] = $tag->count;
                    $ts[$tag->term_id]['name'] = $tag->name;
                    $ts[$tag->term_id]['slug'] = $tag->slug;
                }else{ //set the term name start the count
                    $ts[$tag->term_id] = array('count' => 1, 'name' => $tag->name, 'slug' => $tag->slug);
                }
            }
        }

        //so now $ts holds a list of arrays which each hold the name and the count of posts 
        //that author have in that term/tag, so we just need to display it
        $url = get_author_posts_url($author_id);
        $str= '<div id="myfavestags"><ul>';
        foreach ($ts as $term_id => $term_args) {
            $str.= '<li> <a href="'.add_query_arg('myfaves_tags',$term_args['slug'],$url).'">'.$term_args['name'].'</a></li>';
        }
        $str.= '</ul></div>';
        return $str;
    } else {
       return '<div><i>User need to be logged in.</i></div>';
    }
}


function myfaves_get_myfaves(){
    
    $hidetags = get_option( 'myfaves_hide_tags' );
    $hidetagsopt = $hidetags['myfaves_hide_tags_slct'];
    if ($hidetagsopt==''){ $hidetagsopt='no'; }
    
    $author_id = get_current_user_id();
    $taxonomy = 'myfaves_tags';
    $authurl = get_author_posts_url($author_id);
    $args=array(
        'author' => $author_id,
        'numberposts' => -1,
        'post_status' => 'publish',
        'post_type'=>'myfaves',
        );
    if (is_user_logged_in()) {
    //get author's posts
    $myfaves = get_posts($args);

    $str= '<div id="myfaves"><ul>';
    if ($hidetagsopt=='no') {
        if ( $myfaves ) {
                foreach ( $myfaves as $fave ) { 
                   
                   $tags = wp_get_post_terms( $fave->ID, $taxonomy);
                   $tagstr='';
                    end($tags);
                    $lkey=key($tags);
                   foreach ((array)$tags as $key => $tag) {
                    $tagstr.= ' <a href="'.add_query_arg('myfaves_tags', $tag->slug, $authurl).'">'.$tag->name.'</a> ';
                    if ($key !== $lkey){
                         $tagstr.=' - ';
                     }
                    }
                    $link= get_metadata('post',$fave->ID,'myfaves_link',true);
                    $pic = get_the_post_thumbnail_url($fave->ID);
                    if ($pic != ''){
                        $str .=  '<li><div class="myfaves-class-listinfo"><a href="'.$link.'">'.get_the_title($fave->ID) .' </a>&nbsp; [ '. $tagstr .' ] </div> <div class="myfaves-class-listimgdiv"><img src="'.$pic.'" class="myfaves-class-listimg"></div> </li>';
                    } else {
                        $str .=  '<li><div class="myfaves-class-listinfo"><a href="'.$link.'">'.get_the_title($fave->ID) .' </a>&nbsp; [ '. $tagstr .' ] </div> <div class="myfaves-class-listimgdiv"></div> </li>';  
                    }

            } 
        }
    } else {
        if ( $myfaves ) {
                foreach ( $myfaves as $fave ) { 
                    $link= get_metadata('post',$fave->ID,'myfaves_link',true);
                    $pic = get_the_post_thumbnail_url($fave->ID);
                    if ($pic != ''){
                        $str .=  '<li><div class="myfaves-class-listinfo"><a href="'.$link.'">'.get_the_title($fave->ID) .' </div> <div class="myfaves-class-listimgdiv"><img src="'.$pic.'" class="myfaves-class-listimg"></div> </li>';
                    } else {
                        $str .=  '<li><div class="myfaves-class-listinfo"><a href="'.$link.'">'.get_the_title($fave->ID) .' </div> <div class="myfaves-class-listimgdiv"></div> </li>';  
                    }

            } 
        }
    }
     $str .=  '</ul></div>';
       
     return $str;
    } else {
        return '<div><i>User need to be logged in.</i></div>';
    }
}



function myfaves_register_shortcodes(){
  add_shortcode( 'myfaves-add', 'myfaves_add_fave' );
  add_shortcode( 'myfaves-list', 'myfaves_get_myfaves' );
  add_shortcode( 'myfaves-tags', 'myfaves_get_author_post_tags' );
 

}

add_action( 'init', 'myfaves_register_shortcodes');
