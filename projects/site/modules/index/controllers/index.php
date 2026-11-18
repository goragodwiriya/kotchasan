<?php
/**
 * @filesource site/modules/index/controllers/index.php
 */

namespace Index\Index;

use Kotchasan\Http\Request;
use Kotchasan\Http\Response;
use Kotchasan\Language;
use Kotchasan\Template;
use Kotchasan\View;

/**
 * The default controller. It answers every page of the site.
 *
 * index.php?module=about shows the page "about": its content is
 * themes/default/about.html, put into the layout themes/default/index.html.
 */
class Controller extends \Kotchasan\Controller
{
    /**
     * The pages in menu order: module => title (a language key).
     *
     * @var array
     */
    const PAGES = [
        'home' => 'Home',
        'drinks' => 'Our menu',
        'about' => 'About us',
        'contact' => 'Contact'
    ];

    /**
     * The drinks on the menu page: [name, description, price].
     * A real site would read them from a database (see the database example).
     *
     * @var array
     */
    const DRINKS = [
        ['Espresso', 'A short, strong shot of our house blend', 55],
        ['Thai iced coffee', 'Dark roast with sweet condensed milk over ice', 65],
        ['Iced latte', 'A double shot with cold fresh milk', 70],
        ['Cold brew', 'Steeped for 16 hours, smooth and light', 80],
        ['Thai tea', 'Brewed strong and topped with fresh milk', 60],
        ['Cocoa', 'Rich dark chocolate, hot or iced', 60]
    ];

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // Templates are read from themes/default/ ('skin' in settings/config.php)
        Template::init(self::$cfg->skin);

        // index.php?module=about. No module is the home page.
        $module = $request->get('module', 'home')->filter('a-z');
        if (isset(self::PAGES[$module])) {
            $title = self::PAGES[$module];
            // Template::load() returns themes/default/{module}.html as it is
            $content = $module === 'drinks' ? $this->drinks() : Template::load('', '', $module);
            $status = 200;
        } else {
            $title = 'Page not found';
            $content = Template::load('', '', 'notfound');
            $status = 404;
        }

        // Values for the {...} placeholders of the layout
        $view = View::create();
        $view->setContents([
            '/{LANGUAGE}/' => Language::name(),
            '/{TITLE}/' => '{LNG_'.$title.'}',
            '/{MENU}/' => $this->menu($module),
            '/{LANGUAGES}/' => $this->languages($module),
            '/{CONTENT}/' => $content,
            '/{YEAR}/' => date('Y'),
            '/{VERSION}/' => VERSION
        ]);

        // renderHTML() reads themes/default/index.html and also fills {WEBTITLE},
        // {WEBURL} and {SKIN}. Language::trans() then replaces every {LNG_...}
        // with the text of the current language (language/th.php or en.php).
        return (new Response())->html(Language::trans($view->renderHTML()), $status);
    }

    /**
     * The links of the main menu. The page being shown is marked "active".
     *
     * @param string $module
     *
     * @return string
     */
    private function menu($module)
    {
        $html = '';
        foreach (self::PAGES as $name => $label) {
            $current = $name === $module ? ' class="active" aria-current="page"' : '';
            $html .= '<a href="'.self::url($name).'"'.$current.'>{LNG_'.$label.'}</a>';
        }

        return $html;
    }

    /**
     * TH / EN links. Kotchasan\Language reads ?lang= and keeps the choice in a cookie.
     *
     * @param string $module
     *
     * @return string
     */
    private function languages($module)
    {
        $html = '';
        foreach (self::$cfg->languages as $lang) {
            $current = $lang === Language::name() ? ' class="active" aria-current="true"' : '';
            $html .= '<a href="'.self::url($module, $lang).'"'.$current.' hreflang="'.$lang.'">'.strtoupper($lang).'</a>';
        }

        return $html;
    }

    /**
     * The menu page: one copy of themes/default/drink.html for every drink.
     *
     * @return string
     */
    private function drinks()
    {
        $rows = Template::create('', '', 'drink');
        foreach (self::DRINKS as $drink) {
            $rows->add([
                '/{NAME}/' => '{LNG_'.$drink[0].'}',
                '/{DETAIL}/' => '{LNG_'.$drink[1].'}',
                '/{PRICE}/' => $drink[2]
            ]);
        }

        return Template::create('', '', 'drinks')->add([
            '/{DRINKS}/' => $rows->render()
        ])->render();
    }

    /**
     * URL of a page, e.g. url('about') is index.php?module=about.
     *
     * @param string $module
     * @param string $lang
     *
     * @return string
     */
    private static function url($module, $lang = '')
    {
        $query = [];
        if ($module !== 'home' && isset(self::PAGES[$module])) {
            $query['module'] = $module;
        }
        if ($lang !== '') {
            $query['lang'] = $lang;
        }

        return 'index.php'.(empty($query) ? '' : '?'.http_build_query($query, '', '&amp;'));
    }
}
