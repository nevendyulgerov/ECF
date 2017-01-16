
#Enhanced Custom Fields

Enhanced Custom Fields (ECF) is an light-weight, object-oriented, configuration module, designed to work in the context of WordPress.
This tool is designed for WordPress theme developers. With it, you can easily manage all the necessary custom fields, required
for the theme you're developing.

Define your custom fields and watch them work perfectly without any extra work on your end!

#ECF Features

With ECF, you can define a total of 13 different metafields:

    - text field        (text)
    - number field      (number)
    - email field       (email)
    - checkbox          (checkbox)
    - date field        (date)
    - file field        (file)
    - image field       (image)
    - image gallery     (gallery)
    - textarea          (textarea)
    - dropdown single   (dropdown_single)
    - dropdown multiple (dropdown_multiple)
    - WYSIWYG editor    (editor)
    - google map        (map)
 
Any of these fields can be added to:

    - theme options page with general theme settings
    - custom post types
    - taxonomies

#Configuration

This module should be configured by client programmers from a single xml config file. This means, that you do NOT need to write any code to configure Enhanced Custom Fields. All you have to do is configure your module using xml language and leave the rest to the system.


#How to include Enhanced Custom Fields

In order to use ECF in your theme, you need to:

    - clone or download ECF and place it in your theme directory
    - add the following code to your functions.php file:

```php
/**
 * Load module ECF
 */
function loadECF() {

    // set base paths
    $base      = get_stylesheet_directory();
    
    // set path to ECF's index.php file
    $ecf       = $base . '/path-to-ecf/index.php';
    
    // set path to ECF config file
    $ecfConfig = $base . '/ecf-config.xml';

    // get module ECF
    require_once($ecf);

    // initialize ECF
    \ECF\EnhancedCustomFields::init($ecfConfig);
}

// hook loadECF to action 'after_setup_theme'
add_action('after_setup_theme', 'loadECF');
```

As you can see you simply need to require the index.php file of the module. Then you need to initialize ECF using the class EnhancedCustomFields and calling its only public static method init(). This method accepts a single parameter - your xml config file.

That's it! Enhanced Custom Fields is loaded and ready to use. Please note, that in the loadECF() function you need to modify two variables, based on your theme configuration - $ecf (the path to ecf/index.php) and $ecfConfig (the path to your ECF config file). It's up to you where you wish to place your config file. The typical approach in most cases will be to place it in your theme's root directory.

#How to configure Enhanced Custom Fields

As mentioned already, Enhanced Custom Fields can be easily configured from a single xml config file.
Let's first have a look at a full-featured config file:

```xml
<?xml version="1.0"?>
<config>
    <moduleSettings>
        <googleMapsApiKey>REPLACE_WITH_API_KEY</googleMapsApiKey>
    </moduleSettings>

    <themeOptions>
        <settings>
            <name>Theme Options</name>
            <icon>dashicons-list-view</icon>
        </settings>
        <pages>
            <page>
                <name>Dashboard</name>
                <masonry>true</masonry>
                <showSave>false</showSave>
                <sections>
                    <section>
                        <title>Activity</title>
                        <subtitle>Activity information about posts.</subtitle>
                        <widgets>
                            <widget>activity</widget>
                        </widgets>
                    </section>
                </sections>
            </page>
            <page>
                <name>Settings</name>
                <masonry>true</masonry>
                <sections>
                    <section>
                        <width>1</width>
                        <title>Activity</title>
                        <subtitle>Activity information about posts.</subtitle>
                        <metafields>
                            <metafield>
                                <type>text</type>
                                <name>text</name>
                                <label>Text Field</label>
                                <description>This is a text field.</description>
                            </metafield>
                        </metafields>
                        <metafields>
                            <metafield>
                                <type>gallery</type>
                                <name>gallery</name>
                                <label>Image Gallery</label>
                                <description>This is a gallery field.</description>
                            </metafield>
                        </metafields>
                    </section>
                </sections>
            </page>
        </pages>
    </themeOptions>

    <postTypes>
        <postType>
            <name>page</name>
            <groupName>General</groupName>
            <metafields>
                <metafield>
                    <type>number</type>
                    <name>number</name>
                    <label>Number Field</label>
                    <description>This is a number field.</description>
                </metafield>
            </metafields>
        </postType>
    </postTypes>

    <taxonomies>
        <taxonomy>
            <name>providers</name>
            <metafields>
                <metafield>
                    <type>text</type>
                    <name>text</name>
                    <label>text</label>
                    <description>This is a text field.</description>
                </metafield>
            </metafields>
        </taxonomy>
    </taxonomies>
</config>
```

With the above configuration, the module will create an options page with 2 sub-pages - 'Dashboard' and 'Settings'. Dashboard page will display a single widget - activity. Settings page will display two metafields - text and gallery.

This configuration will also create and attach a metafield 'number' to post type 'page'. Note that you should also define a 'groupName' node. This will be used as a name for the custom metabox in which your custom metafields will appear in the backend.

Now, let's take a closer look at each node in the config file.

First thing you'll notice, when you open config.xml is that the whole configuration is wrapped in a 'config' node. This is a must
for the config file to work.

The 'config' node contains several child nodes:

    - moduleSettings
    - themeOptions
    - postTypes
    - taxonomies
    
The node 'moduleSettings' currently contains only one node - 'googleMapsApiKey'. You can fill this node with your google maps API key. If you do not provide an API key, the module will use the standard js library for google maps, without an API key.
    
The node 'themeOptions' contains the declarations for the options page - a separate page in the admin panel, which can be used for general theme settings. You can define the page's title and icon in the 'settings' node. The 'pages' node contains the declarations for each sub-page of the options page. A 'page' node contains your entire page definition. Let's look at a 'page' node:

```xml
<page>
    <name>            - the page's name
    <masonry>         - masonry support for the page's sections (can have values true/false or you can remove it, which equals to false)
    <sections>        - this node contains <section> nodes. Each section represents an html section, which contains metafields and/or widgets.
    <showSave>        - whether or not to show the save button on the page. Default is true, so you must explicitly declare <showSave>false</showSave> to disable the save button.
</page>
```

The sections node contains section nodes. Here's an example 'sections' node with a single section inside:

```xml
<sections>
    <section>
        <width>       - the section's width (can be 1, 1/6, 2/6, 3/6, 4/6, 5/6)
        <title>       - the section's title
        <subtitle>    - the section's subtitle
        <metafields>  - the <metafields> node contains <metafield> nodes
        <widgets>     - the <widgets> node contains <widget> nodes
    </section>
</sections>
```

The metafields node contains metafield nodes. Let's take a look at the 'metafields' node:

```xml
<metafields>
    <metafield>       - this node contains the definition of a metafield
        <type>        - the metafield's type (can be text, email, hidden, number, checkbox, date, file, image, textarea, dropdown_single, dropdown_multiple, editor, map, gallery, plain_text)
        <name>        - the metafield's name (this is the database identifier for the field; you will require this name to pull out the field's data on the frontend)
        <label>       - the metafield's label
        <description> - the metafield's description (appears on hover on the label)
        <size>        - the metafield's width (can be small (33%)/normal (50%)/large (75%)/auto (100%))
        <selector>    - the metafield's custom css selector (css class)
        <required>    - the metafield's required attribute; enables validation for the metafield (can have values true/false or you can remove it, which equals to false)
    </metafield>
</metafields>
```

As seen previously, the options page contains two pages, first of which is the Dashboard. In the dashboard, we define a widget. Widgets are additional functionalities which can be used to enhance the options page. Currently, ECF supports 3 widgets:

    - activity   (provides information about user activity)
    - statistics (provides information about post activity)
    - plugins    (provides information about installed plugins)
    
Let's look at the 'widgets' node:

```xml
<widgets>
    <widget>          - this node contains the definition of a widget
        <name>        - the widget's name (currently supported widgets are activity, statistics and plugins)
    </widget>
</widgets>
```

Some metafields have access to custom attributes:
	
Number input (type - number):

	- min (default 0)
	- max (default 200)
	- step (default 1)

Date input (type - date):

	- format (default: dd/mm/yy)

Textarea (type - textarea):

	- height (small (100px)/normal (200px)/large (300px))
	- rows
	- cols

Dropdown single (type - dropdown_single) and dropdown multiple (type - dropdown_multiple):

    - dataType (define the data source type - can be post, taxonomy or custom)
    - data (define the data source - can be any custom post type, any taxonomy or any custom array defined in the following format:
    <data>1, 2, 3, 4, 5</data> - the data node

Plain text:

	- block (wrapper for 'text' elements)
	- text (wrapper for 'p', 'ribbon', 'linkText' and 'link' elements)
	- p (converted to paragraph)
	- ribbon (info block)
	- linkText (self-descriptive)
	- link (self-descriptive)

#How to configure Enhanced Custom Fields for Custom Post Types

You can easily configure ECF to work for custom post types, by using the 'postTypes' node:

```xml
<postTypes>
    <postType>
        <name>page</name>
        <groupName>General</groupName>
        <metafields>
            <metafield>
                <type>number</type>
                <name>number</name>
                <label>Number Field</label>
                <description>This is a number field.</description>
            </metafield>
        </metafields>
    </postType>
</postTypes>
```

In the example above, we define a custom metafield of type 'number' for custom post type 'page'.

#How to configure Enhanced Custom Fields for Taxonomies

You can also configure ECF to work for taxonomies, by using the 'taxonomies' node:

```xml
<taxonomies>
    <taxonomy>
        <name>providers</name>
        <metafields>
            <metafield>
                <type>text</type>
                <name>text</name>
                <label>text</label>
                <description>This is a text field.</description>
            </metafield>
        </metafields>
    </taxonomy>
</taxonomies>
```

In the example above, we define a custom metafield of type 'number' for taxonomy 'providers'.

# How to control ECF on the front-end

ECF comes with a ready-to-use public class, called ECF, which exposes public static methods for

    - getting ECF data
    - checking if ECF data exists
 
Here's an en example of getting an ECF field, attached to a custom post type.

```php
// use class ECF from namespace ECF
use ECF\ECF;
global $post;
$fieldVal = null;

// check if field with name 'myField' for custom post type exists
if ( ECF::has('myField', 'cpt', $post->ID) ) {

    // retrieve value for field with name 'myField'
    $fieldVal = ECF::get('myField', 'cpt', $post->ID);
}

``` 

Here's an en example of getting an ECF field, attached to a taxonomy.

```php
// use class ECF from namespace ECF
use ECF\ECF;
$provider = get_term($someTermID, 'providers');
$fieldVal = null;

// check if field with name 'myField' for taxonomy with id exists
if ( ECF::has('myField', 'tax', $provider->term_id) ) {

    // retrieve value for field with name 'myField'
    $fieldVal = ECF::get('myField', 'tax', $provider->term_id);
}

```

Getting a field value from the options page is even easier. Here's an example:

```php
// use class ECF from namespace ECF
use ECF\ECF;
$fieldVal = null;

// check if field with name 'myField' for options page exists
if ( ECF::has('myField', 'opt') ) {

    // retrieve value for field with name 'myField'
    $fieldVal = ECF::get('myField', 'opt');
}

```

Class ECF currently has 2 public methods:

```php

/**
 * ECF::has method
 * @param string $fieldName
 * @param string $type
 * @param int    $id (optional)
 * @return boolean
 */ 

/**
 * ECF::get method
 * @param string $fieldName
 * @param string $type
 * @param int    $id (optional)
 * @return mixed
 */
 
```

All returned values from the get method of ECF class are sanitized with the stripslashes function.