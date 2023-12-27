<?php
namespace Jguillaumesio\PhpMercureHub\Controllers;

use Ubiquity\controllers\Controller;

/**
 * Controller IndexController
 */
class UbiquityHubController extends Controller {

    public function index() {}

    public function publish() {
        (new HubController())->publication();
    }

    public function subscribe() {
        (new HubController())->subscription();
    }

    public function topic($url) {
        (new HubController())->topic($url);
    }
}
