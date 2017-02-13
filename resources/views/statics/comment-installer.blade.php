@extends('layouts.app')

@section('content')


<div class="row">
    <div class="col-md-12">

     <h1 id="aide">Aide</h1>



<h2 id="comment-installer-un-script">Comment installer un script ?</h2>

<p><strong>Prérequis :</strong></p>

<ul>
<li>Pour Chrome: Installer <a href="https://chrome.google.com/webstore/detail/tampermonkey/dhdgffkkebhmkfjojejmpbldmpobfkfo?hl=fr">TamperMonkey</a></li>
<li>Pour Firefox: Installer <a href="https://addons.mozilla.org/fr/firefox/addon/greasemonkey/">GreaseMonkey</a></li>
</ul>

<p><strong>Installation :</strong> <br>
Ensuite vous pouvez choisir un script dans la liste présente sur la page d’accueil, aller sur sa page, et cliquer sur le bouton <em>Installer</em> au-dessus de sa description sur sa page. Vous serez redirigé sur une page ou il ne restera qu’à cliquer sur <em>Installer</em>. </p>

<p><img src="http://image.noelshack.com/fichiers/2017/07/1486990384-screenshot-1.png" alt="Installer un Script sur TamperMonkey" title=""></p>

<p><strong>Si la page ne s’ouvre pas :</strong></p>

<h2 id="comment-installer-un-skin">Comment installer un skin ?</h2>

<p><strong>Prérequis :</strong></p>

<ul>
<li><p>Pour Chrome: Installer <a href="https://chrome.google.com/webstore/detail/stylish-custom-themes-for/fjnbnpbmkenffdnngjfgmeleoegfcffe?hl=fr">Stylish</a></p></li>
<li><p>Pour Firefox: Installer <a href="https://addons.mozilla.org/fr/firefox/addon/stylish/">Stylish</a></p></li>
</ul>

<p><strong>Installation :</strong> <br>
Ensuite vous pouvez aller choisir un skin sur la page d’accueil du site, cliquer sur celui choisis et cliquer sur le bouton <em>Installer</em>. Vous serez redirigé sur la page Stylish ou il y aura un bouton pour installer un script: Cliquer dessus pour terminer l’opération. <br>
<img src="http://image.noelshack.com/fichiers/2017/07/1486991277-screenshot-2.png" alt="Installer un skin avec Stylish" title=""></p>



<h2 id="aidez-moi-mon-script-plante">Aidez-moi ! Mon script plante !</h2>

<p>Des extensions, particuliérement les bloqueurs de pubs (Ad-Block, uBlock…) peuvent bloquer les scripts: Dans ce cas nous vous conseillons de vérifier cela en enteignant votre bloqueur de pubs. <br>
Toutefois si vous avez plusieurs scripts ils peuvent interférer et provoquer des problèmes, vous pouvez vérifier cela en vérifiant la console du navigateur (Ctrl+Maj+J).</p>

<h2 id="comment-mettre-à-jour-les-scripts-et-skins">Comment mettre à jour les scripts et skins ?</h2>

<p>Sur certains scripts vous serez prévenus des mises à jours et dans la plupart des cas elles seront automatiques (Une fois par jour).  <br>
Si vous voulez forcer la mise à jour il suffit d’aller dans les options de TamperMonkey (Ou GreaseMonkey).</p>

<h2 id="quels-sont-les-risques-à-installer-un-script">Quels sont les risques à installer un Script ?</h2>

<p><strong>Modérations :</strong> <br>
Sur chaque script est indiqué à côté du bouton <em>Installer</em> une icone indiquant si le script est interdit par la modération ou pas.  <br>
<img src="http://image.noelshack.com/fichiers/2017/07/1486991578-screenshot-3.png" alt="safe" title=""> Le script est sécurisé, il y a aucun problème à l’utiliser ! <br>
<img src="http://image.noelshack.com/fichiers/2017/07/1486991578-screenshot-5.png" alt="always" title=""> Nous ne sommes pas sur que ce script peut convenir avec la modération. <br>
<img src="http://image.noelshack.com/fichiers/2017/07/1486991578-screenshot-4.png" alt="danger" title=""> L’utilisation de ce script peut mener à des sanctions !</p>

<p><strong>Piratage:</strong>  <br>
Les scripts sont tésté avant la publication, néanmoins on est pas responsable si le script est mis à jour avec un nouveau code malicieux.</p>
    </div>
</div>


@endsection
