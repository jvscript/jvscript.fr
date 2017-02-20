@extends('layouts.app')

@section('content')


<div class="row">
    <div class="col-md-12">
        <div class="panel-body">

<h2 id="comment-installer-un-script">Comment installer un script ?</h2>

<p><strong>Prérequis :</strong></p>

<ul>
<li><p>Pour Chrome: Installer <a href="https://chrome.google.com/webstore/detail/tampermonkey/dhdgffkkebhmkfjojejmpbldmpobfkfo?hl=fr">TamperMonkey</a></p></li>
<li><p>Pour Firefox: Installer <a href="https://addons.mozilla.org/fr/firefox/addon/greasemonkey/">GreaseMonkey</a></p></li>
</ul>


<p><strong>Installation :</strong> <br>
Il vous suffit de cliquer sur le script de votre choix dans la page d'accueil du site, puis de cliquer sur <em>Installer</em>. Vous serez redirigé sur une page où il ne vous restera qu’à cliquer sur <em>Installer</em>. </p>

<p><img src="http://image.noelshack.com/fichiers/2017/07/1486990384-screenshot-1.png" alt="Installer un Script sur TamperMonkey" title=""></p>
            <hr>
<h2 id="comment-installer-un-skin">Comment installer un skin ?</h2>

<p><strong>Prérequis :</strong></p>

<ul>
<li><p>Pour Chrome: Installer <a href="https://chrome.google.com/webstore/detail/stylish-custom-themes-for/fjnbnpbmkenffdnngjfgmeleoegfcffe?hl=fr">Stylish</a></p></li>
<li><p>Pour Firefox: Installer <a href="https://addons.mozilla.org/fr/firefox/addon/stylish/">Stylish</a></p></li>
</ul>

<p><strong>Installation :</strong> <br>
Il vous suffit de cliquer sur le script de votre choix dans la page d'accueil du site, puis de cliquer sur <em>Installer</em>. Vous serez redirigé sur la page Stylish du skin en question. Il ne vous restera plus qu'à cliquer sur le bouton <em>Install with Stylish</em>.<br>
<img src="http://image.noelshack.com/fichiers/2017/07/1486991277-screenshot-2.png" alt="Installer un skin avec Stylish" title=""></p>


            <hr>
<h2 id="aidez-moi-mon-script-plante">Aidez-moi ! Mon script plante !</h2>

<p>Des extensions, particulièrement les bloqueurs de pubs (Ad-Block, uBlock…) peuvent bloquer les scripts. Dans ce cas nous vous conseillons de vérifier cela en éteignant votre bloqueur de pubs. <br>
Aussi, des scripts combinés ensemble peuvent provoquer des erreurs, vous pouvez vérifier cela en vérifiant la console du navigateur (Ctrl+Maj+J).</p>

            <hr>
<h2 id="comment-mettre-à-jour-les-scripts-et-skins">Comment mettre à jour les scripts et skins ?</h2>

<p>Sur certains scripts vous serez prévenus des mises à jour et dans la plupart des cas elles seront automatiques (une fois par jour).  <br>
Si vous voulez forcer la mise à jour il suffit d’aller dans les options de TamperMonkey (Ou GreaseMonkey).</p>

            <hr>
<h2 id="quels-sont-les-risques-à-installer-un-script">Quels sont les risques à installer un Script ?</h2>

<p><strong>Modération :</strong> <br>
Sur chaque script est indiqué à côté du bouton <em>Installer</em> une icone indiquant si le script est interdit par la modération ou pas.  <br>
<img src="/assets/images/check.png" alt="safe" title=""> Le script est autorisé par la modération, il y a aucun problème à l’utiliser ! <br>
<img src="/assets/images/warn.png" alt="always" title=""> Nous ne sommes pas sûrs que ce script soit sans danger. Nous vous conseillons de contacter la modération de jeuxvideo.com .<br>
<img src="/assets/images/danger.png" alt="danger" title=""> L’utilisation de ce script est dangereux. La modération ne tolère pas ce genre de script, son utilisation peut mener à des sanctions.</p>

<p><strong>Piratage:</strong>  <br>
Les scripts sont testés avant la publication, néanmoins nous ne sommes pas responsables si le script est mis à jour avec un nouveau code malicieux.</p>
    </div>
</div>
</div>

@endsection
