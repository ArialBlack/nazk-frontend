<?php
/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * The doctype, html, head and body tags are not in this template. Instead they
 * can be found in the html.tpl.php template in this directory.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see bootstrap_preprocess_page()
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see bootstrap_process_page()
 * @see template_process()
 * @see html.tpl.php
 *
 * @ingroup templates
 */
 
 //$p = field_view_field('node', $node, 'field_main_pic', array('label'=>'hidden'));
 //print render($p);
 
    global $language;
    $lang = $language->language;

    $nids = db_select('node', 'n')
    ->fields('n', array('nid'))
    ->fields('n', array('type'))
    ->condition('n.type', 'newspage')
    ->condition('n.language', $lang)
    ->range(0, 1)
    ->orderBy('created', 'DESC')//ORDER BY created
    ->execute()
    ->fetchCol(); // returns an indexed array
            
    $last_news_nid = $nids[0];
    
    if ($last_news_nid > 0) {
        $node = node_load($last_news_nid);
        
        $body = field_view_field('node', $node, 'body', array(
        'label'=>'hidden',
        'type' => 'text_summary_or_trimmed', 
        'settings'=>array('trim_length' => 200),
        ));
    
        if ($node->field_main_pic) {
            $img_url = $node->field_main_pic['und'][0]['uri'];
        } else $img_url = "";
    }       

?>

<section class="node-news-intro">
    <div class="node-cover" 
    <?php if (strlen($img_url) > 0): ?>
         style="background-image: url('<?php print image_style_url("covercontrast", $img_url) ?>')"
    <?php endif; ?>
    ></div>
        <div class="pre-header">
            <div class="container">
                <?php if ($logo): ?>
                    <a class="logo navbar-btn pull-left" href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>">
                      <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
                    </a>
                <?php endif; ?>
                        
                <?php if (!empty($site_name)): ?>
                    <a class="name navbar-brand" href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>"><?php print $site_name; ?></a>
                <?php endif; ?>
                
                <div class="lang-switch">
                    <span class="ofsite"><img src="/sites/all/themes/nazk_ui/images/uasmall.png" width="14" height="23" alt=""/> <?php print t("Official website"); ?></span>
                    <?php
                        $block = module_invoke('locale', 'block_view', 'language');
                        print render($block['content']);
                    ?>
                </div>
                
                <div class="search-block">
                    <?php
                        $block = module_invoke('views', 'block_view', '-exp-search-page');
                        print render($block['content']);
                    ?>
                </div>
                              
                <div class="social-links pull-right">
                    <a href="https://www.facebook.com/NAZKgov/?fref=ts"><i class="icon ion-social-facebook"></i></a>
                    <a href="https://twitter.com/NAZK_gov"><i class="icon ion-social-twitter"></i></a>
                    <a href="https://www.instagram.com/nazk_gov/"><i class="icon ion-social-instagram-outline"></i></a>
                    <a href="https://www.youtube.com/channel/UCKwoUDbscWm4BT7BoBo0kMg\"><i class="icon ion-social-youtube-outline"></i></a>
                    <a href="https://plus.google.com/u/0/104801978277750249587/about"><i class="icon ion-social-googleplus"></i></a>
                </div>
            </div>
        </div>
        
        <header id="navbar" role="banner" class="navbar navbar-default">
            <div class="<?php print $container_class; ?>">
                <div class="navbar-header">
            
                  <?php if (!empty($primary_nav) || !empty($secondary_nav) || !empty($page['navigation'])): ?>
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                      <span class="sr-only"><?php print t('Toggle navigation'); ?></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                    </button>
                  <?php endif; ?>
                </div>
            
                <?php if (!empty($primary_nav) || !empty($secondary_nav) || !empty($page['navigation'])): ?>
                  <div class="navbar-collapse collapse">
                    <nav role="navigation">
                      <?php if (!empty($primary_nav)): ?>
                        <?php print render($primary_nav); ?>
                      <?php endif; ?>
                      
                <div class="lang-switch">
                    <span class="ofsite"><?php print t("Official website"); ?></span>
                    <?php
                        $block = module_invoke('locale', 'block_view', 'language');
                        print render($block['content']);
                    ?>
                </div>
                
                <div class="social-links">
                    <a href="https://www.facebook.com/NAZKgov/?fref=ts"><i class="icon ion-social-facebook"></i></a>
                    <a href="https://twitter.com/NAZK_gov"><i class="icon ion-social-twitter"></i></a>
                    <a href="https://www.instagram.com/nazk_gov/"><i class="icon ion-social-instagram-outline"></i></a>
                    <a href="https://www.youtube.com/channel/UCKwoUDbscWm4BT7BoBo0kMg\"><i class="icon ion-social-youtube-outline"></i></a>
                    <a href="https://plus.google.com/u/0/104801978277750249587/about"><i class="icon ion-social-googleplus"></i></a>
                </div>   
                      
                      <?php if (!empty($secondary_nav)): ?>
                        <?php print render($secondary_nav); ?>
                      <?php endif; ?>
                      <?php if (!empty($page['navigation'])): ?>
                        <?php print render($page['navigation']); ?>
                      <?php endif; ?>
                    </nav>
                  </div>
                <?php endif; ?>
              </div>
        </header>
    
        <div class="container">
            <div class="container-padding">
                
                <h1 class="page-header"><?php print $node->title; ?></h1>
                
                 <div class="node-cover" 
    <?php if (strlen($img_url) > 0): ?>
         style="background-image: url('<?php print image_style_url("covercontrast", $img_url) ?>')"
    <?php endif; ?>
    ></div>
            
                <b class="page-teaser"><?php print render($body); ?></b>
                
                <div>
                    <span class="submitted">
                        <?php
                        $monthsRU = explode("|", '|января|февраля|марта|апреля|мая|июня|июля|августа|сентября|октября|ноября|декабря');
                        $monthsUA = explode("|", '|Січня|Лютого|Березня|Квітня|Травня|Червня|Липня|Серпня|Вересня|Жовтня|Листопада|Грудня');
	                    
                        $day = intval(format_date($node->created, 'custom', 'd'));
                        $month = intval(format_date($node->created, 'custom', 'm'));
                        $year = intval(format_date($node->created, 'custom', 'Y'));
                        
                        if ($lang == 'uk') {
                            $month = $monthsUA[$month];
                        } else {
                            $month = format_date($node->created, 'custom', 'F');
                        }

                        print $day . ' ' . $month . ' ' . $year;
                        
                        ?>
                    </span>
                
                    <a href="/node/<?php print $last_news_nid; ?>" class="btn btn-default btn-outline"><i class="icon ion-ios-paper-outline"></i> <?php print t("Read"); ?></a>
                </div>
            </div>
        </div> 
</section>


<div class="main-container <?php print $container_class; ?>">
    <div class="container-padding">
      <header role="banner" id="page-header">
        <?php if (!empty($site_slogan)): ?>
          <p class="lead"><?php print $site_slogan; ?></p>
        <?php endif; ?>
    
        <?php print render($page['header']); ?>
      </header> <!-- /#page-header -->
    
      <div class="row">
    
        <?php if (!empty($page['sidebar_first'])): ?>
          <aside class="col-sm-3" role="complementary">
            <?php print render($page['sidebar_first']); ?>
          </aside>  <!-- /#sidebar-first -->
        <?php endif; ?>
    
        <section<?php print $content_column_class; ?>>
          <?php if (!empty($page['highlighted'])): ?>
            <div class="highlighted jumbotron"><?php print render($page['highlighted']); ?></div>
          <?php endif; ?>
          <?php if (!empty($breadcrumb)): print $breadcrumb; endif;?>
          <a id="main-content"></a>
          
          <?php print $messages; ?>
          <?php if (!empty($tabs)): ?>
            <?php print render($tabs); ?>
          <?php endif; ?>
          <?php if (!empty($page['help'])): ?>
            <?php print render($page['help']); ?>
          <?php endif; ?>
          <?php if (!empty($action_links)): ?>
            <ul class="action-links"><?php print render($action_links); ?></ul>
          <?php endif; ?>
          <?php print render($page['content']); ?>
          
        </section>
    
        <?php if (!empty($page['sidebar_second'])): ?>
          <aside class="col-sm-3" role="complementary">
            <?php print render($page['sidebar_second']); ?>
          </aside>  <!-- /#sidebar-second -->
        <?php endif; ?>
    
      </div>
  </div>
</div>

<div class="content_bottom_wrap">
    <?php if (!empty($page['content_bottom'])): ?>
      <div class="container">
        <?php print render($page['content_bottom']); ?>
      </div>
    <?php endif; ?>
</div>

<div id="sideblocks">
    <?php
        $block = module_invoke('block', 'block_view', '1');
        print render($block['content']);
    ?>
</div>

<div class="footer_wrap">
    <?php if (!empty($page['footer'])): ?>
      <footer class="footer <?php print $container_class; ?>">
        <?php print render($page['footer']); ?>
      </footer>
    <?php endif; ?>
</div>
