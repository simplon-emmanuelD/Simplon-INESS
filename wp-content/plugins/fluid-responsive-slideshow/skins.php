<?php
/* 
 * Penanganan skin khusus 
 */
$skin_name = $options[$current]['skin'];
$addon_style .= "";

if($skin_name == 'frs-skin-elegant')
{
    /* number in bullets */
    $query = new WP_Query($condition);
    $i = 1;

    while($query->have_posts())
    {
        $query->the_post();

        $nth_child = "";

        for ($j = 2; $j <= $i; $j++) 
        { 
            $nth_child = $nth_child." + li";
        }

        $addon_script .= "$('#$attr[slide_type_id]-slideshow .frs-bullets-wrapper li.frs-slideshow-nav-bullets').eq(". ($i - 1) .").html(\"$i\");";

        $i++;
    }
}