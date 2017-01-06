
#Enhanced Custom Fields - How to Use

Enhanced Custom Fields (ECF) is an light-weight, object-oriented, configuration MVC module, designed to work in the context of WordPress.
This tool is designed for WordPress theme developers. With it, you can easily add all the necessary custom fields, required
for the theme you're developing.

Define your custom fields and watch them work perfectly without any extra work on your end!

This module should be controlled by client programmers from a single file - config.xml. This means, that you do NOT need to write any code to use Enhanced Custom Fields. All you have to do is configure your module using xml and leave the rest to the system.

#How to include Enhanced Custom Fields

In order to use ECF, you need to include its index.php file (the module's entry-point) in your functions.php file.
So, all you have to do is:

require_once('path-to-efc/index.php');

That's it! Enhanced Custom Fields is loaded and ready to use. Once you update your functions.php file, reload your back-end (wp-admin).
You'll find ECF near the bottom of the admin menu to the left.

#How to configure Enhanced Custom Fields

As mentioned already, Enhanced Custom Fields can be easily configured from a single file - config.xml, found in the root folder of the module.
Let's example config.xml and show you how to configure it according to your needs.

First thing you'll notice, when you open config.xml is that the whole configuration is wrapped in a 'config' node. This is a must
for the config file to work.

The 'config' node contains two required nodes - 'module' and 'pages'. Let's look at 'module' first.

The 'module' node contains necessary information for Enhanced Custom Fields to work. This includes:

```xml
<module>
    <name>            - the module's name (example: Enhanced Custom Fields)
    <version>         - the module's version (example: 1.0.0)
    <author>          - the module's author (example: 'KenobiSoft')
    <dir>             - the module's root folder name (example: 'efc')
    <mode>            - the module's mode (can be 'development' or 'production')
    <collection>      - the module's database settings
        <optionGroup> - the group name of the module's collection setting
        <optionName>  - the option name of the module's collection setting
    </collection>
    <params>          - the module's GET params
        <page>        - the module's page param (used as an identifier for the module's pages)
        <update>      - the module's update param (used as an identifier when an update event is triggered)
    </params>
    <defaultView>     - the module's default view (this view will be loaded as a fallback, when a view cannot be found and loaded)
    <preventSave>     - prevent save for given page (you need to provide the name of the page, where you want to disable the save functionality)
    <menuIcon>        - the module's menu icon
</module>
```

Note that currently ECF supports writing only in the options table (native for WordPress). Due to this, the 'collection' node, in the example above, contains two sub-nodes - 'optionGroup' and 'optionName'. Developers familiar with the add_option WP API will probably recognize these. Behind the curtains, ECF interacts with this API to perform the CRUD operations for managing your custom fields.

The 'pages' node contains 'page' nodes. A 'page' node contains your entire page definition. Let's look at a 'page' node:

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

Finally, let's look at a full configuration:

```xml
<?xml version="1.0"?>
<config>
	<module>
		<name>Enhanced Custom Fields</name>
		<version>2.0.0</version>
		<author>KenobiSoft</author>
		<dir>theme_options</dir>
        <mode>production</mode>
        <collection>
            <optionGroup>sa_theme_options</optionGroup>
            <optionName>sa_options</optionName>
        </collection>
		<params>
			<page>initialzr-page</page>
			<update>update</update>
		</params>
        <defaultView>dashboard</defaultView>
        <menuIcon>dashicons-list-view</menuIcon>
	</module>
    <pages>
        <page>
            <name>Dashboard</name>
            <masonry>true</masonry>
            <sections>
                <section>
                    <width>3/6</width>
                    <title>Activity</title>
                    <subtitle>Activity information about posts.</subtitle>
                    <metafields>
                        <metafield>
                            <type>dropdown_multiple</type>
                            <name>dropdownm1</name>
                            <label>Taxonomy Dropdown</label>
                            <dataType>taxonomy</dataType>
                            <data>category</data>
                        </metafield>
                    </metafields>
                </section>
            </sections>
        </page>
    </pages>
</config>
```

With the above configuration, the module will create a single page, called 'Dashboard' and fills it with just one section, with width of 50%, which contains a single metafield of type 'dropdown_multiple'. The dropdown will pull out all taxonomy items from taxonomy type 'category'.