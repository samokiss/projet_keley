/**
 * Created by samuelgomis on 23/05/15.
 */
$(document).ready(function() {
    var $container = $('#appbundle_product_newTags');


    var $addLink = $('<a href="#" id="add_tag" class="btn btn-w-m btn-info"><i class="fa fa-plus"></i> Ajouter tag</a>');
    $container.after($addLink);

    $addLink.click(function (e) {
        addTag($container);
        e.preventDefault();
        return false;
    });

    var index = $container.find(':input').length;

    if (index == 0) {
//            addTag($container);
    } else {
        $container.children('.form-group').each(function () {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire FactureClientPrestation
    function addTag($container) {
        var $prototype = $($container.attr('data-prototype').replace(/__name__label__/g, 'N°' + (index + 1))
            .replace(/__name__/g, index));

        addDeleteLink($prototype);
        $container.append($prototype);
        index++;
    }

    // La fonction qui ajoute un lien de suppression d'une catÃ©gorie
    function addDeleteLink($prototype) {
        // CrÃ©ation du lien
        $deleteLink = $('<a href="#" class="close">&times</a>');

        // Ajout du lien
        $prototype.prepend($deleteLink);

        // Ajout du listener sur le clic du lien
        $deleteLink.click(function (e) {
            $prototype.remove();
            e.preventDefault(); // Ã©vite qu'un # apparaisse dans l'URL
            return false;
        });
    }
});