<?php

namespace App\Http\Controllers\Wordpress;

use App\Http\Controllers\Controller;
use Automattic\WooCommerce\Client;
use GuzzleHttp\Client as Guzzle;
use Illuminate\Http\Request;
use php_rutils\Rutils;

// use WooCommerce;

/**
 * Controller to iteract with wordpress woocomerce
 */
class WoocomerceController extends Controller
{
    protected $nomadUri = "http://localhost/nomad/wp-json/wp/v2/";
    protected $woocommerce;

    protected $nomad;

    protected $base64;

    protected $restApiClient;

    public $localShop = "http://localhost/nomad";

    public $remoteShop = "http://nomadsynergy.kz";

    public function __construct()
    {
        $this->base64      = base64_encode("super:super");
        $this->woocommerce = new Client("http://localhost/nomad",
            "ck_5c17b31649c496ad475980cdee2e0b6c0f223330",
            "cs_36c1dc83b4aa012e67dc53cd460bec3e87118bb3"
        );

        $this->nomad = new Client("http://nomadsynergy.kz",
            "ck_63ab9cbb9f39036142a8fd3123db1b5b45317a34",
            "cs_c66bf6869c67bacad335427cc1946ea623641a72"
        );

        $this->restApiClient = new Guzzle([
            "base_uri" => $this->nomadUri,
            "timeout"  => 40.0,
            'headers'  => ['Content-Type' => 'application/json', "Accept" => "application/json", 'Authorization' => "Basic " . $this->base64],
            // "auth"     => [
            //     "admin",
            //     "admin",
            // ],
        ]);
    }

    public function test()
    {
        $this->parseSvetApi();
        // $this->clearProducts();
    }

    /**
     * parse optimum svet and paste data to wordpress
     * @return [type] [description]
     */
    public function parseSvetApi()
    {
        set_time_limit(0);
        include_once "simplehtmldom/simple_html_dom.php";
        $base_url = "http://www.svet.optimum74.ru";
        $html     = file_get_html("http://www.svet.optimum74.ru/catalog/");
        $links    = [];

        foreach ($html->find("ul.products-list li a") as $link) {
            // add all links to catalog items to array
            $links[] = $link->href;
            \Log::info('"Just another parse ink for svet is $link"');
        }

        $items = [];

        foreach ($links as $link) {
            // create base link to parse
            $link_html = file_get_html($base_url . $link);

            //items title
            $itemTitle = $link_html->find("#content h1")[0]->plaintext; //find items title
            \Log::info('Product to add: ' . $itemTitle);

            $imgSrc = $link_html->find('.img-holder img')[0]->attr['src'];
            $imageName = '2017/05/' . Rutils::translit()->slugify($itemTitle) . '.png';
            $slugified = Rutils::translit()->slugify($itemTitle);
            \Storage::put($imageName, file_get_contents($base_url . $imgSrc));

            $data["product"] = [
                "title"              => "$itemTitle",
                "type"               => "simple",
                "status"             => "publish",
                "downloadable"       => false,
                "virtual"            => false,
                "permalink"          => $this->remoteShop . "/shop/" . $slugified,
                "sku"                => "",
                "price"              => "",
                "regular_price"      => "",
                "sale_price"         => null,
                "price_html"         => "",
                "taxable"            => true,
                "tax_status"         => "taxable",
                "tax_class"          => "",
                "managing_stock"     => false,
                "stock_quantity"     => null,
                "in_stock"           => true,
                "backorders_allowed" => false,
                "backordered"        => false,
                "sold_individually"  => false,
                "purchaseable"       => false,
                "featured"           => false,
                "visible"            => true,
                "catalog_visibility" => "visible",
                "on_sale"            => false,
                "product_url"        => "",
                "button_text"        => "",
                "weight"             => null,
                "shipping_required"  => true,
                "shipping_taxable"   => true,
                "shipping_class"     => "",
                "shipping_class_id"  => null,
                "description"        => $link_html->find(".item .info")[0]->outertext,
                "short_description"  => "",
                "reviews_allowed"    => true,
                "average_rating"     => "0.00",
                "rating_count"       => 0,
                "related_ids"        => [],
                "upsell_ids"         => [],
                "cross_sell_ids"     => [],
                "parent_id"          => 0,
                "categories"         => [],
                "tags"               => [],
                "images"             => [[
                    // "id"         => 14,
                    // "created_at" => "2017-05-11T08:27:02Z",
                    // "updated_at" => "2017-05-11T08:27:02Z",
                    "src"        => $this->remoteShop . $imagePath,
                    "title"      => $slugified,
                    "alt"        => $itemTitle,
                    "position"   => 0,
                ]],
                // "featured_src"       => "http://localhost/nomad/wp-content/uploads/2017/05/ZDMcAkCVqk.jpg",
                "featured_src"       => $this->remoteShop . $imagePath,
                "attributes"         => [],
                "downloads"          => [],
                "download_limit"     => -1,
                "download_expiry"    => -1,
                "download_type"      => "standard",
                "purchase_note"      => "",
                "total_sales"        => 0,
                "variations"         => [],
                "parent"             => [],
                "grouped_products"   => [],
                "menu_order"         => 0,
            ];
            $this->woocommerce->post("products", $data);
        }

    }

    public function testFtp()
    {
        $res = \FTP::connection("smartsol")->getDirListing();
        dd($res);
    }

    public function saveRequest(Request $req)
    {}

    public function woo()
    {
        $title           = "Some product";
        $data["product"] = [
            // "id"                 => 12312321,
            "title"              => "Some product",
            // "id"                 => 166,
            "type"               => "simple",
            "status"             => "publish",
            "downloadable"       => false,
            "virtual"            => false,
            "permalink"          => "http://localhost/nomad/shop/$title",
            "sku"                => "",
            "price"              => "",
            "regular_price"      => "",
            "sale_price"         => null,
            "price_html"         => "",
            "taxable"            => true,
            "tax_status"         => "taxable",
            "tax_class"          => "",
            "managing_stock"     => false,
            "stock_quantity"     => null,
            "in_stock"           => true,
            "backorders_allowed" => false,
            "backordered"        => false,
            "sold_individually"  => false,
            "purchaseable"       => false,
            "featured"           => false,
            "visible"            => true,
            "catalog_visibility" => "visible",
            "on_sale"            => false,
            "product_url"        => "",
            "button_text"        => "",
            "weight"             => null,
            "shipping_required"  => true,
            "shipping_taxable"   => true,
            "shipping_class"     => "",
            "shipping_class_id"  => null,
            "description"        => "<p>sdsadsadsa</p>\n",
            "short_description"  => "",
            "reviews_allowed"    => true,
            "average_rating"     => "0.00",
            "rating_count"       => 0,
            "related_ids"        => [],
            "upsell_ids"         => [],
            "cross_sell_ids"     => [],
            "parent_id"          => 0,
            "categories"         => [],
            "tags"               => [],
            "images"             => [[
                "id"         => 14,
                "created_at" => "2017-05-11T08:27:02Z",
                "updated_at" => "2017-05-11T08:27:02Z",
                "src"        => "http://localhost/nomad/wp-content/uploads/2017/05/ZDMcAkCVqk.jpg",
                "title"      => "_ZDMcAkCVqk",
                "alt"        => "",
                "position"   => 0,
            ]],
            "featured_src"       => "http://localhost/nomad/wp-content/uploads/2017/05/ZDMcAkCVqk.jpg",
            "attributes"         => [],
            "downloads"          => [],
            "download_limit"     => -1,
            "download_expiry"    => -1,
            "download_type"      => "standard",
            "purchase_note"      => "",
            "total_sales"        => 0,
            "variations"         => [],
            "parent"             => [],
            "grouped_products"   => [],
            "menu_order"         => 0,
        ];
        // dd($this->woocommerce->get("products"));
        $this->woocommerce->post("products", $data);
    }

    public function restApiPost()
    {
        $params = array(
            "title"   => "Hello Updated World!",
            "content" => "Howdy updated content.",
            "type"    => "product",
        );
        // dd(json_encode($params));
        $response = $this->restApiClient->post('posts',
            ['body' => json_encode($params)]
        );
        echo "<pre>";
        echo $response->getBody();
    }

    public function parseSvet()
    {
        include_once "simplehtmldom/simple_html_dom.php";
        $base_url = "http://www.svet.optimum74.ru";
        $html     = file_get_html("http://www.svet.optimum74.ru/catalog/");
        $links    = [];

        foreach ($html->find("ul.products-list li a") as $link) {
            // add all links to catalog items to array
            $links[] = $link->href;
        }

        $items = [];

        foreach ($links as $link) {
            $link_html = file_get_html($base_url . $link);
            $imgSrc    = $link_html->find('.img-holder img')[0]->attr['src'];
            $cat_id    = 5;

            $item              = new Product();
            $item->title       = $link_html->find("#content h1")[0]->plaintext;
            $item->category_id = $cat_id;
            $item->description = $link_html->find(".item .info")[0]->outertext;
            $item->imagePath   = 'preview.png';
            $item->slug        = Rutils::translit()->slugify($link_html->find("#content h1")[0]->plaintext);
            $item->save();
            $items[] = $item;
            Storage::put('cat-5/' . $item->slug . '/preview.png', file_get_contents($base_url . $imgSrc));
        }

    }

    public function clearProducts()
    {
        set_time_limit(0);
        $d           = $this->woocommerce->get("products");
        $idsToDelete = [];

        foreach ($d["products"] as $product) {
            $idsToDelete[] = $product["id"];
        }

        foreach ($idsToDelete as $key) {
            $this->woocommerce->delete("products/$key", ["force" => true]);
        }
        \Log::notice("Products removed from database");
    }

}
