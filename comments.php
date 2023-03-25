<div class="post-comments" id="formreply">
    <div class="header-area">
        <h3 class="block-title">
            <span class="td-pulldown-size">
                <?php
                printf( _nx( '1', '%1$s', get_comments_number(), '', 'laundry' ),
                    number_format_i18n( get_comments_number() ));
                ?>
                Comments
            </span>
        </h3>
    </div>
    <div class="wright-comment-area">
        <div class="left-area">
            <div class="left">
                <div class="img">
                    <?php
                        $user = wp_get_current_user();
                        $avatar = esc_url( get_avatar_url( $user->ID ) );
                        if(!$avatar):
                            $avatar = get_template_directory_uri().'/common/images/blog/prople.png';
                        endif;
                    ?>
                    <img src="<?php echo $avatar;?>" alt="">
                </div>
            </div>
        </div>
        <div class="right-area">
            <?php if ( comments_open() ) : ?>
                <?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
                    <p><a href="<?php echo wp_login_url( get_permalink() ); ?>"><?php _e('Login','laundry')?></a> to comment.</p>
                <?php else : ?>
                <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <input name="comment" id="comment" placeholder="<?php _e('Add a comment','laundry')?>">
                            </div>
                        </div>
                    </div>
                    <?php if(!is_user_logged_in()):?>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <input placeholder="<?php _e('Name','laundry')?>" type="text" name="author" id="author" value="<?php echo esc_attr($comment_author);?>" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <input placeholder="<?php _e('Email','laundry')?>" type="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
                                </div>
                            </div>
                        </div>
                    <?php endif;?>
                    <p><input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('comment','laundry')?>" />
                    <?php comment_id_fields(); ?>
                    </p>
                    <?php do_action('comment_form', $post->ID); ?>	
                </form>
                <?php endif; // If registration required and not logged in ?>	       
            <?php endif; // if you delete this the sky will fall on your head ?>
            <?php 
            cancel_comment_reply_link(); 
            ?>
        </div>
    </div>
    <div class="view-comment-area">
        <ul>
            <?php wp_list_comments('type=comment&callback=fabo_comment'); ?>        
        </ul>
    </div>
    <div class="pagination justify-content-center mt-5">
        <?php 
        paginate_comments_links(array(
            'screen_reader_text'=> __('Pagination','text_domain'),
            'prev_text'=> __('&laquo;','text_domain'),
            'next_text'=> __('&raquo;','text_domain'),
        ));
        ?>
    </div>
</div>