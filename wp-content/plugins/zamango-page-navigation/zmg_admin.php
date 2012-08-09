<?

/*
 * $Date: 2010/02/09 15:49:45 $
 * $Revision: 1.0 $
 */

/*
 * Backend Class for use in all Zamango plugins
 */

/******************************************************************************/
if (!defined('WP_CONTENT_URL'))
    define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');

if (!defined('WP_PLUGIN_URL'))
    define('WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins');

if (!defined('WP_CONTENT_DIR'))
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');

if (!defined('WP_PLUGIN_DIR'))
    define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');

/******************************************************************************/
if (!class_exists('zmg_admin'))
{
    define('ZMG_ADMIN', '1.3');
    define('ZMG_ADMIN_DIR_NAME', basename(dirname(__FILE__)));
    define('ZMG_ADMIN_URL', WP_PLUGIN_URL . '/' . ZMG_ADMIN_DIR_NAME);
    define('ZMG_ADMIN_DIR', WP_PLUGIN_DIR . '/' . ZMG_ADMIN_DIR_NAME);
    define('ZMG_LOGO', ZMG_ADMIN_URL . '/img/zamango_logo.png');

    class zmg_admin
    {
        var $top_hook          = 'zmg-plugins';
        var $hook              = 'zmg-plugins';
        var $access_lvl        = 'manage_options';
        var $page_title        = 'Zamango Plugins';
        var $menu_title        = 'Zamango';
        var $include_js        = array();
        var $include_css       = array();
        var $include_admin_js  = array();
        var $include_admin_css = array();
        var $descriptions      = array();
        var $no_promo          = false;

        /**********************************************************************/
        function zmg_admin()
        {
            add_action('admin_menu', array($this, 'add_options_page'));

            add_action('admin_print_styles',
                       array($this, 'enqueue_admin_css'));

            add_action('admin_print_scripts',
                       array($this, 'enqueue_admin_js'));

            add_action('admin_print_scripts',
                       array($this, 'zmg_hack_js'));

            add_action('zmg-plugin-options-' . $this->hook,
                       array($this, 'zamango_page'));
        }

        /**********************************************************************/
        function admin_init()
        {
            load_plugin_textdomain($this->top_hook, ZMG_ADMIN_URL,
                                                    ZMG_ADMIN_DIR_NAME);

            load_plugin_textdomain($this->hook,
                                   $this->plugin_url,
                                   $this->dir_name);

            $this->fetch_options();

            add_action('admin_menu', array($this, 'add_plugin_options_page'));

            add_action('wp_print_styles', array($this, 'enqueue_css'));
            add_action('admin_print_styles',
                       array($this, 'enqueue_admin_css'));

            add_action('wp_print_scripts', array($this, 'enqueue_js'));
            add_action('admin_print_scripts',
                       array($this, 'enqueue_admin_js'));

            add_action('zmg-plugin-options-' . $this->hook,
                       array($this, 'plugin_option_page_content'));

            add_filter('zamango-plugins', array($this, 'zmg_plugin'));

            add_filter('plugin_action_links',
                       array($this, 'action_links'), 10, 2);
        }

        /**********************************************************************/
        function add_options_page()
        {
            add_menu_page(__($this->page_title, $this->top_hook),
                          __($this->menu_title, $this->top_hook),
                          $this->access_lvl,
                          $this->top_hook,
                          array($this, 'options_page'),
                          ZMG_ADMIN_URL . '/img/zamango_icon.png');
        }

        /**********************************************************************/
        function add_plugin_options_page()
        {
            add_submenu_page($this->top_hook,
                             __($this->page_title, $this->hook),
                             __($this->menu_title, $this->hook),
                             $this->access_lvl,
                             $this->hook,
                             array($this, 'options_page'));
        }

        /**********************************************************************/
        function in_plugin_page()
        {
            return (isset($_GET['page']) && $_GET['page'] == $this->hook);
        }

        /**********************************************************************/
        function add_css($name, $url = '', $is_admin = '', $condition = '')
        {
            if ($condition && is_callable($condition))
            {
                if ($is_admin)
                    $this->include_admin_css[] = array($name, $url, $condition);
                else $this->include_css[] = array($name, $url, $condition);
            }
            else if ($is_admin)
                $this->include_admin_css[] = array($name, $url, NULL);
            else $this->include_css[] = array($name, $url, NULL);
        }

        /**********************************************************************/
        function enqueue_css()
        {
            foreach ($this->include_css as $css)
                if (!$css[2])
                    wp_enqueue_style($css[0], $css[1]);
                else if (call_user_func($css[2]))
                    wp_enqueue_style($css[0], $css[1]);
        }

        /**********************************************************************/
        function enqueue_admin_css()
        {
            if (!$this->in_plugin_page()) return false;

            wp_enqueue_style('dashboard');
            wp_enqueue_style('thickbox');
            wp_enqueue_style('global');
            wp_enqueue_style('wp-admin');

            wp_enqueue_style('zamango-admin', ZMG_ADMIN_URL . '/zmg_admin.css');

            foreach ($this->include_admin_css as $css)
                if (!$css[2])
                    wp_enqueue_style($css[0], $css[1]);
                else if (call_user_func($css[2]))
                    wp_enqueue_style($css[0], $css[1]);
        }

        /**********************************************************************/
        function add_js($name, $url = '', $is_admin = '', $condition = '')
        {
            if ($condition && is_callable($condition))
            {
                if ($is_admin)
                    $this->include_admin_js[] = array($name, $url, $condition);
                else $this->include_js[] = array($name, $url, $condition);
            }
            else if ($is_admin)
                $this->include_admin_js[] = array($name, $url, NULL);
            else $this->include_js[] = array($name, $url, NULL);
        }

        /**********************************************************************/
        function enqueue_js()
        {
            if (is_admin()) return false;

            foreach ($this->include_js as $js)
                if (!$js[2])
                    wp_enqueue_script($js[0], $js[1]);
                else if (call_user_func($js[2]))
                    wp_enqueue_script($js[0], $js[1]);
        }

        /**********************************************************************/
        function enqueue_admin_js()
        {
            if (!$this->in_plugin_page()) return false;

            wp_enqueue_script('postbox');
            wp_enqueue_script('dashboard');
            wp_enqueue_script('thickbox');
            wp_enqueue_script('media-upload');

            wp_enqueue_script('zamango-admin', ZMG_ADMIN_URL . '/zmg_admin.js');

            foreach ($this->include_admin_js as $js)
                if (!$js[2])
                    wp_enqueue_script($js[0], $js[1]);
                else if (call_user_func($js[2]))
                    wp_enqueue_script($js[0], $js[1]);
        }

        /**********************************************************************/
        function zmg_hack_js()
        {
            wp_enqueue_script('zmg_hack', ZMG_ADMIN_URL . '/zmg_hack.js');
        }

        /**********************************************************************/
        function reg_activation_hook()
        {
            if (preg_match("/wp-admin\/plugins\.php/", $_SERVER['REQUEST_URI']))
                register_activation_hook($this->filename,
                                         array($this, 'activate'));
        }

        /**********************************************************************/
        function reg_deactivation_hook()
        {
            if (preg_match("/wp-admin\/plugins\.php/", $_SERVER['REQUEST_URI']))
                register_deactivation_hook($this->filename,
                                         array($this, 'deactivate'));
        }

        /**********************************************************************/
        function fetch_options()
        {
            if (!$this->hook) return false;

            $options = get_option($this->hook);

            foreach ($this->default_options as $opt_name => $option)
                $this->options[$opt_name] = (isset($options[$opt_name])) ?
                                            $options[$opt_name] :
                                            $option["default"];
        }

        /**********************************************************************/
        function save_options()
        {
            if (!$this->hook) return false;

            update_option($this->hook, $this->options);
        }

        /**********************************************************************/
        function validate_params()
        {
            if (!$this->hook) return false;

            foreach ($this->default_options as $opt_name => $option)
            {
                $error = (isset($option["error"])) ?
                         __($option["error"], $this->hook) :
                         __("Not defined or invalid parameter",
                            $this->top_hook);

                if (isset($option["stoper"]) && $option["stoper"]())
                    continue;

                if (isset($option["definedornot"]))
                {
                    $this->options[$opt_name] = isset($_POST[$opt_name]);

                    continue;
                }

                if (isset($_POST[$opt_name]))
                {
                    $this->options[$opt_name] =
                        stripslashes($_POST[$opt_name]);

                    if (isset($option["regs"]))
                        foreach ($option["regs"] as $reg)
                            if (! preg_match($reg, $this->options[$opt_name]))
                    {
                        $this->errors[$opt_name] = $error;

                        continue(2);
                    }

                    $len = mb_strlen($this->options[$opt_name], 'UTF-8');

                    if (isset($option["minlen"]) && $len < $option["minlen"])
                    {
                        $this->errors[$opt_name] = $error;

                        continue;
                    }

                    if (isset($option["maxlen"]) && $len > $option["maxlen"])
                    {
                        $this->errors[$opt_name] = $error;

                        continue;
                    }

                    if (isset($option["max"]) || isset($option["min"]))
                    {
                        if (! preg_match("/^\d+$/", $this->options[$opt_name]))
                        {
                            $this->errors[$opt_name] = $error;

                            continue;
                        }

                        if (isset($option["max"]) &&
                            $this->options[$opt_name] > $option["max"])
                        {
                            $this->errors[$opt_name] = $error;

                            continue;
                        }

                        if (isset($option["min"]) &&
                            $this->options[$opt_name] < $option["min"])
                        {
                            $this->errors[$opt_name] = $error;

                            continue;
                        }
                    }
                }
                else
                {
                    if (isset($option["required"]))
                    {
                        $this->errors[$opt_name] = $error;

                        continue;
                    }

                    if (isset($option["notdefined"]))
                    {
                        $this->options[$opt_name] =
                            (isset($option["notdefined"])) ?
                            $option["notdefined"] :
                            $option["default"];
                    }
                }

                if (isset($option["callback"]) &&
                    is_callable($option["callback"]))
                        $option["callback"]($this);
            }
        }

        /**********************************************************************/
        function action_links($links, $file)
        {
            static $this_plugin;

            if (empty($this_plugin)) $this_plugin = $this->filename;

            if ($file == $this_plugin)
            {
                $settings_link = '<a href="' .
                               admin_url('admin.php?page=' . $this->hook) .
                               '">' . __('Settings') . '</a>';
                array_unshift( $links, $settings_link );

                foreach ($links as $key => $value)
                    if (preg_match("/plugin-editor\.php/", $value))
                        unset($links[$key]);
            }

            return $links;
        }

        /**********************************************************************/
        function zamango_page()
        {
            $rows = array();
            $rows = apply_filters('zamango-plugins', $rows);

            $this->postbox('zamango-plugins',
                           __('Active Zamango plugins', $this->top_hook),
                           $this->table($rows, 'col',
                                        'zamango-plugins-table', ''));
        }

        /**********************************************************************/
        function zmg_plugin($rows)
        {
            $row      = array();
            $row[0][] = array(__($this->page_title, $this->hook) . " v." .
                                 $this->version);
            $row[0][] = array('<a href="' . admin_url('admin.php?page=' .
                              $this->hook) . '">' . __('Settings') . '</a>');

            $rows[] = $row;

            return $rows;
        }

        /**********************************************************************/
        function options_page()
        {
            echo "<div class='wrap'>";

            $this->zmg_header();

            echo "<div id='zmg_central'>";
            echo "<div id='zmg_container' class='postbox-container " .
                 "metabox-holder meta-box-sortables ui-sortable'>";

            do_action("zmg-plugin-options-" . $this->hook);

            echo "</div>";

            if (!$this->no_promo)
            {
                echo "<div id='zmg_adds' class='postbox-container " .
                     "metabox-holder meta-box-sortables ui-sortable'>";
                echo "<div id='zmg_adds_content' style='display:none'>";

                $this->adds();
                $this->postbox('', '', '');

                echo "</div>";
                echo "</div>";
            }
            else
            {
                ?>
                    <script type="text/javascript"><!--
                        jQuery(document).ready(function(){
                            jQuery('#zmg_container').css('width', '100%');
                        });
                    //--></script>
                <?
            }

            echo "<div class='zmg_clear'></div>";
            echo "</div>";

            $this->zmg_footer();

            echo "</div>";

            $this->print_descriptions();
        }

        /**********************************************************************/
        function adds()
        {
            $url = 'http://account.zamango.com/json/ads?language=' . WPLANG;

            $opts = array (
                'method'     => 'GET',
                'timeout'    => 60,
                'user-agent' => 'Mozilla/5.0 (compatible; ' . $this->page_title
                                . '/' . $this->version .
                                '; +http://www.zamango.com/about-zamango.html)',
                'headers'    => array('Referer' => get_option('siteurl'))
            );

            $response = wp_remote_request($url, $opts);

            if (is_object($response) && $response->errors)
            {
                echo (array_key_exists('http_request_failed', $response->errors))
                   ? $response->errors['http_request_failed'][0]
                   : 'Unknown error';
                return false;
            }

            if ($response['response']['code'] != 200)
            {
                echo $response['response']['code'] . ': ' .
                     $response['response']['message'];
                return false;
            }

            $body_len = $response['headers']['content-length'];
            $real_len = strlen($response['body']);

            if ($body_len != $real_len)
            {
                echo "Fetched only $real_len of $body_len bytes";
                return false;
            }

            echo "<script type='text/javascript'>\n window.zmg_adds = " .
                 $response['body'] . "\n</script>";/**/
        }

        /**********************************************************************/
        function zmg_header()
        {
            ?>
                <div id="zmg_header">
                    <div id="zmg_logo_title">
                        <div id="zmg_logo">
                            <a target="_blank" href="http://www.zamango.com/">
                                <img src="<?= ZMG_LOGO /*?>*/?>"
                                     alt="Zamango" />
                            </a>
                        </div>
                        <div id="zmg_title">
                            <? _e($this->page_title, $this->hook); ?>
                        </div>
                    </div>
                </div>
            <?
                if ($this->no_promo) return true;
            ?>
                <div id='zmg_header_container'
                     class='postbox-container metabox-holder meta-box-sortables'>
                    <div id="zmg_header_baner" class="postbox">
                        <div class="handlediv" title="Click to toggle">
                            <br />
                        </div>
                        <h3 class="hndle">
                            <span>Zamango Money Extractor</span>
                        </h3>
                        <div class="inside">
                            <div class="in-baner" id="zmg_header_baner_left">
                                <a href="http://wordpress.org/extend/plugins/zamango-money-extractor/"
                                   target=_blank title="<?
                                        _e('Sell software from choosen ' .
                                           'vendors by only one plugin',
                                           $this->top_hook);
                                /*?>*/ ?>">
                                <?
                                    _e('Wanna monetize your blog? It\'s ' .
                                       'easy! Just install Zamango Money ' .
                                       'Extractor plugin and get easy money!',
                                       $this->top_hook);
                                ?>
                                </a>
                            </div>
                            <div class="in-baner" id="zmg_header_baner_right">
                                <p>
                                    <?
                                        _e('Zamango is the free-of-charge ' .
                                           'content agregator, i.e. free '.
                                           'public service which composes ' .
                                           'content (casual games ' .
                                           'in downloadable and web-based ' .
                                           'forms) released by different ' .
                                           'world-known publishers like ' .
                                           'BigFishGames or Reflexive. It ' .
                                           'gives an opportunity to build ' .
                                           'your own reach storefront in ' .
                                           'minutes using ready toolbox ' .
                                           '(WordPress + Zamango Money ' .
                                           'Extractor plugin) and start ' .
                                           'making money on their affiliate ' .
                                           'programs.', $this->top_hook);
                                    ?>
                                </p>
                            </div>
                            <div class="zmg_clear"></div>
                        </div>
                    </div>
                </div>
                <div class="zmg_clear"></div>
            <?
        }

        /**********************************************************************/
        function zmg_footer()
        {
            ?>
                <div id="zmg_footer">
                    <div id="zmg_developed">
                        <? _e('Developed by', $this->top_hook); ?>
                        <a href="http://www.zamango.com" target=_blank>
                            Zamango
                        </a>
                    </div>
                </div>
            <?
        }

        /**********************************************************************/
        function form_begin($addon = '')
        {
            echo '<form action="" method="post">';
            echo $addon;
        }

        /**********************************************************************/
        function form_end()
        {
            echo '</form>';
        }

        /**********************************************************************/
        function hidden($name, $value = '1')
        {
            $id = $this->hook . "-" . $name;

            $content  = '<input type="hidden"' .
                               ' id="' . $id . '"' .
                               ' name="' . $name . '"' .
                               ' value="' . htmlspecialchars($value) . '" />';

            return $content;
        }

        /**********************************************************************/
        function checkbox($name, $value, $checked, $label = '', $class = '')
        {
            $id = $this->hook . "-" . $name;

            $content  = '<input type="checkbox"' .
                               'id="' . $id . '"';
            $content .= $class ? ' class="' . $class . '"' : '';
            $content .=        ' name="' . $name . '"' .
                               ' value="' . $value . '"';
            $content .= $checked ? ' checked="checked" />' : ' />';
            $content .= $label ? '<label for="' . $id . '">' . $label .
                                 '</label>' : '';

            return $content;
        }

        /**********************************************************************/
        function text($name, $value, $label = '',
                      $class = 'zmg_long_text_input')
        {
            $id = $this->hook . "-" . $name;

            $content  = '<input type="text"' .
                               ' id="' . $id . '"';
            $content .= $class ? ' class="' . $class . '"' : '';
            $content .=        ' name="' . $name . '"' .
                               ' value="' . htmlspecialchars($value) . '" />';
            $content .= $label ? '<label for="' . $id . '">' . $label .
                                 '</label>' : '';

            return $content;
        }

        /**********************************************************************/
        function textarea($name, $value, $rows = '', $cols = '', $label = '',
                          $class = '')
        {
            $id = $this->hook . "-" . $name;

            $content  = '<textarea id="' . $id . '"';
            $content .= $rows ? ' rows="' . $rows . '"' : '';
            $content .= $cols ? ' cols="' . $cols . '"' : '';
            $content .= $class ? ' class="' . $class . '"' : '';
            $content .=        ' name="' . $name . '">';
            $content .= htmlspecialchars($value) . '</textarea>';
            $content .= $label ? '<label for="' . $id . '">' . $label .
                                 '</label>' : '';

            return $content;
        }

        /**********************************************************************/
        function radio($name, $value, $checked, $label = '', $class = '')
        {
            $id = $this->hook . "-" . $name . "-" . $value;

            $content  = '<input type="radio"' .
                               'id="' . $id . '"';
            $content .= $class ? ' class="' . $class . '"' : '';
            $content .=        'name="' . $name . '"' .
                               'value="' . $value . '"';
            $content .= $checked ? 'checked="checked" />' : ' />';
            $content .= $label ? '<label for="' . $id . '">' . $label .
                                 '</label>' : '';

            return $content;
        }

        /**********************************************************************/
        function select($name, $value, $values, $options = array(),
                        $label = '', $class = '')
        {
            if (!$options) $options = $values;

            if (count ($options) < count($values)) return;

            $id = $this->hook . "-" . $name . "-" . $value;

            $content  = '<select name="' . $name . '" id="' . $id . '"';
            $content .= $class ? ' class="' . $class . '">' : '>';

            foreach ($values as $key => $val)
            {
                $content .= '<option value="' . $val . '"';
                $content .= ($val == $value) ? ' selected="selected">' : '>';
                $content .= $options[$key];
                $content .= '</option>';
            }

            $content .= '</select>';

            return $content;
        }

        /**********************************************************************/
        function submit($value, $class = 'button-primary')
        {
            $content  = '<input type="submit"';
            $content .= $class ? ' class="' . $class . '"' : '';
            $content .=        ' value="' . htmlspecialchars($value) . '" />';

            return $content;
        }

        /**********************************************************************/
        function ul($items, $diplay = 'col', $id = '', $class = '')
        {
            if ($diplay == 'row') $class .= ' zmg_ul_inline';
            if (! $items) return false;

            $content  = '<ul ';
            $content .= $id ? 'id="' . $id . '"' : '';
            $content .= $class ? ' class="' . $class . '">' : '>';

            foreach ($items as $item)
            {
                if (! $item[0]) continue;

                $content .= '<li ';
                $content .= $item['id']    ? 'id="' . $item['id'] . '"' : '';
                $content .= $item['class'] ? ' class="' . $item['class'] . '">'
                                           : '>';
                $content .= $item[0] . '</li>';
            }

            $content .= '</ul>';

            return $content;
        }

        /**********************************************************************/
        function table($rows, $thp = 'col', $id = '', $class = 'form-table')
        {
            $content  = '<table ';
            $content .= $id ? 'id="' . $id . '"' : '';
            $content .= $class ? ' class="' . $class . '">' : '>';

            switch ($thp)
            {
                case 'col' :

                    foreach ($rows as $tr)
                    {
                        $content .= '<tr ';
                        $content .= $tr['id'] ? 'id="' . $tr['id'] . '"' : '';
                        $content .= $tr['class'] ? ' class="' . $tr['class'] .
                                                   '">' : '>';

                        $th = $tr[0][0];

                        $content .= '<th ';
                        $content .= $th['id'] ? 'id="' . $th['id'] . '"' : '';
                        $content .= $th['class'] ? ' class="' . $th['class'] .
                                                   '">' : '>';
                        $content .= $th[0];
                        $content .= '</th>';

                        for ($i = 1; $i < count($tr[0]); $i++)
                        {
                            $td = $tr[0][$i];

                            $content .= '<td ';
                            $content .= $td['id'] ? 'id="' . $td['id'] . '"'
                                                  : '';
                            $content .= $td['class'] ? ' class="' . $td['class']
                                                     . '">' : '>';
                            $content .= $td[0];
                            $content .= '</td>';
                        }

                        $content .= '</tr>';
                    }

                break;

                case 'row' :

                    $tr = $rows[0];

                    $content .= '<tr ';
                    $content .= $tr['id'] ? 'id="' . $tr['id'] . '"' : '';
                    $content .= $tr['class'] ? ' class="' . $tr['class'] .
                                               '">' : '>';

                    foreach ($tr[0] as $th)
                    {
                        $content .= '<th ';
                        $content .= $th['id'] ? 'id="' . $th['id'] . '"'
                                              : '';
                        $content .= $th['class'] ? ' class="' . $th['class']
                                                 . '">' : '>';
                        $content .= $th[0];
                        $content .= '</th>';
                    }

                    $content .= '</tr>';

                    for ($i = 1; $i < count($rows); $i++)
                    {
                        $tr = $rows[$i];

                        $content .= '<tr ';
                        $content .= $tr['id'] ? 'id="' . $tr['id'] . '"' : '';
                        $content .= $tr['class'] ? ' class="' . $tr['class'] .
                                                   '">' : '>';

                        foreach ($tr[0] as $td)
                        {
                            $content .= '<td ';
                            $content .= $td['id'] ? 'id="' . $td['id'] . '"'
                                                  : '';
                            $content .= $td['class'] ? ' class="' . $td['class']
                                                     . '">' : '>';
                            $content .= $td[0];
                            $content .= '</td>';
                        }

                        $content .= '</tr>';
                    }

                break;

                case 'none' :

                    foreach ($rows as $tr)
                    {
                        $content .= '<tr ';
                        $content .= $tr['id'] ? 'id="' . $tr['id'] . '"' : '';
                        $content .= $tr['class'] ? ' class="' . $tr['class'] .
                                                   '">' : '>';

                        foreach ($tr[0] as $td)
                        {
                            $content .= '<td ';
                            $content .= $td['id'] ? 'id="' . $td['id'] . '"'
                                                  : '';
                            $content .= $td['class'] ? ' class="' . $td['class']
                                                     . '">' : '>';
                            $content .= $td[0];
                            $content .= '</td>';
                        }

                        $content .= '</tr>';
                    }

                break;
            }

            $content .= '</table>';

            return $content;
        }

        /**********************************************************************/
        function elem($item, $id = '', $class = '')
        {
            return array($item, 'id' => $id, 'class' => $class);
        }

        /**********************************************************************/
        function disappearing_message($message)
        {
            $content  = '<div class="zmg_warning fade"><p>';
            $content .= $message;
            $content .= '</p></div>';

            return $content;
        }

        /**********************************************************************/
        function error_message($message)
        {
            $content  = '<div class="zmg_error"><p>';
            $content .= $message;
            $content .= '</p></div>';

            return $content;
        }

        /**********************************************************************/
        function information_message($message)
        {
            $content  = '<div class="zmg_updated"><p>';
            $content .= $message;
            $content .= '</p></div>';

            return $content;
        }

        /**********************************************************************/
        function add_description($title, $content, $id = '')
        {
            if (!$id) $id = 'zmg_description-' .count($this->descriptions);
            else
            {
                $id = 'zmg_description-' . $id;

                foreach ($this->descriptions as $desc)
                    if ($desc['id'] == $id) return false;
            }

            $this->descriptions[] = array('id'      => $id,
                                          'title'   => $title,
                                          'content' => $content);

            return "zmg_show_help('" . $id . "'); return false;";
        }

        /**********************************************************************/
        function print_descriptions ()
        {
            if (!$this->descriptions || count($this->descriptions) == 0)
                return false;

            foreach ($this->descriptions as $desc)
            {
                $onclick = "zmg_hide_help('" . $desc['id'] . "');"
                          ."return false;";
                $close   = ZMG_ADMIN_URL . '/img/close.png';
                ?>
                    <div id="<?= $desc['id'] /*?>*/?>"
                         class="zmg_description">
                        <div class="zmg_desc_overlay"
                             onclick="<?= $onclick /*?>*/?>"></div>
                        <div class="zmg_desc_window">
                            <div class="zmg_desc_top">
                                <div class="zmg_desc_close"
                                     onclick="<?= $onclick /*?>*/?>"
                                     style="<?= $close /*?>*/?>">
                                     <img src="<?= $close /*?>*/?>" />
                                </div>
                                <h3 class="zmg_desc_title">
                                    <?= $desc['title'] ?>
                                </h3>
                            </div>
                            <div id="<?= $desc['id'] ?>-content"
                                 class="zmg_desc_content">
                                <?= $desc['content'] ?>
                            </div>
                        </div>
                    </div>
                <?
            }
        }

        /**********************************************************************/
        function postbox($id, $title, $content)
        {
            ?>
                <div <?= $id ? 'id="' . $id . '"' : '' ?> class="postbox">
                    <div class="handlediv" title="Click to toggle"><br /></div>
                    <h3 class="hndle"><span><?= $title ?></span></h3>
                    <div class="inside">
                        <?= $content ?>
                    </div>
                </div>
            <?
        }
    }

    /**************************************************************************/
    $zmg_admin = new zmg_admin();
}

