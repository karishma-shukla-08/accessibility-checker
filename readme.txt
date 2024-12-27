=== Accessibility ===
Contributors:      Karishma Shukla
Tags:              block
Tested up to:      6.6
Stable tag:        1.0.0
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html


== Changelog ==

= 1.0.0 =
* Release

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
