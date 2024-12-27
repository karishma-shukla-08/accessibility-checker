=== Accessibility ===
Contributors:      Karishma Shukla
Tags:              block
Tested up to:      6.6
Stable tag:        1.0.0
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Example block scaffolded with Create Block tool.

== Description ==

This is the long description. No limit, and you can use Markdown (as well as in the following sections).

For backwards compatibility, if this section is missing, the full length of the short description will be used, and
Markdown parsed.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload the plugin files to the `/wp-content/plugins/accessibility-checker` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress


== Frequently Asked Questions ==

= A question that someone might have =

An answer to that question.

= What about foo bar? =

Answer to foo bar dilemma.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0.0 =
* Release

== Arbitrary section ==

You may provide arbitrary sections, in the same format as the ones above. This may be of use for extremely complicated
plugins where more information needs to be conveyed that doesn't fit into the categories of "description" or
"installation." Arbitrary sections will be shown below the built-in sections outlined above.
Accessibility Checker Plugin
Description
The Accessibility Checker Plugin is a tool to help WordPress site admins ensure their content meets accessibility standards. It uses the Axe-core library to run accessibility tests on posts and pages to highlight any issues that may affect users with disabilities.

Features
Bulk scan posts and pages for accessibility issues.
Displays accessibility violations with descriptions.
Built using React and integrated into the WordPress block editor.
Uses the Axe-core library to analyze accessibility.
Installation
Prerequisites
WordPress 5.6 or later.
PHP 7.0 or later.
React and Axe-core dependencies included in the build.
Steps to Install
Download the Plugin: Download the plugin folder and extract it to your WordPress plugin directory (wp-content/plugins).

Activate the Plugin: Go to your WordPress admin dashboard and navigate to Plugins > Installed Plugins. Find the Accessibility Checker Plugin and click Activate.

Enqueue the Assets: The plugin automatically enqueues the necessary JavaScript and CSS files for the Gutenberg editor. This ensures that the plugin is ready to run in the block editor.

Usage
Access the Plugin: After activating the plugin, go to the WordPress editor for posts or pages. You will see a new "Accessibility Checker" section in the sidebar.

Run Bulk Accessibility Scan:

Once you open the editor, you can select multiple posts/pages from the list of available content.
Click the Run Scan button to analyze the selected content for accessibility issues.
The plugin will run an accessibility audit using Axe-core and display the results below the content.
Review Results:

The scan results will show the number of accessibility violations for each post.
Each issue will have a description explaining the problem and a suggestion for how to resolve it.
