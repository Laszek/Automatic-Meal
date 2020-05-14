<?php
    session_start();
?>
<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <meta http-equiv="content-type" content="text/html" charset="utf-8">
        <title>Restauracja "Automatic Meal"</title>
        <link rel="stylesheet" href="css/style.css?=<?php echo uniqid()?>">
        <script src="js/script.js" async></script>
        <link href="https://fonts.googleapis.com/css?family=Fascinate+Inline&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Work+Sans&display=swap" rel="stylesheet">
        <?php
            $config = require_once("config.php");
                            $db = new mysqli($config['host'], $config['user'], $config['pass'], $config['database']);
                            $db -> query("SET CHARSET utf8");
                            $db -> query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
            $komunikat = '';
            if (isset($_POST['zaloguj'])){
                if ((!empty($_POST['login'])) && (!empty($_POST['haslo']))){
                    
                    $rekordy = $db -> query("SELECT * FROM `pracownicy` WHERE `Login` = '".$_POST['login']."' && `Haslo`= '".$_POST['haslo']."'");
                    $komunikat = $rekordy;
                    if(mysqli_num_rows($rekordy)>0){
                        $komunikat = "Zalogowano jako ".$_POST['login'];
                        $_SESSION['login'] = $_POST['login'];
                        foreach($db -> query("SELECT * FROM `pracownicy` WHERE `Login` = '".$_SESSION['login']."'") as $value){
                            $_SESSION['imie'] = $value['Imie'];
                            $_SESSION['nazwisko'] = $value['Nazwisko'];
                            foreach($db -> query("SELECT `Nazwa` FROM `stanowiska` WHERE `ID` = ".$value['Stanowisko']."") as $v){
                            $_SESSION['stanowisko'] = $v['Nazwa'];
                            
                            }
                        }
                    }
                    else{
                        $zl = $db -> query("SELECT * FROM `pracownicy` WHERE `Login` = '".$_POST['login']."'");
                        $zh = $db -> query("SELECT * FROM `pracownicy` WHERE `Haslo` = '".$_POST['haslo']."'");
                        
                        if(mysqli_num_rows($zl)==0){
                            $komunikat = "Użytkownik o nazwie <i>".$_POST['login']."</i> nie istnieje";
                        }
                        else if(mysqli_num_rows($zh)==0){
                            $komunikat = "Błędne hasło";
                        }
                        
                    }
                }
            }
              if(isset($_POST['wyloguj'])){
                session_unset();
                
                header("Location: index.php");
            }
            
            if (isset($_POST['usun_prac'])){
                $db -> query ("DELETE FROM `pracownicy` WHERE `ID`=".$_POST['usun_prac']."");
            }
            if (isset($_POST['dodaj_prac'])){
                header("Location: index.php?dodaj_prac");
            }
              if (isset($_POST['usun_menu'])){
                $db -> query ("DELETE FROM `menu` WHERE `idproduktu`=".$_POST['usun_menu']."");
            }
              if (isset($_POST['dodaj_menu'])){
                header("Location: index.php?dodaj_menu");
            }
            if (isset($_POST['prac_dodaj'])){
                $db -> query("INSERT INTO `pracownicy` (`Imie`, `Nazwisko`, `Stanowisko`, `Login`, `Haslo`) VALUES ('".$_POST['Imie']."', '".$_POST['Nazwisko']."', '".$_POST['Stanowisko']."', '".$_POST['Login']."', '".$_POST['Haslo']."');");
            }
              if (isset($_POST['menu_dodaj'])){
                  $db -> query ("INSERT INTO `menu` (`typ`, `nazwa`, `opis`, `cena`) VALUES ('".$_POST['typ']."', '".$_POST['nazwa']."', '".$_POST['gram']."<br>".$_POST['opis']."', '".$_POST['cena']."');");
            }
              if (isset($_POST['nr_stolika'])){
                  $_SESSION['nr_stolika'] = $_POST['nr_stolika'];
                  $_SESSION['zamowienie'] = [];
              }
              if (isset($_POST['anuluj_zam'])){
                  unset($_SESSION['nr_stolika']);
                  unset($_SESSION['zamowienie']);
              }
              if(isset($_GET['zamow'])){
                  
                  $i=1;
                    for($i=1;$i<100;$i++){
                      if(isset($_GET['zam'.$i.''])){
                          array_push($_SESSION['zamowienie'], $_GET['zam'.$i.'']);
                      }
                    }
                
              }
              if(isset($_POST['potwierdz'])){
                  $zamowienie = '';
                  foreach ($_SESSION['zamowienie'] as $val){
                      $zamowienie = $zamowienie.$val.",";
                  }
                  $db -> query("INSERT INTO `zamowienia`(`nr_stolika`, `zamowiono`, `obsluga`, `status`) VALUES ('".$_SESSION['nr_stolika']."','".$zamowienie."','".$_SESSION['nazwisko']."','przygotowywanie')");
                  
                  unset($_SESSION['zamowienie']);
                  unset($_SESSION['nr_stolika']);
              }
              
        ?>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <a href="index.php"><div id="name">AUTOMATIC MEAL</div></a>
            </div>
            <div id="main">
                <div id='nav'>
                            <a href='index.php?kat=new_order' class='nav_option' id='new_order'>Nowe zamówienie</a>
                            <a href='index.php?kat=orders' class='nav_option' id='orders'>Zamówienia</a>
                            <a href='index.php?kat=menu' class='nav_option' id='menu'>Menu</a>
                            <a href='index.php?kat=workers' class='nav_option' id='workers'>Pracownicy</a>
                            </div>
                <div id="content">
                    <?php 
                        if(!isset($_SESSION['login'])){
                            echo "<div id='login_panel'>
                                <h1>Zaloguj się</h1>
                                <form action='index.php' method='POST'><table><tr><td>Login:</td><td><input type='text' name='login'></td></tr><tr><td>Hasło:</td><td><input type='password' name='haslo'></td></tr><tr><td colspan='2'><input type='submit' name='zaloguj' value='zaloguj'></td></tr></table></form>$komunikat</div>";
                        }
                        if(isset($_GET['kat'])){
                            switch ($_GET['kat']){
                           /*--------------------------------------------------------------------*/         
                                case 'new_order':
                                    if(!isset($_SESSION['nr_stolika'])){
                                        echo "<center><h1>WYBIERZ STOLIK</h1></center>";
                                        echo "<div id='stoliki'><form action='index.php?kat=new_order' method='POST'>";
                                            echo "<label for='stolik1'>Stolik nr 1<input id='stolik1' type='submit' name='nr_stolika' value='1'></label>";
                                            echo "<label for='stolik2'>Stolik nr 2<input id='stolik2' type='submit' name='nr_stolika' value='2'></label>";
                                            echo "<label for='stolik3'>Stolik nr 3<input id='stolik3' type='submit' name='nr_stolika' value='3'></label>";
                                            echo "<label for='stolik4'>Stolik nr 4<input id='stolik4' type='submit' name='nr_stolika' value='4'></label>";
                                            echo "<label for='stolik5'>Stolik nr 5<input id='stolik5' type='submit' name='nr_stolika' value='5'></label>";
                                            echo "<label for='stolik6'>Stolik nr 6<input id='stolik6' type='submit' name='nr_stolika' value='6'></label>";
                                            echo "<label for='stolik7'>Stolik nr 7<input id='stolik7' type='submit' name='nr_stolika' value='7'></label>";
                                            echo "<label for='stolik8'>Stolik nr 8<input id='stolik8' type='submit' name='nr_stolika' value='8'></label>";
                                            echo "<label for='stolik9'>Stolik nr 9<input id='stolik9' type='submit' name='nr_stolika' value='9'></label>";
                                            echo "<label for='stolik10'>Stolik nr 10<input id='stolik10' type='submit' name='nr_stolika' value='10'></label>";
                                        
                                        
                                        echo "</form></div>";
                                        
                                    }
                                    else{
                                    echo "<form action='index.php?kat=new_order' method='POST'><input type='submit' name='anuluj_zam' value='Anuluj zamówienie'></form>";
                                    if(!isset($_GET['ord'])){
                                    echo "<div class='kateg'><a href='index.php?kat=new_order&ord=przystawki'><div><h1>Przystawki</h1></div></a></div>
                                        <div class='kateg'><a href='index.php?kat=new_order&ord=zupy'><div><h1>Zupy</h1></div></a></div>
                                        <div class='kateg'><a href='index.php?kat=new_order&ord=salaty'><div><h1>Sałaty</h1></div></a></div>
                                        <div class='kateg'><a href='index.php?kat=new_order&ord=nalesniki'><div><h1>Naleśniki</h1></div></a></div>
                                        <div class='kateg'><a href='index.php?kat=new_order&ord=makarony'><div><h1>Makarony</h1></div></a></div>
                                        <div class='kateg'><a href='index.php?kat=new_order&ord=napoje'><div><h1>Napoje</h1></div></a></div>";
                                    
                                        echo "<form action='index.php' method='get'><div id='zamow'><input type='submit' name='zamow' value='PRZEJDŹ DO ZAMÓWIENIA'></div></form>";
                                    }
                                    else{
                                    echo "<form action='index.php' method='GET'>";
                                        switch($_GET['ord']){
                                            case 'napoje':
                                                $rekordy = $db -> query("SELECT * FROM `menu` WHERE `typ`='napoje'");
                                                $i = 1;
                                                foreach($rekordy as $val){
                                                    echo "<div class='produkt'>
                                                    <div class='desc'><span>".$val['nazwa']."</span>".$val['opis']."</div><div class='cena'><center><span>".$val['cena']."</span> zł</center></div> <div class='check'><center><input type='checkbox' name='zam".$i."' value='".$val['idproduktu']."'></center></div>
                                                    </div>";
                                                    $i++;
                                                }
                                                break;
                                                case 'przystawki':
                                                $rekordy = $db -> query("SELECT * FROM `menu` WHERE `typ`='przystawki'");
                                                
                                                $i = 1;

                                                foreach($rekordy as $val){
                                                    echo "<div class='produkt'>
                                                    <div class='desc'><span>".$val['nazwa']."</span>".$val['opis']."</div><div class='cena'><center><span>".$val['cena']."</span> zł</center></div> <div class='check'><center><input type='checkbox' name='zam".$i."' value='".$val['idproduktu']."'></center></div>
                                                    </div>";
                                                    $i++;
                                                }
                                                break;
                                            case 'zupy':
                                                $rekordy = $db -> query("SELECT * FROM `menu` WHERE `typ`='zupy'");
                                                $i = 1;
                                                foreach($rekordy as $val){
                                                    echo "<div class='produkt'>
                                                    <div class='desc'><span>".$val['nazwa']."</span>".$val['opis']."</div><div class='cena'><center><span>".$val['cena']."</span> zł</center></div> <div class='check'><center><input type='checkbox' name='zam".$i."' value='".$val['idproduktu']."'></center></div>
                                                    </div>";
                                                    $i++;
                                                }
                                                break;
                                            case 'salaty':
                                                $rekordy = $db -> query("SELECT * FROM `menu` WHERE `typ`='salaty'");
                                                $i = 1;
                                                foreach($rekordy as $val){
                                                    echo "<div class='produkt'>
                                                    <div class='desc'><span>".$val['nazwa']."</span>".$val['opis']."</div><div class='cena'><center><span>".$val['cena']."</span> zł</center></div> <div class='check'><center><input type='checkbox' name='zam".$i."' value='".$val['idproduktu']."'></center></div>
                                                    </div>";
                                                    $i++;
                                                }
                                                break;
                                            case 'makarony':
                                                $rekordy = $db -> query("SELECT * FROM `menu` WHERE `typ`='makarony'");
                                                $i = 1;
                                                foreach($rekordy as $val){
                                                    echo "<div class='produkt'>
                                                    <div class='desc'><span>".$val['nazwa']."</span>".$val['opis']."</div><div class='cena'><center><span>".$val['cena']."</span> zł</center></div> <div class='check'><center><input type='checkbox' name='zam".$i."' value='".$val['idproduktu']."'></center></div>
                                                    </div>";
                                                    $i++;
                                                }
                                                break;
                                            case 'nalesniki':
                                                $rekordy = $db -> query("SELECT * FROM `menu` WHERE `typ`='nalesniki'");
                                                $i = 1;
                                                foreach($rekordy as $val){
                                                    echo "<div class='produkt'>
                                                    <div class='desc'><span>".$val['nazwa']."</span>".$val['opis']."</div><div class='cena'><center><span>".$val['cena']."</span> zł</center></div> <div class='check'><center><input type='checkbox' name='zam".$i."' value='".$val['idproduktu']."'></center></div>
                                                    </div>";
                                                    $i++;
                                                }
                                                break;

                                        }
                                    echo "<div id='zamow'><input type='submit' name='zamow' value='DODAJ DO ZAMÓWIENIA'></div>";
                                    echo "</form>";
                                    }
                                    
                                    }
                                    break;
                            /*--------------------------------------------------------------------*/
                                case 'orders':
                                    if(!isset($_GET['stolik'])){
                                        $rekordy = $db -> query("SELECT DISTINCT `nr_stolika` FROM `zamowienia` ORDER BY `nr_stolika` ASC");
                                            foreach($rekordy as $val){
                                                echo "<a href='index.php?kat=orders&stolik=".$val['nr_stolika']."'><div class='produkt'><div class='desc'><span>".$val['nr_stolika']."</span></div>
                                                </div></a>";
                                            }
                                    }
                                    else{
                                        $rekordy = $db -> query("SELECT * FROM `zamowienia` WHERE `nr_stolika`=".$_GET['stolik']."");
                                        
                                        foreach($rekordy as $val){
                                            echo "<h2>Zamówienie dla stolika ".$val['nr_stolika']." przygotowane przez ".$val['obsluga'].":</h2>";
                                            
                                            break;
                                        }
                                        $produkty ='';
                                        foreach($rekordy as $val){
                                            $produkty = $produkty.",".$val['zamowiono'];
                                        }
                                        
                                        
                                        $prod = explode(",", $produkty);
                                        
                                        $produk = [];
                                        foreach($prod as $v){
                                            if($v !=''){
                                                array_push($produk, $v);
                                            }
                                        }
                                        $zaplata = 0;
                                        foreach($produk as $v){
                                            $tmp = $db -> query("SELECT * FROM `menu` WHERE `idproduktu`= ".$v."");
                                            foreach($tmp as $val){
                                                echo "<div class='produkt'>
                                                    <div class='desc'><span>".$val['nazwa']."</span>".$val['opis']."</div><div class='cena'><center><span>".$val['cena']."</span> zł</center></div></div>";
                                                $zaplata += $val['cena'];
                                            }
                                        }
                                        echo "<h2>Do zapłaty: ".$zaplata." zł";
                                        
                                        $rekordy = $db -> query("SELECT * FROM `menu` WHERE ");
                                        /*foreach($rekordy as $val){
                                            echo "<div class='produkt'>
                                                    <div class='desc'><span>".$val['nazwa']."</span>".$val['opis']."</div><div class='cena'><center><span>".$val['cena']."</span> zł</center></div> <div class='check'><center><input type='checkbox' name='zam".$i."' value='".$val['idproduktu']."'></center></div>
                                                    </div>";
                                        }*/
                                    }
                                                
                                    break;
                            /*--------------------------------------------------------------------*/        
                                case 'menu':
                                    if(!isset($_GET['men'])){
                                    echo "<div class='kateg'><a href='index.php?kat=menu&men=przystawki'><div><h1>Przystawki</h1></div></a></div>
                                        <div class='kateg'><a href='index.php?kat=menu&men=zupy'><div><h1>Zupy</h1></div></a></div>
                                        <div class='kateg'><a href='index.php?kat=menu&men=salaty'><div><h1>Sałaty</h1></div></a></div>
                                        <div class='kateg'><a href='index.php?kat=menu&men=nalesniki'><div><h1>Naleśniki</h1></div></a></div>
                                        <div class='kateg'><a href='index.php?kat=menu&men=makarony'><div><h1>Makarony</h1></div></a></div>
                                        <div class='kateg'><a href='index.php?kat=menu&men=napoje'><div><h1>Napoje</h1></div></a></div>";
                                    }
                                    else{
                                        echo "<form action='index.php?kat=menu' method='POST'>";
                                    switch($_GET['men']){
                                        case 'napoje':
                                            $rekordy = $db -> query("SELECT * FROM `menu` WHERE `typ`='napoje'");

                                            foreach($rekordy as $val){
                                                echo "<div class='produkt'>
                                                <div class='desc'><span>".$val['nazwa']."</span>".$val['opis']."</div><div class='cena'><center><span>".$val['cena']."</span> zł</center></div>";
                                                if(($_SESSION['stanowisko'] == 'Kierownik')||($_SESSION['stanowisko'] == 'Kucharz')){
                                                         echo "<div class='check'><label for='usun_menu".$val['idproduktu']."'>Usuń</label><input id='usun_menu".$val['idproduktu']."' type='submit' value='".$val['idproduktu']."' name='usun_menu'></div>";
                                                }
                                                echo "</div>";
                                            }
                                            break;
                                        case 'przystawki':
                                            $rekordy = $db -> query("SELECT * FROM `menu` WHERE `typ`='przystawki'");

                                            foreach($rekordy as $val){
                                                echo "<div class='produkt'>
                                                <div class='desc'><span>".$val['nazwa']."</span>".$val['opis']."</div><div class='cena'><center><span>".$val['cena']."</span> zł</center></div>";
                                                if(($_SESSION['stanowisko'] == 'Kierownik')||($_SESSION['stanowisko'] == 'Kucharz')){
                                                         echo "<div class='check'><label for='usun_menu".$val['idproduktu']."'>Usuń</label><input id='usun_menu".$val['idproduktu']."' type='submit' value='".$val['idproduktu']."' name='usun_menu'></div>";
                                                }
                                                echo "</div>";
                                            }
                                            break;
                                        case 'zupy':
                                            $rekordy = $db -> query("SELECT * FROM `menu` WHERE `typ`='zupy'");

                                            foreach($rekordy as $val){
                                                echo "<div class='produkt'>
                                                <div class='desc'><span>".$val['nazwa']."</span>".$val['opis']."</div><div class='cena'><center><span>".$val['cena']."</span> zł</center></div>";
                                                if(($_SESSION['stanowisko'] == 'Kierownik')||($_SESSION['stanowisko'] == 'Kucharz')){
                                                         echo "<div class='check'><label for='usun_menu".$val['idproduktu']."'>Usuń</label><input id='usun_menu".$val['idproduktu']."' type='submit' value='".$val['idproduktu']."' name='usun_menu'></div>";
                                                }
                                                echo "</div>";
                                            }
                                            break;
                                        case 'salaty':
                                            $rekordy = $db -> query("SELECT * FROM `menu` WHERE `typ`='salaty'");

                                            foreach($rekordy as $val){
                                                echo "<div class='produkt'>
                                                <div class='desc'><span>".$val['nazwa']."</span>".$val['opis']."</div><div class='cena'><center><span>".$val['cena']."</span> zł</center></div>";
                                                if(($_SESSION['stanowisko'] == 'Kierownik')||($_SESSION['stanowisko'] == 'Kucharz')){
                                                         echo "<div class='check'><label for='usun_menu".$val['idproduktu']."'>Usuń</label><input id='usun_menu".$val['idproduktu']."' type='submit' value='".$val['idproduktu']."' name='usun_menu'></div>";
                                                }
                                                echo "</div>";
                                            }
                                            break;
                                        case 'makarony':
                                            $rekordy = $db -> query("SELECT * FROM `menu` WHERE `typ`='makarony'");

                                            foreach($rekordy as $val){
                                                echo "<div class='produkt'>
                                                <div class='desc'><span>".$val['nazwa']."</span>".$val['opis']."</div><div class='cena'><center><span>".$val['cena']."</span> zł</center></div>";
                                               if(($_SESSION['stanowisko'] == 'Kierownik')||($_SESSION['stanowisko'] == 'Kucharz')){
                                                         echo "<div class='check'><label for='usun_menu".$val['idproduktu']."'>Usuń</label><input id='usun_menu".$val['idproduktu']."' type='submit' value='".$val['idproduktu']."' name='usun_menu'></div>";
                                                }
                                                echo "</div>";
                                            }
                                            break;
                                        case 'nalesniki':
                                            $rekordy = $db -> query("SELECT * FROM `menu` WHERE `typ`='nalesniki'");
                                            
                                            foreach($rekordy as $val){
                                                echo "<div class='produkt'>
                                                <div class='desc'><span>".$val['nazwa']."</span>".$val['opis']."</div><div class='cena'><center><span>".$val['cena']."</span> zł</center></div>";
                                                if(($_SESSION['stanowisko'] == 'Kierownik')||($_SESSION['stanowisko'] == 'Kucharz')){
                                                         echo "<div class='check'><label for='usun_menu".$val['idproduktu']."'>Usuń</label><input id='usun_menu".$val['idproduktu']."' type='submit' value='".$val['idproduktu']."' name='usun_menu'></div>";
                                                }
                                                echo "</div>";
                                            }
                                            break;
                                    }
                                     echo "</form>";       
                                    }
                                    if(($_SESSION['stanowisko'] == 'Kierownik')||($_SESSION['stanowisko'] == 'Kucharz')){
                                        echo "<form action='index.php' method='POST'>";
                                                    echo "<div id='zamow'><input type='submit' name='dodaj_menu' value='DODAJ DO MENU'></div>";
                                        echo "</form>"; 
                                    }
                                    break;
                            /*--------------------------------------------------------------------*/        
                                case 'workers':
                                    $stanowisko = $db -> query("SELECT * FROM `pracownicy` WHERE `Login`='".$_SESSION['login']."'");
                                    $wynik = $stanowisko -> fetch_assoc();
                                    
                                    $rekordy = $db -> query("SELECT * FROM `pracownicy` ORDER BY `Nazwisko` ASC");
                                    
                                    
                                            echo "<form action='index.php?kat=workers' method='POST'>";
                                            foreach($rekordy as $val){
                                                echo "<div class='produkt'>
                                                <div class='desc'><span>".$val['Nazwisko']."</span>".$val['Imie']."</div><div class='cena'><center></center></div>";
                                                if($_SESSION['stanowisko'] == 'Kierownik'){
                                                         echo "<div class='check'><label for='usun_prac".$val['ID']."'>Usuń</label><input id='usun_prac".$val['ID']."' type='submit' value='".$val['ID']."' name='usun_prac'></div>";
                                                }
                                                echo "</div>";
                                            }
                                            if($_SESSION['stanowisko'] == 'Kierownik'){
                                                echo "<div id='zamow'><input type='submit' name='dodaj_prac' value='DODAJ PRACOWNIKA'></div>";
                                            }
                                             echo "</form>";
                                            break;
                                        
                                        
                                    break;
                        }
                        }
                        else if(isset($_GET['dodaj_prac'])){
                            echo "<div id='dodaj'>
                                <h2>Dodaj pracownika:</h2>
                                <form action='index.php?kat=workers' method='POST'>
                                    <table>
                                    <tr><td>Imię:</td><td> <input type='text' name='Imie'></td></tr>
                                    <tr><td>Nazwisko:</td><td> <input type='text' name='Nazwisko'></td></tr>
                                    <tr><td>Stanowisko:</td><td><select name='Stanowisko'>
                                        <option value='1'>Kierownik</option>
                                        <option value='2'>Kelner</option>
                                        <option value='3'>Kucharz</option>
                                    </select></td></tr>
                                    <tr><td>Login:</td><td> <input type='text' name='Login'></td></tr>
                                    <tr><td>Hasło:</td><td> <input type='password' name='Haslo'></td></tr>
                                    <tr><td colspan='2'><br><input type='submit' name='prac_dodaj' value='Dodaj'>
                                    </table>
                                </form>
                                </div>";
                        }
                        else if(isset($_GET['dodaj_menu'])){
                                echo "<div id='dodaj'>
                                    <h2>Dodaj produkt:</h2>
                                    <form action='index.php?kat=menu' method='POST'>
                                        <table>
                                        <tr><td>Typ:</td><td> <select name='typ'>
                                            <option value='przystawki'>Przystawki</option>
                                            <option value='zupy'>Zupy</option>
                                            <option value='salaty'>Sałaty</option>
                                            <option value='nalesniki'>Naleśniki</option>
                                            <option value='makarony'>Makarony</option>
                                            <option value='napoje'>Napoje</option>
                                        </select></td></tr>
                                        <tr><td>Nazwa:</td><td> <input type='text' name='nazwa'></td></tr>
                                        <tr><td>Gramatura:</td><td> <input type='textarea' name='gram'></td></tr>
                                        <tr><td>Opis:</td><td> <input type='textarea' name='opis'></td></tr>
                                        <tr><td>Cena:</td><td> <input type='number' name='cena'></td></tr>
                                        <tr><td colspan='2'><br><input type='submit' name='menu_dodaj' value='Dodaj'>
                                        </table>
                                    </form>
                                    </div>";
                            }
                        else if(isset($_GET['zamow'])){
                            echo "<form action='index.php?kat=new_order' method='POST'><input type='submit' name='anuluj_zam' value='Anuluj zamówienie'></form>";
                            if(isset($_POST['usun_zam'])){
                                unset($_SESSION['zamowienie'][$_POST['usun_zam']]);
                                $tmp = [];
                                foreach ($_SESSION['zamowienie'] as $value){
                                    array_push($tmp, $value);
                                }
                                $_SESSION['zamowienie'] = $tmp;
                            }
                            
                            
                            echo "<h2>Zamówiono dla stolika ".$_SESSION['nr_stolika'].":</h2>";
                            echo "<form action='index.php?zamow' method='POST'>";
                            
                            $i=0;
                            foreach($_SESSION['zamowienie'] as $v){
                                $val = $db -> query ("SELECT * FROM `menu` WHERE `idproduktu`=".$v."");
                                foreach($val as $val){
                                    echo "<div class='produkt'>
                                                <div class='desc'><span>".$val['nazwa']."
                                                </span>".$val['opis']."</div><div class='cena'><center>
                                                <span>".$val['cena']."</span> zł</center></div><div 
                                                class='check'><label for='usun_zam".$i."'>Usuń</label>
                                                <input id='usun_zam".$i."' type='submit' 
                                                value='".$i."' name='usun_zam'></div></div>";
                                    
                                    $i++;
                                }
                            }
                            echo "</form>";
                            if(!empty($_SESSION['zamowienie'])){
                            echo "<form action='index.php' method='post'><div id='zamow'><input type='submit' name='potwierdz' value='POTWIERDZ ZAMÓWIENIE'></div></form>";
                            }
                        }
                        
                        
                    ?>
                </div>
            </div>
            <div id="footer">
                <div id="user_info">
                    <?php 
                    if (isset($_SESSION['login'])){
                        echo "<b>Zalogowano jako: </b><i>".$_SESSION['imie']." ".$_SESSION['nazwisko']."</i>".$_SESSION['stanowisko']; 
                        echo "<form method='post'><input type='submit' value='Wyloguj' name='wyloguj'></form>";
                    }
                    else
                        echo "<h2>Nie zalogowano!</h2>"
                    ?>
                </div>
                <div id="autor">Maciej Łaszewski &copy 2019</div>
            </div>
        </div>      
        <?php
                    if(!isset($_SESSION['login'])){
                        echo "<script>document.querySelector('#nav').style.display = 'none';</script>";
                    }
        ?>
    </body>
</html>