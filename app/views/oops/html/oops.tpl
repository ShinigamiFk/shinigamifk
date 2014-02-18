<hr/>
<div class="well text-center">
    <h2>{ Rayos! } </h2>  
</div>
<div class="alert alert-danger">
    <strong>Error ({$code}) :</strong>
    {if $code=='XIII'}
        Error no encontrado.
    {elseif $code == 'XV'}
        Ruta no encontrada, verifique la ruta a la que hace referencia.
    {else}
        No Existe un error asociado a éste #
    {/if}
</div>

<hr/>



