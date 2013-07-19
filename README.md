yapf
====

No libraries, no templates and nothing that you don't need. Yes, this is Yet
Another [very simple] PHP Framework. It's entierly contained within one PHP file
in about 100 lines, including comments. All functionality is then supplied by
plugin files that introduce new functionality.

Currently I see this as a prototyping framework designed to get something in place
with minimal effort and without reinventing the wheel.

How to use
==========

1. Drop index.php in the document root of your server.
2. Create the plugins subdirectory under your root.
3. Write plugins that do wonderful things.
 
Available Plugins
=================

Note that there is no requirement to download or use the plugins available in the
repository. They are not required at all and you are free to never use them. They
are utilities that I feel will be most useful to me in my own workflow. I believe
that leaving them with the repository will help you get a feel for the development
style I have in mind and that they also serve as a source of extended documentation.

The plugins available with this framework are:

1. Actions: An API for managing callbacks.
2. Output: An API for managing a renderable array
3. Preprocess Output: Adds a hook to alter the entire page's renderable array before output
4. Render: An API for rendering a renderable array.
5. Content: Generates a renderable array for the page's main content
6. Page: Generates a renderable array for the entire page; the content array becomes nested within

Additions Welcome
=================

While I maintain that the entire framework is contained within index.php, I realize
that without some additional code it's rather useless. I therefore welcome any
submissions of plugins. When a large enough collection exists, a second repository
will be created dedicated to only plugins -- this will allow this installation
profile to remain very small.
