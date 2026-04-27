<?php
/* partials/footer.php 

*********************************************************************************************************
* Made with  by github.com/oromane
*
*
*
*          /\‾‾‾‾\                   /\‾‾‾‾\                   /\‾‾‾‾\                   /\‾‾‾‾\
*         /::\‾‾‾‾\                 /::\    \                 /::\    \                 /::\    \        
*        /::::\    \               /:::/‾‾‾‾/                /:::/‾‾‾‾/                /:::/‾‾‾‾/        
*       /::::::\    \             /:::/    /                /:::/    /                /:::/    /         
*      /:::/\:::\    \           /:::/   /\‾‾‾‾\           /:::/    /                /:::/    /          
*     /:::/  \:::\    \         /:::/   /::\    \         /:::/    /                /:::/    /           
*    /:::/   /:::/‾‾‾‾/        /:::/   /:::/‾‾‾‾/        /::::\‾‾‾‾\               /::::\‾‾‾‾\           
*   /:::/   /:::/ /\‾‾‾‾\     /:::/   /:::/    /        /::::::\    \             /::::::\    \          
*  /:::/    \::/ /::\    \   /:::/   /:::/   /\‾‾‾‾\   /:::/\:::\    \ /\‾‾‾‾\   /:::/\:::\    \ /\‾‾‾‾\ 
* /:::/    / \/  \:::|‾‾‾‾| /:::/   /:::/   /::\    \ /:::/  \:::\    /::\    \ /:::/  \:::\    /::\    \
* \:::\‾‾‾‾\  ‾‾‾/:::|    | \:::\‾‾/:::/   /:::/‾‾‾‾/ \::/   /\:::\  /:::/‾‾‾‾/ \::/   /\:::\  /:::/‾‾‾‾/
*  \:::\    \   /:::/ ‾‾‾/   \:::\/:::/   /:::/    /   \/   /  \:::\/:::/    /   \/   /  \:::\/:::/    / 
*   \:::\    \ /:::/    /     \::::::/   /:::/    /     ‾‾‾‾    \::::::/    /     ‾‾‾‾    \::::::/    /  
*    \:::\    /:::/    /       \::::/   /:::/    /               \::::/    /               \::::/    /   
*     \:::\  /:::/    /         \:::\‾‾/:::/    /                /:::/    /                /:::/    /    
*      \:::\/:::/    /           \:::\/:::/    /                /:::/    /                /:::/    /     
*       \::::::/    /             \::::::/    /                /:::/    /                /:::/    /      
*        \::::/    /               \::::/    /                /:::/    /                /:::/    /       
*         \::/    /                 \::/    /                 \::/    /                 \::/    /        
*          \/    /                   \/    /                   \/    /                   \/    /         
*           ‾‾‾‾‾                     ‾‾‾‾‾                     ‾‾‾‾‾                     ‾‾‾‾‾  
*                                        
* Module: Annuaire des expériences GEII         
* Auteur: ROSSIGNOL Romane                                            
* Date  : 2025-10-25                                           
* Version: 1
*                                                                          
*********************************************************************************************************

*/
$BASE = '/Annuaire';
?>
<footer class="site-footer-enhanced">
    <div class="footer-container">

        <div class="footer-section footer-links-section">
            <h4>Légal & Administration</h4>
            <ul>
                <li><a href="<?= $BASE ?>/pages/terms.php">Conditions d'utilisation</a></li>
                <li><a href="<?= $BASE ?>/pages/privacy.php">Politique de confidentialité</a></li>
                <li><a href="<?= $BASE ?>/pages/sitemap.php">Plan du site</a></li>
                <?php
                if (function_exists('is_logged') && is_logged()):
                    ?>
                    <li><a href="<?= $BASE ?>/admin/index.php">Administration</a></li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="footer-section footer-links-section footer-resources">
            <h4>Accès Rapides</h4>
            <ul>
                <li><a href="<?= $BASE ?>/index.php">Accueil</a></li>
                <li><a href="<?= $BASE ?>/pages/annuaire.php">Consulter l'Annuaire</a></li>
                <li><a href="<?= $BASE ?>/pages/contact.php">Nous contacter</a></li>
                <li><a href="https://www.linkedin.com/school/iut-lille/" target="_blank" rel="noopener noreferrer">LinkedIn IUT Lille</a></li>
            </ul>
        </div>

        <div class="footer-section footer-brand-section">
            <div class="footer-logos-group">
                <a href="https://iut.univ-lille.fr/" target="_blank" rel="noopener noreferrer" title="Site de l'IUT">
                    <img src="<?= $BASE ?>/assets/images/logoIUTGEIISombre.svg" alt="Logo IUT GEII Université de Lille"
                        class="footer-logo logo-light-only">
                    <img src="<?= $BASE ?>/assets/images/LogoIUTGEIIclair.svg" alt="Logo IUT GEII Université de Lille"
                        class="footer-logo logo-dark-only">
                </a>
            </div>
            <p class="footer-copyright">© Annuaire IUT GEII - <?= date('Y') ?></p>
        </div>

    </div>
</footer>
<script src="<?= $BASE ?>/assets/js/app.js"></script>
<button id="scrollToTop" class="scroll-to-top" aria-label="Retour en haut">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round">
        <path d="M18 15l-6-6-6 6" />
    </svg>
</button>
<div id="toast-container" aria-live="polite"></div>
</body>

</html>