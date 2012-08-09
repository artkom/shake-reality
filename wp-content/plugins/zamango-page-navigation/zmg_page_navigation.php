<?

/*
 * $Date: 2010/03/04 12:20:32 $
 * $Revision: 1.0 $
 */

/*
Plugin Name: Zamango Page Navigation
Plugin URI: http://www.zamango.com/
Description: This plugin adds an advanced page navigation to Wordpress
Author: Zamango
Version: 1.3
Requires at least: 2.8
Author URI: http://www.zamango.com/
License: GPL
*/

require_once('zmg_admin.php');

/******************************************************************************/
if (!class_exists('zmg_page_navigation'))
{
    class zmg_page_navigation extends zmg_admin
    {
        var $hook        = 'zmg-page-navigation';
        var $version     = '1.3';
        var $page_title  = 'Page Navigation';
        var $menu_title  = 'Page Navigation';
        var $filename    = 'zamango-page-navigation/zmg_page_navigation.php';
        var $options     = array();

        /**********************************************************************/
        function zmg_page_navigation()
        {
            require_once('zmg_page_navigation_defaults.php');

            $this->dir_name    = basename(dirname(__FILE__));
            $this->plugin_url  = WP_PLUGIN_URL . '/' . $this->dir_name;
            $this->plugin_path = WP_PLUGIN_DIR . '/' . $this->dir_name;

            $this->reg_deactivation_hook();

            add_action('init', array($this, 'admin_init'), 3);
            add_action('init', array($this, 'init'));
        }

        /**********************************************************************/
        function deactivate()
        {
            $this->options = get_option($this->hook);

            if ($this->options['clear_options']) delete_option($this->hook);
        }

        /**********************************************************************/
        function query_vars_endpoint($vars)
        {
            $vars[] = 'all';

            return $vars;
        }

        /**********************************************************************/
        function add_custom_css()
        {
            echo '\n<style type="text/css">' . $this->options['css'] .
                 '</style>\n';
        }

        /**********************************************************************/
        function init()
        {
            global $wp_rewrite;

            $wp_rewrite->add_endpoint('all', EP_ALL);
            $wp_rewrite->flush_rules();

            add_filter('query_vars', array($this, 'query_vars_endpoint'));

            $this->add_js('zmg-pn-admin-js', $this->plugin_url .
                          '/zmg_page_navigation_admin.js', true);

            $this->add_css('zmg-pn-admin-js', $this->plugin_url .
                           '/zmg_page_navigation_admin.css', true);

            if ($this->options['is_zmg_css'])
                $this->add_css('zmg-pn-css', $this->plugin_url .
                                             '/zmg_page_navigation.css');
            else add_action('wp_head', array($this, 'add_custom_css'));

            if ($wp_query->is_feed || is_admin()) return true;

            $before_loop = new zmg_pn_parser ('before_loop', $this->options,
                                              $this->version);
            $after_loop  = new zmg_pn_parser ('after_loop', $this->options,
                                              $this->version);

            if ($this->options['before_loop'])
                add_action('loop_start', array($before_loop, 'parse_br'));
            if ($this->options['after_loop'])
                add_action('loop_end', array($after_loop, 'parse_br'));
            if ($this->options['before_loop_s'])
                add_action('loop_start', array($before_loop, 'parse_sp'));
            if ($this->options['after_loop_s'])
                add_action('loop_end', array($after_loop, 'parse_sp'));
        }

        /**********************************************************************/
        function plugin_option_page_content()
        {

            if (isset($_POST['ZMG_SUBMIT']))
            {
                $this->validate_params();

                if (isset($this->errors))
                    echo $this->disappearing_message(
                        __('Incorrect settings value', $this->hook)
                    );
                else
                {
                    $this->save_options();

                    echo $this->disappearing_message(
                        __('Settings have been saved', $this->hook)
                    );
                }
            }

            $this->form_begin($this->hidden('ZMG_SUBMIT'));

            $this->postbox($this->hook . '-blogroll_navigation',
                           __('Blogroll navigation', $this->hook),
                           $this->blogroll_navigation());
            $this->postbox($this->hook . '-quick_links_on_single_post',
                           __('Quick links on single post', $this->hook),
                           $this->quick_links_on_single_post());
            $this->postbox($this->hook . '-common_options_and_css',
                           __('Common options & CSS', $this->hook),
                           $this->common_options_and_css());

            $this->form_end();
        }

        /**********************************************************************/
        function blogroll_navigation()
        {
            $content  = '<p>';
            $content .= __('You may use the following Zamango "tags" while ' .
                           'configuring Page Navigation bar on lists pages',
                           $this->hook);
            $content .= '</p>';

            $ul = array();

            $html  = '<code>[zmg_pn:current]</code> ';
            $html .= __('current page number (i.e. page on which the ' .
                        'navigation bar is shown)', $this->hook);

            $ul[] = $this->elem($html);

            $html  = '<code>[zmg_pn:total]</code> ';
            $html .= __('total pages amount', $this->hook);

            $ul[] = $this->elem($html);

            $html  = '<code>[zmg_pn:page]</code> ';
            $html .= __('number of page which is processing by script for ' .
                        'that moment (note that if page navigation bar ' .
                        'contains links to 10 pages plus current then value ' .
                        'of [zmg_pn:page] will have 11 valuessequentially)',
                        $this->hook);

            $ul[] = $this->elem($html);

            $content .= $this->ul($ul);

            $content .= '<p>';
            $content .= __('Also you can use any HTML tags.', $this->hook);
            $content .= '</p>';

            $disc_link = $this->add_description(__('Help on Zamango tags',
                                                   $this->hook), $content,
                                                'blogroll_navigation');

            $content  = $this->information_message(
                __('Navigation bar shown on the index, category, tag, ' .
                   'archive browsing, search results and other lists',
                   $this->hook)
            );

            $rows = array();
            $row  = array();
            $html = '';

            $html = __('Where the pagebar must be inserted:', $this->hook);

            $row[] = $this->elem($html);

            $html  = $this->checkbox('before_loop', '1',
                                     $this->options['before_loop'],
                                     __('before posts list', $this->hook));
            $html .= '<br />';
            $html .= $this->checkbox('after_loop', '1',
                                     $this->options['after_loop'],
                                     __('after posts list', $this->hook));

            $row[]  = $this->elem($html);
            $rows[] = $this->elem($row);
            $row    = array();

            $html = __('Left part', $this->hook);

            $row[] = $this->elem($html);

            $html  = $this->text('left', $this->options['left'],
                                 __('Any positive number which defines amount ' .
                                    'of links in the LEFT part of pagebar',
                                    $this->hook));

            if ($this->errors['left'])
                $html .= $this->error_message(
                    __($this->errors['left'], $this->hook));

            $row[]  = $this->elem($html);
            $rows[] = $this->elem($row);
            $row    = array();

            $html = __('Center part', $this->hook);

            $row[] = $this->elem($html);

            $html  = $this->text('center', $this->options['center'],
                                 __('Any odd positive number which defines ' .
                                    'amount of links in the CENTRAL part of ' .
                                    'pagebar. Note that if you enter even ' .
                                    'number it will be increased by one (to ' .
                                    'become odd one).', $this->hook));

            if ($this->errors['center'])
                $html .= $this->error_message(
                    __($this->errors['center'], $this->hook));

            $row[]  = $this->elem($html);
            $rows[] = $this->elem($row);
            $row    = array();

            $html = __('Right part', $this->hook);

            $row[] = $this->elem($html);

            $html  = $this->text('right', $this->options['right'],
                                 __('Any positive number which defines amount ' .
                                    'of links in the RIGHT part of pagebar',
                                    $this->hook));

            if ($this->errors['right'])
                $html .= $this->error_message(
                    __($this->errors['right'], $this->hook));

            $row[]  = $this->elem($html);
            $rows[] = $this->elem($row);
            $row    = array();

            $html = __('Separator', $this->hook);

            $row[] = $this->elem($html);

            $html  = $this->text('separator', $this->options['separator'],
                                 __('Text separating the central part of page ' .
                                    'bar from the right and left ones',
                                    $this->hook));

            if ($this->errors['separator'])
                $html .= $this->error_message(
                    __($this->errors['separator'], $this->hook));

            $row[]  = $this->elem($html);
            $rows[] = $this->elem($row);
            $row    = array();

            $html = __('Link to previous page:', $this->hook);

            $row[] = $this->elem($html);

            $ul = array();

            $ul[] = $this->elem(
                        $this->radio('prev_link', 'never',
                                     $this->options['prev_link'] == 'never',
                                     __('don\'t show', $this->hook)));
            $ul[] = $this->elem(
                        $this->radio('prev_link', 'auto',
                                     $this->options['prev_link'] == 'auto',
                                     __('auto', $this->hook)));
            $ul[] = $this->elem(
                        $this->radio('prev_link', 'always',
                                     $this->options['prev_link'] == 'always',
                                     __('always show', $this->hook)));

            $html  = $this->ul($ul, 'row');

            if ($this->errors['prev_link'])
                $html .= $this->error_message(
                    __($this->errors['prev_link'], $this->hook));

            $label  = __('Text of the link to previous page', $this->hook);
            $label .= ' <a href="#help_for_zmg_tags"';
            $label .= 'onClick="' . $disc_link . '"';
            $label .= 'return false;" title="';
            $label .= __('Help on Zamango tags', $this->hook) . '">';
            $label .= __('(Zamango tags and HTML are allowed)', $this->hook);
            $label .= '</a>';

            $html .= $this->text('prev_tag', $this->options['prev_tag'],
                                 $label);

            if ($this->errors['prev_tag'])
                $html .= $this->error_message(
                    __($this->errors['prev_tag'], $this->hook));

            $row[]  = $this->elem($html);
            $rows[] = $this->elem($row);
            $row    = array();

            $html = __('Link to next page:', $this->hook);

            $row[] = $this->elem($html);

            $ul = array();

            $ul[] = $this->elem(
                        $this->radio('next_link', 'never',
                                     $this->options['next_link'] == 'never',
                                     __('don\'t show', $this->hook)));
            $ul[] = $this->elem(
                        $this->radio('next_link', 'auto',
                                     $this->options['next_link'] == 'auto',
                                     __('auto', $this->hook)));
            $ul[] = $this->elem(
                        $this->radio('next_link', 'always',
                                     $this->options['next_link'] == 'always',
                                     __('always show', $this->hook)));

            $html  = $this->ul($ul, 'row');

            if ($this->errors['next_link'])
                $html .= $this->error_message(
                    __($this->errors['next_link'], $this->hook));

            $label  = __('Text of the link to next page', $this->hook);
            $label .= ' <a href="#help_for_zmg_tags"';
            $label .= 'onClick="' . $disc_link . '"';
            $label .= 'return false;" title="';
            $label .= __('Help on Zamango tags', $this->hook) . '">';
            $label .= __('(Zamango tags and HTML are allowed)', $this->hook);
            $label .= '</a>';

            $html .= $this->text('next_tag', $this->options['next_tag'],
                                 $label);

            if ($this->errors['next_tag'])
                $html .= $this->error_message(
                    __($this->errors['next_tag'], $this->hook));

            $row[]  = $this->elem($html);
            $rows[] = $this->elem($row);
            $row    = array();

            $html = __('Link to first page:', $this->hook);

            $row[] = $this->elem($html);

            $label  = __('Text of the link to first page', $this->hook);
            $label .= ' <a href="#help_for_zmg_tags"';
            $label .= 'onClick="' . $disc_link . '"';
            $label .= 'return false;" title="';
            $label .= __('Help on Zamango tags', $this->hook) . '">';
            $label .= __('(Zamango tags and HTML are allowed)', $this->hook);
            $label .= '</a>';

            $html = $this->text('first_tag', $this->options['first_tag'],
                                 $label);

            if ($this->errors['first_tag'])
                $html .= $this->error_message(
                    __($this->errors['first_tag'], $this->hook));

            $row[]  = $this->elem($html);
            $rows[] = $this->elem($row);
            $row    = array();

            $html = __('Link to last page:', $this->hook);

            $row[] = $this->elem($html);

            $label  = __('Text of the link to last page', $this->hook);
            $label .= ' <a href="#help_for_zmg_tags"';
            $label .= 'onClick="' . $disc_link . '"';
            $label .= 'return false;" title="';
            $label .= __('Help on Zamango tags', $this->hook) . '">';
            $label .= __('(Zamango tags and HTML are allowed)', $this->hook);
            $label .= '</a>';

            $html = $this->text('last_tag', $this->options['last_tag'],
                                 $label);

            if ($this->errors['last_tag'])
                $html .= $this->error_message(
                    __($this->errors['last_tag'], $this->hook));

            $row[]  = $this->elem($html);
            $rows[] = $this->elem($row);
            $row    = array();

            $html = __('Current page sign:', $this->hook);

            $row[] = $this->elem($html);

            $label  = __('Text defining the current page', $this->hook);
            $label .= ' <a href="#help_for_zmg_tags"';
            $label .= 'onClick="' . $disc_link . '"';
            $label .= 'return false;" title="';
            $label .= __('Help on Zamango tags', $this->hook) . '">';
            $label .= __('(Zamango tags and HTML are allowed)', $this->hook);
            $label .= '</a>';

            $html = $this->text('curr_tag', $this->options['curr_tag'],
                                 $label);

            if ($this->errors['curr_tag'])
                $html .= $this->error_message(
                    __($this->errors['curr_tag'], $this->hook));

            $row[]  = $this->elem($html);
            $rows[] = $this->elem($row);
            $row    = array();

            $html = __('Common link text:', $this->hook);

            $row[] = $this->elem($html);

            $label  = __('Text of the link to other page i.e. which is not ' .
                         'described above', $this->hook);
            $label .= ' <a href="#help_for_zmg_tags"';
            $label .= 'onClick="' . $disc_link . '"';
            $label .= 'return false;" title="';
            $label .= __('Help on Zamango tags', $this->hook) . '">';
            $label .= __('(Zamango tags and HTML are allowed)', $this->hook);
            $label .= '</a>';

            $html = $this->text('stnd_tag', $this->options['stnd_tag'],
                                 $label);

            if ($this->errors['stnd_tag'])
                $html .= $this->error_message(
                    __($this->errors['stnd_tag'], $this->hook));

            $row[]  = $this->elem($html);
            $rows[] = $this->elem($row);
            $row    = array();

            $html = __('Pop-up hints', $this->hook);

            $row[] = $this->elem($html);

            $html  = $this->checkbox('tooltips', '1',
                                     $this->options['tooltips'],
                                     __('Show hints', $this->hook));

            $html .= '<div id="zmg_pn_ttip_tag_div">';
            $html .= $this->text('ttip_tag', $this->options['ttip_tag'],
                                 __('Text of on-hover hint', $this->hook));

            if ($this->errors['ttip_tag'])
                $html .= $this->error_message(
                    __($this->errors['ttip_tag'], $this->hook));

            $html .= '</div>';

            if (! $this->options['tooltips'])
            {
                $html .= '<script type="text/javascript"><!--';
                $html .= 'jQuery("#zmg_pn_ttip_tag_div").hide();';
                $html .= '//--></script>';
            }

            $row[]  = $this->elem($html);
            $rows[] = $this->elem($row);
            $row    = array();
            $row[]  = $this->elem('');
            $html   = $this->submit(__('Save', $this->hook));
            $row[]  = $this->elem($html);
            $rows[] = $this->elem($row);

            $content .= $this->table($rows);

            return $content;
        }

        /**********************************************************************/
        function quick_links_on_single_post()
        {
            $content  = '<p>';
            $content .= __('You may use the following Zamango "tags" while ' .
                           'configuring Page Navigation bar on single posts',
                           $this->hook);
            $content .= '</p>';

            $ul = array();

            $html  = '<code>[zmg_pn:prev_post]</code> ';
            $html .= __('Title for link to previous post', $this->hook);

            $ul[] = $this->elem($html);

            $html  = '<code>[zmg_pn:next_post]</code> ';
            $html .= __('Title for link to next post', $this->hook);

            $ul[] = $this->elem($html);

            $content .= $this->ul($ul);

            $content .= '<p>';
            $content .= __('Also you can use any HTML tags.', $this->hook);
            $content .= '</p>';

            $disc_link = $this->add_description(__('Help on Zamango tags',
                                                   $this->hook), $content,
                                                'quick_links_on_single_post');

            $content  = $this->information_message(
                __('Links to `Previous post` and `Next post` shown on single ' .
                   'post', $this->hook)
            );

            $rows = array();
            $row  = array();
            $html = '';

            $html = __('Where links must be inserted:', $this->hook);

            $row[] = $this->elem($html);

            $html  = $this->checkbox('before_loop_s', '1',
                                     $this->options['before_loop_s'],
                                     __('before post', $this->hook));
            $html .= '<br />';
            $html .= $this->checkbox('after_loop_s', '1',
                                     $this->options['after_loop_s'],
                                     __('after post', $this->hook));

            $row[]  = $this->elem($html);
            $rows[] = $this->elem($row);
            $row    = array();

            $html = __('Linking', $this->hook);

            $row[] = $this->elem($html);

            $html  = $this->checkbox('in_category', '1',
                                     $this->options['in_category'],
                                     __('Show links to the same category ' .
                                        'posts only', $this->hook));
            $html .= '<br />';
            $html .= $this->checkbox('fl_linking', '1',
                                     $this->options['fl_linking'],
                                     __('Crosslink newest and oldest posts ' .
                                        '(make links circle)', $this->hook));

            $row[]  = $this->elem($html);
            $rows[] = $this->elem($row);
            $row    = array();

            $html = __('Link to previous post:', $this->hook);

            $row[] = $this->elem($html);

            $label  = __('Text of the link to previos post', $this->hook);
            $label .= ' <a href="#help_for_zmg_tags"';
            $label .= 'onClick="' . $disc_link . '"';
            $label .= 'return false;" title="';
            $label .= __('Help on Zamango tags', $this->hook) . '">';
            $label .= __('(Zamango tags and HTML are allowed)', $this->hook);
            $label .= '</a>';

            $html = $this->text('prev_tag_s', $this->options['prev_tag_s'],
                                 $label);

            if ($this->errors['prev_tag_s'])
                $html .= $this->error_message(
                    __($this->errors['prev_tag_s'], $this->hook));

            $row[]  = $this->elem($html);
            $rows[] = $this->elem($row);
            $row    = array();

            $html = __('Link to next post:', $this->hook);

            $row[] = $this->elem($html);

            $label  = __('Text of the link to next post', $this->hook);
            $label .= ' <a href="#help_for_zmg_tags"';
            $label .= 'onClick="' . $disc_link . '"';
            $label .= 'return false;" title="';
            $label .= __('Help on Zamango tags', $this->hook) . '">';
            $label .= __('(Zamango tags and HTML are allowed)', $this->hook);
            $label .= '</a>';

            $html = $this->text('next_tag_s', $this->options['next_tag_s'],
                                 $label);

            if ($this->errors['next_tag_s'])
                $html .= $this->error_message(
                    __($this->errors['next_tag_s'], $this->hook));

            $row[]  = $this->elem($html);
            $rows[] = $this->elem($row);
            $row    = array();

            $row[]  = $this->elem('');
            $html   = $this->submit(__('Save', $this->hook));
            $row[]  = $this->elem($html);
            $rows[] = $this->elem($row);

            $content .= $this->table($rows);

            return $content;
        }

        /**********************************************************************/
        function common_options_and_css()
        {
            $content  = $this->information_message(
                __('Common options (only one for now) & CSS customization',
                   $this->hook)
            );

            $rows = array();
            $row  = array();
            $html = '';

            $row[] = $this->elem('');

            $html  = $this->checkbox('clear_options', '1',
                                     $this->options['clear_options'],
                                     __('Delete options when deactivating the ' .
                                        'plugin', $this->hook));

            $row[]  = $this->elem($html);
            $rows[] = $this->elem($row);
            $row    = array();

            $html = __('Use CSS:', $this->hook);

            $row[]  = $this->elem($html);

            $ul = array();

            $ul[] = $this->elem(
                        $this->radio('is_zmg_css', '1',
                                     $this->options['is_zmg_css'],
                                     __('Default CSS', $this->hook)));

            $html  = $this->radio('is_zmg_css', '0',
                                  (! $this->options['is_zmg_css']),
                                  __('Custom CSS:', $this->hook));
            $html .= '<br />';
            $html .= $this->textarea('css', $this->options['css'], 13, 80);

            $ul[] = $this->elem($html);

            $html  = $this->ul($ul, 'col');

            if ($this->errors['is_zmg_css'])
                $html .= $this->error_message(
                    __($this->errors['is_zmg_css'], $this->hook));

            $row[]  = $this->elem($html);
            $rows[] = $this->elem($row);
            $row    = array();

            $row[]  = $this->elem('');
            $html   = $this->submit(__('Save', $this->hook));
            $row[]  = $this->elem($html);
            $rows[] = $this->elem($row);

            $content .= $this->table($rows);

            return $content;
        }
    }

    $zmg_page_navigation = new zmg_page_navigation();

    /**************************************************************************/
    class zmg_pn_parser
    {
        var $current_page;
        var $num_pages;
        var $options;
        var $id;

        /**********************************************************************/
        function zmg_pn_parser($id, $options, $version)
        {
            $this->options = $options;
            $this->id      = $id;
            $this->version = $version;
        }

        /**********************************************************************/
        function parse_br()
        {
            global $paged, $wp_query, $wpdb;

            if (defined('ZMG_BLOGROLL_RENDERED'))
                return true;

            if (!defined('ZMG_BLOGROLL_RENDERED'))
                define('ZMG_BLOGROLL_RENDERED', true);

            if (is_single() || !in_the_loop() || is_feed()) return true;

            $this->current_page = $paged;
            $this->num_pages    = intval($wp_query->max_num_pages);

            if ($this->num_pages <= 1) return 1;

            if (empty($this->current_page)) $this->current_page = 1;

            echo "\n<!-- Zamango Pagebar " . $this->version . " -->\n";
            echo "<div class='zmg_pn_clear'></div>";
            echo "<div class='zmg_pn' id='zmg_pn_br_" . $this->id . "'>\n";

            $this->prev_page();

            if ($this->num_pages <= $this->options["left"] +
                                    $this->options["center"] +
                                    $this->options["right"])
            {
                for ($i = 1; $i <= $this->num_pages; $i++)
                    if ($i == $this->current_page) $this->curr_page();
                    else $this->stnd_page($i);
            }
            else if ($this->current_page < $this->options["left"] +
                                           $this->options["center"])
            {
                $lc = $this->options["left"] + $this->options["center"];
                for ($i = 1; $i <= $lc; $i++)
                    if ($i == $this->current_page) $this->curr_page();
                    else $this->stnd_page($i);

                $this->separator();

                for ($i = $this->num_pages - $this->options["right"] +1;
                     $i <= $this->num_pages; $i++)
                    $this->stnd_page($i);
            }
            else if ($this->current_page - 1> $this->num_pages -
                     $this->options["center"] - $this->options["right"])
            {
                for ($i = 1; $i <= $this->options["left"]; $i++)
                    $this->stnd_page($i);

                $this->separator();

                for ($i = 1 + $this->num_pages - $this->options["center"] -
                          $this->options["right"]; $i <= $this->num_pages; $i++)
                    if ($i == $this->current_page) $this->curr_page();
                    else $this->stnd_page($i);
            }
            else
            {
                for ($i = 1; $i <= $this->options["left"]; $i++)
                    $this->stnd_page($i);

                $this->separator();

                $c = floor ($this->options["center"] / 2);
                for ($i = $this->current_page - $c;
                     $i <= $this->current_page + $c; $i++)
                    if ($i == $this->current_page) $this->curr_page();
                    else $this->stnd_page($i);

                $this->separator();

                for ($i = $this->num_pages - $this->options["right"] +1;
                     $i <= $this->num_pages; $i++)
                    $this->stnd_page($i);
            }

            $this->next_page();

            echo "</div>\n";
            echo "<div class='zmg_pn_clear'></div>";
            echo "<!-- Zamango Pagebar " . $this->version . " -->\n";
        }

        /**********************************************************************/
        function parse_sp()
        {
            global $wpdb;

            if (!is_single() || !in_the_loop() || is_feed()) return true;

            $id = get_the_ID();

            $date = $wpdb->get_var("
                SELECT `post_date_gmt`
                  FROM `$wpdb->posts`
                 WHERE `ID` = '$id'
                 LIMIT 1
            ");

            if ($this->options['in_category'])
            {
                $cat_id = $wpdb->get_var("
                    SELECT `tr`.`term_taxonomy_id`
                      FROM `$wpdb->term_relationships` AS `tr`
                INNER JOIN `$wpdb->term_taxonomy` AS `tt`
                        ON `tr`.`term_taxonomy_id` = `tt`.`term_taxonomy_id`
                     WHERE `tr`.`object_id` = '$id'
                       AND `tt`.`taxonomy` = 'category'
                     LIMIT 1
                ");

                $query = "
                    SELECT `object_id`
                      FROM `$wpdb->term_relationships`
                     WHERE `term_taxonomy_id` = '$cat_id'
                ";

                $posts_id = $wpdb->get_results($query, ARRAY_N);

                if ($posts_id)
                {
                    $posts = array ();

                    foreach ($posts_id as $post_id)
                        array_push($posts, $post_id[0]);

                    $posts_in = implode(',', $posts);

                    $query = "
                        SELECT `post_date_gmt`, `ID`, `post_title`
                          FROM `$wpdb->posts`
                         WHERE `ID` IN ($posts_in)
                           AND `post_status` = 'publish'
                           AND `post_type` = 'post'
                      ORDER BY `post_date_gmt`, `ID`
                    ";

                    $sorted_posts = $wpdb->get_results($query, ARRAY_A);

                    $count = count($sorted_posts);

                    for ($i = 0; $i < $count &&
                                 $sorted_posts[$i]['ID'] != $id; $i++) {}

                    $prev = ($i == 0) ? (($this->options['fl_linking']) ?
                                        $sorted_posts[$count - 1] : NULL)
                                      : $sorted_posts[$i - 1];
                    $next = ($i == $count - 1) ? (($this->options['fl_linking'])
                                               ? $sorted_posts[0] : NULL)
                                               : $sorted_posts[$i + 1];

                }
            }
            else
            {
                $query = "
                    SELECT `ID`, `post_title`
                      FROM `$wpdb->posts`
                     WHERE `post_date_gmt` <= '$date'
                       AND `post_status` = 'publish'
                       AND `post_type` = 'post'
                  ORDER BY `post_date_gmt`, `ID`
                ";

                $sorted_posts = $wpdb->get_results($query, ARRAY_A);

                $count = count($sorted_posts);

                for ($i = 0; $i < $count &&
                             $sorted_posts[$i]['ID'] != $id; $i++) {}

                $prev = ($i == 0) ? (($this->options['fl_linking']) ?
                                    $sorted_posts[$count - 1] : NULL)
                                  : $sorted_posts[$i - 1];
                $next = ($i == $count - 1) ? (($this->options['fl_linking'])
                                           ? $sorted_posts[0] : NULL)
                                           : $sorted_posts[$i + 1];
            }

            if ($prev || $next)
            {
                echo "<!-- Zamango Pagebar " . $this->version . " -->\n";
                echo "<div class='zmg_pn_clear'></div>";
                echo "<div class='zmg_pn' id='zmg_pn_sp_" . $this->id . "'>\n";

                if ($prev)
                {
                    echo "<span class='zmg_pn_prev_post'><a href='"
                         . get_permalink($prev['ID']) . "'>" .
                         str_replace("[zmg_pn:prev_post]", $prev['post_title'],
                                     $this->options['prev_tag_s']) .
                         "</a></span>\n";
                }

                if ($next)
                {
                    echo "<span class='zmg_pn_next_post'><a href='"
                         . get_permalink($next['ID']) . "'>" .
                         str_replace("[zmg_pn:next_post]", $next['post_title'],
                                     $this->options['next_tag_s']) .
                         "</a></span>\n";
                }

                echo "</div>\n";
                echo "<div class='zmg_pn_clear'></div>";
                echo "<!-- Zamango Pagebar " . $this->version . " -->\n";
            }
        }

        /**********************************************************************/
        function prev_page()
        {
            if ($this->options["prev_link"] == "never") return 1;
            if (($this->options["prev_link"] == "auto") &&
                ($this->current_page == 1)) return 1;

            echo ($this->current_page == 1) ?
                "<span class='zmg_pn_inactive zmg_pn_previous'>" .
                $this->replace_tags($this->options["prev_tag"], 0) .
                "</span>\n" : "<span class='zmg_pn_prev'><a href='" .
                $this->get_link($this->current_page - 1) . "'" .
                $this->get_tooltip($this->current_page - 1) . ">" .
                $this->replace_tags($this->options["prev_tag"],
                                    $this->current_page - 1) . "</a></span>\n";
        }

        /**********************************************************************/
        function next_page()
        {
            if ($this->options["next_link"] == "never") return 1;
            if (($this->options["next_link"] == "auto") &&
                ($this->current_page == $this->num_pages)) return 1;

            echo ($this->current_page == $this->num_pages) ?
                "<span class='zmg_pn_inactive zmg_pn_next'>" .
                $this->replace_tags($this->options["next_tag"], 0) .
                "</span>\n" : "<span class='zmg_pn_next'><a href='" .
                $this->get_link($this->current_page + 1) . "'" .
                $this->get_tooltip($this->current_page + 1) . ">" .
                $this->replace_tags($this->options["next_tag"],
                                    $this->current_page + 1) . "</a></span>\n";
        }

        /**********************************************************************/
        function curr_page()
        {
            echo "<span class='zmg_pn_current'>" .
                 $this->replace_tags("", 0) . "</span>\n";
        }

        /**********************************************************************/
        function stnd_page($page)
        {
            echo "<span class='zmg_pn_standar'><a href='" .
                $this->get_link($page) . "'" . $this->get_tooltip($page) . ">" .
                $this->replace_tags("", $page) . "</a></span>\n";
        }

        /**********************************************************************/
        function separator()
        {
            echo "<span class='zmg_pn_separator'>";
            echo ($this->options["separator"] !== "") ?
                  $this->options["separator"] : "...";
            echo"</span>\n";
        }

        /**********************************************************************/
        function get_link($page)
        {
            return get_pagenum_link($page);
        }

        /**********************************************************************/
        function get_tooltip($page)
        {
            if (! $this->options["tooltips"]) return "";
            return " title='" .
                   $this->replace_tags($this->options["ttip_tag"], $page) . "'";
        }

        /**********************************************************************/
        function replace_tags($text, $page)
        {
            if (! $page) $page = $this->current_page;
            if (! $text)
            switch ($page)
            {
                case 1                   : $text = $this->options["first_tag"];
                    break;
                case $this->num_pages    : $text = $this->options["last_tag"];
                    break;
                case $this->current_page : $text = $this->options["curr_tag"];
                    break;
                default                  : $text = $this->options["stnd_tag"];
            }

            $text = str_replace ("[zmg_pn:page]"   , $page, $text);
            $text = str_replace ("[zmg_pn:current]", $this->current_page, $text);
            $text = str_replace ("[zmg_pn:total]"  , $this->num_pages, $text);

            return $text;
        }
    }
}

