<div class="code-detail-text">

The WordPress Projects Manager is a plugin I built to help manage projects in WordPress. The plugin is geared toward web development or other programming projects but can technically be used for other projects. Upon installing the plugin, you will have the ability to add projects as a custom post type which means you will also have the ability to categorize projects by “type”. These are essentially a custom taxonomy for use specifically with this plugin. Templates come built into the plugin but custom templates may also be used by adding a projects-manager/tpl directory structure to your theme, which also allows you to have different templates per theme, if theme is switched.

### Documentation:

1.  [How To Install](#how-to-install)
2.  [How To Use](#how-to-use)
3.  [Details](#details)
4.  [Logo](#logo)
5.  [Thumbnails and Images](#thumbnails-and-images)
6.  [Plugin API and Methods](#plugin-api-and-methods)
7.  [Methods List Coming Soon!](#methods-list-coming-soon!)

<div id="code-detail-docs-content">

### How To Install

1.  Extract the zip and upload to your wp-content/plugins directory
2.  In your WP Admin Dashboard, go to Plugins and click Activate

### How To Use

Use is exactly like any standard page or post except for the following added functionality:

#### Details

On the right hand side of the Add Project or Edit Project pages, you will notice a new Details and Thumbnail metabox. These are detailed below:

*   <span>Featured Project</span> – This flag determines if the project should be featured. Featured projects can then be pulled using the plugin’s API accessible through the $projects_manager global variable using the get_featured_projects() function.
*   <span>Client</span> – Projects can also be categorized by clients. A client can be managed using the clients link under the Projects category in your WP Admin menu. There is also an API method that allows you to pull projects by client. This method is $projects_manager->get_projects_by_client_id().
*   <span>Project Description and URL</span> – The description is used as a small description, much like an excerpt. The project URL is used on the project detail page which will automatically open an external link to this address.
*   <span>Sort Order</span> – Sort Order is used when selecting projects. This can be used in conjunction with the featured project method call (or any method that returns projects) to determine which projects should show first. If this is not set, the default sort order will be by post date in descending order.
*   <span>Services and Tools</span> – The services and tools are a list of items used when this project was built. For instance, if a web project has a design made using Photoshop, this would go under Tools. Examples of Services could be services provided or services used such as APIs, etc.

#### Logo

The logo is simply used by the project detail template to show which project this is while preserving screenshots for later use.

#### Thumbnails and Images

Thumbnails and images are used by the frontend templates. They are located in the $post->meta[‘projects-manager-thumbnails’] and $post->meta[‘projects-manager-images’] arrays. Clicking one of these images or thumbnails will designate said image or thumbnail as featured.

### Plugin API and Methods

All plugin methods are available through the global variable $projects_manager (found in plugin’s init.php file). Methods are detailed below:

### Methods List Coming Soon!

</div>

</div>
