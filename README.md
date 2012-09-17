Room13PageBundle
================

 A simplistic bundle to serve static pages from database.
 If you are looking for a more enterprisy page handling, consider using [SonataPageBundle](https://github.com/sonata-project/SonataPageBundle).
 
 
 * Pages can be organized in a hierachy
 * Pages can have multible urls (shortcuts)
 * Metadata can be stored as attributes of a page
 * A Page can be assigned a list of reusable fratures
 * Ajax and normal page rendering


Installation & Usage
------------
Install the package with your preferred method and activate the bundle in your kernel.


In order to have nice urls like */about/references* you need to setup a catch all route 
at the end of your routing configuration. Add the following route to your routing definition:
 
    catchAll:
      pattern:  /{path}
      defaults: { _controller: Room13PageBundle:Page:page }
      requirements:
        path: .*


After routing is set up you need to create some pages. Since there is no admin you need to create them programaticly for example with a command or doctrine fixtures.

    // create the root page
    $root = new Page('/');
    $root->setTitle('Home');
    $root->setContent('Welcome to my homepage');
    
    // create a subpages
    $sub1 = new Page('/about-me');
    $sub1->setTitle('About');
    $sub1->setContent('About me');
    
    // persist pages
    $em->persist($root);
    $em->persist($sub1);
    
Now the pages are ready to be viewed in the browser. Be aware of other routes with higher priority to block the pagecontroller from serving the page.