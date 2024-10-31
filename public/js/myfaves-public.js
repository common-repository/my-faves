jQuery(document).ready(function($) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
   // var home = myfavesGlobalObj.homeUrl;


    $('a.myfaves-add-btn').click(function(e) {
        
        
            var pgtitle= $('input.myfaves-pgtitle').val().trim();
            var pid = $('input.myfaves-favepid').val().trim();
            // var ftags = $('input#myfaves-favetags').val().trim();
            var x;

            var info = {
                 _ajax_nonce: myfaves_ajax_obj.nonce,
                action: 'myfavesadd',
                myfavesurl: window.location.href,
                myfavestitle : pgtitle,
                myfavespid: pid,
            };


            if (($('input.myfaves-favetags').is(':hidden')) || (!$('input.myfaves-favetags').length)){ 
                
                $.post(myfaves_ajax_obj.ajax_url , info ,function(data){
                    $('div.myfaves-addfavemsg').html(data);
                    })
                    .done(function(data) {
                        $('div.myfaves-addfavemsg').html(data);
                         if ($('input.myfaves-favetags').length ) {
                            $('input.myfaves-favetags').slideDown('slow');
                        } else {
                             x = window.setTimeout(function(){
                                 location.reload();
                             },1000);
                        }
                    }).fail(function(err) {
                        $("div.myfaves-addfavemsg").html("Unexpected error occurred. Try again. ");
                        console.log(err.status+' '+err.message);
                    });
                    
            } else {
                 if ($('input.myfaves-favetags').length ) {
                     $('input.myfaves-favetags').slideUp('slow');
                 }
                    x = window.setTimeout(function(){
                     location.reload();
                 },1000);
            }

        
    });


    $('input.myfaves-favetags').keypress(function(e){
        if ( e.which == 13 ) {
             e.preventDefault();
            var pid = $('input.myfaves-favepid').val().trim();
            var ftags = $(this).val().trim();
            var info = {
                     _ajax_nonce: myfaves_ajax_obj.nonce,
                    action: 'myfavesaddtags',
                    myfavespid: pid,
                    myfavestags: ftags,
                };

             if (ftags.length>0) { 

                 $.post(myfaves_ajax_obj.ajax_url , info ,function(data){
                        $('div.myfaves-addfavemsg').html(data);
                        })
                        .done(function(data) {
                            $('div.myfaves-addfavemsg').html(data);
                        }).fail(function(err) {
                            $("div.myfaves-addfavemsg").html("Unexpected error occurred. Try again. ");
                            console.log(err.status+' '+err.message);
                        });
                }

                $('input.myfaves-favetags').slideUp('slow');
                 var x = window.setTimeout(function(){
                     location.reload();
                 },1000);
        
        } else { 
              return;
        }

       
    });

    $('a.myfaves-remove-btn').click(function(e) {
        
        var pid = $('input.myfaves-favepid').val().trim();

        var info = {
             _ajax_nonce: myfaves_ajax_obj.nonce,
            action: 'myfavesremove',
            myfavespid: pid,
        };

         $.post(myfaves_ajax_obj.ajax_url , info ,function(data){
            $('div.myfaves-unfavemsg').html(data);
            })
            .done(function(data) {
                $('div.myfaves-unfavemsg').html(data);
            }).fail(function(err) {
                $("div.myfaves-unfavemsg").html("Unexpected error occurred. Try again. ");
                console.log(err.status+' '+err.message);
            });
            var x = window.setTimeout(function(){
                 location.reload();
             },1000);

    });

         
});