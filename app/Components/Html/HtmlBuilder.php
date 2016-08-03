<?php
namespace App\Components\Html;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Session\SessionManager ;
use Illuminate\Config\Repository;
/**
 * FormBuilder.
 *
 * 自定义模板的 Form 控件
 * @author davin.bao
 * @since 2016/7/15 9:34
 */
class HtmlBuilder extends \Collective\Html\HtmlBuilder {
    use CentaurusHtmlTrait;
    /**
     * @var SessionManager
     */
    protected $session;

    /**
     * @var Repository
     */
    protected $config;

    public function __construct(UrlGenerator $url = null, SessionManager $session, Repository $config)
    {
        $this->session = $session;
        $this->config = $config;
        $this->url = $url;
        parent::__construct($url);
    }

}