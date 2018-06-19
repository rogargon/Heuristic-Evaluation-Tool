<?php

  /**
   * Uri Handler -> Call controller
   */
  class UriHandler
  {
    // Defined Uris and minmium role to acces in
    private $uris = array(
      "/" => -1,
      "/signin" => -1,
      "/forgot" => -1,
      "/forgot/send" => -1,
      "/forgot/reset" => -1,
      "/account/login" => -1,
      "/account/logout" => 0,
      "/account/profile" => 0,
      "/account/update-profile" => 0,
      "/account/update-password" => 0,
      "/my-projects" => 1,
      "/projects/new" => 1,
      "/projects/edit" => 1,
      "/projects/add" => 1,
      "/projects/update" => 1,
      "/admin/projects" => 2,
      "/admin/update-project" => 1,
      "/admin/update-profile" => 2,
      "/admin/users" => 2,
      "/admin/profile" => 2,
      "/admin/new-user" => 2,
      "/admin/add-user" => 2,
      "/admin/remove-user" => 2,
      "/admin/template" => 2,
      "/admin/set-category" => 2,
      "/admin/importcsv" => 2
    );

    private $uri;

    function __construct($uri) {
      $this->uri = $uri;
    }

    function execute() {

      // 1- Firstly, check if Uri exists
      if (!isset($this->uris[$this->uri])) {
        $view = new BaseController("404","");
        if ($this->uri != "/404"){
          $view->requestedUri = $this->uri;
        }
        $view->render();
      }

      // 2- Check Uri and permissions
      if (!$this->checkUriAndPermissions()) {
        // Can't access or Uri doesn't exist!!
        $view = new BaseController("login","signin");
        $view->required = true;
        $view->render();
      }

      // 3- Load Controller
      if ($this->uri == "/") {
        $view = new BaseController("index","");
        $view->render();
      } else if ($this->uri == "/signin") {
        $view = new BaseController("login","signin");
        $view->render();
      }  else if ($this->uri == "/forgot") {
        $view = new BaseController("login","forgot");
        $view->render();
      } else if ($this->uri == "/forgot/send") {
        $controller = new AccountController();
        $controller->forgot();
      } else if ($this->uri == "/forgot/reset") {
        $controller = new AccountController();
        $controller->reset();
      } else if ($this->uri == "/account/login") {
        $controller = new AccountController();
        $controller->login();
      } else if ($this->uri == "/account/logout") {
        $controller = new AccountController();
        $controller->logout();
      } else if ($this->uri == "/account/profile") {
        $controller = new AccountController();
        $controller->showProfile(false);
      } else if ($this->uri == "/account/update-profile") {
        $controller = new AccountController();
        $controller->updateProfile(false);
      } else if ($this->uri == "/account/update-password") {
        $controller = new AccountController();
        $controller->updatePassword();
      } else if ($this->uri == "/my-projects") {
        $controller = new ProjectController();
        $controller->showProjectList(false);
      } else if ($this->uri == "/projects/new") {
        $controller = new ProjectController();
        $controller->addNewProjectView();
      } else if ($this->uri == "/projects/edit") {
        $controller = new ProjectController();
        $controller->updateProjectView(false, $_GET["param"]);
      } else if ($this->uri == "/projects/update") {
        $controller = new ProjectController();
        $controller->updateProject(false);
      } else if ($this->uri == "/projects/add") {
        $controller = new ProjectController();
        $controller->addNewProject();
      } else if ($this->uri == "/admin/projects") {
        $controller = new ProjectController();
        if (empty($_GET["param"])) {
          $controller->showProjectList(true);
        } else {
          $controller->updateProjectView(true, $_GET["param"]);
        }
      } else if ($this->uri == "/admin/update-project") {
        $controller = new ProjectController();
        $controller->updateProject(true);
      } else if ($this->uri == "/admin/update-profile") {
        $controller = new AccountController();
        $controller->updateProfile(true);
      } else if ($this->uri == "/admin/users") {
        $controller = new AccountController();
        $controller->showUserList();
      } else if ($this->uri == "/admin/profile") {
        $controller = new AccountController();
        $controller->showProfile(true);
      } else if ($this->uri == "/admin/new-user") {
        $controller = new AccountController();
        $controller->addNewUserView();
      } else if ($this->uri == "/admin/add-user") {
        $controller = new AccountController();
        $controller->addNewUser();
      } else if ($this->uri == "/admin/remove-user") {
        $controller = new AccountController();
        $controller->removeUser();
      } else if ($this->uri == "/admin/template") {
        $controller = new TemplateController();
        $controller->showTemplate(0);
      } else if ($this->uri == "/admin/set-category") {
        $controller = new TemplateController();
        $controller->setCategory();
      } else if ($this->uri == "/admin/importcsv") {
        $controller = new ImportCSVController();
        $controller->newImport();
      } else {
        include(BASE_URI."view/index.php");
      }

    }

    function checkUriAndPermissions() {
      if ((!isset($GLOBALS["USER_SESSION"])) && ($this->uri == "/")) { // First page
        header("Location: /signin");
      } else if ($this->uris[$this->uri] < 0) { // User tries to access public content
        return true;
      } else if ((!isset($GLOBALS["USER_SESSION"])) || ($this->uris[$this->uri] > $GLOBALS["USER_SESSION"]->getRole())) { // User tries to access private content without a minumum role level
        return false;
      } else {
        return true;
      }
    }
  }
?>
