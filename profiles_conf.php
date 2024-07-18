<?php
if (isset($_SESSION['username']) === false ) {
    header('location:../logout.php');
    exit();
}

if(!$link){ die("Ressource de connection à la base de données inaccessible."); }

?>
<script type="text/javascript" src="../js/all.js?1.1"></script>
<div  class = "class_menu_haut"  >



    <?php if (strpos($_SESSION['profile'], 'comptes') !== false) { ?>
        <A  HREF="/comptes/" class = "class_a_menu_haut" id="a_comptes" >
            GESTION DES COMPTES
        </A>
        <span class="glyphicon glyphicon-off"></span>
        &nbsp;&nbsp;
    <?php } ?>

    <?php if (strpos($_SESSION['profile'], 'aggregationdata') !== false) { ?>                    
        <A  HREF="/aggregationdata/" class = "class_a_menu_haut" id="a_aggregationdata"  >
            AGGREGATIONS
        </A>
        &nbsp;&nbsp;
    <?php } ?>

    <?php if (strpos($_SESSION['profile'], 'bio_mol') !== false) { ?>                    
        <A  HREF="/bio-mol/" class = "class_a_menu_haut" id="a_bio_mol"  >
            BIO-MOL
        </A>
        &nbsp;&nbsp;
    <?php } ?>      

    
    
    <?php if (strpos($_SESSION['profile'], 'investigations') !== false) { ?>                    
        <A  HREF="/investigations/" class = "class_a_menu_haut" id="a_investigations"    >
            INVESTIGATIONS
        </A>
        &nbsp;&nbsp;
    <?php } ?>
    

    
    <a href='javascript:void(0)' class = "deconnexion_cls" id="a_deconnexion" >
    	<img alt="" src="../images/logout1.png">	
    </a>
        
</div>

<script type = "text/javascript" >
        $(document).ready(function() {
            var operateur = "<?php echo ($_SESSION['username']); ?>";
            alertify.log(operateur, "", 0);
        });
   </script>
