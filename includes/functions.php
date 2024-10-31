<?php


  
function myfaves_add($url, $pgtitle, $pid){
   
	$uid = get_current_user_id();
     $pic=get_post_thumbnail_id($pid);
 	$postdata = array('post_title' => $pgtitle,
 						'post_type'=> 'myfaves',
 						'post_status'=>'publish',
 						'author' => $uid,
 						'post_content' => $url,
 						'meta_input' => array(
 							'myfaves_link'=>$url,	
 							'myfaves_postid'=>$pid,	
                            '_thumbnail_id'	=> $pic,
 						 ) );
 	$x=wp_insert_post($postdata);

 	return $x; 
    
}

function myfaves_remove($pid){
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

        $faved = get_posts($args) ;
        foreach ( $faved as $item ) {
       $x = wp_delete_post($item->ID,true);
   }
       return $x;
}

function myfaves_add_favetags($pid,$tags){
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

        $faved = get_posts($args) ;
        foreach ( $faved as $item ) {
 			$x=wp_set_post_terms($item->ID, $tags,'myfaves_tags',false);
        }
        return $x;	
}

function myfaveswp_hook_css() {
      $myfavesSettings = get_option( 'myfaves_button_settings' );
      $myfavesBtn = $myfavesSettings['myfaves_button_color_txt']??'#2ACCE5';
      $myfavesBtnHvr = $myfavesSettings['myfaves_button_color_hover_txt']??'#333333';
      $myfavesBtnText = $myfavesSettings['myfaves_button_text_color_txt']??'#ffffff';
      $myfavesBtnRadius = $myfavesSettings['myfaves_button_border_radius_txt']??'5';
      if ($myfavesBtn=='') { $myfavesBtn = '#2ACCE5';} 
      if ($myfavesBtnHvr=='') {$myfavesBtnHvr = '#333333';}
      if ($myfavesBtnText=='') {$myfavesBtnText = '#ffffff';}
      ?>
        <style> 

        a.myfaves-add-btn , a.myfaves-remove-btn  {
          border: 0;
          color: <?php echo $myfavesBtnText; ?> !important;
          background-color: <?php echo $myfavesBtn; ?> !important;
          border-radius: <?php echo $myfavesBtnRadius; ?>px !important;
          padding: 10px;
          cursor: pointer;
        }
        /*a.myfaves-add-btn {
          border: 0;
          color: <?php echo $myfavesBtnText; ?> !important;
          background-color: <?php echo $myfavesBtn; ?> !important;
          border-radius: <?php echo $myfavesBtnRadius; ?>px !important;
          padding: 10px;
          cursor: pointer;
        }*/
         a.myfaves-add-btn:hover , a.myfaves-remove-btn:hover {
          background-color: <?php echo $myfavesBtnHvr; ?> !important;
        }

      </style>
        <?php
    }

    add_action('wp_head', 'myfaveswp_hook_css');
