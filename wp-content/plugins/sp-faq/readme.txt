=== WP responsive FAQ with category plugin ===
Contributors: wponlinesupport, anoopranawat 
Tags: faq, faq list, faq plugin, faqs, jquery ui, wp-faq with category, jquery ui accordion,  faq with accordion, custom post type with accordion, frequently asked questions, wordpress, wordpress faq, WordPress Plugin, shortcodes
Requires at least: 3.1
Tested up to: 4.4.1
Author URI: http://wponlinesupport.com
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A quick, easy way to add an responsive FAQs page. You can use this plugin as a jquery ui accordion.

== Description ==
Many CMS site needs a FAQs section. SP faqs plugin  allows you add, manage and display FAQ on your wordpress website. This plugin is created with custom post type.

View [DEMO](http://wponlinesupport.com/sp_plugin/sp-responsive-wp-faq-with-category-plugin/) for additional information.

View [PRO DEMO and Features](http://wponlinesupport.com/sp_plugin/sp-responsive-wp-faq-with-category-plugin/) for additional information.

Now you can also Fillter OR Display FAQ by category.

Here is the example :
<code>
News
[sp_faq category="category_ID" single_open="true" transition_speed="300"]
sports
[sp_faq category="category_ID" single_open="true" transition_speed="300"]
</code>

To use this FAQ plugin just create a new page and add this FAQ short code 
<code>[sp_faq]</code> 
OR
If you want to display FAQ by category then use this short code 
<code>[sp_faq  category="category_ID"]</code>

= Shortcode parameters are =
* **limit** : [sp_faq limit="10"] (ie Limit the number FAQ's items to be display. By default value is limit="-1" ie all)
* **category** : [sp_faq category="category_ID"] (ie Display FAQ's by category. You can find shortcode under **Faq -> FAQ Category**)
* **single_open** : [sp_faq single_open="true"] (ie Display One FAQ item when click to open. By default value is "true". Values are "true" and "false")
* **transition_speed** : [sp_faq transition_speed="300"] (ie transition speed when user click to open FAQ item )

This faqs plugin add a FAQs page in your wordpress website with accordion.

The faq plugin adds a "FAQ" tab to your admin menu, which allows you to enter FAQ Title and FAQ Description items just as you would regular posts.

we have also used faq accordion function so that user can show/hide FAQ content.

= New Features include: =
* wp-faq with category <code>[sp_faq  category="category_ID"]</code> You can find shortcode under **Faq -> FAQ Category**
* Just create a FAQs page and add short code <code>[sp_faq limit="-1"]</code>
* accordion
* Setting page removed and add shortcode parameters ie  single_open and transition_speed
* Add thumb image for FAQ
* Easy to configure FAQ page
* Smooth FAQ Accordion effect
* Smoothly integrates this paq plugin into any theme
* CSS and JS file for FAQ custmization
* Search Engine Friendly URLs
* Added Text Domain and Domain Path

= PRO FAQ Plugin Features include: =

<code>[sp_faq limit="10"  category="category_ID" design="design-2" grid="2"
 category_name="sports"  single_open="false" transition_speed="300" 
 background_color="#000" font_color="#fff" border_color="#444"]</code>

* Added 8 colors design
* Customize any design with shortcode parameters
* Category section with shortcode
* Accordion with better animation
* Custom Colors option as in shortcode parameter
* Display FAQ's in grid view.
* Display category name.

View [PRO DEMO and Features](http://wponlinesupport.com/sp_plugin/sp-responsive-wp-faq-with-category-plugin/) for additional information.

= Pro Shortcode Parameters are =

* **limit** : [sp_faq limit="10"] (ie Limit the number FAQ's items to be display. By default value is limit="-1" ie all)
* **category** : [sp_faq category="category_ID"] (ie Display FAQ's by category)
* **category_name** : [sp_faq category_name="category name"] (ie Display FAQ's category name)
* **design** : [sp_faq design="design-1"] (ie Select design for faq. We have added 8 colors design ie design-1, design-2, design-3 to design-8)
* **grid** : [sp_faq grid="2"] (ie Display FAQ's in grid view.)
* **single_open** : [sp_faq single_open="true"] (ie Display One FAQ item when click to open. By default value is "true". Values are "true" and "false")
* **transition_speed** : [sp_faq transition_speed="300"] (ie transition speed when user click to open FAQ item )
* **background_color** : [sp_faq background_color="#000"] (ie Set background color of FAQ item )
* **font_color** : [sp_faq font_color="#fff"] (ie Set font color of FAQ item )
* **border_color** : [sp_faq border_color="#444"] (ie Set border color of FAQ box)
* **heading_font_size** : [sp_faq heading_font_size="20"] (ie Set font size for FAQ heading)
* **icon_color** : [sp_faq icon_color="white"] (ie Set the icon color. By default value is "black". Options are "black" OR white")
* **icon_type** : [sp_faq icon_type="plus"] (ie Set the icon type. By default value is "arrow". Options are "plus" OR arrtow")
* **icon_position** : [sp_faq icon_position="left"] (ie Set the icon position. By default value is "right". Options are "left" OR right")

**Added New Shortcode to display FAQ's items with categories in the grid view**
<code>[faq-category-grid grid="2" background_color="#f1f1f1" font_color="#000" heading_font_size="20"]</code>
  

SP FAQ allows you to provide a well-designed and informative FAQ section, which can  decrease the amount of user inquiries on various issues.
With the help of given CSS file for this FAQ plugin you can desgin this FAQ plugin as per your layout.

== Installation ==

1. Upload the 'sp-faq' folder to the '/wp-content/plugins/' directory.
2. Activate the sp-faq list plugin through the 'Plugins' menu in WordPress.
3. Add a new page and add this short code <code>[sp_faq limit="-1"]</code>


== Frequently Asked Questions ==

= What templates FAQs are available? =

There is one templates named 'faq.php' which work like same as defult POST TYPE in wordpress.

You can also change the css as per your layout

= Are there shortcodes for FAQs items? =

Yes, Add a new faq page and add this short code <code>[sp_faq limit="-1"]</code>


== Screenshots ==

1. all Faqs
2. Add new Faq
3. preview faq
4. How to add short code
5. Faq with category


== Changelog ==

= 3.2.1 =
* Fixed some bugs
* Added new shortcode for Pro version 

= 3.2 =
* Setting page removed and add shortcode parameters ie  single_open and transition_speed
* Added Text Domain and Domain Path

= 3.1.1 =
* Added Pro Designs and link

= 3.1 =
* Fixed some bugs

= 3.0 =
* Setting page added.
* Fixed some bugs

= 2.2 =
* wp-faq with category

= 2.1 =
* Added jquery UI
* Adds custom post type
* Adds FAQs
* Smooth accordion effect

= 2.0 =
* Adds custom post type
* Adds FAQs
* New css and css file
* Smooth accordion effect

= 1.0 =
* Initial release
* Adds custom post type
* Adds FAQs


== Upgrade Notice ==

= 3.2.1 =
* Fixed some bugs
* Added new shortcode for Pro version

= 3.2 =
* Setting page removed and add shortcode parameters ie  single_open and transition_speed
* Added Text Domain and Domain Path

= 3.1.1 =
* Added Pro Designs and link

= 3.1 =
* Fixed some bugs

= 3.0 =
* Setting page added.
* Fixed some bugs

= 2.2 =
wp-faq with category

= 2.1 =
added new jquery ui

= 2.0 =
added new css and js file

= 1.0 =
Initial release