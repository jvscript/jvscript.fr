@extends('layouts.app')

@section('content') 


<div class="row">
    <div class="col-md-12">

        <h2> Chers amis développeurs </h2>
        <p>
            Bonjour amis développeurs,  <br>
            L'un des objectifs de ce site est de rassembler les développeurs de JVC pour permettre une meilleure collaboration 
            sur le développement des différents scripts JVC. <br>

        </p>

        <h2>Soumettez-nous votre script </h2>
        <p>
            <a  href="{{route('script.form')}}">Soumettez-nous votre script</a> pour le référencer sur le site.
        </p>

        <h2>Soyez soutenu </h2>

        <p>Un bouton de don au développeur sera présent pour chaque script présent sur ce site.</p>

        <h2>Rejoignez notre organisation github </h2>
        <p>
            Rejoignez notre organisation <a target="_blank" href="https://github.com/jvscript/">https://github.com/jvscript</a> pour y publier votre code à un plus large public et gagner des contributeurs.
            <br>Pour celà,  <a  href="{{route('contact.form',[ "message_body" => "Mon pseudo github est : "])}}"> indiquez nous votre pseudo github</a>. <br>
        </p>


        <h2>Contribuez au site </h2>


        <p>Envie d'améliorer le site ? Contribuez au développement du site sur notre <a target="_blank" href='https://github.com/jvscript/jvscript.github.io'> repo github</a>. </p>

    </div>
</div>

@endsection
