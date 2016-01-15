<tr class='list_item' id="list_item_<?php echo $post->ID ?>" style="background-color:#F9F9F9;">  
    <td class='frs_td_container'>
        <div class='frs_slide_thumbnail'>
        <?php

            $thumb_img = get_the_post_thumbnail(get_the_ID(),'thumbnail');     

            if(! empty($thumb_img))
            {
                echo "<a href='" . get_edit_post_link(get_the_ID()) . "'>" . $thumb_img . "</a>";
            }
            else
            {
                $postmeta = get_post_meta(get_the_ID(), 'tonjoo_frs_meta',true);

                if (!isset($postmeta["slider_bg"] )) $postmeta["slider_bg"] = "#000000";

                echo "<div style='height:100px;width:100px;background-color:{$postmeta["slider_bg"]};'></div>";
            }
        ?>

        </div>
        <div class='frs_slide_info'>
        <h3><?php the_title() ?></h3>
        </div>
        <div class='frs_slide_edit'>           
            <span class="spinner frs-button-spinner" ></span>
            <a class='button button-frs button-primary button-large' frs-edit-slide data-post-id='<?php echo the_ID() ?>'>Edit Slide</a>
            &nbsp;
            <a class='button button-frs  button-danger button-large' data-post-id='<?php echo the_ID() ?>'frs-delete-slide>Delete Slide</a>
            &nbsp;
        </div>
    </td>
</tr>