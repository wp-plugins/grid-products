=== Grid Products ===
Contributors: jonathanhadams, ModDish
Donate link: http://printingpeach.ca/
Tags: grid products, products grid, products in table, products display
Requires at least: 3.0.1
Tested up to: 3.5.1
Stable tag: 1.2
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Grid Products allows you to display products (or other data) in a grid format or list format within your WordPress website.

== Description ==

Grid Products allows you to display products (or other data) in a grid format or list format within your WordPress website.

You can create product categories and individual products with descriptions and product images in one central location to be output on multiple pages in various formats (grid, list, featured products etc). You can also manage unique excerpts/descriptions for each product.

This plug-in is also compatible with Cart66 should you wish to add the ability to purchase products. Also compatible with other plug-ins such as NextGEN Gallery.

We created this plugin to display items on a clients website and have even used it ourself to display printing products such as flyers, brochures, postcards, business cards etc. We decided to share it with the world :-)


== Installation ==

Extract the zip file and upload the contents to the wp-content/plugins/ directory of your WordPress installation and then activate the plugin from plugins page. 

The plugin registers a new custom post type, so you'll want to update your permalinks. No need to change your permalink structure, just go to "Settings->Permalinks" and click "Save Changes" without making any modifications.


Possible Shortcode [product]

Shortcode options :

-------------------------------------------------------------

cat

Used to display only produces in a certain category. If not set ALL products from any category will be shown.

Usage : cat="category-slug"

-------------------------------------------------------------

id

You can insert a single Product 

Usage : id="1234" - where 1234 is the post ID.

* Note: the cat & the id attributes are mutually exclusive. Don't use both in the same shortcode.

-------------------------------------------------------------

hidetitle

Used in conjunction with the "cat" shortcode to hide the category title incase you would like to use something else instead of the category name.

Usage : hidetitle="yes"

-------------------------------------------------------------

featured

Will set the background of the container to a default light grey.

Usage : featured="yes"

-------------------------------------------------------------

view

The default view is a grid view, if you would prefer to use "list" view set this attribute to equal list 

Usage : view="list"

-------------------------------------------------------------

buttontext

The default button text is "Read More" if you would like to change the text use this attribute 

Usage : buttontext="your text here"

-------------------------------------------------------------

des

Used to disable the product excerpt in the default grid view. 

Usage : des="no"

-------------------------------------------------------------

maxdes

Used to set the number of words used in the excerpt in the default grid view. (default - 20) must be a number.

Usage : maxdes="50"

== Screenshots ==

1. Front end example

2. Front end example

3. Front end example

4. Front end example

5. Front end example

6. Front end example

7. Back end example

8. Back end example

9. Back end example



