export function FilterSearchProjects($originURL, $projectTypeURL, $projectsCategoryURL, $source, $search) {
    let $categoriesChecked = []; // tableau qui contiendra les checkboxes sélectionnées

    // constructeur objet CategorySelected
    function CategorySelected(id, name) {
        this.id = Number(id);
        this.name = String(name.trim());
    }

    // fonction de classement ASC des catégories cochées
    // => https://stackoverflow.com/questions/1129216/sort-array-of-objects-by-string-property-value
    function orderedASC( a, b ) {
        if ( a.name < b.name ) return -1;
        if ( a.name > b.name ) return 1;
        return 0;
    }

    // champs de recherche => https://api.jqueryui.com/autocomplete/
    $(function () {
        $('#search-project').autocomplete({
            delay: 1000,
            minLength: 3,
            source: $originURL + $source,
            select: function (event, ui) {
                $('#search-project').val(ui.item.name);
                window.location.href = $originURL + $search + ui.item.id;
                return false;
            }
        })
            .autocomplete("instance")._renderItem = function (ul, item) {
            return $("<li>")
                .append(item.name + ' / ' + item.info_short)
                .appendTo(ul);
        };
    });

    // bouton filtrer
    $('#filter').click(function(){
        if ($categoriesChecked.length > 0) {
            let $param = [];
            for (let i=0; i<$categoriesChecked.length; i++) {
                $param.push( parseInt($categoriesChecked[i].id) );
            }
            window.location.href = $originURL + $projectsCategoryURL + $param;
        }
    });

    // boutons checkboxes et radios
    $('.form-check').each(function () {
        // const $input = $(this).find('input')['prevObject'][0]; // console.log($input);
        const $input = $(this).find('input'); // console.log($input);
        // const $index = $(this).find('input')[0]['id']; // console.log($index);
        const $type = $(this).find('input')[0]['type']; // console.log($type);
        const $checked = $(this).find('input')[0]['checked']; // console.log($checked);
        const $label = $(this).find('label')[0]['innerText']; // console.log($label);
        const $id = $(this).find('input')[0]['id'].replace(/^[a-zA-Z]+/, ''); // console.log($id);

        if ($checked && $type === 'checkbox') {
            // si des catégories sont déjà sélectionnées
            $categoriesChecked.push( new CategorySelected($id, $label) );
        }

        // évènements pour les boutons checkboxes et radios
        $input.change(function (e){
            e.preventDefault();

            // boutons radios
            if ($input.is(":checked") && $input[0].getAttribute('type') === "radio") {
                window.location.href = $originURL + $projectTypeURL + $id;
            }

            // boutons checkboxes
            if ($input.is(":checked") && $input[0].getAttribute('type') === "checkbox") {
                $categoriesChecked.push( new CategorySelected($id, $input[0].offsetParent.innerText) );
                $categoriesChecked.sort(orderedASC); // console.log($projects);
            } else if (!$input.is(":checked") && $input[0].getAttribute('type') === "checkbox") {
                $categoriesChecked.splice($categoriesChecked.indexOf($categoriesChecked[$id]), 1); // console.log($projects);
            }
            // console.log($categoriesChecked);
        })
    });
    // console.log($categoriesChecked);
}