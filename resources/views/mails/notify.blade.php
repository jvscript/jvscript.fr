Bonjour,<br>

<?php
if (isset($script->js_url)) {
    $item = "script";
} elseif (isset($script->skin_url)) {
    $item = "skin";
}
?> 
Le {{$item}} que vous avez ajouté sur jvscript.io 
@if($script->status == 1)
a été validé. <a href="{{route($item.'.show',$script->slug)}}">Suivez ce lien pour le voir.</a> 
@elseif($script->status == 2)
a été refusé. <a href="{{route('contact.form')}}">Contactez-nous</a> pour plus d'info. <br>
@endif

<br>

Cordialement, <br>
jvscript.io