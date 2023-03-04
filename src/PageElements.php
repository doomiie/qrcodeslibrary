<?php

/**
 * Page Elements for printing
 * 
 * @see       https://github.com/doomiie/gps/
 *
 *
 * @author    Jerzy Zientkowski <jerzy@zientkowski.pl>
 * @copyright 2020 - 2023 Jerzy Zientkowski
 * @license   FIXME need to have a licence
 * @note      This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace qrcodeslibrary;

class PageElements
{
    public static function printMeta($title)
    {
        printf('<head>
        <!-- Zawartość meta tutaj -->
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="Jerzy Zientkowski CODEBOIS 2023" />
        <title>%s::CodeBois::QRCODES v2.0</title>
        <!-- css styling -->
        <link href="assets/css/fontawesome.css" rel="stylesheet">
        <link href="assets/css/brands.css" rel="stylesheet">
        <link href="assets/css/solid.css" rel="stylesheet">
        <link href="css/styles.css" rel="stylesheet" />
        <link href="css/video.css" rel="stylesheet" />
        <link href="css/lds-roller.css" rel="stylesheet" />
        
        <!-- JQUERY needs to be the first script -->
        <script type="text/javascript" src="js/jquery-3.6.3.min.js"></script>
        <script type="text/javascript" src="js/adapter.min.js"></script>
        <script type="text/javascript" src="js/vue.min.js"></script>
        <script type="text/javascript" src="js/instascan.min.js"></script>
        <script src="js/feather.min.js" crossorigin="anonymous"></script>
        <script src="js/beep.js"></script>
        <script type="text/javascript" src="js/qrcode-gps.js"></script>
        
        
    </head>', $title);
    }
    public static function printFooter($index = -1, $visible = "d-none")
    {
        //NOTE - d-none wyłącza tutaj widoczność inputu
        echo '
        <input  class="' . $visible . '" id="objectID" name="objectID" type="" value = "' . $index . '"/>
        <input  class="' . $visible . '" id="positionInfo" name="positionInfo" type="" />
        <footer class="footer-admin mt-auto footer-light">
        <div class="container-xl px-4">
            <div class="row justify-content-center">
                <div class="col-md-6 small justify-content-center  text-center">Copyright &copy; CODEBOIS Jerzy Zientkowski, Daniel Tadeusiak 2021-2023</div>
            </div>
        </div>
    </footer>';
    }
    public static function topBar1($title, $homePage = 'index.php')
    {
        PageElements::sectionHeader($title, $homePage);
        PageElements::ulGlobalListStart();
        PageElements::liGlobalListStart();
        //PageElements::liGlobalListItem("<button onClick='logoutGlobal()'>Wyloguj</button>", "title");
        PageElements::liGlobalListStop();
        PageElements::ulGlobalListStop();
    }
    public static function sectionHeader($title, $homePage = 'index.php')
    {
        printf("<a class='navbar-brand pe-3 ps-4 ps-lg-2 mr-auto' href='%s'>%s</a>",  $homePage, $title);
    }
    public static function sectionHeaderSideNav($title)
    {
        echo "<div class='sidenav-menu-heading'>$title</div>" . PHP_EOL;
    }
    public static function ulGlobalListStart()
    {
        printf('<!--ulGlobalListStart --><ul class="navbar-nav align-items-center ml-auto mr-3">');
    }
    public static function liGlobalListStart()
    {
        global $user;
        printf(
            "
        <li id='safetyMarker' class=' nav-item fa %s no-caret dropdown-user me-3 me-lg-4'></li>
        <li id='gpsMarker' class='d-none nav-item fa fa-earth no-caret dropdown-user me-3 me-lg-4'>        </li>

        <li class='nav-item  dropdown no-caret dropdown-user me-3 me-lg-4'>
        <a class='btn btn-icon btn-transparent-dark dropdown-toggle' id='navbarDropdownUserImage' href='javascript:void(0);' role='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
        <img class='img-fluid' src='assets/img/illustrations/profiles/profile-2.png' />
        </a>
        <div class='dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up' aria-labelledby='navbarDropdownUserImage'>",
            
            isset($user) ? "fa-user-lock" : "fa-skull-crossbones"
        );
    }
    public static function liGlobalListItem($title, $html, $feather = "settings")
    {
        printf("<!--liGlobalListItem -->\n
                <a class='dropdown-item' href='#!'>\n
                 <div class='dropdown-item-icon'><i data-feather= %s ></i></div>%s, %s </a>\n
                 <div class='dropdown-divider'></div>\n", $feather, $title, $html);
    }
    public static function liGlobalListStop()
    {
        printf('</div></li>');
    }
    public static function ulGlobalListStop()
    {
        printf('</ul>');
    }
    public static function sidenavFooter($username, $pageProtected)
    {
        echo "<div class='sidenav-footer'>
        <div class='sidenav-footer-content'>
            <div class='sidenav-footer-subtitle'>Zalogowany:</div>
            <div class='sidenav-footer-title'>[" . $username . "]  [" . $pageProtected . "]</div>
        </div>
    </div>";
    }
    /**
     * [Description for sectionHeaderCollapsed]
     *
     * @param mixed $title tytuł sekcji
     * @param string $feather typ ikonki
     * 
     * @return string collapse ID, do kolejnych elementów
     * 
     */
    public static function sectionHeaderCollapsed($title, $feather = 'activity')
    {
        $collapseUID = "collapse" . uniqid();
        printf('<a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#%s" aria-expanded="false" aria-controls="%s">
                                <div class="nav-link-icon"><i data-feather="%s"></i></div>
                                %s
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>', $collapseUID, $collapseUID, $feather, $title);
        // echo "<a class='nav-link collapsed' href='javascript:void(0);' data-bs-toggle='collapse' data-bs-target='#$collapseUID' aria-expanded='false' aria-controls='$collapseUID'>" . PHP_EOL;
        // echo "<div class='nav-link-icon'><em data-feather='activity'></em></div>". PHP_EOL;
        //echo $title;
        //echo "<div class='sidenav-collapse-arrow'><em class='fas fa-angle-down'></em></div>". PHP_EOL;
        //echo "</a>". PHP_EOL;
        return $collapseUID;
    }
    public static function sectionCollapseOpen($sectionUID)
    {
        printf('<div class="collapse" id="%s" data-bs-parent="#accordionSidenav">
                                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">', $sectionUID);
        //echo "<div class='' id='$sectionUID' data-bs-parent='#accordionSidenav'>". PHP_EOL;
        //echo "<nav class='sidenav-menu-nested nav accordion' id='accordionSidenavPages'>";
        return $sectionUID;
    }
    public static function sectionCollapseClose($sectionUID)
    {
        printf("</nav></div>\n");
        return $sectionUID;
    }
    protected static function colorLink($href, $title, $color)
    {
        printf('<a class="nav-link %s" href="%s">%s</a>', $color, $href, $title);
    }
    protected static function simpleLink($href, $title)
    {
        printf('<a class="nav-link" href="%s">%s</a>', $href, $title);
        //echo "<a class='nav-link' href='$href'>$title</a>". PHP_EOL;
    }
    public static function Menu2()
    {
        self::sectionHeaderSideNav("Aplikacje");
        $sectionUID = self::sectionHeaderCollapsed("Status systemu", 'list');
        self::sectionCollapseOpen($sectionUID);
        self::simpleLink("#!", "Status");
        self::simpleLink("#!", "Mapa projektów");
        self::sectionCollapseClose($sectionUID);
        self::sectionHeaderSideNav("ZARZĄDZANIE");
        $sectionUID = self::sectionHeaderCollapsed("Zarządzanie wieżami", 'list');
        self::sectionCollapseOpen($sectionUID);
        self::simpleLink("#!", "Lista wież");
        self::simpleLink("#!", "Dodaj wieżę");
        self::sectionCollapseClose($sectionUID);
        $sectionUID = self::sectionHeaderCollapsed("Zarządzanie projektami", 'grid');
        self::sectionCollapseOpen($sectionUID);
        self::simpleLink("#!", "Lista projektów");
        self::simpleLink("#!", "Dodaj projekt");
        self::sectionCollapseClose($sectionUID);
        $sectionUID = self::sectionHeaderCollapsed("Zarządzanie organizacjami", 'server');
        self::sectionCollapseOpen($sectionUID);
        self::simpleLink("#!", "Lista organizacji");
        self::simpleLink("organization-add.php", "Dodaj organizację");
        self::sectionCollapseClose($sectionUID);
        $sectionUID = self::sectionHeaderCollapsed("Zarządzanie użytkownikami", 'grid');
        self::sectionCollapseOpen($sectionUID);
        self::simpleLink("#!", "Lista użytkowników");
        self::simpleLink("user-management-user-add.php", "Dodaj użytkownika");
        self::simpleLink("user-management-priviledges-list.php", "Zarządzanie dostępami");
        self::simpleLink("#!", "Dodaj projekt");
        self::sectionCollapseClose($sectionUID);
    }
    public static function Menu1()
    {
        self::sectionHeaderSideNav("Obiekty");
        $sectionUID = self::sectionHeaderCollapsed("Status systemu", 'list');
        self::sectionCollapseOpen($sectionUID);
        self::simpleLink("login.php", "login");
        self::simpleLink("logout.php", "logout");
        //self::simpleLink("#!", "Mapa projektów");
        self::sectionCollapseClose($sectionUID);
        self::sectionHeaderSideNav("ZARZĄDZANIE");
        $sectionUID = self::sectionHeaderCollapsed("Zarządzanie", 'list');
        self::sectionCollapseOpen($sectionUID);
        self::simpleLink("element-list-rury-plac.php", "Wszystkie elementy:RURY z placu/bez QR");
        self::simpleLink("element-list-rury.php", "Wszystkie elementy:RURY");
        self::simpleLink("element-list.php", "Wszystkie elementy");
        self::simpleLink("mileage-list.php", "Wszystkie kilometraże");
        self::simpleLink("cut-list.php", "Wszystkie cięcia");
        self::simpleLink("bend-list.php", "Wszystkie gięcia");
        self::simpleLink("joint-list.php", "Wszystkie spoiny");
        self::sectionCollapseClose($sectionUID);
        self::sectionHeaderSideNav("RAPORTY");
        $sectionUID = self::sectionHeaderCollapsed("Raporty", 'list');
        self::sectionCollapseOpen($sectionUID);
        self::simpleLink("raport-7.2.php", "Raport 7-2");
        self::simpleLink("raport-9-3.1.php", "Raport 9-3.1");
        self::sectionCollapseClose($sectionUID);
    }
    /**
     * Dodatkowe menu tylko dla admina i superadmina
     *
     * @param mixed $user
     * @param mixed $pageProtected
     * 
     * @return [type]
     * 
     */
    public static function menuInfo($user, $pageProtected, array $pageProtectionArray)
    {
        //if($user->isAdmin() == Priviledge::USER_OTHER) return;  // to menu jest TYLKO dla admina i superadmina!
        $sectionUID = self::sectionHeaderCollapsed("Informacje o stronie", 'layout');
        self::sectionCollapseOpen($sectionUID);
        //
        self::colorLink("#!", "USER: " . $user->name, "text-success small");
        self::colorLink("#!", "PRIVS: " .  json_encode($user->listPriviledges()), "text-success small");
        self::colorLink("#!", "PR Level: " . $pageProtected . $user->getConstantName("PageNavigation\PageProtection", $pageProtected), "text-success small");
        self::colorLink("#!", "PR access: " . json_encode($pageProtectionArray), "text-success small");
        // self::simpleLink("test2.php", "Test");
        //self::simpleLink("user-management-priviledges-list.php", "Zarządzanie dostępami");
        //self::simpleLink("#!", "Dodaj projekt");
        self::sectionCollapseClose($sectionUID);
    }
    public static function pageHeaderFull($title, $subtitle)
    {
        echo "<header class='page-header page-header-dark bg-gradient-primary-to-secondary pb-10'>
        <div class='container-xl px-4'>
            <div class='page-header-content pt-4'>
                <div class='row align-items-center justify-content-between'>
                    <div class='col-auto mt-4'>
                        <h1 class='page-header-title'>
                            <div class='page-header-icon'><i data-feather='activity'></i>
                            </div>
                            $title
                            </span>
                        </h1>
                        <div class='page-header-subtitle'>$subtitle</div>
                    </div>
                </div>
            </div>
        </div>
    </header>";
    }
    public static function pageHeaderCompactFluid($title, $subtitle)
    {
        echo '
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center d-flex justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title ">
                            <div class="page-header-icon"><i data-feather="file"></i></div>
                            ' . $title . '
                            </h1>
                            </div>
                            
                            <div class="col-auto mb-3 ">                         
                            <div id="MyClockDisplay" class="clock" onload="showTime()">TU</div>
        
                            </div>
                    <div class="d-none col-4 mb-3">
                    <div class="progress" style="height: 2px;">
                    <div id="ProgressCircle1" class="d-none progress-bar progress-bar-striped" role="progressbar" style=" width: 100%" ></div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </header>';
    }
    public static function pageHeaderFullFluid($title, $subtitle)
    {
        echo "<header class='page-header page-header-dark bg-gradient-primary-to-secondary pb-10'>
        <div class='container-fluid px-4'>
            <div class='page-header-content pt-4'>
                <div class='row align-items-center justify-content-between'>
                    <div class='col-auto mt-4'>
                        <h1 class='page-header-title'>
                            <div class='page-header-icon'><i data-feather='activity'></i>
                            </div>
                            $title
                            </span>
                        </h1>
                        <div class='page-header-subtitle'>$subtitle</div>
                    </div>
                </div>
            </div>
        </div>
    </header>";
    }
    public static function addBlock($title, $link, $description, $icon = null)
    {
        print(self::addBlockString($title, $link, $description, $icon));
        return;
        printf('
        <div class="card bg-primary text-white m-4 shadow-lg">
            <div class="card-body">
                <div class="d-flex justify-content-between ">
                    <div class="me-3">
                        <div class="text-white-75  h3"> 
                        %s
                        </div>
                    </div>
                    <em data-toggle="tooltip" title="%s" class="fa-solid %s ml-2 "></em>
                </div>
            </div>
            <div class="card-footer bg-white d-flex align-items-center justify-content-between small">
                <a class=" stretched-link" href="%s">%s</a>
                    <div class="">
                    <em class="fas fa-arrow-right fa-2x text-gray-500"></em>
                    </div>
            </div>
        </div>', $title, $title, $icon == null ? 'fa-question-mark' : $icon, $link, $description);
    }
    public static function addBlockString($title, $link, $description, $icon = null)
    {
        return sprintf('
        <div class="card bg-primary text-white m-4 shadow-lg" >
            <div class="card-body">
                <div class="d-flex justify-content-between ">
                    <div class="me-3">
                        <div class="text-white-75  h3"> 
                        %s
                        </div>
                    </div>
                    <em data-toggle="tooltip" title="%s" class="fa-solid %s ml-2 "></em>
                </div>
            </div>
            <div class="card-footer bg-white d-flex align-items-center justify-content-between small">
                <a class=" stretched-link" href="%s">%s</a>
                    <div class="">
                    <em class="fas fa-arrow-right fa-2x text-gray-500"></em>
                    </div>
            </div>
        </div>', $title, $title, $icon == null ? 'fa-question-mark' : $icon, $link, $description);
    }
    public static function addInput($title, $ident, $value="")
    {
        echo self::addInputString($title, $ident);
    }
    public static function addInputString($title, $ident, $value="", $required = true)
    {
        $returnString = sprintf('<div>');
        $returnString .= sprintf('<label class="text-white text-uppercase  mb-1 " for="%s">%s</label>', $ident, $title);
        $returnString .= sprintf('<input autocomplete="on" style="text-align: right !important;" class="bigInput display-3 text-right form-control text-primary mb-3" id="%s" name="%s" value="%s" %s />', $ident, $ident, $value, $required?"required":"");
        $returnString .= sprintf('</div>');
        return $returnString;
    }
    public static function addInputNumeric($title, $ident)
    {
        printf('<label class="text-white text-uppercase  mb-1 " for="%s">%s</label>', $ident, $title);
        printf('<input inputmode="numeric" pattern="[0-9]*" type="text" autocomplete="on" style="text-align: right !important;" class="bigInput display-3 text-right form-control text-primary mb-3" id="%s" name="%s"  required />', $ident, $ident);
    }
    public static function addButton($title, $function, $color = "btn-primary")
    {
        printf(self::addButtonString($title, $function, $color));
    }
    public static function addButtonString($title, $function, $color = "btn-primary")
    {
        return sprintf('<button class="btn btn-lg %s m-1 input-block-level form-control" onClick="%s">%s</button>', $color, $function, $title);
    }
    public static function createTableHistory($tableArray)
    {
        $returnString = "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>
											<thead>
												<tr>
													<th>ID</th>
													<th>Klasa</th>
													<th>Data dodania</th>
													<th>Data aktualizacji</th>
													<th>Nazwa</th>
												</tr>
											</thead>
											<tfoot>
												<tr>
                                                <th>ID</th>
													<th>Klasa</th>
													<th>Data dodania</th>
													<th>Data aktualizacji</th>
													<th>Nazwa</th>
												</tr>
											</tfoot>
											<tbody id='historyTable'>";
        foreach ($tableArray as $key => $value) {
            # code...
            if ($value['id'] == -1) continue;
            $returnString .= sprintf('<tr>');
            $returnString .= sprintf('<td class="text-break">%s</td>', $value['id']);
            $returnString .= sprintf('<td class="text-break">%s</td>', $value['class']);
            $returnString .= sprintf('<td class="text-break">%s</td>', $value['time_added']);
            $returnString .= sprintf('<td class="text-break">%s</td>', $value['time_updated']);
            $returnString .= sprintf('<td class="text-break">%s</td>', $value['name']);
            $returnString .= sprintf('</tr>');
        }
        $returnString .= "</tbody></table>";
        return $returnString;
    }
    public static function lineForElement($title, $value)
    {
        return sprintf("<div class='d-flex border-bottom border-1 border-bottom-yellow justify-content-between align-items-stretch col-12'><div>%s:</div> <div class='text-white'>%s</div></div>", $title, $value);
    }
    /**
     * Tworzymy tabelę na podstawie array danych
     *
     * @param mixed $id identyfikator tabeli do inicjacji via datatables
     * @param array $dataArray wejście dla danych
     * 
     * @return [array
     * 
     * Created at: 2/16/2023, 10:42:54 AM (Europe/Warsaw)
     * @author     Jerzy "Doom_" Zientkowski 
     * @see       {@link https://github.com/doomiie} 
     */
    public static function createTable($tableID, array $dataArray, $className = 'element')
    {
        $depth = self::getArrayDepth($dataArray);
        //return "<div class='text-wrap'>" . json_encode($dataArray) . "</div>";
        $arrayHTML = "<div style='overflow-x:scroll;'><table class='table table-bordered' id='$tableID' width='100%' cellspacing='0'><thead>";
        //$columnArray = '<div class="d-none" id="columnArray">columns: [';
        $arrayHTML .= sprintf("</thead>");
        $arrayHTML .= sprintf("<tbody id='%sBody'>", $tableID);

        foreach ($dataArray as $key => $value) {
            //$index = array_search('id', $value);
            # code...
            $arrayHTML .= sprintf("<tr>");
            //$arrayHTML .= sprintf("<td>%s</td>", self::addButtonViewItemInTable($className, $value['id']));
            // $arrayHTML .= sprintf("<td>%s</td>", "wywalić");

            foreach ($value as $key1 => $value1) {
                $color = 'bg-white';
                if (isset($value1['error'])) {
                    if ($value1['error'] == false)
                        $color = 'bg-warning';
                }
                if (isset($value1['count']))
                    $arrayHTML .= sprintf("<td class='%s text-break'>%s</td>", $color, $value1['count']);
                else
                if (is_array($value1)) {
                    # podział na podpola
                    $arrayHTML .= sprintf("<td class='%s '>", $color);
                    $arrayHTML .= sprintf("<table class='m-0 p-0 border-0 border-white   border-bottom-black align-content-center'><tbody>");

                    foreach ($value1 as $key2 => $value2) {
                        //error_log("TUTAJ podział" . sprintf("<tr class='%s text-break'><td>%s</td></tr>", $color, $value2));
                        $arrayHTML .= sprintf("<tr class='%s justify-content-center border-bottom-black  '><td class='  text-center align-middle align-content-center justify-content-center'>%s</td></tr>", $color, $value2);
                    }
                    $arrayHTML .= sprintf("</tbody></table>");
                    $arrayHTML .= sprintf("</td>");
                } else
                    $arrayHTML .= sprintf("<td class='%s  text-center  align-middle align-content-center justify-content-center'>%s</td>", $color, $value1);
            }
            $arrayHTML .= sprintf("</tr>");
        }
        $arrayHTML .= sprintf("</tr> </tbody></table></div>");
        return $arrayHTML;
    }

    protected static function getArrayDepth($array)
    {
        $depth = 0;
        $iteIte = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array));

        foreach ($iteIte as $ite) {
            $d = $iteIte->getDepth();
            $depth = $d > $depth ? $d : $depth;
        }

        return $depth;
    }
    public static function createArrayForTable($id, array $dataArray)
    {
        $columnArray = "";
        //sprintf("{data:'Action button',title:'Przyciski działań'},");
        foreach ($dataArray[0] as $key => $value) {
            # code...
            $columnArray .= sprintf("{data:'%s',title:'%s'},", $key, $key);
            //$temp['data'] = $key;
            //$temp['title'] = $key;
            //$columnArray[] = $temp;
            //$columnArray[]['data'] = $key;
            //$columnArray[]['title'] = $key;
        }
        $columnArray = rtrim($columnArray, ',');
        return $columnArray;
    }

    public static function addButtonViewItemInTable($className, $id)
    {
        return sprintf("<button class='btn btn-primary' id='viewButton'  onClick=gotoElementSingle('%s','%s')>
        <i class='feather text-warning' data-feather='eye'></i>
        </button>", $className, $id);
    }


    public static function addCardInfoQR($show = 'd-none')
    {
        return printf('<div id="card-info-qr" class="%s col-12  col-sm-8 col-lg-6 col-xl-4 mb-4">
        <div class="card  bg-gray-700 text-white m-4 shadow-lg">
            <div class="card-header">
                <a class="card-header text-white" href="#collapseQrCode" data-bs-toggle="" role="button" aria-expanded="true" aria-controls="">Dane skanowania - QR
                    
                </a>
            </div>
            <div class="" id="collapseQrCode">
                <div class="card-body d-flex justify-content-center">
                    <div class="d-flex justify-content-center">
                        <div class="" id="infoDisplayQrCode"></div>
                    </div>
                </div>
            </div>
            <div class="card-footer  bg-white d-flex align-items-center justify-content-between small">
                <div class="">
                    <div class="text-warning" id="errorDisplayQrCode"></div>
                </div>
            </div>
        </div>
    </div>', $show);
    }
    public static function addCardInfoElement($show = 'd-none')
    {
        return printf('
    <div id="card-info-scan" class="%s col-12  col-sm-8 col-lg-6 col-xl-4 mb-4">
    
        <div class="card bg-primary text-white m-4 shadow-lg">
            <div class="card-header">
                <a class="card-header text-white" href="#collapseElement" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">Dane skanowania - element
                 
                </a>
            </div>
            <div class=" show" id="collapseElement">
                <div class="card-body d-flex justify-content-center">
                    <div class="d-flex justify-content-center">
                        <div id="infoDisplayElement"></div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white d-flex align-items-center justify-content-between small">
                <div class="">
                    <div class="text-warning" id="errorDisplayElement"></div>
                </div>
            </div>
        </div>
    </div>', $show);
    }

    public static function addCardCamera($show = "d-none")
    {
        return printf('<div id="card-camera" class="%s col-12  col-sm-8 col-lg-6 col-xl-4 mb-4">
        <div class="card bg-primary text-white m-4 shadow-lg">
            <div class="card-header">
                <div id="previewName"></div>
            </div>
            <div class="card-body d-flex justify-content-center">
                <div class="d-flex justify-content-center">
                    <div class="preview-container">
                        <video class="embed-responsive" id="preview"></video>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white d-flex align-items-center justify-content-between small">
                <div class="">
                    <button class="btn btn-sm btn-primary m-1" onClick="changeCamera()">Zmień kamerę</button>
                    <button class="btn btn-sm btn-primary m-1" onClick="changeMirror()">Mirror mode</button>
                    <button class="btn btn-sm btn-primary m-1" onClick="startCamera()">Wystartuj kamery</button>
                    <button class="btn btn-sm btn-primary m-1" onClick="stopCamera()">Zatrzymaj kamery</button>
                </div>
            </div>
        </div>
    </div>', $show);
    }
    public static function addCardScanActions()
    {
        return printf('<div id="card-scan-actions" class="col-12  col-sm-8 col-lg-6 col-xl-4 mb-4">
        <!-- Dashboard actions for element-->
        <div class="card bg-primary text-white m-4 shadow-lg">
            <div class="card-header">
                <div id="">Akcje dla elementu</div>
            </div>
            <div class="card-body bg-white d-flex justify-content-center">
                <div class="align-items-stretch col-12" id="akcjeDisplayElement">
                    <select onChange="installCallbackFunctionForCamera(callback_elementGetQR);" class="form-control text-lg " id="funkcjaSelect" name="funkcjaSelect" type="text">
                        <option default  id="add" value="add">Doskanuj po kolei</option>;
                        <option  id="addA" value="addA">Doskanuj na pozycji A</option>;
                        <option  id="addB"  value="addB">Doskanuj na pozycji B</option>;
                        <option  id="addC"  value="addC">Doskanuj na pozycji C</option>;
                        <option  id="addD"  value="addD">Doskanuj na pozycji D</option>;
                        <option  id="del"  value="del">Usuń kod QR z elementu</option>;
                        <option  id="getQR"  value="getQRCodePosition">Sprawdź pozycję kodu QR</option>;


                    </select>


                </div>
            </div>
            <div class="card-footer bg-white d-flex align-items-center justify-content-between small">
                <div class="">
                </div>
            </div>
        </div>
    </div>');
    }
    public static function addCardAkcje($show = 'd-none')
    {
        return printf('<div id="card-akcje" class="%s col-12  col-sm-12 col-lg-6 col-xl-4 m-1 p-0 mb-4">
        <!-- Dashboard actions for element-->
        <div class="card bg-primary text-white mb-4 shadow-lg">
            <div class="card-header">
                <div id="">Akcje dla elementu</div>
            </div>
            <div class="card-body bg-white d-flex justify-content-center">

                <div class="align-items-stretch col-12 text-primary" id="akcjeDisplayElement">


                </div>
            </div>
            <div class="card-footer bg-white d-flex align-items-center justify-content-between small">
                <div class="">
                </div>
            </div>
        </div>
    </div>', $show);
    }
    public static function addCardSzukaj()
    {
        return printf(' <div id="card-szukaj" class="d-none col-12  col-sm-8 col-lg-6 col-xl-4 mb-4">
        <!-- Dashboard search element card-->
        <div class="card bg-primary text-white m-4 shadow-lg">
            <div class="card-header">
                <div id="">Wyszukaj element po nr wytopu i rury</div>
            </div>
            <div class="card-body d-flex justify-content-center">
                <div class="row">
                    <div class="display-flex justify-content-between align-items-center">
                        <label class="text-black text-uppercase  mb-1 " for="inputNrRury">Nr rury</label>
                    </div>
                    <div class="">
                        
                        <input autocomplete="on" style="text-align: right !important;" class="bigInput display-3 text-right form-control text-primary" id="inputNrRury" name="inputNrRury" type="" required />
                    </div>
                </div>
                <div class="row">
                    <div class="display-flex justify-content-between align-items-center">
                        <label class="text-black text-uppercase  mb-1 " for="inputWytop">Wytop</label>
                    </div>
                    <div class="">
                        <input autocomplete="on" style="text-align: right !important;" class="bigInput display-3 text-right form-control text-primary" id="inputWytop" name="inputWytop" type="" required />
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white d-flex align-items-center justify-content-between small">
                <div class="text-primary" id="infoDisplaySzukaj">
                </div>
            </div>
        </div>
    </div>');
    }

    public static function addCardSzukajSpoiny()
    {
        return printf(' <div id="card-szukaj" class="d-none col-12  col-sm-8 col-lg-6 col-xl-4 mb-4">
        <!-- Dashboard search element card-->
        <div class="card bg-primary text-white m-4 shadow-lg">
            <div class="card-header">
                <div id="">Wyszukaj spoinę po numerze</div>
            </div>
            <div class="card-body d-flex justify-content-center">
                <div class="row">
                    <div class="display-flex justify-content-between align-items-center">
                        <label class="text-black text-uppercase  mb-1 " for="inputNrSpoiny">Nr spoiny</label>
                    </div>
                    <div class="">
                        
                        <input autocomplete="on" style="text-align: right !important;" class="bigInput display-3 text-right form-control text-primary" id="inputNrSpoiny" name="inputNrSpoiny" type="" required />
                    </div>
                </div>
                
            </div>
            <div class="card-footer bg-white d-flex align-items-center justify-content-between small">
                <div class="text-primary" id="infoDisplaySzukaj">
                </div>
            </div>
        </div>
    </div>');
    }

public static function addCardEdit($show = 'd-none')
    {

        return printf('<div id="card-element-edit" class="%s col-12  col-sm-12 col-lg-6 col-xl-4 m-1 p-0 mb-4">
        <!-- Dashboard actions for element-->
        <div class="card bg-primary text-white mb-4 shadow-lg">
            <div class="card-header">
                <div id="">Edycja pól elementu</div>
            </div>
            <div class="card-body text-wrap d-flex ">

                <div class="row" id="editElement">


                </div>
            </div>
            <div class="card-footer bg-white d-flex align-items-center justify-content-between small">
                <div class="">
                </div>
            </div>
        </div>
    </div>', $show);
    }
} // koniec klasy!