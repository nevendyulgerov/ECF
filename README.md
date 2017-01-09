
#Enhanced Custom Fields

Enhanced Custom Fields (ECF) is an light-weight, object-oriented, configuration module, designed to work in the context of WordPress.
This tool is designed for WordPress theme developers. With it, you can easily add all the necessary custom fields, required
for the theme you're developing.

Define your custom fields and watch them work perfectly without any extra work on your end!

#ECF Features

With ECF, you can define a total of 13 different metafields:

    - text
    - number
    - email
    - checkbox
    - date
    - file
    - image
    - image gallery
    - textarea
    - dropdown single
    - dropdown multiple
    - WYSIWYG editor
    - google map
 
Any of these fields can be added to:

    - custom post types
    - theme options page with general theme settings

#Configuration

This module should be configured by client programmers from a single xml config file. This means, that you do NOT need to write any code to use Enhanced Custom Fields. All you have to do is configure your module using xml language and leave the rest to the system.


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

As you can see you simply need to require the index.php file of the module. Then you need to initialize ECF using the class EnhancedCustomFields and calling its only public method init(). This method accepts a single parameter - your xml config file.

That's it! Enhanced Custom Fields is loaded and ready to use. Please note, that in the loadECF() function you need to modify two variables, based on your theme configuration - $ecf (the path to ecf/index.php) and $ecfConfig (the path to your ECF config file). It's up to you where you wish to place your config file. The typical approach in most cases will be to place it in your theme directory.

#How to configure Enhanced Custom Fields

As mentioned already, Enhanced Custom Fields can be easily configured from a single xml config file.
Let's first have a look at a full-featured config file:

```xml
<?xml version="1.0"?>
<config>
    <themeOptions>

        <settings>
            <name>Theme Options</name>
            <menuIcon>dashicons-list-view</menuIcon>
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
                        <width>3/6</width>
                        <title>Activity</title>
                        <subtitle>Activity information about posts.</subtitle>
                        <metafields>

                            <metafield>
                                <type>gallery</type>
                                <name>gallery</name>
                                <label>Gallery Field</label>
                                <description>This is a gallery field.</description>
                            </metafield>

                            <metafield>
                                <type>editor</type>
                                <name>editor</name>
                                <label>Editor Field</label>
                                <description>This is an editor field.</description>
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
                    <name>number_field</name>
                    <label>Number</label>
                    <description>This is a number field.</description>
                    <required>true</required>
                    <size>small</size>
                </metafield>

            </metafields>

        </postType>

    </postTypes>

</config>
```

With the above configuration, the module will create an options page with 2 sub-pages - 'Dashboard' and 'Settings'. Dashboard page will display a single widget - activity. Settings page will display two metafields - gallery and editor.

This configuration will also create and attach a metafield 'number' to post type 'page'.

Now, let's take a closer look at each node in the config file.

First thing you'll notice, when you open config.xml is that the whole configuration is wrapped in a 'config' node. This is a must
for the config file to work.

The 'config' node contains several child nodes:

    - themeOptions
    - postTypes
    
The node 'themeOptions' contains the declarations for an options page - a separate page in the admin panel, which can be used for general theme settings. You can define the page's title and icon in the 'settings' node. The 'pages' node contains the declarations for each sub-page of the options page. A 'page' node contains your entire page definition. Let's look at a 'page' node:

```xml
<page>
    <name>            - the page's name
    <masonry>         - masonry support for the page's sections (can have values true/false or you can remove it, which equals to false)
    <sections>        - this node contains <section> nodes. Each section represents an html section, which contains metafields and/or widgets.
</page>
```

Here's an example 'sections' node with a single section inside:

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

Let's take a look at the 'metafields' node:

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
